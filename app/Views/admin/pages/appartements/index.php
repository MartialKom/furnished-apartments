<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="<?= base_url('assets/admin/css/dataTables.bs5.min.css') ?>">

<style>
    /* Styles personnalisés pour DataTables */
    div.dataTables_wrapper div.dataTables_length select {
        padding: 5px 10px !important;
        border-radius: 5px !important;
        border: 1px solid #e9ecef !important;
        width: auto !important;
        display: inline-block !important;
    }

    div.dataTables_wrapper div.dataTables_filter input {
        padding: 5px 15px !important;
        border-radius: 5px !important;
        border: 1px solid #e9ecef !important;
        margin-left: 10px !important;
    }

    div.dataTables_wrapper div.dataTables_paginate ul.pagination {
        margin: 10px 0 !important;
    }

    div.dataTables_wrapper div.dataTables_paginate .paginate_button.page-item.active .page-link {
        background-color: #d29751 !important;
        border-color: #d29751 !important;
        color: white !important;
    }

    div.dataTables_wrapper div.dataTables_paginate .paginate_button.page-item .page-link:hover {
        background-color: #b8834a !important;
        border-color: #b8834a !important;
        color: white !important;
    }

    div.dataTables_wrapper div.dataTables_paginate .paginate_button.page-item .page-link {
        border-radius: 5px !important;
        margin: 0 2px !important;
    }

    div.dataTables_wrapper div.dataTables_info {
        padding-top: 15px !important;
        color: #6c757d !important;
    }

    div.dataTables_wrapper div.dataTables_length,
    div.dataTables_wrapper div.dataTables_filter {
        margin-bottom: 15px !important;
    }

    div.dataTables_wrapper div.dataTables_filter label,
    div.dataTables_wrapper div.dataTables_length label {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
    }
