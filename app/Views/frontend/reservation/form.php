<?= $this->extend('frontend/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Reservation Section -->
<section class="breadcrumb-area" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 100px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10 col-md-12">
                <div class="reservation-form-wrapper" style="background: #fff; padding: 50px; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                    <div class="text-center mb-4">
                        <h2 style="color: #d29751; font-weight: 600; margin-bottom: 10px;">Réservation</h2>
                        <p style="color: #6e6e6e; font-size: 16px;">Réservez votre appartement meublé en quelques clics</p>
                    </div>

                    <!-- Messages de succès/erreur -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success" style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger" style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Formulaire de réservation -->
                    <form action="<?= base_url('reservation/create') ?>" method="POST" class="reservation-form" id="reservationForm">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <!-- Informations personnelles -->
                            <div class="col-md-6">
                                <h5 style="color: #d29751; margin-bottom: 20px; font-weight: 600;">
                                    <i class="fas fa-user" style="margin-right: 8px;"></i>
                                    Informations personnelles
                                </h5>
                                
                                <div class="form-group mb-4">
                                    <label for="nom" style="color: #555; font-weight: 500; margin-bottom: 8px; display: block;">
                                        <i class="fas fa-user" style="color: #d29751; margin-right: 8px;"></i>
                                        Nom complet <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="nom" 
                                        name="nom" 
                                        class="form-control" 
                                        placeholder="Entrez votre nom complet"
                                        value="<?= old('nom') ?>"
                                        style="padding: 15px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; transition: all 0.3s ease;"
                                        required
                                    >
                                    <?php if (isset($errors['nom'])): ?>
                                        <small class="text-danger" style="color: #dc3545; font-size: 14px; margin-top: 5px; display: block;">
                                            <i class="fas fa-exclamation-triangle"></i> <?= $errors['nom'] ?>
                                        </small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="email" style="color: #555; font-weight: 500; margin-bottom: 8px; display: block;">
                                        <i class="fas fa-envelope" style="color: #d29751; margin-right: 8px;"></i>
                                        Email <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        class="form-control" 
                                        placeholder="votre.email@exemple.com"
                                        value="<?= old('email') ?>"
                                        style="padding: 15px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; transition: all 0.3s ease;"
                                        required
                                    >
                                    <?php if (isset($errors['email'])): ?>
                                        <small class="text-danger" style="color: #dc3545; font-size: 14px; margin-top: 5px; display: block;">
                                            <i class="fas fa-exclamation-triangle"></i> <?= $errors['email'] ?>
                                        </small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="telephone" style="color: #555; font-weight: 500; margin-bottom: 8px; display: block;">
                                        <i class="fas fa-phone" style="color: #d29751; margin-right: 8px;"></i>
                                        Téléphone
                                    </label>
                                    <input 
                                        type="tel" 
                                        id="telephone" 
                                        name="telephone" 
                                        class="form-control" 
                                        placeholder="+237 6XX XX XX XX"
                                        value="<?= old('telephone') ?>"
                                        style="padding: 15px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; transition: all 0.3s ease;"
                                    >
                                    <?php if (isset($errors['telephone'])): ?>
                                        <small class="text-danger" style="color: #dc3545; font-size: 14px; margin-top: 5px; display: block;">
                                            <i class="fas fa-exclamation-triangle"></i> <?= $errors['telephone'] ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Détails de la réservation -->
                            <div class="col-md-6">
                                <h5 style="color: #d29751; margin-bottom: 20px; font-weight: 600;">
                                    <i class="fas fa-calendar-alt" style="margin-right: 8px;"></i>
                                    Détails de la réservation
                                </h5>

                                <div class="form-group mb-4">
                                    <label for="appartement_id" style="color: #555; font-weight: 500; margin-bottom: 8px; display: block;">
                                        <i class="fas fa-home" style="color: #d29751; margin-right: 8px;"></i>
                                        Appartement <span style="color: #dc3545;">*</span>
                                    </label>
                                    <select 
                                        id="appartement_id" 
                                        name="appartement_id" 
                                        class="form-control appartement-select" 
                                        style="padding: 15px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; transition: all 0.3s ease; height: auto; line-height: 1.4;"
                                        required
                                    >
                                        <option value="">Sélectionnez un appartement</option>
                                        <?php if (isset($appartements) && !empty($appartements)): ?>
                                            <?php foreach ($appartements as $appartement): ?>
                                                <option value="<?= $appartement['id'] ?>" <?= old('appartement_id') == $appartement['id'] ? 'selected' : '' ?> 
                                                        style="padding: 8px; line-height: 1.6; white-space: normal;">
                                                    <?= esc($appartement['adresse']) ?> - <?= number_format($appartement['tarifs'], 0, ',', ' ') ?> FCFA/mois
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php if (isset($errors['appartement_id'])): ?>
                                        <small class="text-danger" style="color: #dc3545; font-size: 14px; margin-top: 5px; display: block;">
                                            <i class="fas fa-exclamation-triangle"></i> <?= $errors['appartement_id'] ?>
                                        </small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="dateDebut" style="color: #555; font-weight: 500; margin-bottom: 8px; display: block;">
                                        <i class="fas fa-calendar-plus" style="color: #d29751; margin-right: 8px;"></i>
                                        Date de début <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input 
                                        type="date" 
                                        id="dateDebut" 
                                        name="dateDebut" 
                                        class="form-control" 
                                        value="<?= old('dateDebut') ?>"
                                        min="<?= date('Y-m-d') ?>"
                                        style="padding: 15px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; transition: all 0.3s ease;"
                                        required
                                    >
                                    <?php if (isset($errors['dateDebut'])): ?>
                                        <small class="text-danger" style="color: #dc3545; font-size: 14px; margin-top: 5px; display: block;">
                                            <i class="fas fa-exclamation-triangle"></i> <?= $errors['dateDebut'] ?>
                                        </small>
                                    <?php endif; ?>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="dateFin" style="color: #555; font-weight: 500; margin-bottom: 8px; display: block;">
                                        <i class="fas fa-calendar-minus" style="color: #d29751; margin-right: 8px;"></i>
                                        Date de fin <span style="color: #dc3545;">*</span>
                                    </label>
                                    <input 
                                        type="date" 
                                        id="dateFin" 
                                        name="dateFin" 
                                        class="form-control" 
                                        value="<?= old('dateFin') ?>"
                                        min="<?= date('Y-m-d') ?>"
                                        style="padding: 15px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; transition: all 0.3s ease;"
                                        required
                                    >
                                    <?php if (isset($errors['dateFin'])): ?>
                                        <small class="text-danger" style="color: #dc3545; font-size: 14px; margin-top: 5px; display: block;">
                                            <i class="fas fa-exclamation-triangle"></i> <?= $errors['dateFin'] ?>
                                        </small>
                                    <?php endif; ?>
                                </div>

                                <!-- Zone d'affichage de la disponibilité -->
                                <div id="availability-status" style="margin-top: 10px; padding: 10px; border-radius: 5px; display: none;"></div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button 
                                    type="submit" 
                                    class="btn btn-primary w-100" 
                                    style="background: #d29751; border: none; padding: 15px; border-radius: 8px; font-size: 16px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s ease;"
                                    id="submitBtn"
                                >
                                    <i class="fas fa-calendar-check" style="margin-right: 8px;"></i>
                                    Confirmer la réservation
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p style="color: #6e6e6e; font-size: 14px; margin-bottom: 15px;">
                            Vous recevrez une confirmation par email après validation.
                        </p>
                        <a href="<?= base_url('/') ?>" style="color: #d29751; text-decoration: none; font-weight: 500;">
                            <i class="fas fa-arrow-left" style="margin-right: 5px;"></i>
                            Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Styles personnalisés pour le formulaire de réservation */
.form-control:focus {
    border-color: #d29751 !important;
    box-shadow: 0 0 0 0.2rem rgba(210, 151, 81, 0.25) !important;
    outline: none;
}

/* Amélioration du select d'appartements */
.appartement-select {
    min-height: 50px !important;
    padding: 15px !important;
}

.appartement-select option {
    padding: 10px 15px !important;
    line-height: 1.6 !important;
    white-space: normal !important;
    word-wrap: break-word !important;
    font-size: 15px !important;
    background: white !important;
    color: #333 !important;
}

.appartement-select option:hover {
    background: #f8f9fa !important;
}

.appartement-select option:checked {
    background: #d29751 !important;
    color: white !important;
}

/* Amélioration des étoiles obligatoires */
label span[style*="color: #dc3545"] {
    font-weight: bold;
    font-size: 18px;
}

.btn:hover {
    background: #b8834a !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(210, 151, 81, 0.3) !important;
}

.reservation-form-wrapper {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert {
    animation: slideInDown 0.5s ease-out;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Effet hover sur les champs */
.form-control:hover {
    border-color: #d29751;
}

/* Status de disponibilité */
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

/* Responsive */
@media (max-width: 768px) {
    .reservation-form-wrapper {
        padding: 30px 20px !important;
        margin: 20px;
    }
    
    .breadcrumb-area {
        padding: 50px 0 !important;
    }
}
</style>

<script>
// Validation des dates
document.getElementById('dateDebut').addEventListener('change', function() {
    const dateDebut = this.value;
    const dateFin = document.getElementById('dateFin');
    
    if (dateDebut) {
        dateFin.min = dateDebut;
        
        // Si la date de fin est antérieure à la date de début, la réinitialiser
        if (dateFin.value && dateFin.value < dateDebut) {
            dateFin.value = '';
        }
    }
    
    checkAvailability();
});

document.getElementById('dateFin').addEventListener('change', checkAvailability);
document.getElementById('appartement_id').addEventListener('change', checkAvailability);

// Vérification de la disponibilité
function checkAvailability() {
    const appartementId = document.getElementById('appartement_id').value;
    const dateDebut = document.getElementById('dateDebut').value;
    const dateFin = document.getElementById('dateFin').value;
    const statusDiv = document.getElementById('availability-status');
    const submitBtn = document.getElementById('submitBtn');
    
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

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-20px)';
        setTimeout(function() {
            alert.style.display = 'none';
        }, 300);
    });
}, 5000);

// Validation du formulaire avant soumission
document.getElementById('reservationForm').addEventListener('submit', function(e) {
    const dateDebut = document.getElementById('dateDebut').value;
    const dateFin = document.getElementById('dateFin').value;
    
    if (dateDebut && dateFin && dateFin <= dateDebut) {
        e.preventDefault();
        alert('La date de fin doit être postérieure à la date de début.');
        return false;
    }
});
</script>

<?= $this->endSection() ?>