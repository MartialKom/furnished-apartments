<!-- Modal Créer Facture d'Eau -->
<div class="modal fade" id="createFactureModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #d29751; color: white;">
                <h5 class="modal-title">
                    <i class="feather-droplet me-2"></i>Créer une Facture d'Eau
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createFactureForm">
                <div class="modal-body">
                    <div class="row g-3">

                        <!-- Sélection du contrat -->
                        <div class="col-md-12">
                            <label for="contrat_id" class="form-label">
                                <i class="feather-file-text me-1"></i>Contrat (Locataire - Appartement) <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="contrat_id" name="contrat_id" required>
                                <option value="">Sélectionnez un contrat</option>
                                <?php if (isset($contrats) && !empty($contrats)): ?>
                                    <?php foreach ($contrats as $contrat): ?>
                                        <option value="<?= $contrat['id'] ?>">
                                            <?= esc($contrat['locataire_nom']) ?> - <?= esc($contrat['appartement_adresse']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner un contrat.</div>
                        </div>

                        <!-- Mois/Année -->
                        <div class="col-md-6">
                            <label for="mois_annee" class="form-label">
                                <i class="feather-calendar me-1"></i>Mois / Année <span class="text-danger">*</span>
                            </label>
                            <input type="month" class="form-control" id="mois_annee" name="mois_annee" value="<?= date('Y-m') ?>" required>
                            <small class="text-muted">Format: YYYY-MM</small>
                            <div class="invalid-feedback">Le mois/année est obligatoire.</div>
                        </div>

                        <!-- Montant -->
                        <div class="col-md-6">
                            <label for="montant" class="form-label">
                                <i class="feather-dollar-sign me-1"></i>Montant (FCFA) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" class="form-control" id="montant" name="montant" required>
                            <div class="invalid-feedback">Le montant est obligatoire.</div>
                        </div>

                        <!-- Index précédent -->
                        <div class="col-md-4">
                            <label for="index_precedent" class="form-label">
                                <i class="feather-activity me-1"></i>Index précédent
                            </label>
                            <input type="number" step="0.01" class="form-control" id="index_precedent" name="index_precedent" onchange="calculerConsommation()">
                        </div>

                        <!-- Index actuel -->
                        <div class="col-md-4">
                            <label for="index_actuel" class="form-label">
                                <i class="feather-activity me-1"></i>Index actuel
                            </label>
                            <input type="number" step="0.01" class="form-control" id="index_actuel" name="index_actuel" onchange="calculerConsommation()">
                        </div>

                        <!-- Consommation -->
                        <div class="col-md-4">
                            <label for="consommation_m3" class="form-label">
                                <i class="feather-droplet me-1"></i>Consommation (m³)
                            </label>
                            <input type="number" step="0.01" class="form-control" id="consommation_m3" name="consommation_m3" readonly>
                        </div>

                        <!-- Date d'émission -->
                        <div class="col-md-6">
                            <label for="date_emission" class="form-label">
                                <i class="feather-calendar me-1"></i>Date d'émission <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="date_emission" name="date_emission" value="<?= date('Y-m-d') ?>" required>
                            <div class="invalid-feedback">La date d'émission est obligatoire.</div>
                        </div>

                        <!-- Date d'échéance -->
                        <div class="col-md-6">
                            <label for="date_echeance" class="form-label">
                                <i class="feather-calendar me-1"></i>Date d'échéance <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="date_echeance" name="date_echeance" value="<?= date('Y-m-25') ?>" required>
                            <div class="invalid-feedback">La date d'échéance est obligatoire.</div>
                        </div>

                        <!-- Notes -->
                        <div class="col-md-12">
                            <label for="notes" class="form-label">
                                <i class="feather-file-text me-1"></i>Notes
                            </label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn text-white" style="background: #d29751;">
                        <i class="feather-save me-2"></i>Créer la facture
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Calculer la consommation automatiquement
function calculerConsommation() {
    const indexPrecedent = parseFloat(document.getElementById('index_precedent').value) || 0;
    const indexActuel = parseFloat(document.getElementById('index_actuel').value) || 0;

    if (indexPrecedent > 0 && indexActuel > 0) {
        const consommation = indexActuel - indexPrecedent;
        document.getElementById('consommation_m3').value = consommation.toFixed(2);
    }
}

// Soumettre le formulaire de création
document.getElementById('createFactureForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    // Convertir le mois au format YYYY-MM
    const moisAnnee = data.mois_annee;
    data.mois_annee = moisAnnee;

    fetch('<?= base_url('admin/factures-eau/store') ?>', {
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
            $('#createFactureModal').modal('hide');
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
        alert('Erreur lors de la création de la facture');
    });
});

// Réinitialiser le formulaire quand le modal est fermé
$('#createFactureModal').on('hidden.bs.modal', function () {
    document.getElementById('createFactureForm').reset();
});
</script>
