<!-- Modal Créer Voiture -->
<div class="modal fade" id="createVoitureModal" tabindex="-1" aria-labelledby="createVoitureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #d29751; color: white;">
                <h5 class="modal-title" id="createVoitureModalLabel">
                    <i class="feather-truck me-2"></i>Ajouter une Voiture
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createVoitureForm">
                    <div class="row">
                        <!-- Informations Générales -->
                        <div class="col-12 mb-3">
                            <h6 class="text-muted border-bottom pb-2">Informations Générales</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="marque" class="form-label">Marque <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="marque" name="marque" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="modele" class="form-label">Modèle <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modele" name="modele" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="annee" class="form-label">Année <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="annee" name="annee" min="1900" max="2030" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="immatriculation" class="form-label">Immatriculation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="immatriculation" name="immatriculation" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="couleur" class="form-label">Couleur</label>
                            <input type="text" class="form-control" id="couleur" name="couleur">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="numero_chassis" class="form-label">Numéro de Chassis</label>
                            <input type="text" class="form-control" id="numero_chassis" name="numero_chassis">
                        </div>

                        <!-- Caractéristiques Techniques -->
                        <div class="col-12 mb-3 mt-3">
                            <h6 class="text-muted border-bottom pb-2">Caractéristiques Techniques</h6>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="nombre_places" class="form-label">Nombre de Places <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="nombre_places" name="nombre_places" min="1" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="type_carburant" class="form-label">Type de Carburant <span class="text-danger">*</span></label>
                            <select class="form-select" id="type_carburant" name="type_carburant" required>
                                <option value="">Sélectionner...</option>
                                <option value="essence">Essence</option>
                                <option value="diesel">Diesel</option>
                                <option value="electrique">Électrique</option>
                                <option value="hybride">Hybride</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="transmission" class="form-label">Transmission <span class="text-danger">*</span></label>
                            <select class="form-select" id="transmission" name="transmission" required>
                                <option value="">Sélectionner...</option>
                                <option value="manuelle">Manuelle</option>
                                <option value="automatique">Automatique</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="kilometrage" class="form-label">Kilométrage <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="kilometrage" name="kilometrage" min="0" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="photoFiles" class="form-label">Photos (Maximum 5)</label>
                            <div class="border rounded p-3" style="background-color: #f8f9fa;">
                                <div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-sm btn-primary me-3" onclick="document.getElementById('photoFiles').click()">
                                        <i class="feather-upload me-2"></i>Choisir les photos
                                    </button>
                                    <small class="text-muted">JPG, PNG, WEBP (Max 5MB par photo, 5 photos max)</small>
                                </div>
                                <div id="photoPreviewContainer" class="mt-3" style="display: none;">
                                    <div id="photoPreviewList" class="d-flex gap-2 flex-wrap"></div>
                                </div>
                            </div>
                            <input type="file" id="photoFiles" name="photoFiles[]" multiple accept="image/jpeg,image/png,image/webp,image/jpg" style="display: none;">
                        </div>

                        <!-- Tarification -->
                        <div class="col-12 mb-3 mt-3">
                            <h6 class="text-muted border-bottom pb-2">Tarification</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tarif_journalier" class="form-label">Tarif Journalier (FCFA) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="tarif_journalier" name="tarif_journalier" min="0" step="0.01" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="caution" class="form-label">Caution (FCFA)</label>
                            <input type="number" class="form-control" id="caution" name="caution" min="0" step="0.01">
                        </div>

                        <!-- Documents et Échéances -->
                        <div class="col-12 mb-3 mt-3">
                            <h6 class="text-muted border-bottom pb-2">Documents et Échéances</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="assurance_expire_le" class="form-label">Assurance Expire le</label>
                            <input type="date" class="form-control" id="assurance_expire_le" name="assurance_expire_le">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="visite_technique_expire_le" class="form-label">Visite Technique Expire le</label>
                            <input type="date" class="form-control" id="visite_technique_expire_le" name="visite_technique_expire_le">
                        </div>

                        <!-- Notes -->
                        <div class="col-12 mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn text-white" style="background: #d29751;" onclick="submitCreateVoiture()">
                    <i class="feather-save me-2"></i>Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Prévisualisation des photos
let selectedPhotos = [];

document.getElementById('photoFiles').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const maxPhotos = 5;

    if (files.length > maxPhotos) {
        alert('Vous ne pouvez télécharger que ' + maxPhotos + ' photos maximum');
        e.target.value = '';
        return;
    }

    selectedPhotos = files;
    displayPhotoPreview(files);
});

function displayPhotoPreview(files) {
    const container = document.getElementById('photoPreviewContainer');
    const list = document.getElementById('photoPreviewList');

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
                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" onclick="removePhoto(${index})" style="padding: 2px 6px;">
                    <i class="feather-x" style="font-size: 12px;"></i>
                </button>
            `;
            list.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function removePhoto(index) {
    const dt = new DataTransfer();
    const input = document.getElementById('photoFiles');
    const files = Array.from(input.files);

    files.forEach((file, i) => {
        if (i !== index) dt.items.add(file);
    });

    input.files = dt.files;
    selectedPhotos = Array.from(dt.files);
    displayPhotoPreview(selectedPhotos);
}

function submitCreateVoiture() {
    const form = document.getElementById('createVoitureForm');

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const formData = new FormData(form);

    // Ajouter les fichiers photos
    const photoInput = document.getElementById('photoFiles');
    if (photoInput.files.length > 0) {
        // Supprimer l'ancien champ photoFiles[] s'il existe
        formData.delete('photoFiles[]');

        // Ajouter chaque fichier individuellement
        Array.from(photoInput.files).forEach(file => {
            formData.append('photoFiles[]', file);
        });
    }

    fetch('<?= base_url('admin/voitures/store') ?>', {
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
        alert('Erreur lors de la création de la voiture');
    });
}
</script>
