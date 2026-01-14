<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header d-flex justify-content-between align-items-center" style="background: #fff; border-bottom: 1px solid #eee;">
                <h5 class="mb-0">
                    <i class="feather-file-text me-2" style="color: #d29751;"></i>
                    Liste des Rapports
                </h5>
                <button type="button" class="btn text-white" style="background: #d29751;" data-bs-toggle="modal" data-bs-target="#createRapportModal">
                    <i class="feather-plus me-2"></i>Écrire un rapport
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th class="border-0">Date</th>
                                <th class="border-0">Auteur</th>
                                <th class="border-0">Aperçu du rapport</th>
                                <th class="border-0 text-center">Document</th>
                                <th class="border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($rapports)): ?>
                                <?php foreach ($rapports as $rapport): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold text-primary">
                                                <?= date('d/m/Y', strtotime($rapport['date_rapport'])) ?>
                                            </div>
                                            <small class="text-muted">
                                                <?= date('H:i', strtotime($rapport['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2" style="width: 35px; height: 35px; background: linear-gradient(135deg, #d29751 0%, #b8834a 100%); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px;">
                                                    <?= strtoupper(substr($rapport['prenom'], 0, 1) . substr($rapport['nom'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold"><?= esc($rapport['prenom']) ?> <?= esc($rapport['nom']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted" style="max-width: 500px;">
                                                <?= strlen($rapport['contenu']) > 150 ?
                                                    substr(esc($rapport['contenu']), 0, 150) . '...' :
                                                    esc($rapport['contenu']) ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!empty($rapport['document_path'])): ?>
                                                <a href="<?= base_url('admin/rapports/download/' . $rapport['id']) ?>"
                                                   class="btn btn-sm btn-outline-success"
                                                   title="<?= esc($rapport['document_nom_original']) ?>">
                                                    <i class="feather-file"></i>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-info view-rapport"
                                                        data-id="<?= $rapport['id'] ?>"
                                                        title="Voir le rapport">
                                                    <i class="feather-eye"></i>
                                                </button>
                                                <?php if (session()->get('user_id') == $rapport['utilisateur_id'] || session()->get('user_role') == 'admin'): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-primary edit-rapport"
                                                            data-id="<?= $rapport['id'] ?>"
                                                            title="Modifier">
                                                        <i class="feather-edit-2"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-rapport"
                                                            data-id="<?= $rapport['id'] ?>"
                                                            title="Supprimer">
                                                        <i class="feather-trash-2"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="feather-file-text" style="font-size: 48px; color: #d29751; opacity: 0.5;"></i>
                                        <p class="text-muted mt-2">Aucun rapport trouvé</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de création de rapport -->
<div class="modal fade" id="createRapportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%);">
                <h5 class="modal-title text-white">
                    <i class="feather-plus me-2"></i>Écrire un rapport
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createRapportForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="date_rapport" class="form-label">Date du rapport <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date_rapport" name="date_rapport"
                                   value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="contenu" class="form-label">Contenu du rapport <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="contenu" name="contenu" rows="12"
                                      placeholder="Écrivez votre rapport ici..." required
                                      style="resize: vertical; font-size: 14px; line-height: 1.6;"></textarea>
                            <small class="text-muted">Minimum 10 caractères</small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="document" class="form-label">
                                <i class="feather-paperclip me-2"></i>Joindre un document (optionnel)
                            </label>
                            <input type="file" class="form-control" id="document" name="document"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                            <small class="text-muted">Formats acceptés: PDF, Word, Excel, Images (max 10 MB)</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8f9fa;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="feather-x me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn text-white" style="background: #d29751;">
                        <i class="feather-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de modification de rapport -->
<div class="modal fade" id="editRapportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%);">
                <h5 class="modal-title text-white">
                    <i class="feather-edit-2 me-2"></i>Modifier le rapport
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRapportForm">
                <input type="hidden" id="edit_rapport_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="edit_date_rapport" class="form-label">Date du rapport <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_date_rapport" name="date_rapport" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="edit_contenu" class="form-label">Contenu du rapport <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="edit_contenu" name="contenu" rows="12"
                                      required style="resize: vertical; font-size: 14px; line-height: 1.6;"></textarea>
                            <small class="text-muted">Minimum 10 caractères</small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="edit_document" class="form-label">
                                <i class="feather-paperclip me-2"></i>Remplacer le document (optionnel)
                            </label>
                            <input type="file" class="form-control" id="edit_document" name="document"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                            <small class="text-muted">Formats acceptés: PDF, Word, Excel, Images (max 10 MB)</small>
                            <div id="current_document" class="mt-2" style="display: none;">
                                <small class="text-info">
                                    <i class="feather-file me-1"></i>Document actuel : <span id="current_document_name"></span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background: #f8f9fa;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="feather-x me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn text-white" style="background: #d29751;">
                        <i class="feather-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de visualisation de rapport -->
<div class="modal fade" id="viewRapportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%);">
                <h5 class="modal-title text-white">
                    <i class="feather-file-text me-2"></i>Rapport
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Date :</strong>
                        <span id="view_date_rapport"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Auteur :</strong>
                        <span id="view_auteur"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <strong>Contenu :</strong>
                        <div id="view_contenu" class="mt-2 p-3" style="background: #f8f9fa; border-radius: 8px; white-space: pre-wrap; font-size: 14px; line-height: 1.8;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8f9fa;">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="feather-x me-2"></i>Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Création de rapport
    $('#createRapportForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '<?= base_url('admin/rapports/create') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#createRapportModal').modal('hide');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    if (response.errors) {
                        Object.values(response.errors).forEach(error => {
                            toastr.error(error);
                        });
                    } else {
                        toastr.error(response.message || 'Erreur lors de la création du rapport');
                    }
                }
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
                toastr.error('Erreur lors de la création du rapport');
            }
        });
    });

    // Visualiser un rapport
    $('.view-rapport').on('click', function() {
        const rapportId = $(this).data('id');

        $.ajax({
            url: '<?= base_url('admin/rapports/get') ?>/' + rapportId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const rapport = response.data;
                    $('#view_date_rapport').text(new Date(rapport.date_rapport).toLocaleDateString('fr-FR'));
                    $('#view_auteur').text(rapport.prenom + ' ' + rapport.nom);
                    $('#view_contenu').text(rapport.contenu);
                    $('#viewRapportModal').modal('show');
                } else {
                    toastr.error(response.message || 'Rapport non trouvé');
                }
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
                toastr.error('Erreur lors du chargement du rapport');
            }
        });
    });

    // Modifier un rapport
    $('.edit-rapport').on('click', function() {
        const rapportId = $(this).data('id');

        $.ajax({
            url: '<?= base_url('admin/rapports/get') ?>/' + rapportId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const rapport = response.data;
                    $('#edit_rapport_id').val(rapport.id);
                    $('#edit_date_rapport').val(rapport.date_rapport);
                    $('#edit_contenu').val(rapport.contenu);
                    $('#editRapportModal').modal('show');
                } else {
                    toastr.error(response.message || 'Rapport non trouvé');
                }
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
                toastr.error('Erreur lors du chargement du rapport');
            }
        });
    });

    // Soumission du formulaire de modification
    $('#editRapportForm').on('submit', function(e) {
        e.preventDefault();

        const rapportId = $('#edit_rapport_id').val();

        $.ajax({
            url: '<?= base_url('admin/rapports/update') ?>/' + rapportId,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#editRapportModal').modal('hide');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    if (response.errors) {
                        Object.values(response.errors).forEach(error => {
                            toastr.error(error);
                        });
                    } else {
                        toastr.error(response.message || 'Erreur lors de la modification');
                    }
                }
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
                toastr.error('Erreur lors de la modification du rapport');
            }
        });
    });

    // Supprimer un rapport
    $('.delete-rapport').on('click', function() {
        const rapportId = $(this).data('id');

        if (confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?')) {
            $.ajax({
                url: '<?= base_url('admin/rapports/delete') ?>/' + rapportId,
                type: 'POST',
                data: { _method: 'DELETE' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toastr.error(response.message || 'Erreur lors de la suppression');
                    }
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseText);
                    toastr.error('Erreur lors de la suppression du rapport');
                }
            });
        }
    });

    // Réinitialiser le formulaire lors de la fermeture du modal
    $('#createRapportModal').on('hidden.bs.modal', function() {
        $('#createRapportForm')[0].reset();
        $('#date_rapport').val('<?= date('Y-m-d') ?>');
    });
});
</script>

<?= $this->endSection() ?>