</style>

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
                                <th class="border-0">Catégorie</th>
                                <th class="border-0">Type</th>
                                <th class="border-0">Chambres</th>
                                <th class="border-0">Tarif</th>
                                <th class="border-0">Statut</th>
                                <th class="border-0 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($appartements)): ?>
                                <?php foreach ($appartements as $appartement): ?>
                                    <tr data-appartement-id="<?= $appartement['id'] ?>">
                                        <td>
                                            <div class="fw-semibold"><?= esc($appartement['adresse']) ?></div>
                                            <?php if (!empty($appartement['numero_bien'])): ?>
                                                <small class="text-muted"><i class="feather-hash"></i> <?= esc($appartement['numero_bien']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $categorieClass = match($appartement['categorie'] ?? 'appartement') {
                                                'appartement' => 'bg-info',
                                                'logement' => 'bg-warning text-dark',
                                                'boutique' => 'bg-secondary text-white',
                                                default => 'bg-secondary'
                                            };
                                            $categorieText = match($appartement['categorie'] ?? 'appartement') {
                                                'appartement' => 'Appartement',
                                                'logement' => 'Logement',
                                                'boutique' => 'Boutique',
                                                default => ucfirst($appartement['categorie'] ?? 'Appartement')
                                            };
                                            ?>
                                            <span class="badge <?= $categorieClass ?>"><?= $categorieText ?></span>
                                            <?php if (!empty($appartement['superficie'])): ?>
                                                <br><small class="text-muted"><?= $appartement['superficie'] ?> m²</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $typeClass = match($appartement['type'] ?? 'meuble') {
                                                'meuble' => 'bg-primary',
                                                'non_meuble' => 'bg-secondary',
                                                default => 'bg-secondary'
                                            };
                                            $typeText = match($appartement['type'] ?? 'meuble') {
                                                'meuble' => 'Meublé',
                                                'non_meuble' => 'Non meublé',
                                                default => 'Meublé'
                                            };
                                            ?>
                                            <span class="badge <?= $typeClass ?>"><?= $typeText ?></span>
                                        </td>
                                        <td>
                                            <?php if (!empty($appartement['nombre_chambres'])): ?>
                                                <span class="badge bg-dark"><?= $appartement['nombre_chambres'] ?> ch.</span>
                                            <?php else: ?>
                                                <small class="text-muted">-</small>
                                            <?php endif; ?>
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
                                                <button type="button" class="btn btn-sm btn-outline-info toggle-type" 
                                                        data-appartement-id="<?= $appartement['id'] ?>"
                                                        data-current-type="<?= $appartement['type'] ?? 'meuble' ?>"
                                                        data-bs-toggle="tooltip" 
                                                        title="Changer le type">
                                                    <i class="feather-refresh-cw"></i>
                                                </button>
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
                                    <td colspan="7" class="text-center py-5">
                                        <i class="feather-home" style="font-size: 48px; color: #d29751; opacity: 0.5;"></i>
                                        <p class="text-muted mt-2">Aucun bien trouvé</p>
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
            <form action="<?= base_url('appartements/create') ?>" method="POST" id="createAppartementForm">
                <?= csrf_field() ?>
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
                                <label for="categorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                                <select class="form-select" id="categorie" name="categorie" required>
                                    <option value="">Sélectionner la catégorie</option>
                                    <option value="appartement">Appartement (2-3 chambres)</option>
                                    <option value="logement">Logement (Studio, Maison, etc.)</option>
                                    <option value="boutique">Boutique / Local commercial</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Sélectionner le type</option>
                                    <option value="meuble">Meublé</option>
                                    <option value="non_meuble">Non meublé</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4" id="nombreChambresContainer" style="display: none;">
                            <div class="mb-3">
                                <label for="nombre_chambres" class="form-label">Nombre de chambres <span class="text-danger">*</span></label>
                                <select class="form-select" id="nombre_chambres" name="nombre_chambres">
                                    <option value="">Sélectionner</option>
                                    <option value="2">2 chambres</option>
                                    <option value="3">3 chambres</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="superficie" class="form-label">Superficie (m²)</label>
                                <input type="number" class="form-control" id="superficie" name="superficie" min="0" step="0.01" placeholder="65.5">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="etage" class="form-label">Étage</label>
                                <input type="number" class="form-control" id="etage" name="etage" placeholder="Ex: 3">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="numero_bien" class="form-label">Numéro / Référence du bien</label>
                                <input type="text" class="form-control" id="numero_bien" name="numero_bien" maxlength="50" placeholder="Ex: APT-A301">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
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

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description détaillée du bien..."></textarea>
                        <div class="invalid-feedback"></div>
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
                            </div>
                            <input type="file" id="photoFiles" name="photoFiles[]" multiple accept="image/jpeg,image/png,image/webp,image/jpg" style="display: none;">
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
                                <label for="editCategorie" class="form-label">Catégorie <span class="text-danger">*</span></label>
                                <select class="form-select" id="editCategorie" name="categorie" required>
                                    <option value="">Sélectionner la catégorie</option>
                                    <option value="appartement">Appartement (2-3 chambres)</option>
                                    <option value="logement">Logement (Studio, Maison, etc.)</option>
                                    <option value="boutique">Boutique / Local commercial</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editType" class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="editType" name="type" required>
                                    <option value="">Sélectionner le type</option>
                                    <option value="meuble">Meublé</option>
                                    <option value="non_meuble">Non meublé</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4" id="editNombreChambresContainer" style="display: none;">
                            <div class="mb-3">
                                <label for="editNombreChambres" class="form-label">Nombre de chambres <span class="text-danger">*</span></label>
                                <select class="form-select" id="editNombreChambres" name="nombre_chambres">
                                    <option value="">Sélectionner</option>
                                    <option value="2">2 chambres</option>
                                    <option value="3">3 chambres</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editSuperficie" class="form-label">Superficie (m²)</label>
                                <input type="number" class="form-control" id="editSuperficie" name="superficie" min="0" step="0.01" placeholder="65.5">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="editEtage" class="form-label">Étage</label>
                                <input type="number" class="form-control" id="editEtage" name="etage" placeholder="Ex: 3">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editNumeroBien" class="form-label">Numéro / Référence du bien</label>
                                <input type="text" class="form-control" id="editNumeroBien" name="numero_bien" maxlength="50" placeholder="Ex: APT-A301">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
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

                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="3" placeholder="Description détaillée du bien..."></textarea>
                        <div class="invalid-feedback"></div>
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
                            </div>
                            <input type="file" id="editPhotoFiles" name="photoFiles[]" multiple accept="image/jpeg,image/png,image/webp,image/jpg" style="display: none;">
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
    // Vérifier si on doit ouvrir la modale d'ajout
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('action') === 'create') {
        $('#createAppartementModal').modal('show');
        // Nettoyer l'URL sans recharger la page
        window.history.replaceState({}, document.title, window.location.pathname);
    }

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
        e.stopPropagation();
        document.getElementById('photoFiles').click();
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
        e.stopPropagation();
        document.getElementById('editPhotoFiles').click();
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
                    $('#editType').val(appartement.type || 'meuble');
                    $('#editCategorie').val(appartement.categorie || 'appartement');
                    $('#editNombreChambres').val(appartement.nombre_chambres || '');
                    $('#editSuperficie').val(appartement.superficie || '');
                    $('#editNumeroBien').val(appartement.numero_bien || '');
                    $('#editEtage').val(appartement.etage || '');
                    $('#editDescription').val(appartement.description || '');

                    // Afficher/masquer le champ nombre de chambres selon la catégorie
                    if (appartement.categorie === 'appartement') {
                        $('#editNombreChambresContainer').show();
                        $('#editNombreChambres').prop('required', true);
                    } else {
                        $('#editNombreChambresContainer').hide();
                        $('#editNombreChambres').prop('required', false);
                    }
                    
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

    // Gérer le toggle du type d'appartement
    $(document).on('click', '.toggle-type', function() {
        const appartementId = $(this).data('appartement-id');
        const currentType = $(this).data('current-type');
        const nouveauType = currentType === 'meuble' ? 'non_meuble' : 'meuble';
        const action = currentType === 'meuble' ? 'transformer en non meublé' : 'transformer en meublé';
        
        if (confirm(`Êtes-vous sûr de vouloir ${action} cet appartement ?`)) {
            $.ajax({
                url: `<?= base_url("/admin/appartements/toggle-type") ?>/${appartementId}`,
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
                    showToast('error', 'Erreur lors de la modification du type.');
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

    // ===== GESTION DE L'AFFICHAGE CONDITIONNEL DU CHAMP NOMBRE DE CHAMBRES =====

    // Pour le formulaire de création
    $('#categorie').on('change', function() {
        const categorie = $(this).val();
        const $nombreChambresContainer = $('#nombreChambresContainer');
        const $nombreChambres = $('#nombre_chambres');

        if (categorie === 'appartement') {
            $nombreChambresContainer.show();
            $nombreChambres.prop('required', true);
        } else {
            $nombreChambresContainer.hide();
            $nombreChambres.prop('required', false);
            $nombreChambres.val(''); // Vider la valeur
        }
    });

    // Pour le formulaire de modification
    $('#editCategorie').on('change', function() {
        const categorie = $(this).val();
        const $nombreChambresContainer = $('#editNombreChambresContainer');
        const $nombreChambres = $('#editNombreChambres');

        if (categorie === 'appartement') {
            $nombreChambresContainer.show();
            $nombreChambres.prop('required', true);
        } else {
            $nombreChambresContainer.hide();
            $nombreChambres.prop('required', false);
            $nombreChambres.val(''); // Vider la valeur
        }
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

<?= $this->section('scripts') ?>
<!-- DataTables JS -->
<script src="<?= base_url('assets/admin/js/dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/admin/js/dataTables.bs5.min.js') ?>"></script>

<script>
$(document).ready(function() {
    // ===== INITIALISATION DE DATATABLES POUR LA PAGINATION =====
    if ($.fn.DataTable.isDataTable('#appartementsTable')) {
        $('#appartementsTable').DataTable().destroy();
    }

    $('#appartementsTable').DataTable({
        language: {
            processing: "Traitement en cours...",
            search: "Rechercher&nbsp;:",
            lengthMenu: "Afficher _MENU_ &eacute;l&eacute;ments",
            info: "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            infoEmpty: "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
            infoFiltered: "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            infoPostFix: "",
            loadingRecords: "Chargement en cours...",
            zeroRecords: "Aucun &eacute;l&eacute;ment &agrave; afficher",
            emptyTable: "Aucune donn&eacute;e disponible dans le tableau",
            paginate: {
                first: "Premier",
                previous: "Pr&eacute;c&eacute;dent",
                next: "Suivant",
                last: "Dernier"
            },
            aria: {
                sortAscending: ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre d&eacute;croissant"
            }
        },
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tout"]],
        order: [[0, 'asc']],
        responsive: true,
        dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row mt-3"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        drawCallback: function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });
});
</script>
<?= $this->endSection() ?>