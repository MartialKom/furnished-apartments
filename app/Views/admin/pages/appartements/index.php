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
                    </div>
                    
                    <!-- Section Photos -->
                    <div class="mb-4">
                        <label class="form-label">Photos de l'appartement</label>
                        <div class="photo-upload-container">
                            <div id="photoDropZone" class="photo-drop-zone">
                                <div class="drop-zone-content">
                                    <i class="feather-camera" style="font-size: 2rem; color: #d29751;"></i>
                                    <p class="mb-2">Glissez vos photos ici ou <span class="text-primary">parcourez</span></p>
                                    <small class="text-muted">Formats acceptés: JPG, PNG, WebP (Max: 5MB chacune)</small>
                                </div>
                                <input type="file" id="photoFiles" name="photoFiles[]" multiple accept="image/jpeg,image/png,image/webp,image/jpg" style="display: none;">
                            </div>
                            <div id="photoPreviewContainer" class="photo-preview-container mt-3"></div>
                            <input type="hidden" id="photos" name="photos">
                        </div>
                    </div>
                    
                    <!-- Section Équipements -->
                    <div class="mb-3">
                        <label class="form-label">Équipements</label>
                        <div id="equipmentContainer">
                            <div class="equipment-row mb-2">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="feather-settings"></i></span>
                                    <input type="text" class="form-control equipment-input" placeholder="Ex: WiFi gratuit" name="equipment[]">
                                    <button type="button" class="btn btn-outline-danger remove-equipment" style="display: none;">
                                        <i class="feather-trash-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="addEquipment" class="btn btn-outline-primary btn-sm">
                            <i class="feather-plus me-1"></i>Ajouter un équipement
                        </button>
                        <input type="hidden" id="equipements" name="equipements">
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
                    </div>
                    
                    <!-- Section Photos pour modification -->
                    <div class="mb-4">
                        <label class="form-label">Photos de l'appartement</label>
                        <div class="photo-upload-container">
                            <div id="editPhotoDropZone" class="photo-drop-zone">
                                <div class="drop-zone-content">
                                    <i class="feather-camera" style="font-size: 2rem; color: #d29751;"></i>
                                    <p class="mb-2">Glissez vos photos ici ou <span class="text-primary">parcourez</span></p>
                                    <small class="text-muted">Formats acceptés: JPG, PNG, WebP (Max: 5MB chacune)</small>
                                </div>
                                <input type="file" id="editPhotoFiles" name="photoFiles[]" multiple accept="image/jpeg,image/png,image/webp,image/jpg" style="display: none;">
                            </div>
                            <div id="editPhotoPreviewContainer" class="photo-preview-container mt-3"></div>
                            <input type="hidden" id="editPhotos" name="photos">
                        </div>
                    </div>
                    
                    <!-- Section Équipements pour modification -->
                    <div class="mb-3">
                        <label class="form-label">Équipements</label>
                        <div id="editEquipmentContainer">
                            <div class="equipment-row mb-2">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="feather-settings"></i></span>
                                    <input type="text" class="form-control equipment-input" placeholder="Ex: WiFi gratuit" name="editEquipment[]">
                                    <button type="button" class="btn btn-outline-danger remove-equipment" style="display: none;">
                                        <i class="feather-trash-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="editAddEquipment" class="btn btn-outline-primary btn-sm">
                            <i class="feather-plus me-1"></i>Ajouter un équipement
                        </button>
                        <input type="hidden" id="editEquipements" name="equipements">
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

/* Photo Upload Styles */
.photo-drop-zone {
    border: 2px dashed #d29751;
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    background: rgba(210, 151, 81, 0.05);
    cursor: pointer;
    transition: all 0.3s ease;
}

.photo-drop-zone:hover,
.photo-drop-zone.drag-over {
    border-color: #b8834a;
    background: rgba(210, 151, 81, 0.1);
}

