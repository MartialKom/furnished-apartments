<!-- Modal Modifier Voiture -->
<div class="modal fade" id="editVoitureModal" tabindex="-1" aria-labelledby="editVoitureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #d29751; color: white;">
                <h5 class="modal-title" id="editVoitureModalLabel">
                    <i class="feather-edit me-2"></i>Modifier la Voiture
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editVoitureForm">
                    <input type="hidden" id="edit_voiture_id" name="id">

                    <div class="row">
                        <!-- Informations Générales -->
                        <div class="col-12 mb-3">
                            <h6 class="text-muted border-bottom pb-2">Informations Générales</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_marque" class="form-label">Marque <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_marque" name="marque" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_modele" class="form-label">Modèle <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_modele" name="modele" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_annee" class="form-label">Année <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_annee" name="annee" min="1900" max="2030" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_immatriculation" class="form-label">Immatriculation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_immatriculation" name="immatriculation" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_couleur" class="form-label">Couleur</label>
                            <input type="text" class="form-control" id="edit_couleur" name="couleur">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_numero_chassis" class="form-label">Numéro de Chassis</label>
                            <input type="text" class="form-control" id="edit_numero_chassis" name="numero_chassis">
                        </div>

                        <!-- Caractéristiques Techniques -->
                        <div class="col-12 mb-3 mt-3">
                            <h6 class="text-muted border-bottom pb-2">Caractéristiques Techniques</h6>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="edit_nombre_places" class="form-label">Nombre de Places <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_nombre_places" name="nombre_places" min="1" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="edit_type_carburant" class="form-label">Type de Carburant <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_type_carburant" name="type_carburant" required>
                                <option value="">Sélectionner...</option>
                                <option value="essence">Essence</option>
                                <option value="diesel">Diesel</option>
                                <option value="electrique">Électrique</option>
                                <option value="hybride">Hybride</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="edit_transmission" class="form-label">Transmission <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_transmission" name="transmission" required>
                                <option value="">Sélectionner...</option>
                                <option value="manuelle">Manuelle</option>
                                <option value="automatique">Automatique</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_kilometrage" class="form-label">Kilométrage <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_kilometrage" name="kilometrage" min="0" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_statut" class="form-label">Statut <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_statut" name="statut" required>
                                <option value="disponible">Disponible</option>
                                <option value="louee">Louée</option>
                                <option value="maintenance">En Maintenance</option>
                                <option value="indisponible">Indisponible</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="editPhotoFiles" class="form-label">Photos (Maximum 5 au total)</label>
                            <div class="border rounded p-3" style="background-color: #f8f9fa;">
                                <div id="editExistingPhotos" class="mb-3"></div>
                                <div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-sm btn-primary me-3" onclick="document.getElementById('editPhotoFiles').click()">
                                        <i class="feather-upload me-2"></i>Ajouter des photos
                                    </button>
                                    <small class="text-muted">JPG, PNG, WEBP (Max 5MB par photo, 5 photos max)</small>
                                </div>
                                <div id="editPhotoPreviewContainer" class="mt-3" style="display: none;">
                                    <div id="editPhotoPreviewList" class="d-flex gap-2 flex-wrap"></div>
                                </div>
                            </div>
                            <input type="file" id="editPhotoFiles" name="photoFiles[]" multiple accept="image/jpeg,image/png,image/webp,image/jpg" style="display: none;">
                        </div>

                        <!-- Tarification -->
                        <div class="col-12 mb-3 mt-3">
                            <h6 class="text-muted border-bottom pb-2">Tarification</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_tarif_journalier" class="form-label">Tarif Journalier (FCFA) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_tarif_journalier" name="tarif_journalier" min="0" step="0.01" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_caution" class="form-label">Caution (FCFA)</label>
                            <input type="number" class="form-control" id="edit_caution" name="caution" min="0" step="0.01">
                        </div>

                        <!-- Documents et Échéances -->
                        <div class="col-12 mb-3 mt-3">
                            <h6 class="text-muted border-bottom pb-2">Documents et Échéances</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_assurance_expire_le" class="form-label">Assurance Expire le</label>
                            <input type="date" class="form-control" id="edit_assurance_expire_le" name="assurance_expire_le">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="edit_visite_technique_expire_le" class="form-label">Visite Technique Expire le</label>
                            <input type="date" class="form-control" id="edit_visite_technique_expire_le" name="visite_technique_expire_le">
                        </div>

                        <!-- Notes -->
                        <div class="col-12 mb-3">
                            <label for="edit_notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn text-white" style="background: #d29751;" onclick="submitEditVoiture()">
                    <i class="feather-save me-2"></i>Enregistrer les Modifications
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Variables pour l'édition
let editSelectedPhotos = [];
let editExistingPhotosData = [];

// Gestion de l'upload de nouvelles photos pour l'édition
document.getElementById('editPhotoFiles').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const maxPhotos = 5;
    const totalPhotos = editExistingPhotosData.length + files.length;

    if (totalPhotos > maxPhotos) {
        alert('Vous ne pouvez avoir que ' + maxPhotos + ' photos maximum au total');
        e.target.value = '';
        return;
    }

    editSelectedPhotos = files;
    displayEditPhotoPreview(files);
});

