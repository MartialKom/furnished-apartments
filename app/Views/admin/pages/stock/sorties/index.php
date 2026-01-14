<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="feather-shopping-cart me-2"></i>Sorties de Stock</h5>
            <button type="button" class="btn text-white" style="background: #d29751;" data-bs-toggle="modal" data-bs-target="#createSortieModal">
                <i class="feather-plus me-2"></i>Nouvelle sortie
            </button>
        </div>

        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th class="border-0">Date</th>
                                <th class="border-0">Produit</th>
                                <th class="border-0">Quantité</th>
                                <th class="border-0">Destination</th>
                                <th class="border-0">Motif</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sorties as $sortie): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($sortie['date_sortie'])) ?></td>
                                    <td class="fw-semibold"><?= esc($sortie['produit_nom']) ?></td>
                                    <td><?= number_format($sortie['quantite'], 2) ?> <?= esc($sortie['unite_mesure']) ?></td>
                                    <td><?= esc($sortie['appartement_nom'] ?? $sortie['destination'] ?? '-') ?></td>
                                    <td><?= esc($sortie['motif'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if ($pager): ?>
                <div class="card-footer">
                    <div class="d-flex justify-content-center">
                        <?= $pager->links() ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Création -->
<div class="modal fade" id="createSortieModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle Sortie de Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createSortieForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Produit <span class="text-danger">*</span></label>
                            <select class="form-select" name="produit_id" id="produit_id" required>
                                <option value="">Sélectionner...</option>
                                <?php foreach ($produits as $prod): ?>
                                    <option value="<?= $prod['id'] ?>" data-stock="<?= $prod['stock_actuel'] ?>" data-unite="<?= $prod['unite_mesure'] ?>">
                                        <?= esc($prod['nom']) ?> (Stock: <?= $prod['stock_actuel'] ?> <?= $prod['unite_mesure'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted" id="stock_info"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date_sortie" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantité <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="quantite" id="quantite" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Appartement (optionnel)</label>
                            <select class="form-select" name="appartement_id">
                                <option value="">Sélectionner...</option>
                                <?php foreach ($appartements as $appart): ?>
                                    <option value="<?= $appart['id'] ?>"><?= esc($appart['adresse']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Autre destination</label>
                            <input type="text" class="form-control" name="destination" placeholder="Ex: Salle commune, Nettoyage, etc.">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Motif</label>
                            <input type="text" class="form-control" name="motif" placeholder="Ex: Distribution hebdomadaire">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn text-white" style="background: #d29751;">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#produit_id').on('change', function() {
        const stock = $(this).find(':selected').data('stock');
        const unite = $(this).find(':selected').data('unite');
        if (stock !== undefined) {
            $('#stock_info').text(`Stock disponible: ${stock} ${unite}`);
        }
    });

    $('#createSortieForm').on('submit', function(e) {
        e.preventDefault();
        const stock = $('#produit_id').find(':selected').data('stock');
        const quantite = parseFloat($('#quantite').val());

        if (quantite > stock) {
            toastr.error('Stock insuffisant!');
            return;
        }

        $.ajax({
            url: '<?= base_url('admin/stock/sorties/create') ?>',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
