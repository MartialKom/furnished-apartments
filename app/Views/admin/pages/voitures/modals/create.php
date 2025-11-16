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

                        <div class="col-md-6 mb-3">
                            <label for="photo" class="form-label">Photo URL</label>
                            <input type="text" class="form-control" id="photo" name="photo">
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
function submitCreateVoiture() {
    const form = document.getElementById('createVoitureForm');

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const formData = new FormData(form);
    const data = {};

    formData.forEach((value, key) => {
        if (value !== '') {
            data[key] = value;
        }
    });

    // Statut par défaut
    data.statut = 'disponible';

    fetch('<?= base_url('admin/voitures/store') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
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
