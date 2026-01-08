<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<style>
.nav-tabs .nav-link {
    color: #666;
    border: none;
    border-bottom: 3px solid transparent;
    font-weight: 500;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    border-color: #d29751;
    color: #d29751;
}

.nav-tabs .nav-link.active {
    color: #d29751;
    background-color: transparent;
    border-bottom: 3px solid #d29751;
}

.btn-group .btn {
    margin: 0 2px;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    padding: 6px 12px;
    font-size: 11px;
    font-weight: 600;
    border-radius: 20px;
}
</style>

<div class="row">
    <div class="col-12">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header text-white" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%); border-radius: 10px 10px 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold"><i class="feather-credit-card me-2"></i>Gestion des Paiements</h5>
                    <div>
                        <span class="badge bg-light text-dark">Total: <?= count($paiements['en_attente']) + count($paiements['paye']) + count($paiements['rembourse']) + count($paiements['annule']) ?></span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Tabs pour organiser les paiements par statut -->
                <ul class="nav nav-tabs mb-3" id="paiementTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="en-attente-tab" data-bs-toggle="tab" data-bs-target="#en-attente" type="button" role="tab">
                            <i class="feather-clock me-1"></i> En Attente <span class="badge bg-warning text-dark ms-1"><?= count($paiements['en_attente']) ?></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="payes-tab" data-bs-toggle="tab" data-bs-target="#payes" type="button" role="tab">
                            <i class="feather-check-circle me-1"></i> Payés <span class="badge bg-success ms-1"><?= count($paiements['paye']) ?></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="rembourses-tab" data-bs-toggle="tab" data-bs-target="#rembourses" type="button" role="tab">
                            <i class="feather-rotate-ccw me-1"></i> Remboursés <span class="badge bg-info ms-1"><?= count($paiements['rembourse']) ?></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="annules-tab" data-bs-toggle="tab" data-bs-target="#annules" type="button" role="tab">
                            <i class="feather-x-circle me-1"></i> Annulés <span class="badge bg-danger ms-1"><?= count($paiements['annule']) ?></span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="paiementTabsContent">
                    <!-- En Attente -->
                    <div class="tab-pane fade show active" id="en-attente" role="tabpanel">
                        <div class="table-responsive mt-3">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Locataire</th>
                                        <th>Email</th>
                                        <th>Montant</th>
                                        <th>Date</th>
                                        <th>Appartement</th>
                                        <th>Période</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($paiements['en_attente'])): ?>
                                        <?php foreach ($paiements['en_attente'] as $paiement): ?>
                                            <tr>
                                                <td><?= $paiement['id'] ?></td>
                                                <td><?= esc($paiement['locataire_nom']) ?></td>
                                                <td><?= esc($paiement['email']) ?></td>
                                                <td><?= number_format($paiement['montant'], 0, ',', ' ') ?> FCFA</td>
                                                <td><?= date('d/m/Y', strtotime($paiement['date'])) ?></td>
                                                <td><?= esc($paiement['adresse']) ?></td>
                                                <td><?= date('d/m/Y', strtotime($paiement['date_debut'])) ?> - <?= date('d/m/Y', strtotime($paiement['date_fin'])) ?></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-success update-statut" data-id="<?= $paiement['id'] ?>" data-statut="paye" title="Marquer comme payé">
                                                            <i class="feather-check"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger update-statut" data-id="<?= $paiement['id'] ?>" data-statut="annule" title="Annuler">
                                                            <i class="feather-x"></i>
                                                        </button>
                                                        <a href="<?= base_url('admin/paiements/generer-facture/' . $paiement['id']) ?>" target="_blank" class="btn btn-sm" style="background: #d29751; color: white;" title="Voir la facture">
                                                            <i class="feather-file-text"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Aucun paiement en attente</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Payés -->
                    <div class="tab-pane fade" id="payes" role="tabpanel">
                        <div class="table-responsive mt-3">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Locataire</th>
                                        <th>Email</th>
                                        <th>Montant</th>
                                        <th>Date</th>
                                        <th>Appartement</th>
                                        <th>Période</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($paiements['paye'])): ?>
                                        <?php foreach ($paiements['paye'] as $paiement): ?>
                                            <tr>
                                                <td><?= $paiement['id'] ?></td>
                                                <td><?= esc($paiement['locataire_nom']) ?></td>
                                                <td><?= esc($paiement['email']) ?></td>
                                                <td><?= number_format($paiement['montant'], 0, ',', ' ') ?> FCFA</td>
                                                <td><?= date('d/m/Y', strtotime($paiement['date'])) ?></td>
                                                <td><?= esc($paiement['adresse']) ?></td>
                                                <td><?= date('d/m/Y', strtotime($paiement['date_debut'])) ?> - <?= date('d/m/Y', strtotime($paiement['date_fin'])) ?></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-warning update-statut" data-id="<?= $paiement['id'] ?>" data-statut="rembourse" title="Rembourser">
                                                            <i class="feather-rotate-ccw"></i>
                                                        </button>
                                                        <a href="<?= base_url('admin/paiements/generer-facture/' . $paiement['id']) ?>" target="_blank" class="btn btn-sm" style="background: #d29751; color: white;" title="Voir la facture">
                                                            <i class="feather-file-text"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Aucun paiement payé</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Remboursés -->
                    <div class="tab-pane fade" id="rembourses" role="tabpanel">
                        <div class="table-responsive mt-3">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Locataire</th>
                                        <th>Email</th>
                                        <th>Montant</th>
                                        <th>Date</th>
                                        <th>Appartement</th>
                                        <th>Période</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($paiements['rembourse'])): ?>
                                        <?php foreach ($paiements['rembourse'] as $paiement): ?>
                                            <tr>
                                                <td><?= $paiement['id'] ?></td>
                                                <td><?= esc($paiement['locataire_nom']) ?></td>
                                                <td><?= esc($paiement['email']) ?></td>
                                                <td><?= number_format($paiement['montant'], 0, ',', ' ') ?> FCFA</td>
                                                <td><?= date('d/m/Y', strtotime($paiement['date'])) ?></td>
                                                <td><?= esc($paiement['adresse']) ?></td>
                                                <td><?= date('d/m/Y', strtotime($paiement['date_debut'])) ?> - <?= date('d/m/Y', strtotime($paiement['date_fin'])) ?></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="<?= base_url('admin/paiements/generer-facture/' . $paiement['id']) ?>" target="_blank" class="btn btn-sm btn-info" title="Voir la facture">
                                                            <i class="feather-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Aucun paiement remboursé</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Annulés -->
                    <div class="tab-pane fade" id="annules" role="tabpanel">
                        <div class="table-responsive mt-3">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Locataire</th>
                                        <th>Email</th>
                                        <th>Montant</th>
                                        <th>Date</th>
                                        <th>Appartement</th>
                                        <th>Période</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($paiements['annule'])): ?>
                                        <?php foreach ($paiements['annule'] as $paiement): ?>
                                            <tr>
                                                <td><?= $paiement['id'] ?></td>
                                                <td><?= esc($paiement['locataire_nom']) ?></td>
                                                <td><?= esc($paiement['email']) ?></td>
                                                <td><?= number_format($paiement['montant'], 0, ',', ' ') ?> FCFA</td>
                                                <td><?= date('d/m/Y', strtotime($paiement['date'])) ?></td>
                                                <td><?= esc($paiement['adresse']) ?></td>
                                                <td><?= date('d/m/Y', strtotime($paiement['date_debut'])) ?> - <?= date('d/m/Y', strtotime($paiement['date_fin'])) ?></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="<?= base_url('admin/paiements/generer-facture/' . $paiement['id']) ?>" target="_blank" class="btn btn-sm btn-info" title="Voir la facture">
                                                            <i class="feather-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Aucun paiement annulé</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Mettre à jour le statut d'un paiement
    $('.update-statut').on('click', function() {
        const $button = $(this);
        const id = $button.data('id');
        const statut = $button.data('statut');

        let message = '';
        let titre = '';
        switch(statut) {
            case 'paye':
                message = 'Voulez-vous marquer ce paiement comme payé ?';
                titre = 'Confirmation de paiement';
                break;
            case 'rembourse':
                message = 'Voulez-vous marquer ce paiement comme remboursé ?';
                titre = 'Confirmation de remboursement';
                break;
            case 'annule':
                message = 'Voulez-vous annuler ce paiement ? Cette action est irréversible.';
                titre = 'Confirmation d\'annulation';
                break;
        }

        if (confirm(titre + '\n\n' + message)) {
            // Désactiver le bouton pendant le traitement
            $button.prop('disabled', true);

            $.ajax({
                url: '<?= base_url('admin/paiements/update-statut/') ?>' + id,
                type: 'POST',
                data: {
                    statut: statut,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                },
                dataType: 'json',
                beforeSend: function() {
                    toastr.info('Mise à jour en cours...', 'Traitement');
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message, 'Succès');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error(response.message, 'Erreur');
                        $button.prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur AJAX:', error);
                    toastr.error('Erreur lors de la mise à jour du statut. Veuillez réessayer.', 'Erreur');
                    $button.prop('disabled', false);
                }
            });
        }
    });

    // Initialiser les tooltips
    $('[title]').tooltip();
});
</script>
<?= $this->endSection() ?>
