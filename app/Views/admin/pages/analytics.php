<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0"><i class="feather-bar-chart-2 me-2"></i>Analytics Globale</h4>
        <p class="text-muted">Vue d'ensemble des performances de la plateforme</p>
    </div>
</div>

<!-- KPIs Principaux -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle p-3" style="background: rgba(210, 151, 81, 0.1);">
                            <i class="feather-home" style="color: #d29751; font-size: 24px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Taux d'Occupation</h6>
                        <h3 class="mb-0"><?= number_format($taux_occupation, 1) ?>%</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle p-3" style="background: rgba(40, 167, 69, 0.1);">
                            <i class="feather-trending-up" style="color: #28a745; font-size: 24px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Taux de Conversion</h6>
                        <h3 class="mb-0"><?= number_format($taux_conversion, 1) ?>%</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle p-3" style="background: rgba(23, 162, 184, 0.1);">
                            <i class="feather-dollar-sign" style="color: #17a2b8; font-size: 24px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Revenu Moyen/Résa</h6>
                        <h3 class="mb-0"><?= number_format($revenu_moyen_reservation, 0, ',', ' ') ?> FCFA</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle p-3" style="background: rgba(220, 53, 69, 0.1);">
                            <i class="feather-alert-circle" style="color: #dc3545; font-size: 24px;"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Paiements en Retard</h6>
                        <h3 class="mb-0"><?= $paiements_retard ?></h3>
                        <small class="text-danger"><?= number_format($montant_retard, 0, ',', ' ') ?> FCFA</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenus -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="feather-dollar-sign me-2"></i>Revenus Totaux</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Réservations</span>
                        <strong><?= number_format($revenu_total_reservations, 0, ',', ' ') ?> FCFA</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: <?= $revenu_total_reservations > 0 ? 60 : 0 ?>%"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Contrats Mensuels</span>
                        <strong><?= number_format($revenu_total_contrats, 0, ',', ' ') ?> FCFA</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar" style="background: #d29751; width: <?= $revenu_total_contrats > 0 ? 40 : 0 ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="feather-award me-2"></i>Top 5 Appartements</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($top_appartements)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                <?php foreach ($top_appartements as $index => $appart): ?>
                                    <tr>
                                        <td width="30"><?= $index + 1 ?>.</td>
                                        <td><?= esc($appart['adresse']) ?></td>
                                        <td class="text-end">
                                            <span class="badge bg-success"><?= $appart['nb_reservations'] ?> résa</span>
                                        </td>
                                        <td class="text-end">
                                            <strong><?= number_format($appart['revenu_total'], 0, ',', ' ') ?></strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">Aucune donnée disponible</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Tendances sur 6 mois -->
<div class="row">
    <div class="col-12">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="feather-trending-up me-2"></i>Tendances sur 6 Mois</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($tendances)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mois</th>
                                    <th class="text-center">Réservations</th>
                                    <th class="text-end">Revenus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tendances as $tendance): ?>
                                    <tr>
                                        <td><?= $tendance['mois_libelle'] ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-primary"><?= $tendance['nb_reservations'] ?></span>
                                        </td>
                                        <td class="text-end">
                                            <strong><?= number_format($tendance['revenus'], 0, ',', ' ') ?> FCFA</strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">Aucune donnée disponible</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