function displayEditPhotoPreview(files) {
    const container = document.getElementById('editPhotoPreviewContainer');
    const list = document.getElementById('editPhotoPreviewList');

    if (files.length === 0) {
        container.style.display = 'none';
        list.innerHTML = '';
        return;
    }

    container.style.display = 'block';
    list.innerHTML = '';

    files.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'position-relative';
            div.style.width = '100px';
            div.style.height = '100px';
            div.innerHTML = `
                <img src="${e.target.result}" class="img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" onclick="removeEditPhoto(${index})" style="padding: 2px 6px;">
                    <i class="feather-x" style="font-size: 12px;"></i>
                </button>
            `;
            list.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function removeEditPhoto(index) {
    const dt = new DataTransfer();
    const input = document.getElementById('editPhotoFiles');
    const files = Array.from(input.files);

    files.forEach((file, i) => {
        if (i !== index) dt.items.add(file);
    });

    input.files = dt.files;
    editSelectedPhotos = Array.from(dt.files);
    displayEditPhotoPreview(editSelectedPhotos);
}

function modifierVoiture(id) {
    const modal = new bootstrap.Modal(document.getElementById('editVoitureModal'));

    // Réinitialiser les photos
    editExistingPhotosData = [];
    editSelectedPhotos = [];
    document.getElementById('editPhotoFiles').value = '';
    document.getElementById('editPhotoPreviewContainer').style.display = 'none';

    // Récupérer les données de la voiture
    fetch(`<?= base_url('admin/voitures/get') ?>/${id}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const voiture = data.voiture;

            // Remplir le formulaire
            document.getElementById('edit_voiture_id').value = voiture.id;
            document.getElementById('edit_marque').value = voiture.marque;
            document.getElementById('edit_modele').value = voiture.modele;
            document.getElementById('edit_annee').value = voiture.annee;
            document.getElementById('edit_immatriculation').value = voiture.immatriculation;
            document.getElementById('edit_couleur').value = voiture.couleur || '';
            document.getElementById('edit_numero_chassis').value = voiture.numero_chassis || '';
            document.getElementById('edit_nombre_places').value = voiture.nombre_places;
            document.getElementById('edit_type_carburant').value = voiture.type_carburant;
            document.getElementById('edit_transmission').value = voiture.transmission;
            document.getElementById('edit_kilometrage').value = voiture.kilometrage;
            document.getElementById('edit_statut').value = voiture.statut;
            document.getElementById('edit_tarif_journalier').value = voiture.tarif_journalier;
            document.getElementById('edit_caution').value = voiture.caution || '';
            document.getElementById('edit_assurance_expire_le').value = voiture.assurance_expire_le || '';
            document.getElementById('edit_visite_technique_expire_le').value = voiture.visite_technique_expire_le || '';
            document.getElementById('edit_notes').value = voiture.notes || '';

            // Afficher les photos existantes
            if (voiture.photos) {
                editExistingPhotosData = voiture.photos.split(',').filter(p => p.trim() !== '');
                displayExistingPhotos(editExistingPhotosData);
            } else {
                document.getElementById('editExistingPhotos').innerHTML = '';
            }

            modal.show();
        } else {
            alert('Erreur: ' + (data.message || 'Impossible de charger les données'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors du chargement des données');
    });
}

function displayExistingPhotos(photos) {
    const container = document.getElementById('editExistingPhotos');

    if (photos.length === 0) {
        container.innerHTML = '';
        return;
    }

    container.innerHTML = '<label class="form-label">Photos existantes:</label><div class="d-flex gap-2 flex-wrap" id="existingPhotosList"></div>';
    const list = document.getElementById('existingPhotosList');

    photos.forEach((photo, index) => {
        const div = document.createElement('div');
        div.className = 'position-relative';
        div.style.width = '100px';
        div.style.height = '100px';
        div.innerHTML = `
            <img src="<?= base_url() ?>/${photo}" class="img-thumbnail" style="width: 100%; height: 100%; object-fit: cover;">
        `;
        list.appendChild(div);
    });
}

function submitEditVoiture() {
    const form = document.getElementById('editVoitureForm');

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const formData = new FormData(form);
    const voitureId = document.getElementById('edit_voiture_id').value;

    // Ajouter les nouveaux fichiers photos
    const photoInput = document.getElementById('editPhotoFiles');
    if (photoInput.files.length > 0) {
        formData.delete('photoFiles[]');
        Array.from(photoInput.files).forEach(file => {
            formData.append('photoFiles[]', file);
        });
    }

    fetch(`<?= base_url('admin/voitures/update') ?>/${voitureId}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            if (data.errors) {
                let errorMsg = 'Erreurs de validation:\n';
                for (let field in data.errors) {
                    errorMsg += '- ' + data.errors[field] + '\n';
                }
                alert(errorMsg);
            } else {
                alert('Erreur: ' + (data.message || 'Une erreur est survenue'));
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la modification de la voiture');
    });
}
</script>
