<!-- Formulaire de réservation pour Modal -->
<form action="<?= base_url('reservation/create') ?>" method="POST" class="reservation-form" id="reservationFormModal">
    <?= csrf_field() ?>

    <input type="hidden" id="modal_appartement_id" name="appartement_id">

    <div class="row">
        <!-- Informations personnelles -->
        <div class="col-md-6">
            <h6 style="color: #d29751; margin-bottom: 20px; font-weight: 600;">
                <i class="fas fa-user" style="margin-right: 8px;"></i>
                Vos informations
            </h6>

            <div class="form-group mb-3">
                <label for="modal_nom" style="color: #555; font-weight: 500; margin-bottom: 8px; display: block;">
                    Nom complet <span style="color: #dc3545;">*</span>
                </label>
                <input
                    type="text"
                    id="modal_nom"
                    name="nom"
                    class="form-control"
                    placeholder="Entrez votre nom complet"
                    style="padding: 12px; border: 2px solid #e9ecef; border-radius: 8px;"
                    required
                >
            </div>

            <div class="form-group mb-3">
                <label for="modal_email" style="color: #555; font-weight: 500; margin-bottom: 8px; display: block;">
                    Email <span style="color: #dc3545;">*</span>
                </label>
                <input
                    type="email"
                    id="modal_email"
                    name="email"
                    class="form-control"
                    placeholder="votre.email@exemple.com"
                    style="padding: 12px; border: 2px solid #e9ecef; border-radius: 8px;"
                    required
                >
            </div>

            <div class="form-group mb-3">
                <label for="modal_telephone" style="color: #555; font-weight: 500; margin-bottom: 8px; display: block;">
                    Téléphone <span style="color: #dc3545;">*</span>
                </label>
                <input
                    type="tel"
                    id="modal_telephone"
                    name="telephone"
                    class="form-control"
                    placeholder="+225 XX XX XX XX XX"
                    style="padding: 12px; border: 2px solid #e9ecef; border-radius: 8px;"
                    required
                >
            </div>
        </div>

        <!-- Détails de la réservation -->
        <div class="col-md-6">
            <h6 style="color: #d29751; margin-bottom: 20px; font-weight: 600;">
                <i class="fas fa-calendar-alt" style="margin-right: 8px;"></i>
                Dates de séjour
            </h6>

            <div class="form-group mb-3">
                <label for="modal_dateDebut" style="color: #555; font-weight: 500; margin-bottom: 8px; display: block;">
                    Date d'arrivée <span style="color: #dc3545;">*</span>
                </label>
                <input
                    type="date"
                    id="modal_dateDebut"
                    name="dateDebut"
                    class="form-control"
                    min="<?= date('Y-m-d') ?>"
                    style="padding: 12px; border: 2px solid #e9ecef; border-radius: 8px;"
                    required
                >
            </div>

            <div class="form-group mb-3">
                <label for="modal_dateFin" style="color: #555; font-weight: 500; margin-bottom: 8px; display: block;">
                    Date de départ <span style="color: #dc3545;">*</span>
                </label>
                <input
                    type="date"
                    id="modal_dateFin"
                    name="dateFin"
                    class="form-control"
                    min="<?= date('Y-m-d') ?>"
                    style="padding: 12px; border: 2px solid #e9ecef; border-radius: 8px;"
                    required
                >
            </div>

            <!-- Zone d'affichage de la disponibilité -->
            <div id="modal-availability-status" style="margin-top: 10px; padding: 10px; border-radius: 5px; display: none;"></div>

            <!-- Affichage du prix estimé -->
            <div id="modal-price-estimate" style="margin-top: 15px; padding: 12px; background: #f8f9fa; border-radius: 8px; display: none;">
                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-weight: 600; color: #555;">Durée du séjour:</span>
                    <span id="modal-duration" style="color: #d29751; font-weight: 600;"></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span style="font-weight: 600; color: #555;">Prix estimé:</span>
                    <span id="modal-total-price" style="color: #28a745; font-size: 18px; font-weight: bold;"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <button
                type="submit"
                class="btn btn-primary w-100"
                style="background: #d29751; border: none; padding: 15px; border-radius: 8px; font-size: 16px; font-weight: 600;"
                id="modal_submitBtn"
            >
                <i class="fas fa-calendar-check" style="margin-right: 8px;"></i>
                Confirmer la réservation
            </button>
        </div>
    </div>

    <div class="text-center mt-3">
        <small style="color: #6e6e6e;">
            Vous recevrez une confirmation par email après validation.
        </small>
    </div>
