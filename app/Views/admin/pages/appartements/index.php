<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="feather-home me-2"></i>
                Gestion des Appartements
            </h5>
            <button type="button" class="btn text-white" style="background: #d29751;" data-bs-toggle="modal" data-bs-target="#createAppartementModal">
                <i class="feather-plus me-2"></i>Créer un appartement
            </button>
        </div>
        
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="appartementsTable">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th class="border-0">Adresse</th>
                                <th class="border-0">Tarif/Nuit</th>
                                <th class="border-0">Statut</th>
                                <th class="border-0">Équipements</th>
                                <th class="border-0">Date création</th>
                                <th class="border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($appartements)): ?>
                                <?php foreach ($appartements as $appartement): ?>
                                    <tr data-appartement-id="<?= $appartement['id'] ?>">
                                        <td>
                                            <div class="fw-semibold"><?= esc($appartement['adresse']) ?></div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success"><?= number_format($appartement['tarifs'], 0, ',', ' ') ?> FCFA</span>
                                        </td>
                                        <td>
                                            <?php
                                            $statutClass = match($appartement['statut']) {
                                                'disponible' => 'bg-success',
                                                'occupe' => 'bg-warning text-dark', 
                                                'maintenance' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };
                                            $statutText = match($appartement['statut']) {
                                                'disponible' => 'Disponible',
                                                'occupe' => 'Occupé',
                                                'maintenance' => 'Maintenance',
                                                default => $appartement['statut']
                                            };
                                            ?>
                                            <span class="badge <?= $statutClass ?>"><?= $statutText ?></span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= !empty($appartement['equipements']) ? 
                                                    (strlen($appartement['equipements']) > 50 ? 
                                                        substr(esc($appartement['equipements']), 0, 50) . '...' : 
                                                        esc($appartement['equipements'])) 
                                                    : 'Non spécifié' ?>
                                            </small>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('d/m/Y H:i', strtotime($appartement['created_at'])) ?>
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary edit-appartement" 
                                                        data-appartement-id="<?= $appartement['id'] ?>"
                                                        data-bs-toggle="tooltip" title="Modifier">
                                                    <i class="feather-edit-2"></i>
                                                </button>
                                                <?php if ($appartement['statut'] !== 'occupe'): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-warning toggle-status" 
                                                            data-appartement-id="<?= $appartement['id'] ?>"
                                                            data-current-status="<?= $appartement['statut'] ?>"
                                                            data-bs-toggle="tooltip" 
                                                            title="<?= $appartement['statut'] === 'disponible' ? 'Mettre en maintenance' : 'Remettre en service' ?>">
                                                        <i class="feather-<?= $appartement['statut'] === 'disponible' ? 'tool' : 'check-circle' ?>"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-appartement" 
                                                        data-appartement-id="<?= $appartement['id'] ?>"
                                                        data-appartement-adresse="<?= esc($appartement['adresse']) ?>"
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
                                        <i class="feather-home" style="font-size: 48px; color: #d29751; opacity: 0.5;"></i>
                                        <p class="text-muted mt-2">Aucun appartement trouvé</p>
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

<!-- Modal de création d'appartement -->
<div class="modal fade" id="createAppartementModal" tabindex="-1" aria-labelledby="createAppartementModalLabel" aria-hidden="true" style="z-index: 99999 !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%);">
                <h5 class="modal-title text-white" id="createAppartementModalLabel">
                    <i class="feather-plus me-2"></i>Créer un appartement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createAppartementForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="adresse" name="adresse" required placeholder="123 Rue de la Paix, Paris">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="tarifs" class="form-label">Tarif/Nuit (FCFA) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="tarifs" name="tarifs" required min="0" step="0.01" placeholder="50000">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                                <select class="form-select" id="statut" name="statut" required>
                                    <option value="">Sélectionner le statut</option>
                                    <option value="disponible">Disponible</option>
                                    <option value="maintenance">En maintenance</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="photos" class="form-label">Photos (URLs séparées par des virgules)</label>
                                <input type="text" class="form-control" id="photos" name="photos" placeholder="url1.jpg, url2.jpg, url3.jpg">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="equipements" class="form-label">Équipements</label>
                        <textarea class="form-control" id="equipements" name="equipements" rows="3" placeholder="WiFi, Climatisation, Cuisine équipée, Parking..."></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn text-white" style="background: #d29751;">
                        <i class="feather-save me-2"></i>Créer l'appartement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de modification d'appartement -->
