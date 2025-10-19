<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h4 class="mb-0"><i class="feather-file-text me-2"></i>Rapports Généraux</h4>
        <p class="text-muted">Statistiques financières et opérationnelles</p>
    </div>
    <div class="col-md-4">
        <form method="GET" action="<?= base_url('admin/reports') ?>" class="row g-2">
            <div class="col-md-5">
                <input type="date" name="date_debut" class="form-control form-control-sm" value="<?= $date_debut ?>" required>
            </div>
            <div class="col-md-5">
                <input type="date" name="date_fin" class="form-control form-control-sm" value="<?= $date_fin ?>" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm text-white w-100" style="background: #d29751;">
                    <i class="feather-filter"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Résumé Financier -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px; border-left: 4px solid #17a2b8;">
            <div class="card-body">
                <h6 class="text-muted mb-2">Réservations</h6>
                <h3 class="mb-0"><?= number_format($ventes_reservations['total'], 0, ',', ' ') ?> FCFA</h3>
                <small class="text-muted"><?= $ventes_reservations['nb_paiements'] ?> paiements / <?= $ventes_reservations['nb_reservations'] ?> réservations</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px; border-left: 4px solid #28a745;">
            <div class="card-body">
                <h6 class="text-muted mb-2">Loyers Mensuels</h6>
                <h3 class="mb-0"><?= number_format($loyers_mensuels['total'], 0, ',', ' ') ?> FCFA</h3>
                <small class="text-muted"><?= $loyers_mensuels['nb_paiements'] ?> paiements</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px; border-left: 4px solid #d29751;">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Global</h6>
                <h3 class="mb-0"><?= number_format($total_global, 0, ',', ' ') ?> FCFA</h3>
                <small class="text-muted">Réservations + Loyers</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px; border-left: 4px solid #ffc107;">
            <div class="card-body">
                <h6 class="text-muted mb-2">Contrats Actifs</h6>
                <h3 class="mb-0"><?= $contrats_actifs ?></h3>
                <small class="text-muted">En cours</small>
            </div>
        </div>
    </div>
</div>

<!-- Répartition par Appartement -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="feather-pie-chart me-2"></i>Répartition par Appartement</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($ventes_par_appartement)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Appartement</th>
                                    <th class="text-center">Réservations</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ventes_par_appartement as $vente): ?>
                                    <tr>
                                        <td><?= esc($vente['adresse']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-primary"><?= $vente['nb_reservations'] ?></span>
                                        </td>
                                        <td class="text-end">
                                            <strong><?= number_format($vente['total_reservations'], 0, ',', ' ') ?> FCFA</strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">Aucune réservation pour cette période</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="feather-calendar me-2"></i>Période</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Date de début</label>
                    <p class="mb-0 fw-bold"><?= date('d/m/Y', strtotime($date_debut)) ?></p>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Date de fin</label>
                    <p class="mb-0 fw-bold"><?= date('d/m/Y', strtotime($date_fin)) ?></p>
                </div>
                <div>
                    <label class="text-muted small">Durée</label>
                    <p class="mb-0 fw-bold">
                        <?php
                        $debut = new DateTime($date_debut);
                        $fin = new DateTime($date_fin);
                        $diff = $debut->diff($fin);
                        echo $diff->days + 1 . ' jours';
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Évolution Mensuelle -->
<div class="row">
    <div class="col-12">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="feather-trending-up me-2"></i>Évolution Mensuelle <?= date('Y') ?></h5>
            </div>
            <div class="card-body">
                <?php if (!empty($evolution_mensuelle)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Mois</th>
                                    <th class="text-end">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($evolution_mensuelle as $mois): ?>
                                    <tr>
                                        <td><?= date('F Y', strtotime($mois['mois'] . '-01')) ?></td>
                                        <td class="text-end">
                                            <strong><?= number_format($mois['total'], 0, ',', ' ') ?> FCFA</strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">Aucune donnée disponible</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
