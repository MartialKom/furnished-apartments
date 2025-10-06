<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="feather-box me-2"></i>
                Gestion des Produits
            </h5>
            <div>
                <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#categorieModal">
                    <i class="feather-tag me-2"></i>Catégories
                </button>
                <button type="button" class="btn text-white" style="background: #d29751;" data-bs-toggle="modal" data-bs-target="#createProduitModal">
                    <i class="feather-plus me-2"></i>Nouveau produit
                </button>
            </div>
        </div>

        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="produitsTable">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th class="border-0">Produit</th>
                                <th class="border-0">Catégorie</th>
                                <th class="border-0">Stock actuel</th>
                                <th class="border-0">Stock minimum</th>
                                <th class="border-0">Unité</th>
                                <th class="border-0">Prix moyen</th>
                                <th class="border-0">Statut</th>
                                <th class="border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($produits)): ?>
                                <?php foreach ($produits as $produit): ?>
                                    <tr data-produit-id="<?= $produit['id'] ?>">
                                        <td class="fw-semibold"><?= esc($produit['nom']) ?></td>
                                        <td><?= esc($produit['categorie_nom']) ?></td>
                                        <td>
                                            <?php
                                            $alertClass = $produit['stock_actuel'] <= $produit['stock_minimum'] ? 'text-danger' : 'text-success';
                                            ?>
                                            <span class="fw-bold <?= $alertClass ?>">
                                                <?= number_format($produit['stock_actuel'], 2) ?>
                                            </span>
                                        </td>
                                        <td><?= number_format($produit['stock_minimum'], 2) ?></td>
                                        <td><?= esc($produit['unite_mesure']) ?></td>
                                        <td><?= number_format($produit['prix_moyen'], 0, ',', ' ') ?> FCFA</td>
                                        <td>
                                            <button class="btn btn-sm btn-toggle-status <?= $produit['actif'] == 1 ? 'btn-success' : 'btn-danger' ?>"
                                                    data-id="<?= $produit['id'] ?>"
                                                    data-status="<?= $produit['actif'] ?>">
                                                <?= $produit['actif'] == 1 ? 'Actif' : 'Inactif' ?>
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-warning btn-edit" data-id="<?= $produit['id'] ?>" title="Modifier">
                                                <i class="feather-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $produit['id'] ?>" title="Supprimer">
                                                <i class="feather-trash-2"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <p class="text-muted mb-0">Aucun produit enregistré</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
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

<!-- Modal Création Produit -->
<div class="modal fade" id="createProduitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau Produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createProduitForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom du produit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nom" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select class="form-select" name="categorie_id" required>
                                <option value="">Sélectionner...</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= esc($cat['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Unité de mesure <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="unite_mesure" placeholder="Ex: pièce, litre, kg" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock minimum (alerte)</label>
                            <input type="number" step="0.01" class="form-control" name="stock_minimum" value="0">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn text-white" style="background: #d29751;">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Édition Produit -->
<div class="modal fade" id="editProduitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le Produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editProduitForm">
                <input type="hidden" id="edit_produit_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom du produit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nom" name="nom" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_categorie_id" name="categorie_id" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= esc($cat['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Unité de mesure <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_unite_mesure" name="unite_mesure" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock minimum (alerte)</label>
                            <input type="number" step="0.01" class="form-control" id="edit_stock_minimum" name="stock_minimum">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
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

<!-- Modal Catégories (liste rapide) - Création simplifiée -->
<div class="modal fade" id="categorieModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Catégories de Produits</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <a href="<?= base_url('admin/stock/categories') ?>" class="btn btn-sm text-white w-100 mb-3" style="background: #d29751;">
                    <i class="feather-external-link me-2"></i>Gérer les catégories
                </a>
                <div class="list-group">
                    <?php foreach ($categories as $cat): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?= esc($cat['nom']) ?></span>
                            <span class="badge bg-primary rounded-pill">Produits actifs</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Création produit
    $('#createProduitForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= base_url('admin/stock/produits/create') ?>',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message || 'Erreur lors de la création');
                }
            }
        });
    });

    // Édition produit
    $('.btn-edit').on('click', function() {
        const id = $(this).data('id');
        $.ajax({
            url: '<?= base_url('admin/stock/produits/get') ?>/' + id,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#edit_produit_id').val(response.data.id);
                    $('#edit_nom').val(response.data.nom);
                    $('#edit_categorie_id').val(response.data.categorie_id);
                    $('#edit_unite_mesure').val(response.data.unite_mesure);
                    $('#edit_stock_minimum').val(response.data.stock_minimum);
                    $('#edit_description').val(response.data.description);
                    $('#editProduitModal').modal('show');
                }
            }
        });
    });

    $('#editProduitForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit_produit_id').val();
        $.ajax({
            url: '<?= base_url('admin/stock/produits/update') ?>/' + id,
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

    // Toggle statut
    $('.btn-toggle-status').on('click', function() {
        const id = $(this).data('id');
        $.ajax({
            url: '<?= base_url('admin/stock/produits/toggle-status') ?>/' + id,
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                }
            }
        });
    });

    // Suppression
    $('.btn-delete').on('click', function() {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
            const id = $(this).data('id');
            $.ajax({
                url: '<?= base_url('admin/stock/produits/delete') ?>/' + id,
                method: 'DELETE',
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
    });
});
</script>

<?= $this->endSection() ?>
