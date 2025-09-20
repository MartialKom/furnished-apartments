<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Gestion des Paiements</h5>
            </div>
            <div class="card-body">
                <!-- Tabs pour organiser les paiements par statut -->
                <ul class="nav nav-tabs" id="paiementTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="en-attente-tab" data-bs-toggle="tab" data-bs-target="#en-attente" type="button" role="tab">
                            En Attente (<?= count($paiements['en_attente']) ?>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="payes-tab" data-bs-toggle="tab" data-bs-target="#payes" type="button" role="tab">
                            Payés (<?= count($paiements['paye']) ?>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="rembourses-tab" data-bs-toggle="tab" data-bs-target="#rembourses" type="button" role="tab">
                            Remboursés (<?= count($paiements['rembourse']) ?>)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="annules-tab" data-bs-toggle="tab" data-bs-target="#annules" type="button" role="tab">
                            Annulés (<?= count($paiements['annule']) ?>)
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
                                                    <button class="btn btn-sm btn-success update-statut" data-id="<?= $paiement['id'] ?>" data-statut="paye">
                                                        <i class="feather-check"></i> Marquer Payé
                                                    </button>
                                                    <button class="btn btn-sm btn-warning update-statut" data-id="<?= $paiement['id'] ?>" data-statut="annule">
                                                        <i class="feather-x"></i> Annuler
                                                    </button>
                                                    <button class="btn btn-sm btn-info generer-facture" data-id="<?= $paiement['id'] ?>">
                                                        <i class="feather-file"></i> Facture
                                                    </button>
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
                                                    <button class="btn btn-sm btn-warning update-statut" data-id="<?= $paiement['id'] ?>" data-statut="rembourse">
                                                        <i class="feather-rotate-ccw"></i> Rembourser
                                                    </button>
                                                    <button class="btn btn-sm btn-info generer-facture" data-id="<?= $paiement['id'] ?>">
                                                        <i class="feather-file"></i> Facture
                                                    </button>
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
                                                    <button class="btn btn-sm btn-info generer-facture" data-id="<?= $paiement['id'] ?>">
                                                        <i class="feather-file"></i> Facture
                                                    </button>
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
                                                    <button class="btn btn-sm btn-info generer-facture" data-id="<?= $paiement['id'] ?>">
                                                        <i class="feather-file"></i> Facture
                                                    </button>
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
        const id = $(this).data('id');
        const statut = $(this).data('statut');
        
        let message = '';
        switch(statut) {
            case 'paye':
                message = 'Marquer ce paiement comme payé ?';
                break;
            case 'rembourse':
                message = 'Marquer ce paiement comme remboursé ?';
                break;
            case 'annule':
                message = 'Annuler ce paiement ?';
                break;
        }
        
        if (confirm(message)) {
            $.ajax({
                url: '<?= base_url('/admin/paiements/update-statut/') ?>' + id,
                type: 'POST',
                data: { statut: statut },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Erreur: ' + response.message);
                    }
                },
                error: function() {
                    alert('Erreur lors de la mise à jour du statut');
                }
            });
        }
    });

    // Générer une facture
    $('.generer-facture').on('click', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: '<?= base_url('/admin/paiements/generer-facture/') ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Afficher les détails de la facture dans une modal ou nouvelle fenêtre
                    const facture = response.facture;
                    let factureContent = `
                        <div class="modal fade" id="factureModal" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Facture #${facture.id}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <h6>Locataire</h6>
                                                <p>${facture.locataire_nom}<br>
                                                ${facture.email}</p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6>Appartement</h6>
                                                <p>${facture.adresse}<br>
                                                ${facture.date_debut} - ${facture.date_fin}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-6">
                                                <h6>Montant</h6>
                                                <p class="h5 text-success">${new Intl.NumberFormat('fr-FR').format(facture.montant)} FCFA</p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6>Statut</h6>
                                                <span class="badge bg-${facture.statut === 'paye' ? 'success' : facture.statut === 'en_attente' ? 'warning' : 'danger'}">${facture.statut}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                        <button type="button" class="btn btn-primary" onclick="window.print()">Imprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    $('body').append(factureContent);
                    $('#factureModal').modal('show');
                    
                    $('#factureModal').on('hidden.bs.modal', function() {
                        $(this).remove();
                    });
                } else {
                    alert('Erreur: ' + response.message);
                }
            },
            error: function() {
                alert('Erreur lors de la génération de la facture');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
