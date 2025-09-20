<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Liste des Locataires</h5>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLocataireModal">
                    <i class="feather-plus"></i> Ajouter un Locataire
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="locatairesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Date d'inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($locataires)): ?>
                                <?php foreach ($locataires as $locataire): ?>
                                    <tr>
                                        <td><?= $locataire['id'] ?></td>
                                        <td><?= esc($locataire['nom']) ?></td>
                                        <td><?= esc($locataire['email']) ?></td>
                                        <td><?= esc($locataire['telephone']) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($locataire['created_at'])) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info edit-locataire" data-id="<?= $locataire['id'] ?>">
                                                <i class="feather-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-locataire" data-id="<?= $locataire['id'] ?>">
                                                <i class="feather-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Aucun locataire trouvé</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter Locataire -->
<div class="modal fade" id="addLocataireModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Locataire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addLocataireForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="telephone" name="telephone">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier Locataire -->
<div class="modal fade" id="editLocataireModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le Locataire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editLocataireForm">
                <input type="hidden" id="edit_locataire_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nom" class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_telephone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="edit_telephone" name="telephone">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Ajouter un locataire
    $('#addLocataireForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?= base_url('/admin/locataires/create') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Erreur: ' + response.message);
                }
            },
            error: function() {
                alert('Erreur lors de l\'ajout du locataire');
            }
        });
    });

    // Modifier un locataire
    $('.edit-locataire').on('click', function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: '<?= base_url('/admin/locataires/get/') ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const locataire = response.locataire;
                    $('#edit_locataire_id').val(locataire.id);
                    $('#edit_nom').val(locataire.nom);
                    $('#edit_email').val(locataire.email);
                    $('#edit_telephone').val(locataire.telephone);
                    $('#editLocataireModal').modal('show');
                }
            }
        });
    });

    $('#editLocataireForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit_locataire_id').val();
        
        $.ajax({
            url: '<?= base_url('/admin/locataires/update/') ?>' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Erreur: ' + response.message);
                }
            }
        });
    });

    // Supprimer un locataire
    $('.delete-locataire').on('click', function() {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce locataire ?')) {
            const id = $(this).data('id');
            
            $.ajax({
                url: '<?= base_url('/admin/locataires/delete/') ?>' + id,
                type: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Erreur: ' + response.message);
                    }
                }
            });
        }
    });
});
</script>
<?= $this->endSection() ?>
