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
                                            data-tarif-jour="<?= $voiture['tarif_journalier'] ?>"
                                            data-tarif-heure="<?= $voiture['tarif_horaire'] ?? round($voiture['tarif_journalier'] / 24, 2) ?>"
                                            data-caution="<?= $voiture['caution'] ?>">
                                        <?= esc($voiture['marque']) ?> <?= esc($voiture['modele']) ?> (<?= esc($voiture['immatriculation']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Type de location -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Type de Location <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type_location" id="type_jour" value="jour" checked onchange="toggleTypeLocation()">
                                    <label class="form-check-label" for="type_jour">
                                        Par Jour
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type_location" id="type_heure" value="heure" onchange="toggleTypeLocation()">
                                    <label class="form-check-label" for="type_heure">
                                        Par Heure
                                    </label>
                                </div>
                            </div>
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
                            <label for="locataire_id" class="form-label">Rechercher un Client <span class="text-danger">*</span></label>
                            <select class="form-select select2-ajax-location" id="locataire_id" name="locataire_id" style="width: 100%;">
                                <option value="">Tapez pour rechercher un client...</option>
                            </select>
                            <small class="form-text text-muted">Tapez le nom, l'email ou le téléphone du client</small>
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
                            <label for="date_debut" class="form-label">Date et heure de départ <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="date_debut" name="date_debut" required onchange="calculerMontant()">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_fin_prevue" class="form-label">Date et heure de retour prévue <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" id="date_fin_prevue" name="date_fin_prevue" required onchange="calculerMontant()">
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

                        <!-- État du véhicule -->
                        <div class="col-12 mb-3">
                            <label for="kilometrage_depart" class="form-label">Kilométrage de départ</label>
                            <input type="number" class="form-control" id="kilometrage_depart" name="kilometrage_depart" min="0" step="1" placeholder="Ex: 25000">
                        </div>

                        <div class="col-12 mb-3">
                            <label for="etat_depart" class="form-label">État du véhicule au départ</label>
                            <textarea class="form-control" id="etat_depart" name="etat_depart" rows="2" placeholder="Bon état général, sans rayures...">Bon état</textarea>
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

<style>
/* Styles pour Select2 dans le modal */
.select2-container {
    z-index: 9999 !important;
}

.select2-dropdown {
    z-index: 99999 !important;
}

.select2-container--open .select2-dropdown {
    z-index: 99999 !important;
}

.select2-container .select2-selection--single,
.select2-container--bootstrap-5 .select2-selection--single {
    height: 38px !important;
    display: flex !important;
    align-items: center !important;
}

.select2-container .select2-selection__rendered,
.select2-container--bootstrap-5 .select2-selection__rendered {
    padding-left: 12px !important;
    padding-right: 40px !important;
    line-height: 36px !important;
    color: #212529 !important;
    font-weight: 500 !important;
}

.select2-container .select2-selection__placeholder,
.select2-container--bootstrap-5 .select2-selection__placeholder {
    color: #6c757d !important;
    font-weight: 400 !important;
}

.select2-container.select2-container--focus .select2-selection,
.select2-container.select2-container--open .select2-selection {
    border-color: #d29751 !important;
    box-shadow: 0 0 0 0.2rem rgba(210, 151, 81, 0.25) !important;
}

/* Correction du positionnement du dropdown dans la modale */
.modal .select2-container--open {
    z-index: 99999 !important;
}

.modal .select2-container--open .select2-dropdown {
    position: absolute !important;
}

/* Assurer que le modal-body permet le scroll si nécessaire */
#createLocationModal .modal-body {
    overflow-y: visible !important;
    position: relative;
}

/* Positionner le dropdown correctement */
.select2-container--bootstrap-5 .select2-dropdown {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    margin-top: 2px;
}

.select2-container--bootstrap-5.select2-container--open .select2-dropdown--below {
    border-top: none;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
</style>

<script>
// Initialisation au chargement du document
$(document).ready(function() {
    initSelect2ForLocataireLocation();

    // Définir la date/heure minimum à maintenant
    const now = new Date();
    const dateTimeString = now.toISOString().slice(0, 16);
    document.getElementById('date_debut').min = dateTimeString;
    document.getElementById('date_fin_prevue').min = dateTimeString;
});

// Fix pour permettre le focus dans Select2
$(document).on('select2:open', () => {
    const searchField = document.querySelector('.select2-search__field');
    if (searchField) {
        searchField.focus();
    }
});

// Réinitialiser Select2 quand le modal s'ouvre
$('#createLocationModal').on('shown.bs.modal', function() {
    initSelect2ForLocataireLocation();

    // Réinitialiser le formulaire
    document.getElementById('createLocationForm').reset();
    document.getElementById('recap_tarif').style.display = 'none';

    // Réinitialiser les dates min
    const now = new Date();
    const dateTimeString = now.toISOString().slice(0, 16);
    document.getElementById('date_debut').min = dateTimeString;
    document.getElementById('date_fin_prevue').min = dateTimeString;
});

// Fonction d'initialisation de Select2 pour les locataires
function initSelect2ForLocataireLocation() {
    // S'assurer que Select2 n'est pas déjà initialisé
    if ($('#locataire_id').hasClass('select2-hidden-accessible')) {
        $('#locataire_id').select2('destroy');
    }

    $('#locataire_id').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#createLocationModal'),
        placeholder: 'Tapez pour rechercher un client...',
        allowClear: true,
        minimumInputLength: 2,
        width: '100%',
        dropdownAutoWidth: true,
        language: {
            inputTooShort: function() {
                return "Tapez au moins 2 caractères pour rechercher...";
            },
            searching: function() {
                return "Recherche en cours...";
            },
            noResults: function() {
                return "Aucun client trouvé";
            }
        },
        ajax: {
            url: '<?= base_url('admin/locataires/search') ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    term: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (data) {
                return {
                    results: data.results
                };
            },
            cache: true
        },
        templateResult: formatLocataireResultLocation,
        templateSelection: formatLocataireSelectionLocation
    }).on('select2:open', function() {
        // Forcer le repositionnement du dropdown après l'ouverture
        setTimeout(function() {
            const dropdown = $('.select2-dropdown');
            if (dropdown.length) {
                dropdown.css({
                    'position': 'absolute',
                    'top': '',
                    'left': ''
                });
            }
        }, 10);
    });
}

