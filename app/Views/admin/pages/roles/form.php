<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><?= $role ? 'Modifier le Rôle' : 'Créer un Rôle' ?></h5>
            </div>
            <form id="roleForm" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="feather-info"></i>
                        <strong>Information:</strong> Configurez le rôle en sélectionnant les permissions appropriées.
                        Les utilisateurs assignés à ce rôle auront accès uniquement aux modules cochés.
                    </div>

                    <!-- Role Information -->
                    <h6 class="mb-3">Informations du Rôle</h6>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom du Rôle <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nom" name="nom"
                                       value="<?= $role ? esc($role['nom']) : '' ?>"
                                       placeholder="Ex: Réceptionniste, Comptable, etc."
                                       required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Code Unique <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="code" name="code"
                                       value="<?= $role ? esc($role['code']) : '' ?>"
                                       placeholder="Ex: receptionniste, comptable"
                                       <?= $role && $role['is_system'] == 1 ? 'readonly' : '' ?>
                                       required>
                                <small class="form-text text-muted">Utilisé en interne (lettres minuscules, chiffres et underscores uniquement)</small>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="2"
                                          placeholder="Description du rôle..."><?= $role ? esc($role['description']) : '' ?></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Permissions Section -->
                    <div class="row align-items-center mb-3">
                        <div class="col-md-6">
                            <h6 class="mb-0">Attribution des Permissions</h6>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-success" id="selectAll">
                                    <i class="feather-check-square"></i> Tout Cocher
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="deselectAll">
                                    <i class="feather-x-square"></i> Tout Décocher
                                </button>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($permissions)): ?>
                        <?php foreach ($permissions as $groupe => $groupPermissions): ?>
                            <div class="card mb-3 permission-group">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="feather-folder me-2 text-primary"></i>
                                            <?= esc($groupe) ?>
                                        </h6>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-success select-group" data-group="<?= esc($groupe) ?>">
                                                <i class="feather-check"></i> Tout
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary deselect-group" data-group="<?= esc($groupe) ?>">
                                                <i class="feather-x"></i> Aucun
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php foreach ($groupPermissions as $permission): ?>
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input permission-checkbox"
                                                           type="checkbox"
                                                           name="permissions[]"
                                                           value="<?= $permission['id'] ?>"
                                                           id="perm_<?= $permission['id'] ?>"
                                                           data-group="<?= esc($groupe) ?>"
                                                           <?= in_array($permission['id'], $selectedPermissions) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="perm_<?= $permission['id'] ?>">
                                                        <strong><?= esc($permission['nom']) ?></strong>
                                                        <?php if (!empty($permission['description'])): ?>
                                                            <i class="feather-info text-muted ms-1"
                                                               data-bs-toggle="tooltip"
                                                               data-bs-placement="top"
                                                               title="<?= esc($permission['description']) ?>"></i>
                                                        <?php endif; ?>
                                                        <br>
                                                        <small class="text-muted"><code><?= esc($permission['code']) ?></code></small>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            Aucune permission disponible. Veuillez d'abord créer des permissions.
                        </div>
                    <?php endif; ?>

                    <div class="mt-4 pt-4 border-top">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= base_url('/admin/roles') ?>" class="btn btn-lg btn-light">
                                        <i class="feather-x"></i> Annuler
                                    </a>
                                    <button type="submit" class="btn btn-lg btn-primary" id="submitBtn">
                                        <i class="feather-save"></i>
                                        <?= $role ? 'Enregistrer les Modifications' : 'Créer le Rôle' ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Select all permissions
    $('#selectAll').on('click', function() {
        $('.permission-checkbox').prop('checked', true);
    });

    // Deselect all permissions
    $('#deselectAll').on('click', function() {
        $('.permission-checkbox').prop('checked', false);
    });

    // Select all in group
    $('.select-group').on('click', function() {
        const group = $(this).data('group');
        $(`.permission-checkbox[data-group="${group}"]`).prop('checked', true);
    });

    // Deselect all in group
    $('.deselect-group').on('click', function() {
        const group = $(this).data('group');
        $(`.permission-checkbox[data-group="${group}"]`).prop('checked', false);
    });

    // Auto-generate code from nom
    $('#nom').on('input', function() {
        <?php if (!$role || $role['is_system'] != 1): ?>
        const nom = $(this).val();
        let code = nom.toLowerCase()
                      .replace(/[éèêë]/g, 'e')
                      .replace(/[àâä]/g, 'a')
                      .replace(/[îï]/g, 'i')
                      .replace(/[ôö]/g, 'o')
                      .replace(/[ùûü]/g, 'u')
                      .replace(/[ç]/g, 'c')
                      .replace(/[^a-z0-9]/g, '_')
                      .replace(/_+/g, '_')
                      .replace(/^_|_$/g, '');
        $('#code').val(code);
        <?php endif; ?>
    });

    // Form submission
    $('#roleForm').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = $('#submitBtn');
        const originalBtnText = submitBtn.html();

        // Clear previous errors and alerts
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('.alert:not(.alert-info)').remove();

        // Disable submit button
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement...');

        const formData = form.serialize();
        const url = <?= $role ? "'/admin/roles/update/{$role['id']}'" : "'/admin/roles/store'" ?>;

        console.log('=== SOUMISSION DU FORMULAIRE ===');
        console.log('URL:', url);
        console.log('Form Data:', formData);
        console.log('Permissions sélectionnées:', $('.permission-checkbox:checked').length);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                console.log('=== RÉPONSE DU SERVEUR ===');
                console.log('Response:', response);

                if (response.success) {
                    // Show success message
                    const alertHtml = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="feather-check-circle"></i> ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    $('.card-body').prepend(alertHtml);

                    console.log('✓ Succès - Redirection dans 1.5s vers:', response.redirect || '/admin/roles');

                    // Redirect after 1.5 seconds
                    setTimeout(function() {
                        window.location.href = response.redirect || '/admin/roles';
                    }, 1500);
                } else {
                    console.error('✗ Échec:', response.message);
                    console.error('Erreurs:', response.errors);
                    // Show error message
                    if (response.message) {
                        const alertHtml = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="feather-alert-circle"></i> ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `;
                        $('.card-body').prepend(alertHtml);
                    }

                    // Show field errors
                    if (response.errors) {
                        for (const [field, error] of Object.entries(response.errors)) {
                            const input = $(`#${field}`);
                            input.addClass('is-invalid');
                            input.siblings('.invalid-feedback').text(error);
                        }
                    }

                    // Re-enable submit button
                    submitBtn.prop('disabled', false).html(originalBtnText);
                }
            },
            error: function(xhr, status, error) {
                console.error('=== ERREUR AJAX ===');
                console.error('Status:', xhr.status);
                console.error('Status Text:', xhr.statusText);
                console.error('Error:', error);
                console.error('Response Text:', xhr.responseText);
                console.error('Response JSON:', xhr.responseJSON);

                let errorMessage = 'Une erreur est survenue lors de l\'enregistrement.';
                let errorDetails = '';

                if (xhr.status === 0) {
                    errorMessage = 'Impossible de contacter le serveur. Vérifiez votre connexion.';
                } else if (xhr.status === 403) {
                    errorMessage = 'Accès refusé. Vérifiez vos permissions.';
                } else if (xhr.status === 404) {
                    errorMessage = 'URL non trouvée: ' + url;
                } else if (xhr.status === 419) {
                    errorMessage = 'Session expirée. Rechargez la page et réessayez.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Erreur serveur interne.';
                    errorDetails = xhr.responseText ? '<pre class="mt-2 small">' + xhr.responseText.substring(0, 500) + '</pre>' : '';
                }

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                const alertHtml = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="feather-alert-circle"></i> <strong>Erreur ${xhr.status}:</strong> ${errorMessage}
                        ${errorDetails}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('.card-body').prepend(alertHtml);

                // Re-enable submit button
                submitBtn.prop('disabled', false).html(originalBtnText);
            }
        });
    });

    // Count selected permissions
    function updatePermissionCount() {
        const count = $('.permission-checkbox:checked').length;
        console.log(`${count} permission(s) sélectionnée(s)`);
    }

    $('.permission-checkbox').on('change', updatePermissionCount);
    updatePermissionCount();
});
</script>
<?= $this->endSection() ?>
