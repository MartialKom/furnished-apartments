<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white text-center">
                <h4 class="mb-0">
                    <i class="feather-alert-triangle me-2"></i>
                    Acces Refuse
                </h4>
            </div>
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="feather-lock" style="font-size: 80px; color: #dc3545;"></i>
                </div>

                <h5 class="text-muted mb-3">Vous n'avez pas les permissions necessaires</h5>

                <p class="text-muted">
                    Votre role <strong>"<?= esc($user_role_name ?? 'Non defini') ?>"</strong>
                    ne dispose pas des permissions necessaires pour acceder aux pages du systeme.
                </p>

                <?php if (empty($user_permissions)): ?>
                    <div class="alert alert-warning mt-4">
                        <i class="feather-info me-2"></i>
                        <strong>Aucune permission assignee a votre role.</strong><br>
                        Contactez un administrateur pour obtenir les permissions necessaires.
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mt-4">
                        <i class="feather-info me-2"></i>
                        Vous avez <?= count($user_permissions) ?> permission(s), mais aucune ne permet d'acceder aux pages principales.
                    </div>
                <?php endif; ?>

                <div class="mt-4">
                    <a href="<?= base_url('/admin/logout') ?>" class="btn btn-outline-danger">
                        <i class="feather-log-out me-2"></i>
                        Se deconnecter
                    </a>
                </div>
            </div>
            <div class="card-footer text-muted text-center">
                <small>
                    Si vous pensez qu'il s'agit d'une erreur, contactez l'administrateur du systeme.
                </small>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
