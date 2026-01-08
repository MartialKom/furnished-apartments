<!-- Modal Modifier Facture d'Eau -->
<div class="modal fade" id="editFactureModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #d29751; color: white;">
                <h5 class="modal-title">
                    <i class="feather-edit me-2"></i>Modifier une Facture d'Eau
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editFactureForm">
                <input type="hidden" id="edit_facture_id" name="facture_id">
                <div class="modal-body">
                    <div class="row g-3">

                        <!-- Affichage du contrat (non modifiable) -->
                        <div class="col-md-12">
                            <label class="form-label">
                                <i class="feather-file-text me-1"></i>Contrat (Locataire - Appartement)
                            </label>
                            <input type="text" class="form-control" id="edit_contrat_info" readonly>
                        </div>

                        <!-- Mois/Année (non modifiable) -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="feather-calendar me-1"></i>Mois / Année
                            </label>
                            <input type="text" class="form-control" id="edit_mois_annee_display" readonly>
                        </div>

                        <!-- Montant -->
                        <div class="col-md-6">
                            <label for="edit_montant" class="form-label">
                                <i class="feather-dollar-sign me-1"></i>Montant (FCFA) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" class="form-control" id="edit_montant" name="montant" required>
                            <div class="invalid-feedback">Le montant est obligatoire.</div>
                        </div>

                        <!-- Index précédent -->
                        <div class="col-md-4">
                            <label for="edit_index_precedent" class="form-label">
                                <i class="feather-activity me-1"></i>Index précédent
                            </label>
                            <input type="number" step="0.01" class="form-control" id="edit_index_precedent" name="index_precedent" onchange="calculerConsommationEdit()">
                        </div>

                        <!-- Index actuel -->
                        <div class="col-md-4">
                            <label for="edit_index_actuel" class="form-label">
                                <i class="feather-activity me-1"></i>Index actuel
                            </label>
                            <input type="number" step="0.01" class="form-control" id="edit_index_actuel" name="index_actuel" onchange="calculerConsommationEdit()">
                        </div>

                        <!-- Consommation -->
                        <div class="col-md-4">
                            <label for="edit_consommation_m3" class="form-label">
                                <i class="feather-droplet me-1"></i>Consommation (m³)
                            </label>
                            <input type="number" step="0.01" class="form-control" id="edit_consommation_m3" name="consommation_m3" readonly>
                        </div>

                        <!-- Date d'émission -->
                        <div class="col-md-6">
                            <label for="edit_date_emission" class="form-label">
                                <i class="feather-calendar me-1"></i>Date d'émission <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="edit_date_emission" name="date_emission" required>
                            <div class="invalid-feedback">La date d'émission est obligatoire.</div>
                        </div>

                        <!-- Date d'échéance -->
                        <div class="col-md-6">
                            <label for="edit_date_echeance" class="form-label">
                                <i class="feather-calendar me-1"></i>Date d'échéance <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="edit_date_echeance" name="date_echeance" required>
                            <div class="invalid-feedback">La date d'échéance est obligatoire.</div>
                        </div>

                        <!-- Notes -->
                        <div class="col-md-12">
                            <label for="edit_notes" class="form-label">
                                <i class="feather-file-text me-1"></i>Notes
                            </label>
                            <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn text-white" style="background: #d29751;">
                        <i class="feather-save me-2"></i>Modifier la facture
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Calculer la consommation automatiquement pour l'édition
function calculerConsommationEdit() {
    const indexPrecedent = parseFloat(document.getElementById('edit_index_precedent').value) || 0;
    const indexActuel = parseFloat(document.getElementById('edit_index_actuel').value) || 0;

    if (indexPrecedent > 0 && indexActuel > 0) {
        const consommation = indexActuel - indexPrecedent;
        document.getElementById('edit_consommation_m3').value = consommation.toFixed(2);
    }
}

// Soumettre le formulaire de modification
document.getElementById('editFactureForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const factureId = document.getElementById('edit_facture_id').value;
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    // Retirer le facture_id de data car il est déjà dans l'URL
    delete data.facture_id;

    fetch(`<?= base_url('admin/factures-eau/update') ?>/${factureId}`, {
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
            $('#editFactureModal').modal('hide');
            location.reload();
        } else {
            if (data.errors) {
                let errorMsg = 'Erreurs de validation:\n';
                for (let field in data.errors) {
                    errorMsg += `- ${data.errors[field]}\n`;
                }
                alert(errorMsg);
            } else {
                alert('Erreur: ' + (data.message || 'Erreur inconnue'));
            }
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la modification de la facture');
    });
});

// Réinitialiser le formulaire quand le modal est fermé
$('#editFactureModal').on('hidden.bs.modal', function () {
    document.getElementById('editFactureForm').reset();
});
</script>
