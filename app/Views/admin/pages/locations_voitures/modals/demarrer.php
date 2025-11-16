<!-- Modal Démarrer Location -->
<div class="modal fade" id="demarrerLocationModal" tabindex="-1" aria-labelledby="demarrerLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #28a745; color: white;">
                <h5 class="modal-title" id="demarrerLocationModalLabel">
                    <i class="feather-play me-2"></i>Démarrer la Location
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="demarrerLocationForm">
                    <input type="hidden" id="demarrer_location_id" name="location_id">

                    <div class="mb-3">
                        <label for="kilometrage_depart" class="form-label">Kilométrage au Départ <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="kilometrage_depart" name="kilometrage_depart" min="0" required>
                        <small class="text-muted">Relevé du compteur kilométrique au moment du départ</small>
                    </div>

                    <div class="mb-3">
                        <label for="etat_depart" class="form-label">État du Véhicule au Départ</label>
                        <select class="form-select" id="etat_depart" name="etat_depart">
                            <option value="excellent">Excellent</option>
                            <option value="bon" selected>Bon</option>
                            <option value="moyen">Moyen</option>
                            <option value="mauvais">Mauvais</option>
                        </select>
                    </div>

                    <div class="alert alert-info">
                        <i class="feather-info me-2"></i>
                        <small>Assurez-vous de bien vérifier l'état du véhicule avant le départ. Le kilométrage sera utilisé pour calculer la distance parcourue au retour.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" onclick="submitDemarrerLocation()">
                    <i class="feather-play me-2"></i>Démarrer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function demarrerLocation(id) {
    document.getElementById('demarrer_location_id').value = id;
    const modal = new bootstrap.Modal(document.getElementById('demarrerLocationModal'));
    modal.show();
}

function submitDemarrerLocation() {
    const form = document.getElementById('demarrerLocationForm');

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const locationId = document.getElementById('demarrer_location_id').value;
    const data = {
        kilometrage_depart: document.getElementById('kilometrage_depart').value,
        etat_depart: document.getElementById('etat_depart').value
    };

    fetch(`<?= base_url('admin/locations-voitures/demarrer') ?>/${locationId}`, {
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
        alert('Erreur lors du démarrage de la location');
    });
}
</script>
