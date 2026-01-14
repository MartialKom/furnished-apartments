<!-- Modal Terminer Location -->
<div class="modal fade" id="terminerLocationModal" tabindex="-1" aria-labelledby="terminerLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #17a2b8; color: white;">
                <h5 class="modal-title" id="terminerLocationModalLabel">
                    <i class="feather-check me-2"></i>Retour de Véhicule
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="terminerLocationForm">
                    <input type="hidden" id="terminer_location_id" name="location_id">

                    <div class="mb-3">
                        <label for="date_fin_reelle" class="form-label">Date de Retour Effective <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date_fin_reelle" name="date_fin_reelle" required>
                    </div>

                    <div class="mb-3">
                        <label for="kilometrage_retour" class="form-label">Kilométrage au Retour <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="kilometrage_retour" name="kilometrage_retour" min="0" required>
                        <small class="text-muted">Relevé du compteur kilométrique au moment du retour</small>
                    </div>

                    <div class="mb-3">
                        <label for="etat_retour" class="form-label">État du Véhicule au Retour</label>
                        <select class="form-select" id="etat_retour" name="etat_retour">
                            <option value="excellent">Excellent</option>
                            <option value="bon" selected>Bon</option>
                            <option value="moyen">Moyen</option>
                            <option value="mauvais">Mauvais</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="caution_restituee" name="caution_restituee" value="1">
                            <label class="form-check-label" for="caution_restituee">
                                Caution restituée au client
                            </label>
                        </div>
                        <small class="text-muted">Cochez cette case si la caution a été rendue au client</small>
                    </div>

                    <div class="alert alert-warning">
                        <i class="feather-alert-triangle me-2"></i>
                        <small>Vérifiez bien l'état du véhicule et les éventuels dommages avant de restituer la caution.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-info text-white" onclick="submitTerminerLocation()">
                    <i class="feather-check me-2"></i>Valider le Retour
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function terminerLocation(id) {
    document.getElementById('terminer_location_id').value = id;
    // Mettre la date d'aujourd'hui par défaut
    document.getElementById('date_fin_reelle').value = new Date().toISOString().split('T')[0];

    const modal = new bootstrap.Modal(document.getElementById('terminerLocationModal'));
    modal.show();
}

function submitTerminerLocation() {
    const form = document.getElementById('terminerLocationForm');

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const locationId = document.getElementById('terminer_location_id').value;
    const data = {
        date_fin_reelle: document.getElementById('date_fin_reelle').value,
        kilometrage_retour: document.getElementById('kilometrage_retour').value,
        etat_retour: document.getElementById('etat_retour').value,
        caution_restituee: document.getElementById('caution_restituee').checked
    };

    fetch(`<?= base_url('admin/locations-voitures/terminer') ?>/${locationId}`, {
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
            alert('Erreur: ' + (data.message || 'Une erreur est survenue'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'enregistrement du retour');
    });
}
</script>
