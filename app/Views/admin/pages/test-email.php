<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Test d'envoi d'email</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Tableau de Bord</a></li>
                        <li class="breadcrumb-item active">Test Email</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration SMTP -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Configuration SMTP</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Protocol</th>
                                <td><?= $emailConfig->protocol ?></td>
                            </tr>
                            <tr>
                                <th>SMTP Host</th>
                                <td><?= $emailConfig->SMTPHost ?: '<span class="text-danger">Non configuré</span>' ?></td>
                            </tr>
                            <tr>
                                <th>SMTP Port</th>
                                <td><?= $emailConfig->SMTPPort ?></td>
                            </tr>
                            <tr>
                                <th>SMTP Crypto</th>
                                <td><span class="badge badge-info"><?= strtoupper($emailConfig->SMTPCrypto) ?></span></td>
                            </tr>
                            <tr>
                                <th>SMTP User</th>
                                <td><?= $emailConfig->SMTPUser ?: '<span class="text-danger">Non configuré</span>' ?></td>
                            </tr>
                            <tr>
                                <th>SMTP Password</th>
                                <td><?= !empty($emailConfig->SMTPPass) ? '<span class="text-success">✓ Configuré</span>' : '<span class="text-danger">✗ Non configuré</span>' ?></td>
                            </tr>
                            <tr>
                                <th>From Email</th>
                                <td><?= $emailConfig->fromEmail ?: '<span class="text-warning">Utilise SMTP User</span>' ?></td>
                            </tr>
                            <tr>
                                <th>From Name</th>
                                <td><?= $emailConfig->fromName ?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="mdi mdi-information-outline"></i>
                        <strong>Info:</strong> La configuration est chargée depuis le fichier <code>.env</code> et <code>app/Config/Email.php</code>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de test -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Envoyer un email de test</h5>
                </div>
                <div class="card-body">
                    <form id="testEmailForm">
                        <div class="form-group">
                            <label for="email">Adresse email destinataire <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= $emailConfig->SMTPUser ?>" required>
                            <small class="form-text text-muted">L'email sera envoyé à cette adresse</small>
                        </div>

                        <div class="form-group">
                            <label for="subject">Sujet</label>
                            <input type="text" class="form-control" id="subject" name="subject"
                                   value="Email de test - <?= date('Y-m-d H:i:s') ?>">
                        </div>

                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="8">Bonjour,

Ceci est un email de test envoyé depuis le système de gestion d'appartements meublés.

Date/Heure: <?= date('Y-m-d H:i:s') ?>

Système: Furnished Apartments Management

Si vous recevez cet email, la configuration SMTP fonctionne correctement !

Cordialement,
Le système de gestion</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="mdi mdi-send"></i> Envoyer l'email de test
                        </button>
                    </form>

                    <hr>

                    <button type="button" class="btn btn-info btn-block" id="sendNotificationTest">
                        <i class="mdi mdi-bell-ring"></i> Tester une notification d'échéance
                    </button>

                    <!-- Zone de résultat -->
                    <div id="testResult" class="mt-3" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Instructions de test</h5>
                </div>
                <div class="card-body">
                    <h6>Comment tester l'envoi d'email :</h6>
                    <ol>
                        <li>Vérifiez que la configuration SMTP ci-dessus est correcte</li>
                        <li>Saisissez l'adresse email du destinataire (par défaut votre adresse SMTP)</li>
                        <li>Modifiez le sujet et le message si nécessaire</li>
                        <li>Cliquez sur "Envoyer l'email de test"</li>
                        <li>Vérifiez votre boîte de réception (et le dossier spam)</li>
                    </ol>

                    <h6 class="mt-3">Test de notification :</h6>
                    <p>Le bouton "Tester une notification d'échéance" envoie un email avec le format utilisé pour les rappels de paiement aux locataires.</p>

                    <h6 class="mt-3">En cas de problème :</h6>
                    <ul>
                        <li>Vérifiez que les informations SMTP dans le fichier <code>.env</code> sont correctes</li>
                        <li>Assurez-vous que le port et le type de cryptage correspondent (465/SSL ou 587/TLS)</li>
                        <li>Vérifiez que votre serveur SMTP autorise l'envoi depuis cette application</li>
                        <li>Consultez les logs dans <code>writable/logs/</code></li>
                    </ul>

                    <div class="alert alert-warning">
                        <i class="mdi mdi-alert"></i>
                        <strong>Note:</strong> Pour Gmail, vous devez utiliser un "App Password" et non votre mot de passe habituel.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Test d'envoi d'email simple
    $('#testEmailForm').on('submit', function(e) {
        e.preventDefault();

        const email = $('#email').val();
        const subject = $('#subject').val();
        const message = $('#message').val();

        if (!email) {
            showResult('danger', 'Veuillez saisir une adresse email');
            return;
        }

        // Afficher un indicateur de chargement
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="mdi mdi-loading mdi-spin"></i> Envoi en cours...').prop('disabled', true);

        $.ajax({
            url: '<?= base_url('admin/test-email/send-test') ?>',
            type: 'POST',
            data: {
                email: email,
                subject: subject,
                message: message
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showResult('success', response.message);
                } else {
                    showResult('danger', response.message);
                }
            },
            error: function(xhr) {
                showResult('danger', 'Erreur AJAX: ' + xhr.statusText);
            },
            complete: function() {
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });

    // Test de notification
    $('#sendNotificationTest').on('click', function() {
        const email = $('#email').val();

        if (!email) {
            showResult('danger', 'Veuillez saisir une adresse email');
            return;
        }

        const btn = $(this);
        const originalText = btn.html();
        btn.html('<i class="mdi mdi-loading mdi-spin"></i> Envoi en cours...').prop('disabled', true);

        $.ajax({
            url: '<?= base_url('admin/test-email/send-test-notification') ?>',
            type: 'POST',
            data: {
                email: email
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showResult('success', response.message);
                } else {
                    showResult('danger', response.message);
                }
            },
            error: function(xhr) {
                showResult('danger', 'Erreur AJAX: ' + xhr.statusText);
            },
            complete: function() {
                btn.html(originalText).prop('disabled', false);
            }
        });
    });

    function showResult(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'mdi-check-circle' : 'mdi-alert-circle';

        $('#testResult')
            .removeClass('alert-success alert-danger')
            .addClass('alert ' + alertClass)
            .html('<i class="mdi ' + icon + '"></i> ' + message)
            .fadeIn();

        // Masquer automatiquement après 10 secondes si succès
        if (type === 'success') {
            setTimeout(function() {
                $('#testResult').fadeOut();
            }, 10000);
        }
    }
});
</script>

<?= $this->endSection() ?>