.photo-preview-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.photo-preview-item {
    position: relative;
    width: 120px;
    height: 120px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #e9ecef;
    background: #f8f9fa;
}

.photo-preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-preview-remove {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    background: rgba(220, 53, 69, 0.9);
    border: none;
    color: white;
    font-size: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.photo-preview-remove:hover {
    background: #dc3545;
}

/* Equipment Styles */
.equipment-row {
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.remove-equipment {
    transition: all 0.3s ease;
}

.equipment-input:focus {
    border-color: #d29751;
    box-shadow: 0 0 0 0.2rem rgba(210, 151, 81, 0.25);
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

    // Variables pour la gestion des photos
    let selectedFiles = [];
    let photoCounter = 0;

    // Gestion du drag & drop pour les photos
    const dropZone = $('#photoDropZone');
    const fileInput = $('#photoFiles');
    const previewContainer = $('#photoPreviewContainer');

    dropZone.on('click', function(e) {
        e.preventDefault();
        fileInput[0].click(); // Utiliser l'élément DOM natif
    });

    dropZone.on('dragover dragenter', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('drag-over');
    });

    dropZone.on('dragleave dragend', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('drag-over');
    });

    dropZone.on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('drag-over');
        
        const files = e.originalEvent.dataTransfer.files;
        handleFiles(files);
    });

    fileInput.on('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/') && file.size <= 5 * 1024 * 1024) { // 5MB max
                selectedFiles.push(file);
                addPhotoPreview(file);
            } else {
                showToast('error', 'Fichier non valide: ' + file.name + '. Seules les images de moins de 5MB sont acceptées.');
            }
        });
        updatePhotosInput();
    }

    function addPhotoPreview(file) {
        const reader = new FileReader();
        const photoId = 'photo_' + (++photoCounter);
        
        reader.onload = function(e) {
            const previewHtml = `
                <div class="photo-preview-item" data-photo-id="${photoId}">
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="photo-preview-remove" onclick="removePhoto('${photoId}')">
                        <i class="feather-x"></i>
                    </button>
                </div>
            `;
            previewContainer.append(previewHtml);
        };
        
        reader.readAsDataURL(file);
        file.photoId = photoId;
    }

    window.removePhoto = function(photoId) {
        // Supprimer de l'interface
        $(`.photo-preview-item[data-photo-id="${photoId}"]`).remove();
        
        // Supprimer du tableau des fichiers
        selectedFiles = selectedFiles.filter(file => file.photoId !== photoId);
        updatePhotosInput();
    };

    function updatePhotosInput() {
        // Les fichiers seront envoyés via FormData, pas besoin de mettre à jour le champ caché
        // Le champ caché est conservé pour compatibilité mais les vrais fichiers sont dans selectedFiles
    }

    // Gestion des équipements dynamiques
    let equipmentCounter = 1;

    $('#addEquipment').on('click', function() {
        const newRow = `
            <div class="equipment-row mb-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="feather-settings"></i></span>
                    <input type="text" class="form-control equipment-input" placeholder="Ex: Climatisation" name="equipment[]">
                    <button type="button" class="btn btn-outline-danger remove-equipment">
                        <i class="feather-trash-2"></i>
                    </button>
                </div>
            </div>
        `;
        $('#equipmentContainer').append(newRow);
        equipmentCounter++;
        updateRemoveButtons();
    });

    // Supprimer un équipement
    $(document).on('click', '.remove-equipment', function() {
        $(this).closest('.equipment-row').remove();
        updateRemoveButtons();
        updateEquipmentInput();
    });

    // Mettre à jour l'affichage des boutons de suppression
    function updateRemoveButtons() {
        const rows = $('.equipment-row');
        if (rows.length <= 1) {
            $('.remove-equipment').hide();
        } else {
            $('.remove-equipment').show();
        }
    }

    // Mettre à jour le champ caché des équipements
    function updateEquipmentInput() {
        const equipments = [];
        $('.equipment-input').each(function() {
            const value = $(this).val().trim();
            if (value) {
                equipments.push(value);
            }
        });
        $('#equipements').val(equipments.join(', '));
    }

    // Mettre à jour les équipements à chaque saisie
    $(document).on('input', '.equipment-input', function() {
        updateEquipmentInput();
    });

    // Gestion du formulaire de modification - Photos
    let editSelectedFiles = [];
    let editPhotoCounter = 0;

    const editDropZone = $('#editPhotoDropZone');
    const editFileInput = $('#editPhotoFiles');
    const editPreviewContainer = $('#editPhotoPreviewContainer');

    editDropZone.on('click', function(e) {
        e.preventDefault();
        editFileInput[0].click(); // Utiliser l'élément DOM natif
    });

    // Mêmes événements drag & drop pour le formulaire de modification
    editDropZone.on('dragover dragenter', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('drag-over');
    });

    editDropZone.on('dragleave dragend', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('drag-over');
    });

    editDropZone.on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('drag-over');
        
        const files = e.originalEvent.dataTransfer.files;
        handleEditFiles(files);
    });

    editFileInput.on('change', function() {
        handleEditFiles(this.files);
    });

    function handleEditFiles(files) {
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/') && file.size <= 5 * 1024 * 1024) {
                editSelectedFiles.push(file);
                addEditPhotoPreview(file);
            } else {
                showToast('error', 'Fichier non valide: ' + file.name + '. Seules les images de moins de 5MB sont acceptées.');
            }
        });
        updateEditPhotosInput();
    }

    function addEditPhotoPreview(file) {
        const reader = new FileReader();
        const photoId = 'edit_photo_' + (++editPhotoCounter);
        
        reader.onload = function(e) {
            const previewHtml = `
                <div class="photo-preview-item" data-photo-id="${photoId}">
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="photo-preview-remove" onclick="removeEditPhoto('${photoId}')">
                        <i class="feather-x"></i>
                    </button>
                </div>
            `;
            editPreviewContainer.append(previewHtml);
        };
        
        reader.readAsDataURL(file);
        file.photoId = photoId;
    }

    window.removeEditPhoto = function(photoId) {
        $(`.photo-preview-item[data-photo-id="${photoId}"]`).remove();
        editSelectedFiles = editSelectedFiles.filter(file => file.photoId !== photoId);
        updateEditPhotosInput();
    };

    function updateEditPhotosInput() {
        // Les fichiers seront envoyés via FormData
    }

    // Gestion des équipements pour la modification
    $('#editAddEquipment').on('click', function() {
        const newRow = `
            <div class="equipment-row mb-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="feather-settings"></i></span>
                    <input type="text" class="form-control equipment-input" placeholder="Ex: Climatisation" name="editEquipment[]">
                    <button type="button" class="btn btn-outline-danger remove-equipment">
                        <i class="feather-trash-2"></i>
                    </button>
                </div>
            </div>
        `;
        $('#editEquipmentContainer').append(newRow);
        updateEditRemoveButtons();
    });

    function updateEditRemoveButtons() {
        const rows = $('#editEquipmentContainer .equipment-row');
        if (rows.length <= 1) {
            $('#editEquipmentContainer .remove-equipment').hide();
        } else {
            $('#editEquipmentContainer .remove-equipment').show();
        }
    }

    function updateEditEquipmentInput() {
        const equipments = [];
        $('#editEquipmentContainer .equipment-input').each(function() {
            const value = $(this).val().trim();
            if (value) {
                equipments.push(value);
            }
        });
        $('#editEquipements').val(equipments.join(', '));
    }

    $(document).on('input', '#editEquipmentContainer .equipment-input', function() {
        updateEditEquipmentInput();
    });

    // Fonction pour charger les équipements existants
    function loadExistingEquipments(equipementsString) {
        // Vider le container
        $('#editEquipmentContainer').empty();
        
        if (equipementsString && equipementsString.trim()) {
            const equipements = equipementsString.split(',').map(e => e.trim()).filter(e => e);
            
            equipements.forEach((equipement, index) => {
                const showRemoveBtn = equipements.length > 1 ? '' : 'style="display: none;"';
                const newRow = `
                    <div class="equipment-row mb-2">
                        <div class="input-group">
                            <span class="input-group-text"><i class="feather-settings"></i></span>
                            <input type="text" class="form-control equipment-input" value="${equipement}" name="editEquipment[]">
                            <button type="button" class="btn btn-outline-danger remove-equipment" ${showRemoveBtn}>
                                <i class="feather-trash-2"></i>
                            </button>
                        </div>
                    </div>
                `;
                $('#editEquipmentContainer').append(newRow);
            });
        } else {
            // Si pas d'équipements, ajouter une ligne vide
            const newRow = `
                <div class="equipment-row mb-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="feather-settings"></i></span>
                        <input type="text" class="form-control equipment-input" placeholder="Ex: WiFi gratuit" name="editEquipment[]">
                        <button type="button" class="btn btn-outline-danger remove-equipment" style="display: none;">
                            <i class="feather-trash-2"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#editEquipmentContainer').append(newRow);
        }
        
        updateEditRemoveButtons();
        updateEditEquipmentInput();
    }

    // Fonction pour charger les photos existantes
    function loadExistingPhotos(photosString) {
        // Vider les photos
        editSelectedFiles = [];
        $('#editPhotoPreviewContainer').empty();
        $('#editPhotos').val('');
        
        if (photosString && photosString.trim()) {
            $('#editPhotos').val(photosString);
            // Note: Dans un vrai projet, vous afficheriez les photos existantes
            // Ici on ne fait que conserver la chaîne de caractères
        }
    }

    // Modal fixes are now handled globally in main layout

    // Gérer le formulaire de création
    $('#createAppartementForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Ajouter les fichiers sélectionnés
        selectedFiles.forEach(file => {
            formData.append('photoFiles[]', file);
        });
        
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
                    
                    // Charger les équipements existants
                    loadExistingEquipments(appartement.equipements);
                    
                    // Charger les photos existantes (si c'étaient des URLs)
                    loadExistingPhotos(appartement.photos);
                    
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
        
        // Ajouter les nouveaux fichiers sélectionnés
        editSelectedFiles.forEach(file => {
            formData.append('photoFiles[]', file);
        });
        
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
    $('#createAppartementModal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').text('');
        
        // Nettoyer les photos
        selectedFiles = [];
        $('#photoPreviewContainer').empty();
        $('#photos').val('');
        
        // Nettoyer les équipements (garder une ligne vide)
        $('#equipmentContainer').html(`
            <div class="equipment-row mb-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="feather-settings"></i></span>
                    <input type="text" class="form-control equipment-input" placeholder="Ex: WiFi gratuit" name="equipment[]">
                    <button type="button" class="btn btn-outline-danger remove-equipment" style="display: none;">
                        <i class="feather-trash-2"></i>
                    </button>
                </div>
            </div>
        `);
        $('#equipements').val('');
    });

    $('#editAppartementModal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').text('');
        
        // Nettoyer les photos d'édition
        editSelectedFiles = [];
        $('#editPhotoPreviewContainer').empty();
        $('#editPhotos').val('');
        
        // Nettoyer les équipements d'édition
        $('#editEquipmentContainer').html(`
            <div class="equipment-row mb-2">
                <div class="input-group">
                    <span class="input-group-text"><i class="feather-settings"></i></span>
                    <input type="text" class="form-control equipment-input" placeholder="Ex: WiFi gratuit" name="editEquipment[]">
                    <button type="button" class="btn btn-outline-danger remove-equipment" style="display: none;">
                        <i class="feather-trash-2"></i>
                    </button>
                </div>
            </div>
        `);
        $('#editEquipements').val('');
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