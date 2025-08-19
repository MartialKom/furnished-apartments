<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="feather-user-check me-2"></i>
                Gestion des Utilisateurs
            </h5>
            <button type="button" class="btn text-white" style="background: #d29751;" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="feather-plus me-2"></i>Créer un utilisateur
            </button>
        </div>
        
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="usersTable">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th class="border-0">Utilisateur</th>
                                <th class="border-0">Contact</th>
                                <th class="border-0">Rôle</th>
                                <th class="border-0">Statut</th>
                                <th class="border-0">Date création</th>
                                <th class="border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($utilisateurs)): ?>
                                <?php foreach ($utilisateurs as $utilisateur): ?>
                                    <tr data-user-id="<?= $utilisateur['id'] ?>">
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-text avatar-md" style="background: #d29751; color: white;">
                                                    <?= strtoupper(substr($utilisateur['prenom'], 0, 1) . substr($utilisateur['nom'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold"><?= esc($utilisateur['prenom']) ?> <?= esc($utilisateur['nom']) ?></div>
                                                    <small class="text-muted">@<?= esc($utilisateur['nomUtilisateur']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <?php if (!empty($utilisateur['email'])): ?>
                                                    <div class="fw-medium"><?= esc($utilisateur['email']) ?></div>
                                                <?php endif; ?>
                                                <small class="text-muted"><?= esc($utilisateur['telephone']) ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge <?= $utilisateur['role'] === 'admin' ? 'bg-danger' : 'bg-info' ?>">
                                                <?= $utilisateur['role'] === 'admin' ? 'Administrateur' : 'Gestionnaire' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge status-badge <?= ($utilisateur['statut'] ?? 'actif') === 'actif' ? 'bg-success' : 'bg-secondary' ?>">
                                                <?= ($utilisateur['statut'] ?? 'actif') === 'actif' ? 'Actif' : 'Inactif' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('d/m/Y H:i', strtotime($utilisateur['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary edit-user" 
                                                        data-user-id="<?= $utilisateur['id'] ?>"
                                                        data-bs-toggle="tooltip" title="Modifier">
                                                    <i class="feather-edit-2"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-warning toggle-status" 
                                                        data-user-id="<?= $utilisateur['id'] ?>"
                                                        data-current-status="<?= $utilisateur['statut'] ?? 'actif' ?>"
                                                        data-bs-toggle="tooltip" 
                                                        title="<?= ($utilisateur['statut'] ?? 'actif') === 'actif' ? 'Désactiver' : 'Activer' ?>">
                                                    <i class="feather-<?= ($utilisateur['statut'] ?? 'actif') === 'actif' ? 'user-x' : 'user-check' ?>"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-user" 
                                                        data-user-id="<?= $utilisateur['id'] ?>"
                                                        data-user-name="<?= esc($utilisateur['prenom'] . ' ' . $utilisateur['nom']) ?>"
                                                        data-bs-toggle="tooltip" title="Supprimer">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="feather-users" style="font-size: 48px; color: #d29751; opacity: 0.5;"></i>
                                        <p class="text-muted mt-2">Aucun utilisateur trouvé</p>
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

<!-- Modal de création d'utilisateur -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true" style="z-index: 99999 !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%);">
                <h5 class="modal-title text-white" id="createUserModalLabel">
                    <i class="feather-user-plus me-2"></i>Créer un utilisateur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createUserForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nomUtilisateur" class="form-label">Nom d'utilisateur <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nomUtilisateur" name="nomUtilisateur" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Sélectionner un rôle</option>
                                    <option value="gestionnaire">Gestionnaire</option>
                                    <option value="admin">Administrateur</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="motDePasse" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="motDePasse" name="motDePasse" required minlength="6">
                        <div class="form-text">Le mot de passe doit contenir au moins 6 caractères.</div>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn text-white" style="background: #d29751;">
                        <i class="feather-save me-2"></i>Créer l'utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de modification d'utilisateur -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true" style="z-index: 99999 !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%);">
                <h5 class="modal-title text-white" id="editUserModalLabel">
                    <i class="feather-edit-2 me-2"></i>Modifier l'utilisateur
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm">
                <input type="hidden" id="editUserId" name="userId">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editPrenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editPrenom" name="prenom" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editNom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editNom" name="nom" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editNomUtilisateur" class="form-label">Nom d'utilisateur <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editNomUtilisateur" name="nomUtilisateur" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editTelephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="editTelephone" name="telephone" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editRole" class="form-label">Rôle <span class="text-danger">*</span></label>
                                <select class="form-select" id="editRole" name="role" required>
                                    <option value="">Sélectionner un rôle</option>
                                    <option value="gestionnaire">Gestionnaire</option>
                                    <option value="admin">Administrateur</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editMotDePasse" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="editMotDePasse" name="motDePasse" minlength="6">
                        <div class="form-text">Laissez vide pour conserver le mot de passe actuel.</div>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn text-white" style="background: #d29751;">
                        <i class="feather-save me-2"></i>Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.btn:hover {
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: rgba(210, 151, 81, 0.05);
}

.avatar-text {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-weight: 600;
    font-size: 14px;
}

/* Fix modal z-index - must be higher than admin layout elements */
</style>

<script>
$(document).ready(function() {
    // Initialiser les tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Fix modal z-index when showing
    $('.modal').on('show.bs.modal', function () {
        var zIndex = 99999 + $('.modal:visible').length;
        $(this).css('z-index', zIndex);
        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });

    // Gérer le formulaire de création
    $('#createUserForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '<?= base_url("/admin/utilisateurs/create") ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#createUserModal').modal('hide');
                    showToast('success', response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    if (response.errors) {
                        displayFormErrors('#createUserForm', response.errors);
                    } else {
                        showToast('error', response.message);
                    }
                }
            },
            error: function() {
                showToast('error', 'Une erreur est survenue lors de la création.');
            }
        });
    });

    // Gérer le bouton de modification (event delegation)
    $(document).on('click', '.edit-user', function() {
        const userId = $(this).data('user-id');
        
        $.ajax({
            url: `<?= base_url("/admin/utilisateurs/get") ?>/${userId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const user = response.utilisateur;
                    $('#editUserId').val(user.id);
                    $('#editPrenom').val(user.prenom);
                    $('#editNom').val(user.nom);
                    $('#editNomUtilisateur').val(user.nomUtilisateur);
                    $('#editTelephone').val(user.telephone);
                    $('#editEmail').val(user.email);
                    $('#editRole').val(user.role);
                    $('#editMotDePasse').val('');
                    
                    $('#editUserModal').modal('show');
                } else {
                    showToast('error', response.message);
                }
            },
            error: function() {
                showToast('error', 'Erreur lors du chargement des données.');
            }
        });
    });

    // Gérer le formulaire de modification
    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();
        
        const userId = $('#editUserId').val();
        const formData = new FormData(this);
        
        $.ajax({
            url: `<?= base_url("/admin/utilisateurs/update") ?>/${userId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#editUserModal').modal('hide');
                    showToast('success', response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    if (response.errors) {
                        displayFormErrors('#editUserForm', response.errors);
                    } else {
                        showToast('error', response.message);
                    }
                }
            },
            error: function() {
                showToast('error', 'Une erreur est survenue lors de la modification.');
            }
        });
    });

    // Gérer le toggle du statut (event delegation)
    $(document).on('click', '.toggle-status', function() {
        const userId = $(this).data('user-id');
        const currentStatus = $(this).data('current-status');
        const action = currentStatus === 'actif' ? 'désactiver' : 'activer';
        
        if (confirm(`Êtes-vous sûr de vouloir ${action} cet utilisateur ?`)) {
            $.ajax({
                url: `<?= base_url("/admin/utilisateurs/toggle-status") ?>/${userId}`,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function() {
                    showToast('error', 'Erreur lors de la modification du statut.');
                }
            });
        }
    });

    // Gérer la suppression (event delegation)
    $(document).on('click', '.delete-user', function() {
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');
        
        if (confirm(`Êtes-vous sûr de vouloir supprimer l'utilisateur "${userName}" ?\n\nCette action est irréversible.`)) {
            $.ajax({
                url: `<?= base_url("/admin/utilisateurs/delete") ?>/${userId}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function() {
                    showToast('error', 'Erreur lors de la suppression.');
                }
            });
        }
    });

    // Fonction pour afficher les erreurs de formulaire
    function displayFormErrors(formSelector, errors) {
        // Reset previous errors
        $(formSelector + ' .is-invalid').removeClass('is-invalid');
        $(formSelector + ' .invalid-feedback').text('');
        
        // Display new errors
        for (const field in errors) {
            const input = $(formSelector + ` [name="${field}"]`);
            if (input.length) {
                input.addClass('is-invalid');
                input.siblings('.invalid-feedback').text(errors[field]);
            }
        }
    }

    // Reset form on modal hide
    $('#createUserModal, #editUserModal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').text('');
    });
});

// Fonction pour afficher les toasts
function showToast(type, message) {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="feather-${type === 'success' ? 'check-circle' : 'alert-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    // Create toast container if it doesn't exist
    if (!$('#toast-container').length) {
        $('body').append('<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>');
    }
    
    const $toast = $(toastHtml);
    $('#toast-container').append($toast);
    
    const toast = new bootstrap.Toast($toast[0]);
    toast.show();
    
    // Remove toast element after it's hidden
    $toast.on('hidden.bs.toast', function() {
        $(this).remove();
    });
}
</script>

<?= $this->endSection() ?>