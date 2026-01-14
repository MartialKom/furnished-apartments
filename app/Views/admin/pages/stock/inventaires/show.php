<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-0"><?= esc($inventaire['numero_inventaire']) ?></h5>
                <small class="text-muted">Date: <?= date('d/m/Y', strtotime($inventaire['date_inventaire'])) ?></small>
            </div>
            <div>
                <?php if ($inventaire['statut'] == 'en_cours'): ?>
                    <button class="btn btn-info" onclick="terminerInventaire()">
                        <i class="feather-check me-2"></i>Terminer
                    </button>
                <?php elseif ($inventaire['statut'] == 'termine'): ?>
                    <button class="btn btn-success" onclick="validerInventaire()">
                        <i class="feather-check-circle me-2"></i>Valider et ajuster les stocks
                    </button>
                <?php endif; ?>
                <a href="<?= base_url('admin/stock/inventaires') ?>" class="btn btn-secondary">
                    <i class="feather-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>

        <!-- Résumé écarts -->
        <?php if (!empty($resume)): ?>
        <div class="card mb-4" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body">
                <h6 class="mb-3">Résumé des écarts</h6>
                <div class="row text-center">
                    <div class="col-md-4">
                        <p class="text-muted mb-1">Nombre d'écarts</p>
                        <h4><?= $resume['nb_ecarts'] ?></h4>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted mb-1">Écarts positifs</p>
                        <h4 class="text-success">+<?= number_format($resume['ecart_positif'], 0, ',', ' ') ?> FCFA</h4>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted mb-1">Écarts négatifs</p>
                        <h4 class="text-danger">-<?= number_format($resume['ecart_negatif'], 0, ',', ' ') ?> FCFA</h4>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Détails -->
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th class="border-0">Produit</th>
                                <th class="border-0">Stock théorique</th>
                                <th class="border-0">Stock physique</th>
                                <th class="border-0">Écart</th>
                                <th class="border-0">Valeur écart</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inventaire['details'] as $detail): ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($detail['produit_nom']) ?></td>
                                    <td><?= number_format($detail['stock_theorique'], 2) ?> <?= esc($detail['unite_mesure']) ?></td>
                                    <td>
                                        <?php if ($inventaire['statut'] == 'en_cours'): ?>
                                            <input type="number" step="0.01" class="form-control form-control-sm stock-physique"
                                                   data-detail-id="<?= $detail['id'] ?>"
                                                   data-produit-id="<?= $detail['produit_id'] ?>"
                                                   value="<?= $detail['stock_physique'] ?>" style="width: 100px;">
                                        <?php else: ?>
                                            <?= number_format($detail['stock_physique'], 2) ?> <?= esc($detail['unite_mesure']) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $ecartClass = $detail['ecart'] > 0 ? 'text-success' : ($detail['ecart'] < 0 ? 'text-danger' : '');
                                        ?>
                                        <span class="fw-bold <?= $ecartClass ?>">
                                            <?= $detail['ecart'] > 0 ? '+' : '' ?><?= number_format($detail['ecart'], 2) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="<?= $ecartClass ?>">
                                            <?= $detail['valeur_ecart'] > 0 ? '+' : '' ?><?= number_format($detail['valeur_ecart'], 0, ',', ' ') ?> FCFA
                                        </span>
                                    </td>
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
$(document).ready(function() {
    $('.stock-physique').on('change', function() {
        const detailId = $(this).data('detail-id');
        const produitId = $(this).data('produit-id');
        const stockPhysique = $(this).val();

        $.ajax({
            url: '<?= base_url('admin/stock/inventaires/update-stock-physique') ?>',
            method: 'POST',
            data: {
                inventaire_id: <?= $inventaire['id'] ?>,
                produit_id: produitId,
                stock_physique: stockPhysique
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Stock mis à jour');
                    setTimeout(() => location.reload(), 1000);
                }
            }
        });
    });
});

function terminerInventaire() {
    if (confirm('Terminer cet inventaire ?')) {
        $.ajax({
            url: '<?= base_url('admin/stock/inventaires/terminer/'.$inventaire['id']) ?>',
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                }
            }
        });
    }
}

function validerInventaire() {
    if (confirm('Valider cet inventaire ? Les stocks seront ajustés selon les quantités physiques.')) {
        $.ajax({
            url: '<?= base_url('admin/stock/inventaires/valider/'.$inventaire['id']) ?>',
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
            }
        });
    }
}
</script>

<?= $this->endSection() ?>