<div class="modal fade" id="editAppartementModal" tabindex="-1" aria-labelledby="editAppartementModalLabel" aria-hidden="true" style="z-index: 99999 !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%);">
                <h5 class="modal-title text-white" id="editAppartementModalLabel">
                    <i class="feather-edit-2 me-2"></i>Modifier l'appartement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAppartementForm">
                <input type="hidden" id="editAppartementId" name="appartementId">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="editAdresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editAdresse" name="adresse" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editTarifs" class="form-label">Tarif/Nuit (FCFA) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="editTarifs" name="tarifs" required min="0" step="0.01">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editStatut" class="form-label">Statut <span class="text-danger">*</span></label>
                                <select class="form-select" id="editStatut" name="statut" required>
                                    <option value="">Sélectionner le statut</option>
                                    <option value="disponible">Disponible</option>
                                    <option value="maintenance">En maintenance</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editPhotos" class="form-label">Photos (URLs séparées par des virgules)</label>
                                <input type="text" class="form-control" id="editPhotos" name="photos">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editEquipements" class="form-label">Équipements</label>
                        <textarea class="form-control" id="editEquipements" name="equipements" rows="3"></textarea>
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

/* Modal fixes are handled in modal-fix.css */
</style>

<script>
$(document).ready(function() {
    // Initialiser les tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Modal fixes are now handled globally in main layout

    // Gérer le formulaire de création
    $('#createAppartementForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '<?= base_url("/admin/appartements/create") ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#createAppartementModal').modal('hide');
                    showToast('success', response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    if (response.errors) {
                        displayFormErrors('#createAppartementForm', response.errors);
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

    // Gérer le bouton de modification
    $(document).on('click', '.edit-appartement', function() {
        const appartementId = $(this).data('appartement-id');
        
        $.ajax({
            url: `<?= base_url("/admin/appartements/get") ?>/${appartementId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const appartement = response.appartement;
                    $('#editAppartementId').val(appartement.id);
                    $('#editAdresse').val(appartement.adresse);
                    $('#editTarifs').val(appartement.tarifs);
                    $('#editStatut').val(appartement.statut);
                    $('#editPhotos').val(appartement.photos);
                    $('#editEquipements').val(appartement.equipements);
                    
                    $('#editAppartementModal').modal('show');
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
    $('#editAppartementForm').on('submit', function(e) {
        e.preventDefault();
        
        const appartementId = $('#editAppartementId').val();
        const formData = new FormData(this);
        
        $.ajax({
            url: `<?= base_url("/admin/appartements/update") ?>/${appartementId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#editAppartementModal').modal('hide');
                    showToast('success', response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    if (response.errors) {
                        displayFormErrors('#editAppartementForm', response.errors);
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

    // Gérer le toggle du statut
    $(document).on('click', '.toggle-status', function() {
        const appartementId = $(this).data('appartement-id');
        const currentStatus = $(this).data('current-status');
        const action = currentStatus === 'disponible' ? 'mettre en maintenance' : 'remettre en service';
        
        if (confirm(`Êtes-vous sûr de vouloir ${action} cet appartement ?`)) {
            $.ajax({
                url: `<?= base_url("/admin/appartements/toggle-status") ?>/${appartementId}`,
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

    // Gérer la suppression
    $(document).on('click', '.delete-appartement', function() {
        const appartementId = $(this).data('appartement-id');
        const appartementAdresse = $(this).data('appartement-adresse');
        
        if (confirm(`Êtes-vous sûr de vouloir supprimer l'appartement "${appartementAdresse}" ?\n\nCette action est irréversible.`)) {
            $.ajax({
                url: `<?= base_url("/admin/appartements/delete") ?>/${appartementId}`,
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
    $('#createAppartementModal, #editAppartementModal').on('hidden.bs.modal', function() {
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