<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">
                    <i class="feather-dollar-sign me-2"></i>
                    Paiements Mensuels
                </h5>
                <div class="btn-group">
                    <a href="<?= base_url('admin/paiements-mensuels/dashboard') ?>" class="btn btn-primary">
                        <i class="feather-bar-chart-2 me-2"></i>Tableau de Bord
                    </a>
                    <a href="<?= base_url('admin/contrats') ?>" class="btn btn-secondary">
                        <i class="feather-file-text me-2"></i>Contrats
                    </a>
                </div>
            </div>
            
            <!-- Statistiques rapides -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%); border: none; box-shadow: 0 4px 12px rgba(210, 151, 81, 0.3);">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="card-title mb-0 fw-semibold">Échéances Proches</h6>
                                    <h4 class="mb-0 fw-bold mt-2"><?= count($echeances_proches) ?></h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avatar-text avatar-lg text-white" style="background: rgba(255,255,255,0.2);">
                                        <i class="feather-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border: none; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="card-title mb-0 fw-semibold">Retards</h6>
                                    <h4 class="mb-0 fw-bold mt-2"><?= count($retards) ?></h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avatar-text avatar-lg text-white" style="background: rgba(255,255,255,0.2);">
                                        <i class="feather-alert-triangle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%); border: none; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="card-title mb-0 fw-semibold">Paiements Aujourd'hui</h6>
                                    <h4 class="mb-0 fw-bold mt-2"><?= count(array_filter($echeances_proches, function($e) { return date('Y-m-d', strtotime($e['date_echeance'])) === date('Y-m-d'); })) ?></h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avatar-text avatar-lg text-white" style="background: rgba(255,255,255,0.2);">
                                        <i class="feather-check-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 1px solid #dee2e6; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="card-title mb-0 fw-semibold" style="color: #d29751;">Actions</h6>
                                    <button class="btn btn-sm mt-2 text-white" style="background: #d29751; border: none;" onclick="verifierEcheances()">
                                        <i class="feather-refresh-cw me-1"></i>Vérifier
                                    </button>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="avatar-text avatar-lg text-white" style="background: #d29751;">
                                        <i class="feather-settings"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Échéances proches -->
        <div class="col-md-6">
            <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%); border-radius: 10px 10px 0 0;">
                    <h6 class="mb-0 fw-semibold">
                        <i class="feather-calendar me-2"></i>
                        Échéances Proches (5 jours)
                    </h6>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($echeances_proches)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th class="border-0">Locataire</th>
                                        <th class="border-0">Montant</th>
                                        <th class="border-0">Échéance</th>
                                        <th class="border-0 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($echeances_proches as $echeance): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-semibold"><?= esc($echeance['locataire_nom']) ?></div>
                                                <small class="text-muted"><?= esc($echeance['appartement_adresse']) ?></small>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-primary"><?= number_format($echeance['montant_du'], 0, ',', ' ') ?> FCFA</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark d-flex align-items-center">
                                                    <i class="feather-calendar me-1" style="font-size: 12px;"></i>
                                                    <?= date('d/m/Y', strtotime($echeance['date_echeance'])) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-info" onclick="envoyerRappel(<?= $echeance['id'] ?>)" 
                                                        data-bs-toggle="tooltip" title="Envoyer rappel">
                                                    <i class="feather-mail"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center p-4">
                            <i class="feather-check-circle text-success" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Aucune échéance proche</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Retards de paiement -->
        <div class="col-md-6">
            <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">
                        <i class="feather-alert-triangle me-2"></i>
                        Retards de Paiement
                    </h6>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($retards)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th class="border-0">Locataire</th>
                                        <th class="border-0">Montant</th>
                                        <th class="border-0">Retard</th>
                                        <th class="border-0 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($retards as $retard): ?>
                                        <?php 
                                        $joursRetard = floor((time() - strtotime($retard['date_echeance'])) / (60 * 60 * 24));
                                        ?>
                                        <tr class="<?= $joursRetard > 30 ? 'table-danger' : 'table-warning' ?>">
                                            <td>
                                                <div class="fw-semibold"><?= esc($retard['locataire_nom']) ?></div>
                                                <small class="text-muted"><?= esc($retard['appartement_adresse']) ?></small>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-danger"><?= number_format($retard['montant_du'], 0, ',', ' ') ?> FCFA</span>
                                            </td>
                                            <td>
                                                <?php
                                                $retardClass = $joursRetard > 30 ? 'bg-danger' : 'bg-warning text-dark';
                                                $retardIcon = $joursRetard > 30 ? 'feather-alert-triangle' : 'feather-clock';
                                                ?>
                                                <span class="badge <?= $retardClass ?> d-flex align-items-center">
                                                    <i class="<?= $retardIcon ?> me-1" style="font-size: 12px;"></i>
                                                    <?= $joursRetard ?> jour(s)
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-danger" onclick="envoyerRappel(<?= $retard['id'] ?>)" 
                                                        data-bs-toggle="tooltip" title="Envoyer rappel">
                                                    <i class="feather-mail"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center p-4">
                            <i class="feather-check-circle text-success" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Aucun retard de paiement</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function envoyerRappel(paiementId) {
    $.ajax({
        url: '<?= base_url('admin/paiements-mensuels/rappel') ?>/' + paiementId,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        },
        error: function() {
            toastr.error('Erreur lors de l\'envoi du rappel');
        }
    });
}

function verifierEcheances() {
    $.ajax({
        url: '<?= base_url('admin/contrats/echeances-proches') ?>',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                toastr.info('Vérification terminée. ' + response.echeances.length + ' échéances trouvées.');
                location.reload();
            }
        },
        error: function() {
            toastr.error('Erreur lors de la vérification');
        }
    });
}

// Initialiser les tooltips
$(document).ready(function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<?= $this->endSection() ?>
