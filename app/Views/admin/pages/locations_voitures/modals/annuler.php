<!-- Modal Annuler Location -->
<div class="modal fade" id="annulerLocationModal" tabindex="-1" aria-labelledby="annulerLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #dc3545; color: white;">
                <h5 class="modal-title" id="annulerLocationModalLabel">
                    <i class="feather-x-circle me-2"></i>Annuler la Location
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="annulerLocationForm">
                    <input type="hidden" id="annuler_location_id" name="location_id">

                    <div class="alert alert-danger">
                        <i class="feather-alert-triangle me-2"></i>
                        <strong>Attention !</strong> Cette action annulera définitivement la location. La voiture redeviendra disponible.
                    </div>

                    <div class="mb-3">
                        <label for="motif_annulation" class="form-label">Motif de l'Annulation <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motif_annulation" name="motif" rows="4" required placeholder="Veuillez indiquer la raison de l'annulation..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-danger" onclick="submitAnnulerLocation()">
                    <i class="feather-x me-2"></i>Confirmer l'Annulation
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function annulerLocation(id) {
    document.getElementById('annuler_location_id').value = id;
    document.getElementById('motif_annulation').value = '';

    const modal = new bootstrap.Modal(document.getElementById('annulerLocationModal'));
    modal.show();
}

function submitAnnulerLocation() {
    const form = document.getElementById('annulerLocationForm');

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const locationId = document.getElementById('annuler_location_id').value;
    const data = {
        motif: document.getElementById('motif_annulation').value
    };

    fetch(`<?= base_url('admin/locations-voitures/annuler') ?>/${locationId}`, {
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
        alert('Erreur lors de l\'annulation de la location');
    });
}
</script>
