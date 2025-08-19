<?= $this->extend('frontend/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Login Section -->
<section class="breadcrumb-area" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 100px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8 col-md-10">
                <div class="login-form-wrapper" style="background: #fff; padding: 50px; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                    <div class="text-center mb-4">
                        <h2 style="color: #d29751; font-weight: 600; margin-bottom: 10px;">Connexion</h2>
                        <p style="color: #6e6e6e; font-size: 16px;">Accès réservé aux gestionnaires et administrateurs</p>
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

                    <!-- Formulaire de connexion -->
                    <form action="<?= base_url('auth/attempt-login') ?>" method="POST" class="login-form">
                        <?= csrf_field() ?>
                        
                        <div class="form-group mb-4">
                            <label for="username" style="color: #555; font-weight: 500; margin-bottom: 8px; display: block;">
                                <i class="fas fa-user" style="color: #d29751; margin-right: 8px;"></i>
                                Nom d'utilisateur ou Téléphone
                            </label>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="form-control" 
                                placeholder="Entrez votre nom d'utilisateur ou téléphone"
                                value="<?= old('username') ?>"
                                style="padding: 15px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; transition: all 0.3s ease;"
                                required
                            >
                            <?php if (isset($errors['username'])): ?>
                                <small class="text-danger" style="color: #dc3545; font-size: 14px; margin-top: 5px; display: block;">
                                    <i class="fas fa-exclamation-triangle"></i> <?= $errors['username'] ?>
                                </small>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-4">
                            <label for="password" style="color: #555; font-weight: 500; margin-bottom: 8px; display: block;">
                                <i class="fas fa-lock" style="color: #d29751; margin-right: 8px;"></i>
                                Mot de passe
                            </label>
                            <div style="position: relative;">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-control" 
                                    placeholder="Entrez votre mot de passe"
                                    style="padding: 15px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 16px; transition: all 0.3s ease;"
                                    required
                                >
                                <button 
                                    type="button" 
                                    id="togglePassword" 
                                    style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #6e6e6e; cursor: pointer;"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <?php if (isset($errors['password'])): ?>
                                <small class="text-danger" style="color: #dc3545; font-size: 14px; margin-top: 5px; display: block;">
                                    <i class="fas fa-exclamation-triangle"></i> <?= $errors['password'] ?>
                                </small>
                            <?php endif; ?>
                        </div>

                        <button 
                            type="submit" 
                            class="btn btn-primary w-100" 
                            style="background: #d29751; border: none; padding: 15px; border-radius: 8px; font-size: 16px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s ease;"
                        >
                            <i class="fas fa-sign-in-alt" style="margin-right: 8px;"></i>
                            Se connecter
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p style="color: #6e6e6e; font-size: 14px; margin-bottom: 15px;">
                            Vous n'avez pas accès ? Contactez votre administrateur.
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
/* Styles personnalisés pour le formulaire de connexion */
.form-control:focus {
    border-color: #d29751 !important;
    box-shadow: 0 0 0 0.2rem rgba(210, 151, 81, 0.25) !important;
    outline: none;
}

.btn:hover {
    background: #b8834a !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(210, 151, 81, 0.3) !important;
}

.login-form-wrapper {
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

/* Responsive */
@media (max-width: 768px) {
    .login-form-wrapper {
        padding: 30px 20px !important;
        margin: 20px;
    }
    
    .breadcrumb-area {
        padding: 50px 0 !important;
    }
}
</style>

<script>
// Toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordField = document.getElementById('password');
    const toggleIcon = this.querySelector('i');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
});

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
</script>

<?= $this->endSection() ?>