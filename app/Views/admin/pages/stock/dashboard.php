<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="feather-package me-2"></i>
                Tableau de Bord Stock
            </h5>
        </div>
    </div>
</div>

<!-- Statistiques Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm bg-primary-subtle rounded">
                            <span class="avatar-title text-primary">
                                <i class="feather-box"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1">Produits actifs</p>
                        <h4 class="mb-0"><?= $statistiques['total_produits'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm bg-success-subtle rounded">
                            <span class="avatar-title text-success">
                                <i class="feather-trending-up"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1">Entrées du mois</p>
                        <h4 class="mb-0"><?= $statistiques['approvisionnements_mois']['total_approvisionnements'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm bg-warning-subtle rounded">
                            <span class="avatar-title text-warning">
                                <i class="feather-trending-down"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1">Sorties du mois</p>
                        <h4 class="mb-0"><?= $statistiques['sorties_mois']['total_sorties'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="avatar-sm bg-info-subtle rounded">
                            <span class="avatar-title text-info">
                                <i class="feather-dollar-sign"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-1">Valeur du stock</p>
                        <h4 class="mb-0"><?= number_format($valeurStock, 0, ',', ' ') ?> FCFA</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alertes Stock Faible -->
<?php if (!empty($produitsAlerte)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0">
                    <i class="feather-alert-triangle text-warning me-2"></i>
                    Alertes Stock Faible
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th class="border-0">Produit</th>
                                <th class="border-0">Catégorie</th>
                                <th class="border-0">Stock actuel</th>
                                <th class="border-0">Stock minimum</th>
                                <th class="border-0">Unité</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produitsAlerte as $produit): ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($produit['nom']) ?></td>
                                    <td><?= esc($produit['categorie_nom']) ?></td>
                                    <td>
                                        <span class="badge bg-danger">
                                            <?= number_format($produit['stock_actuel'], 2) ?>
                                        </span>
                                    </td>
                                    <td><?= number_format($produit['stock_minimum'], 2) ?></td>
                                    <td><?= esc($produit['unite_mesure']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Actions Rapides -->
<div class="row">
    <div class="col-md-4 mb-3">
        <a href="<?= base_url('admin/stock/produits') ?>" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <i class="feather-box" style="font-size: 48px; color: #d29751;"></i>
                    </div>
                    <h6 class="mb-2">Gérer les Produits</h6>
                    <p class="text-muted small mb-0">Créer, modifier et consulter les produits</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4 mb-3">
        <a href="<?= base_url('admin/stock/approvisionnements') ?>" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <i class="feather-truck" style="font-size: 48px; color: #d29751;"></i>
                    </div>
                    <h6 class="mb-2">Approvisionnements</h6>
                    <p class="text-muted small mb-0">Enregistrer les entrées de stock</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4 mb-3">
        <a href="<?= base_url('admin/stock/sorties') ?>" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <i class="feather-shopping-cart" style="font-size: 48px; color: #d29751;"></i>
                    </div>
                    <h6 class="mb-2">Sorties de Stock</h6>
                    <p class="text-muted small mb-0">Enregistrer les distributions</p>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6 mb-3">
        <a href="<?= base_url('admin/stock/inventaires') ?>" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <i class="feather-clipboard" style="font-size: 48px; color: #d29751;"></i>
                    </div>
                    <h6 class="mb-2">Inventaires</h6>
                    <p class="text-muted small mb-0">Effectuer des inventaires physiques</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-6 mb-3">
        <a href="<?= base_url('admin/stock/rapports') ?>" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 10px;">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <i class="feather-bar-chart-2" style="font-size: 48px; color: #d29751;"></i>
                    </div>
                    <h6 class="mb-2">Rapports</h6>
                    <p class="text-muted small mb-0">Consulter les rapports et statistiques</p>
                </div>
            </div>
        </a>
    </div>
</div>

<?= $this->endSection() ?>
