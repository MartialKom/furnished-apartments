<!-- Modal Ajouter Paiement -->
<div class="modal fade" id="paiementLocationModal" tabindex="-1" aria-labelledby="paiementLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #ffc107; color: #333;">
                <h5 class="modal-title" id="paiementLocationModalLabel">
                    <i class="feather-dollar-sign me-2"></i>Enregistrer un Paiement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="paiementLocationForm">
                    <input type="hidden" id="paiement_location_id" name="location_id">

                    <div class="mb-3">
                        <label for="montant_paiement" class="form-label">Montant du Paiement (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="montant_paiement" name="montant" min="0" step="0.01" required>
                        <small class="text-muted" id="montant_restant_info"></small>
                    </div>

                    <div class="mb-3">
                        <label for="mode_paiement_location" class="form-label">Mode de Paiement <span class="text-danger">*</span></label>
                        <select class="form-select" id="mode_paiement_location" name="mode_paiement" required>
                            <option value="">Sélectionner...</option>
                            <option value="especes">Espèces</option>
                            <option value="virement">Virement Bancaire</option>
                            <option value="cheque">Chèque</option>
                            <option value="mobile_money">Mobile Money</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-warning text-white" onclick="submitPaiementLocation()">
                    <i class="feather-save me-2"></i>Enregistrer le Paiement
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function ajouterPaiement(id) {
    document.getElementById('paiement_location_id').value = id;
    document.getElementById('montant_paiement').value = '';
    document.getElementById('mode_paiement_location').value = '';

    // Récupérer le montant restant
    fetch(`<?= base_url('admin/locations-voitures/get') ?>/${id}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const montantRestant = data.location.montant_restant;
            document.getElementById('montant_restant_info').textContent =
                `Montant restant à payer: ${Number(montantRestant).toLocaleString('fr-FR')} FCFA`;
            document.getElementById('montant_paiement').max = montantRestant;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });

    const modal = new bootstrap.Modal(document.getElementById('paiementLocationModal'));
    modal.show();
}

function submitPaiementLocation() {
    const form = document.getElementById('paiementLocationForm');

    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const locationId = document.getElementById('paiement_location_id').value;
    const data = {
        montant: document.getElementById('montant_paiement').value,
        mode_paiement: document.getElementById('mode_paiement_location').value
    };

    fetch(`<?= base_url('admin/locations-voitures/paiement') ?>/${locationId}`, {
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
        alert('Erreur lors de l\'enregistrement du paiement');
    });
}
</script>
