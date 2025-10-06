<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="feather-truck me-2"></i>Approvisionnements</h5>
            <button type="button" class="btn text-white" style="background: #d29751;" data-bs-toggle="modal" data-bs-target="#createApproModal">
                <i class="feather-plus me-2"></i>Nouvel approvisionnement
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
                                <th class="border-0">Prix unitaire</th>
                                <th class="border-0">Total</th>
                                <th class="border-0">Fournisseur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($approvisionnements as $appro): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($appro['date_approvisionnement'])) ?></td>
                                    <td class="fw-semibold"><?= esc($appro['produit_nom']) ?></td>
                                    <td><?= number_format($appro['quantite'], 2) ?> <?= esc($appro['unite_mesure']) ?></td>
                                    <td><?= number_format($appro['prix_unitaire'], 0, ',', ' ') ?> FCFA</td>
                                    <td class="fw-bold"><?= number_format($appro['prix_total'], 0, ',', ' ') ?> FCFA</td>
                                    <td><?= esc($appro['fournisseur'] ?? '-') ?></td>
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
<div class="modal fade" id="createApproModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvel Approvisionnement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createApproForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Produit <span class="text-danger">*</span></label>
                            <select class="form-select" name="produit_id" required>
                                <option value="">Sélectionner...</option>
                                <?php foreach ($produits as $prod): ?>
                                    <option value="<?= $prod['id'] ?>"><?= esc($prod['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date_approvisionnement" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Quantité <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="quantite" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Prix unitaire <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="prix_unitaire" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Fournisseur</label>
                            <input type="text" class="form-control" name="fournisseur">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Référence facture</label>
                            <input type="text" class="form-control" name="reference_facture">
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
    $('#createApproForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= base_url('admin/stock/approvisionnements/create') ?>',
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
