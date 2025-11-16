<!-- Modal Marquer comme Payé -->
<div class="modal fade" id="marquerPayeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="feather-check-circle me-2"></i>Marquer comme Payé
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="marquerPayeForm">
                <div class="modal-body">
                    <div class="row g-3">

                        <!-- Montant payé -->
                        <div class="col-md-12">
                            <label for="montant_paye" class="form-label">
                                <i class="feather-dollar-sign me-1"></i>Montant payé (FCFA) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" class="form-control" id="montant_paye" name="montant_paye" required>
                            <div class="invalid-feedback">Le montant payé est obligatoire.</div>
                        </div>

                        <!-- Date de paiement -->
                        <div class="col-md-12">
                            <label for="date_paiement" class="form-label">
                                <i class="feather-calendar me-1"></i>Date de paiement <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="date_paiement" name="date_paiement" value="<?= date('Y-m-d') ?>" required>
                            <div class="invalid-feedback">La date de paiement est obligatoire.</div>
                        </div>

                        <!-- Mode de paiement -->
                        <div class="col-md-12">
                            <label for="mode_paiement" class="form-label">
                                <i class="feather-credit-card me-1"></i>Mode de paiement <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="mode_paiement" name="mode_paiement" required>
                                <option value="">Sélectionnez un mode</option>
                                <option value="especes">Espèces</option>
                                <option value="virement">Virement bancaire</option>
                                <option value="cheque">Chèque</option>
                                <option value="mobile_money">Mobile Money</option>
                            </select>
                            <div class="invalid-feedback">Le mode de paiement est obligatoire.</div>
                        </div>

                        <!-- Référence de paiement -->
                        <div class="col-md-12">
                            <label for="reference_paiement" class="form-label">
                                <i class="feather-hash me-1"></i>Référence de paiement
                            </label>
                            <input type="text" class="form-control" id="reference_paiement" name="reference_paiement" placeholder="Ex: VIR20250116-001">
                            <small class="text-muted">Numéro de transaction, numéro de chèque, etc.</small>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="feather-check me-2"></i>Confirmer le paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Soumettre le formulaire de paiement
document.getElementById('marquerPayeForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const factureId = $(this).data('facture-id');
    if (!factureId) {
        alert('Erreur: ID de facture manquant');
        return;
    }

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    fetch(`<?= base_url('admin/factures-eau/marquer-paye') ?>/${factureId}`, {
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
            $('#marquerPayeModal').modal('hide');
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
        alert('Erreur lors de l\'enregistrement du paiement');
    });
});

// Réinitialiser le formulaire quand le modal est fermé
$('#marquerPayeModal').on('hidden.bs.modal', function () {
    document.getElementById('marquerPayeForm').reset();
    $(this).find('form').removeData('facture-id');
});
</script>
