<!-- Modal Créer Location -->
<div class="modal fade" id="createLocationModal" tabindex="-1" aria-labelledby="createLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #d29751; color: white;">
                <h5 class="modal-title" id="createLocationModalLabel">
                    <i class="feather-plus me-2"></i>Nouvelle Location de Voiture
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createLocationForm">
                    <div class="row">
                        <!-- Sélection de la voiture -->
                        <div class="col-12 mb-3">
                            <h6 class="text-muted border-bottom pb-2">Voiture</h6>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="voiture_id" class="form-label">Voiture <span class="text-danger">*</span></label>
                            <select class="form-select" id="voiture_id" name="voiture_id" required onchange="updateTarifInfo()">
                                <option value="">Sélectionner une voiture...</option>
                                <?php foreach ($voitures as $voiture): ?>
                                    <option value="<?= $voiture['id'] ?>"
                                            data-tarif="<?= $voiture['tarif_journalier'] ?>"
                                            data-caution="<?= $voiture['caution'] ?>">
                                        <?= esc($voiture['marque']) ?> <?= esc($voiture['modele']) ?> (<?= esc($voiture['immatriculation']) ?>) - <?= number_format($voiture['tarif_journalier'], 0, ',', ' ') ?> FCFA/jour
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Type de client -->
                        <div class="col-12 mb-3 mt-3">
                            <h6 class="text-muted border-bottom pb-2">Client</h6>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Type de Client <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="client_type" id="client_existant" value="existant" checked onchange="toggleClientFields()">
                                    <label class="form-check-label" for="client_existant">
                                        Client Existant
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="client_type" id="client_nouveau" value="nouveau" onchange="toggleClientFields()">
                                    <label class="form-check-label" for="client_nouveau">
                                        Nouveau Client
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Client existant -->
                        <div class="col-12 mb-3" id="existant_fields">
                            <label for="locataire_id" class="form-label">Sélectionner le Client <span class="text-danger">*</span></label>
                            <select class="form-select" id="locataire_id" name="locataire_id">
                                <option value="">Sélectionner...</option>
                                <?php foreach ($locataires as $locataire): ?>
                                    <option value="<?= $locataire['id'] ?>">
                                        <?= esc($locataire['nom']) ?> - <?= esc($locataire['telephone']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Nouveau client -->
                        <div id="nouveau_fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="client_nom" class="form-label">Nom Complet <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="client_nom" name="client_nom">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="client_telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="client_telephone" name="client_telephone">
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="client_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="client_email" name="client_email">
                                </div>
                            </div>
                        </div>

                        <!-- Informations permis -->
                        <div class="col-12 mb-3">
                            <label for="client_permis" class="form-label">Numéro de Permis de Conduire</label>
                            <input type="text" class="form-control" id="client_permis" name="client_permis">
                        </div>

                        <!-- Dates -->
                        <div class="col-12 mb-3 mt-3">
                            <h6 class="text-muted border-bottom pb-2">Période de Location</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_debut" class="form-label">Date de Début <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut" required onchange="calculerMontant()">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_fin_prevue" class="form-label">Date de Fin Prévue <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date_fin_prevue" name="date_fin_prevue" required onchange="calculerMontant()">
                        </div>

                        <!-- Récapitulatif tarif -->
                        <div class="col-12 mb-3" id="recap_tarif" style="display: none;">
                            <div class="alert alert-info">
                                <strong>Récapitulatif:</strong><br>
                                <span id="recap_nombre_jours"></span><br>
                                <span id="recap_tarif"></span><br>
                                <strong id="recap_total"></strong>
                            </div>
                        </div>

                        <!-- Paiement -->
                        <div class="col-12 mb-3 mt-3">
                            <h6 class="text-muted border-bottom pb-2">Paiement Initial</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="montant_paye" class="form-label">Montant Payé (FCFA)</label>
                            <input type="number" class="form-control" id="montant_paye" name="montant_paye" min="0" step="0.01" value="0">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="mode_paiement" class="form-label">Mode de Paiement</label>
                            <select class="form-select" id="mode_paiement" name="mode_paiement">
                                <option value="">Sélectionner...</option>
                                <option value="especes">Espèces</option>
                                <option value="virement">Virement Bancaire</option>
                                <option value="cheque">Chèque</option>
                                <option value="mobile_money">Mobile Money</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="caution_versee" class="form-label">Caution Versée (FCFA)</label>
                            <input type="number" class="form-control" id="caution_versee" name="caution_versee" min="0" step="0.01">
                        </div>

                        <!-- Notes -->
                        <div class="col-12 mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn text-white" style="background: #d29751;" onclick="submitCreateLocation()">
                    <i class="feather-save me-2"></i>Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function toggleClientFields() {
    const typeClient = document.querySelector('input[name="client_type"]:checked').value;
    const existantFields = document.getElementById('existant_fields');
    const nouveauFields = document.getElementById('nouveau_fields');

    if (typeClient === 'existant') {
        existantFields.style.display = 'block';
        nouveauFields.style.display = 'none';
        document.getElementById('locataire_id').required = true;
        document.getElementById('client_nom').required = false;
        document.getElementById('client_telephone').required = false;
    } else {
        existantFields.style.display = 'none';
        nouveauFields.style.display = 'block';
        document.getElementById('locataire_id').required = false;
        document.getElementById('client_nom').required = true;
        document.getElementById('client_telephone').required = true;
    }
}

function updateTarifInfo() {
    const select = document.getElementById('voiture_id');
    const option = select.options[select.selectedIndex];

    if (option.value) {
        const caution = option.dataset.caution;
        if (caution) {
            document.getElementById('caution_versee').value = caution;
        }
        calculerMontant();
    }
}

function calculerMontant() {
    const voitureSelect = document.getElementById('voiture_id');
    const dateDebut = document.getElementById('date_debut').value;
    const dateFin = document.getElementById('date_fin_prevue').value;

    if (voitureSelect.value && dateDebut && dateFin) {
        const option = voitureSelect.options[voitureSelect.selectedIndex];
        const tarif = parseFloat(option.dataset.tarif);

        const debut = new Date(dateDebut);
        const fin = new Date(dateFin);
        const diff = Math.ceil((fin - debut) / (1000 * 60 * 60 * 24)) + 1;

        if (diff > 0) {
            const montantTotal = tarif * diff;

            document.getElementById('recap_nombre_jours').textContent = `Nombre de jours: ${diff}`;
            document.getElementById('recap_tarif').textContent = `Tarif journalier: ${tarif.toLocaleString('fr-FR')} FCFA`;
            document.getElementById('recap_total').textContent = `Montant total: ${montantTotal.toLocaleString('fr-FR')} FCFA`;
            document.getElementById('recap_tarif').style.display = 'block';
        }
    }
}

function submitCreateLocation() {
    const form = document.getElementById('createLocationForm');

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

    // Ajouter le type de client
    data.client_type = document.querySelector('input[name="client_type"]:checked').value;

    fetch('<?= base_url('admin/locations-voitures/store') ?>', {
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
        alert('Erreur lors de la création de la location');
    });
}
</script>