// Formatter pour les résultats de recherche
function formatLocataireResultLocation(locataire) {
    if (locataire.loading) {
        return locataire.text;
    }

    var $container = $(
        "<div class='select2-result-locataire clearfix'>" +
            "<div class='select2-result-locataire__meta'>" +
                "<div class='select2-result-locataire__title'></div>" +
                "<div class='select2-result-locataire__description'></div>" +
            "</div>" +
        "</div>"
    );

    $container.find(".select2-result-locataire__title").text(locataire.nom || locataire.text);
    $container.find(".select2-result-locataire__description").html(
        '<small>' + (locataire.email || '') + ' • ' + (locataire.telephone || '') + '</small>'
    );

    return $container;
}

// Formatter pour la sélection
function formatLocataireSelectionLocation(locataire) {
    if (!locataire.id) {
        return locataire.text;
    }

    if (locataire.nom) {
        return locataire.nom + ' (' + (locataire.telephone || '') + ')';
    }

    return locataire.text || 'Client sélectionné';
}

function toggleTypeLocation() {
    calculerMontant();
}

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
    const typeLocation = document.querySelector('input[name="type_location"]:checked').value;

    if (voitureSelect.value && dateDebut && dateFin) {
        const option = voitureSelect.options[voitureSelect.selectedIndex];
        const debut = new Date(dateDebut);
        const fin = new Date(dateFin);

        let montantTotal, duree, tarifText;

        if (typeLocation === 'heure') {
            // Location horaire
            const tarifHeure = parseFloat(option.dataset.tarifHeure);
            const diffMs = fin - debut;
            const diffHeures = Math.ceil(diffMs / (1000 * 60 * 60));
            duree = diffHeures < 1 ? 1 : diffHeures;
            montantTotal = tarifHeure * duree;
            tarifText = `Tarif horaire: ${tarifHeure.toLocaleString('fr-FR')} FCFA`;
            document.getElementById('recap_nombre_jours').textContent = `Durée: ${duree} heure(s)`;
        } else {
            // Location journalière
            const tarifJour = parseFloat(option.dataset.tarifJour);
            const diffJours = Math.ceil((fin - debut) / (1000 * 60 * 60 * 24)) + 1;
            duree = diffJours;
            montantTotal = tarifJour * duree;
            tarifText = `Tarif journalier: ${tarifJour.toLocaleString('fr-FR')} FCFA`;
            document.getElementById('recap_nombre_jours').textContent = `Nombre de jours: ${duree}`;
        }

        if (duree > 0) {
            document.getElementById('recap_tarif').textContent = tarifText;
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

    // Ajouter le type de client et type de location
    data.client_type = document.querySelector('input[name="client_type"]:checked').value;
    data.type_location = document.querySelector('input[name="type_location"]:checked').value;

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