</form>

<style>
.form-control:focus {
    border-color: #d29751 !important;
    box-shadow: 0 0 0 0.2rem rgba(210, 151, 81, 0.25) !important;
}

.availability-success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.availability-error {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}
</style>

<script>
let modalAppartementPrice = 0;

// Fonction pour mettre à jour l'appartement sélectionné dans le modal
function setModalAppartementData(appartementId, price) {
    document.getElementById('modal_appartement_id').value = appartementId;
    modalAppartementPrice = price;

    // Réinitialiser les dates et la disponibilité
    document.getElementById('modal_dateDebut').value = '';
    document.getElementById('modal_dateFin').value = '';
    document.getElementById('modal-availability-status').style.display = 'none';
    document.getElementById('modal-price-estimate').style.display = 'none';
}

// Validation des dates
document.getElementById('modal_dateDebut').addEventListener('change', function() {
    const dateDebut = this.value;
    const dateFin = document.getElementById('modal_dateFin');

    if (dateDebut) {
        dateFin.min = dateDebut;

        if (dateFin.value && dateFin.value < dateDebut) {
            dateFin.value = '';
        }
    }

    checkModalAvailability();
    calculateModalPrice();
});

document.getElementById('modal_dateFin').addEventListener('change', function() {
    checkModalAvailability();
    calculateModalPrice();
});

// Vérification de la disponibilité
function checkModalAvailability() {
    const appartementId = document.getElementById('modal_appartement_id').value;
    const dateDebut = document.getElementById('modal_dateDebut').value;
    const dateFin = document.getElementById('modal_dateFin').value;
    const statusDiv = document.getElementById('modal-availability-status');
    const submitBtn = document.getElementById('modal_submitBtn');

    if (appartementId && dateDebut && dateFin) {
        fetch(`<?= base_url('reservation/check-availability') ?>?appartement_id=${appartementId}&date_debut=${dateDebut}&date_fin=${dateFin}`)
            .then(response => response.json())
            .then(data => {
                statusDiv.style.display = 'block';

                if (data.disponible) {
                    statusDiv.className = 'availability-success';
                    statusDiv.innerHTML = '<i class="fas fa-check-circle"></i> Appartement disponible pour ces dates';
                    submitBtn.disabled = false;
                } else {
                    statusDiv.className = 'availability-error';
                    statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Appartement non disponible pour ces dates';
                    submitBtn.disabled = true;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                statusDiv.style.display = 'none';
                submitBtn.disabled = false;
            });
    } else {
        statusDiv.style.display = 'none';
        submitBtn.disabled = false;
    }
}

// Calcul du prix estimé
function calculateModalPrice() {
    const dateDebut = document.getElementById('modal_dateDebut').value;
    const dateFin = document.getElementById('modal_dateFin').value;
    const priceDiv = document.getElementById('modal-price-estimate');

    if (dateDebut && dateFin && modalAppartementPrice) {
        const debut = new Date(dateDebut);
        const fin = new Date(dateFin);
        const diffTime = Math.abs(fin - debut);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        if (diffDays > 0) {
            const totalPrice = diffDays * modalAppartementPrice;

            document.getElementById('modal-duration').textContent = diffDays + ' nuit' + (diffDays > 1 ? 's' : '');
            document.getElementById('modal-total-price').textContent = new Intl.NumberFormat('fr-FR').format(totalPrice) + ' FCFA';
            priceDiv.style.display = 'block';
        } else {
            priceDiv.style.display = 'none';
        }
    } else {
        priceDiv.style.display = 'none';
    }
}

// Validation du formulaire
document.getElementById('reservationFormModal').addEventListener('submit', function(e) {
    const dateDebut = document.getElementById('modal_dateDebut').value;
    const dateFin = document.getElementById('modal_dateFin').value;

    if (dateDebut && dateFin && dateFin <= dateDebut) {
        e.preventDefault();
        alert('La date de départ doit être postérieure à la date d\'arrivée.');
        return false;
    }
});
</script>
