<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="feather-tag me-2"></i>Catégories de Produits</h5>
            <button type="button" class="btn text-white" style="background: #d29751;" data-bs-toggle="modal" data-bs-target="#createCategorieModal">
                <i class="feather-plus me-2"></i>Nouvelle catégorie
            </button>
        </div>

        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th class="border-0">Nom</th>
                                <th class="border-0">Description</th>
                                <th class="border-0">Nombre de produits</th>
                                <th class="border-0">Statut</th>
                                <th class="border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($cat['nom']) ?></td>
                                    <td><?= esc($cat['description'] ?? '-') ?></td>
                                    <td><span class="badge bg-primary"><?= $cat['nb_produits'] ?></span></td>
                                    <td>
                                        <button class="btn btn-sm btn-toggle-status <?= $cat['actif'] == 1 ? 'btn-success' : 'btn-danger' ?>"
                                                data-id="<?= $cat['id'] ?>"
                                                data-status="<?= $cat['actif'] ?>">
                                            <?= $cat['actif'] == 1 ? 'Active' : 'Inactive' ?>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning btn-edit" data-id="<?= $cat['id'] ?>">
                                            <i class="feather-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $cat['id'] ?>">
                                            <i class="feather-trash-2"></i>
                                        </button>
                                    </td>
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
<div class="modal fade" id="createCategorieModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle Catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createCategorieForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
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

<!-- Modal Édition -->
<div class="modal fade" id="editCategorieModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier la Catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCategorieForm">
                <input type="hidden" id="edit_categorie_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
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
    $('#createCategorieForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= base_url('admin/stock/categories/create') ?>',
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

    $('.btn-edit').on('click', function() {
        const id = $(this).data('id');
        $.ajax({
            url: '<?= base_url('admin/stock/categories/get') ?>/' + id,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#edit_categorie_id').val(response.data.id);
                    $('#edit_nom').val(response.data.nom);
                    $('#edit_description').val(response.data.description);
                    $('#editCategorieModal').modal('show');
                }
            }
        });
    });

    $('#editCategorieForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit_categorie_id').val();
        $.ajax({
            url: '<?= base_url('admin/stock/categories/update') ?>/' + id,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                }
            }
        });
    });

    $('.btn-toggle-status').on('click', function() {
        const id = $(this).data('id');
        $.ajax({
            url: '<?= base_url('admin/stock/categories/toggle-status') ?>/' + id,
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                }
            }
        });
    });

    $('.btn-delete').on('click', function() {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')) {
            const id = $(this).data('id');
            $.ajax({
                url: '<?= base_url('admin/stock/categories/delete') ?>/' + id,
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
