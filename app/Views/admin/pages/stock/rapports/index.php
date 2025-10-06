<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="feather-bar-chart-2 me-2"></i>Rapports Stock</h5>
        </div>

        <!-- Filtres de période -->
        <div class="card mb-4" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Date début</label>
                        <input type="date" class="form-control" name="date_debut" value="<?= $date_debut ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date fin</label>
                        <input type="date" class="form-control" name="date_fin" value="<?= $date_fin ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn text-white d-block w-100" style="background: #d29751;">
                            <i class="feather-filter me-2"></i>Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Valeur totale du stock</h6>
                        <h3 class="mb-0"><?= number_format($statistiques['valeur_stock'], 0, ',', ' ') ?> FCFA</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Entrées période</h6>
                        <h3 class="mb-0"><?= number_format($statistiques['approvisionnements']['montant_total'], 0, ',', ' ') ?> FCFA</h3>
                        <small class="text-muted"><?= $statistiques['approvisionnements']['total_approvisionnements'] ?> opérations</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Sorties période (valeur)</h6>
                        <h3 class="mb-0"><?= number_format($statistiques['sorties']['valeur_totale'], 0, ',', ' ') ?> FCFA</h3>
                        <small class="text-muted"><?= $statistiques['sorties']['total_sorties'] ?> opérations</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row mb-4">
            <div class="col-md-6">
                <button class="btn btn-primary w-100" onclick="imprimerStock()">
                    <i class="feather-printer me-2"></i>Imprimer l'état du stock
                </button>
            </div>
            <div class="col-md-6">
                <button class="btn btn-secondary w-100" onclick="imprimerSorties()">
                    <i class="feather-printer me-2"></i>Imprimer les sorties
                </button>
            </div>
        </div>

        <!-- Produits en alerte -->
        <?php if (!empty($produitsAlerte)): ?>
        <div class="card mb-4" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0"><i class="feather-alert-triangle text-warning me-2"></i>Produits en alerte</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th class="border-0">Produit</th>
                                <th class="border-0">Catégorie</th>
                                <th class="border-0">Stock actuel</th>
                                <th class="border-0">Stock minimum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produitsAlerte as $produit): ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($produit['nom']) ?></td>
                                    <td><?= esc($produit['categorie_nom']) ?></td>
                                    <td><span class="badge bg-danger"><?= number_format($produit['stock_actuel'], 2) ?></span></td>
                                    <td><?= number_format($produit['stock_minimum'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- État du stock -->
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0">État du stock actuel</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th class="border-0">Produit</th>
                                <th class="border-0">Catégorie</th>
                                <th class="border-0">Stock</th>
                                <th class="border-0">Prix moyen</th>
                                <th class="border-0">Valeur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produits as $produit): ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($produit['nom']) ?></td>
                                    <td><?= esc($produit['categorie_nom']) ?></td>
                                    <td><?= number_format($produit['stock_actuel'], 2) ?> <?= esc($produit['unite_mesure']) ?></td>
                                    <td><?= number_format($produit['prix_moyen'], 0, ',', ' ') ?> FCFA</td>
                                    <td><?= number_format($produit['stock_actuel'] * $produit['prix_moyen'], 0, ',', ' ') ?> FCFA</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function imprimerStock() {
    window.open('<?= base_url('admin/stock/rapports/imprimer-stock') ?>', '_blank');
}

function imprimerSorties() {
    const dateDebut = '<?= $date_debut ?>';
    const dateFin = '<?= $date_fin ?>';
    window.open(`<?= base_url('admin/stock/rapports/imprimer-sorties') ?>?date_debut=${dateDebut}&date_fin=${dateFin}`, '_blank');
}
</script>

<?= $this->endSection() ?>
