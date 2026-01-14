<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Tableau de Bord</a></li>
                        <li class="breadcrumb-item active">Paramètres</li>
                    </ol>
                </div>
                <h4 class="page-title">Paramètres de la Structure</h4>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="feather-check-circle me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="feather-alert-circle me-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/parametres/update') ?>" method="post" enctype="multipart/form-data" id="settingsForm">
        <?= csrf_field() ?>
        
        <!-- Informations de la Structure -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Informations de la Structure</h4>
                        <p class="text-muted mb-0">Configurez les informations qui apparaîtront sur les contrats et documents</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="structure_name" class="form-label">Nom de la Structure *</label>
                                    <input type="text" class="form-control" id="structure_name" name="structure_name" 
                                           value="<?= esc($structureParams['structure_name']) ?>" required>
                                    <small class="form-text text-muted">Nom qui apparaîtra sur les contrats</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="structure_legal_form" class="form-label">Forme Juridique</label>
                                    <select class="form-control" id="structure_legal_form" name="structure_legal_form">
                                        <option value="SARL" <?= $structureParams['structure_legal_form'] === 'SARL' ? 'selected' : '' ?>>SARL</option>
                                        <option value="SA" <?= $structureParams['structure_legal_form'] === 'SA' ? 'selected' : '' ?>>SA</option>
                                        <option value="EURL" <?= $structureParams['structure_legal_form'] === 'EURL' ? 'selected' : '' ?>>EURL</option>
                                        <option value="SAS" <?= $structureParams['structure_legal_form'] === 'SAS' ? 'selected' : '' ?>>SAS</option>
                                        <option value="AUTRE" <?= $structureParams['structure_legal_form'] === 'AUTRE' ? 'selected' : '' ?>>Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="structure_address" class="form-label">Adresse Complète *</label>
                                    <textarea class="form-control" id="structure_address" name="structure_address" rows="3" required><?= esc($structureParams['structure_address']) ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="structure_phone" class="form-label">Téléphone *</label>
                                    <input type="tel" class="form-control" id="structure_phone" name="structure_phone" 
                                           value="<?= esc($structureParams['structure_phone']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="structure_email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="structure_email" name="structure_email" 
                                           value="<?= esc($structureParams['structure_email']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="structure_website" class="form-label">Site Web</label>
                                    <input type="url" class="form-control" id="structure_website" name="structure_website" 
                                           value="<?= esc($structureParams['structure_website']) ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="structure_logo_file" class="form-label">Logo de l'Entreprise</label>
                                    <input type="file" class="form-control" id="structure_logo_file" name="structure_logo_file"
                                           accept="image/png,image/jpeg,image/jpg,image/gif">
                                    <small class="form-text text-muted">Format acceptés: PNG, JPG, GIF (Max: 2 Mo)</small>
                                    <input type="hidden" name="structure_logo" value="<?= esc($structureParams['structure_logo']) ?>">

                                    <!-- Prévisualisation du logo -->
                                    <div class="mt-3" id="logo-preview-container">
                                        <?php if (!empty($structureParams['structure_logo'])): ?>
                                            <div class="border rounded p-3 text-center position-relative">
                                                <img src="<?= base_url($structureParams['structure_logo']) ?>"
                                                     alt="Logo" id="logo-preview" class="img-fluid" style="max-height: 150px;">
                                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                                        id="delete-logo-btn" title="Supprimer le logo">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <div class="border rounded p-3 text-center d-none" id="logo-preview-box">
                                                <img src="" alt="Logo" id="logo-preview" class="img-fluid" style="max-height: 150px;">
                                            </div>
                                            <p class="text-muted" id="no-logo-text">Aucun logo actuellement</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations Légales -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Informations Légales</h4>
                        <p class="text-muted mb-0">Numéros d'enregistrement et informations fiscales</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="structure_rc" class="form-label">Registre du Commerce</label>
                                    <input type="text" class="form-control" id="structure_rc" name="structure_rc" 
                                           value="<?= esc($structureParams['structure_rc']) ?>" 
                                           placeholder="RC-ABJ-2024-A-12345">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="structure_nif" class="form-label">NIF</label>
                                    <input type="text" class="form-control" id="structure_nif" name="structure_nif" 
                                           value="<?= esc($structureParams['structure_nif']) ?>" 
                                           placeholder="NIF-ABJ-2024-67890">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paramètres par Défaut -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Paramètres par Défaut</h4>
                        <p class="text-muted mb-0">Valeurs par défaut pour les nouveaux locataires</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="default_nationality" class="form-label">Nationalité par Défaut</label>
                                    <input type="text" class="form-control" id="default_nationality" name="default_nationality" 
                                           value="<?= esc($structureParams['default_nationality'] ?? 'Ivoirienne') ?>" 
                                           placeholder="Ivoirienne">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="default_id_type" class="form-label">Type de Pièce par Défaut</label>
                                    <select class="form-control" id="default_id_type" name="default_id_type">
                                        <option value="CNI" <?= ($structureParams['default_id_type'] ?? 'CNI') === 'CNI' ? 'selected' : '' ?>>CNI</option>
                                        <option value="PASSPORT" <?= ($structureParams['default_id_type'] ?? '') === 'PASSPORT' ? 'selected' : '' ?>>Passeport</option>
                                        <option value="CARTE_SEJOUR" <?= ($structureParams['default_id_type'] ?? '') === 'CARTE_SEJOUR' ? 'selected' : '' ?>>Carte de Séjour</option>
                                        <option value="AUTRE" <?= ($structureParams['default_id_type'] ?? '') === 'AUTRE' ? 'selected' : '' ?>>Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration Email & SMTP -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">
                            <i class="feather-mail me-2"></i>Configuration Email & SMTP
                        </h4>
                        <p class="text-muted mb-0">
                            Paramètres d'envoi des emails et notifications
                        </p>
                    </div>
                    <div class="card-body">
                        <!-- Note d'information -->
                        <div class="alert alert-info">
                            <i class="feather-info me-2"></i>
                            Ces paramètres sont prioritaires sur le fichier .env.
                            Laissez vide pour utiliser la configuration par défaut.
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Serveur SMTP</label>
                                    <input type="text" class="form-control" name="smtp_host"
                                           value="<?= esc($structureParams['smtp_host'] ?? '') ?>"
                                           placeholder="smtp.example.com">
                                    <small class="text-muted">Ex: smtp-fr.securemail.pro</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Port SMTP</label>
                                    <input type="number" class="form-control" name="smtp_port"
                                           value="<?= esc($structureParams['smtp_port'] ?? '') ?>"
                                           placeholder="465">
                                    <small class="text-muted">465 (SSL) ou 587 (TLS)</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Chiffrement</label>
                                    <select class="form-control" name="smtp_crypto">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="ssl" <?= ($structureParams['smtp_crypto'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                        <option value="tls" <?= ($structureParams['smtp_crypto'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Utilisateur SMTP</label>
                                    <input type="email" class="form-control" name="smtp_user"
                                           value="<?= esc($structureParams['smtp_user'] ?? '') ?>"
                                           placeholder="contact@example.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mot de passe SMTP</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="smtp_pass"
                                               id="smtp_pass" value="<?= esc($structureParams['smtp_pass'] ?? '') ?>"
                                               placeholder="••••••••">
                                        <button class="btn btn-outline-secondary" type="button" id="toggleSmtpPass">
                                            <i class="feather-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Laissez vide pour conserver l'actuel</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email expéditeur</label>
                                    <input type="email" class="form-control" name="smtp_from_email"
                                           value="<?= esc($structureParams['smtp_from_email'] ?? '') ?>"
                                           placeholder="noreply@example.com">
                                    <small class="text-muted">Email qui apparaîtra comme expéditeur</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nom expéditeur</label>
                                    <input type="text" class="form-control" name="smtp_from_name"
                                           value="<?= esc($structureParams['smtp_from_name'] ?? 'T-Lodge') ?>"
                                           placeholder="T-Lodge - Système de Gestion">
                                </div>
                            </div>
                        </div>

                        <!-- Boutons de test -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-primary" id="testSmtpConnection">
                                        <i class="feather-check-circle me-2"></i>Tester la Connexion SMTP
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="previewEmailTemplate">
                                        <i class="feather-eye me-2"></i>Prévisualiser Template Email
                                    </button>
                                </div>
                                <div id="smtpTestResult" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouton de Sauvegarde -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                            <i class="feather-save me-2"></i>Enregistrer les Paramètres
                        </button>
                        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary btn-lg ms-2">
                            <i class="feather-arrow-left me-2"></i>Retour au Tableau de Bord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.required {
    color: #dc3545;
}

#logo-preview-container {
    min-height: 50px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prévisualisation du logo lors de la sélection
    const logoInput = document.getElementById('structure_logo_file');
    const logoPreview = document.getElementById('logo-preview');
    const logoPreviewBox = document.getElementById('logo-preview-box');
    const noLogoText = document.getElementById('no-logo-text');
    const deleteLogoBtn = document.getElementById('delete-logo-btn');

    // Test de soumission du formulaire
    const form = document.getElementById('settingsForm');
    const submitBtn = document.getElementById('submitBtn');

    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            console.log('Bouton cliqué !');

            // Vérifier la validité du formulaire
            if (form) {
                console.log('Formulaire valide ?', form.checkValidity());

                if (!form.checkValidity()) {
                    console.error('Le formulaire contient des erreurs de validation');

                    // Trouver les champs invalides
                    const invalidFields = form.querySelectorAll(':invalid');
                    console.error('Champs invalides (' + invalidFields.length + '):', invalidFields);

                    invalidFields.forEach(function(field) {
                        console.error('- Champ invalide:', field.name || field.id, field.validationMessage);
                    });
                }
            }
        });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('✅ Formulaire submit déclenché !');
            console.log('Form action:', form.action);
            console.log('Form method:', form.method);
            // Ne pas bloquer la soumission
        });
    }

    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];

            if (file) {
                // Vérifier la taille du fichier (2 Mo max)
                if (file.size > 2048 * 1024) {
                    alert('Le fichier est trop volumineux. Taille maximum: 2 Mo');
                    logoInput.value = '';
                    return;
                }

                // Vérifier le type de fichier
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Format de fichier non valide. Utilisez PNG, JPG ou GIF');
                    logoInput.value = '';
                    return;
                }

                // Créer un lecteur de fichier pour la prévisualisation
                const reader = new FileReader();

                reader.onload = function(e) {
                    logoPreview.src = e.target.result;

                    if (logoPreviewBox) {
                        logoPreviewBox.classList.remove('d-none');
                    }

                    if (noLogoText) {
                        noLogoText.classList.add('d-none');
                    }
                };

                reader.readAsDataURL(file);
            }
        });
    }

    // Supprimer le logo
    if (deleteLogoBtn) {
        deleteLogoBtn.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir supprimer le logo ?')) {
                // Appel AJAX pour supprimer le logo
                fetch('<?= base_url('admin/parametres/deleteLogo') ?>', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Masquer la prévisualisation
                        document.getElementById('logo-preview-container').innerHTML =
                            '<p class="text-muted" id="no-logo-text">Aucun logo actuellement</p>';

                        // Réinitialiser l'input
                        logoInput.value = '';
                        document.querySelector('input[name="structure_logo"]').value = '';

                        // Afficher un message de succès
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show';
                        alertDiv.innerHTML = `
                            <i class="feather-check-circle me-2"></i>
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        document.querySelector('.container-fluid > .row').after(alertDiv);

                        // Faire disparaître l'alerte après 3 secondes
                        setTimeout(() => alertDiv.remove(), 3000);
                    } else {
                        alert('Erreur: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Erreur lors de la suppression du logo');
                    console.error('Error:', error);
                });
            }
        });
    }

    // Toggle password visibility pour SMTP
    const toggleSmtpPassBtn = document.getElementById('toggleSmtpPass');
    if (toggleSmtpPassBtn) {
        toggleSmtpPassBtn.addEventListener('click', function() {
            const input = document.getElementById('smtp_pass');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'feather-eye-off';
            } else {
                input.type = 'password';
                icon.className = 'feather-eye';
            }
        });
    }

    // Test SMTP connection
    const testSmtpBtn = document.getElementById('testSmtpConnection');
    if (testSmtpBtn) {
        testSmtpBtn.addEventListener('click', function() {
            const btn = this;
            const resultDiv = document.getElementById('smtpTestResult');

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Test en cours...';

            // Récupérer les valeurs du formulaire
            const formData = new FormData(document.getElementById('settingsForm'));

            fetch('<?= base_url('admin/parametres/test-smtp') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<i class="feather-check-circle me-2"></i>Tester la Connexion SMTP';

                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="feather-check-circle me-2"></i>
                            ${data.message}
                            ${data.details ? '<br><small>' + data.details + '</small>' : ''}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="feather-alert-circle me-2"></i>
                            ${data.message}
                            ${data.error ? '<br><small class="text-muted">' + data.error + '</small>' : ''}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                }
            })
            .catch(error => {
                btn.disabled = false;
                btn.innerHTML = '<i class="feather-check-circle me-2"></i>Tester la Connexion SMTP';
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="feather-alert-circle me-2"></i>
                        Erreur lors du test : ${error.message}
                    </div>
                `;
            });
        });
    }

    // Preview email template
    const previewBtn = document.getElementById('previewEmailTemplate');
    if (previewBtn) {
        previewBtn.addEventListener('click', function() {
            window.open('<?= base_url('admin/parametres/preview-email') ?>', '_blank', 'width=800,height=600');
        });
    }
});
</script>
<?= $this->endSection() ?>

