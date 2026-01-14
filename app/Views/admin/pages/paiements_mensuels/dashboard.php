<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Statistiques -->
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100 text-white" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%); border: none; box-shadow: 0 4px 12px rgba(210, 151, 81, 0.3); border-radius: 10px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div>
                            <h4 class="fw-bolder mb-0"><?= number_format($stats['total_du_mois'], 0, ',', ' ') ?> FCFA</h4>
                            <p class="fs-13 fw-medium mb-0 opacity-75">Total dû ce mois</p>
                        </div>
                        <div class="avatar-text avatar-lg text-white" style="background: rgba(255,255,255,0.2);">
                            <i class="feather-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100 text-white" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%); border: none; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3); border-radius: 10px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div>
                            <h4 class="fw-bolder mb-0"><?= number_format($stats['total_paye_mois'], 0, ',', ' ') ?> FCFA</h4>
                            <p class="fs-13 fw-medium mb-0 opacity-75">Total payé ce mois</p>
                        </div>
                        <div class="avatar-text avatar-lg text-white" style="background: rgba(255,255,255,0.2);">
                            <i class="feather-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100 text-white" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); border: none; box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3); border-radius: 10px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div>
                            <h4 class="fw-bolder mb-0"><?= $stats['en_retard'] ?></h4>
                            <p class="fs-13 fw-medium mb-0 opacity-75">Paiements en retard</p>
                        </div>
                        <div class="avatar-text avatar-lg text-white" style="background: rgba(255,255,255,0.2);">
                            <i class="feather-alert-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100 text-white" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); border: none; box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3); border-radius: 10px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div>
                            <h4 class="fw-bolder mb-0"><?= $stats['taux_recouvrement'] ?>%</h4>
                            <p class="fs-13 fw-medium mb-0 opacity-75">Taux de recouvrement</p>
                        </div>
                        <div class="avatar-text avatar-lg text-white" style="background: rgba(255,255,255,0.2);">
                            <i class="feather-trending-up"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Échéances proches -->
        <div class="col-md-6">
            <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%); border-radius: 10px 10px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold"><i class="feather-calendar me-2"></i>Échéances Proches (5 jours)</h6>
                        <button class="btn btn-light btn-sm" onclick="envoyerRappelsGroupes()">
                            <i class="feather-mail me-1"></i> Envoyer rappels
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($echeances_proches)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Locataire</th>
                                        <th>Appartement</th>
                                        <th>Montant</th>
                                        <th>Échéance</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($echeances_proches as $echeance): ?>
                                        <tr>
                                            <td><?= $echeance['locataire_nom'] ?></td>
                                            <td><?= $echeance['appartement_adresse'] ?></td>
                                            <td><?= number_format($echeance['montant_du'], 0, ',', ' ') ?> FCFA</td>
                                            <td>
                                                <span class="badge bg-warning text-dark d-flex align-items-center">
                                                    <i class="feather-calendar me-1" style="font-size: 12px;"></i>
                                                    <?= date('d/m/Y', strtotime($echeance['date_echeance'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info" onclick="envoyerRappel(<?= $echeance['id'] ?>, this)">
                                                    <i class="fas fa-envelope"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success text-center">
                            <i class="fas fa-check-circle"></i> Aucune échéance proche.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Retards de paiement -->
        <div class="col-md-6">
            <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border-radius: 10px 10px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold"><i class="feather-alert-triangle me-2"></i>Retards de Paiement</h6>
                        <button class="btn btn-light btn-sm" onclick="envoyerRappelsRetard()">
                            <i class="feather-bell me-1"></i> Rappels retard
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($retards)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Locataire</th>
                                        <th>Appartement</th>
                                        <th>Montant</th>
                                        <th>Retard</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($retards as $retard): ?>
                                        <?php 
                                        $joursRetard = floor((time() - strtotime($retard['date_echeance'])) / (60 * 60 * 24));
                                        ?>
                                        <tr class="<?= $joursRetard > 30 ? 'table-danger' : 'table-warning' ?>">
                                            <td><?= $retard['locataire_nom'] ?></td>
                                            <td><?= $retard['appartement_adresse'] ?></td>
                                            <td><?= number_format($retard['montant_du'], 0, ',', ' ') ?> FCFA</td>
                                            <td>
                                                <?php
                                                $retardClass = $joursRetard > 30 ? 'bg-danger' : 'bg-warning text-dark';
                                                $retardIcon = $joursRetard > 30 ? 'feather-alert-triangle' : 'feather-clock';
                                                ?>
                                                <span class="badge <?= $retardClass ?> d-flex align-items-center">
                                                    <i class="<?= $retardIcon ?> me-1" style="font-size: 12px;"></i>
                                                    <?= $joursRetard ?> jour(s)
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" onclick="envoyerRappel(<?= $retard['id'] ?>, this)">
                                                    <i class="fas fa-envelope"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success text-center">
                            <i class="fas fa-check-circle"></i> Aucun retard de paiement.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%); border-radius: 10px 10px 0 0;">
                    <h6 class="mb-0 fw-semibold"><i class="feather-zap me-2"></i>Actions Rapides</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="<?= base_url('admin/paiements-mensuels') ?>" class="btn w-100 text-white" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%); border: none; box-shadow: 0 2px 6px rgba(210, 151, 81, 0.3);">
                                <i class="feather-list me-2"></i> Voir tous les paiements
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= base_url('admin/contrats') ?>" class="btn btn-outline-primary w-100" style="border-color: #d29751; color: #d29751;">
                                <i class="feather-file-text me-2"></i> Gérer les contrats
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-warning w-100" onclick="verifierEcheances()">
                                <i class="feather-refresh-cw me-2"></i> Vérifier les échéances
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-success w-100" onclick="genererRapport()">
                                <i class="feather-download me-2"></i> Générer rapport
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function envoyerRappel(paiementId, buttonElement) {
    // Désactiver le bouton et ajouter un indicateur de chargement
    const $btn = $(buttonElement);
    const originalHtml = $btn.html();
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

    // Afficher un message d'information
    toastr.info('Envoi du rappel en cours...', 'Veuillez patienter', {
        timeOut: 2000
    });

    $.ajax({
        url: '<?= base_url('admin/paiements-mensuels/rappel') ?>/' + paiementId,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Afficher une popup de succès attractive
                toastr.success(response.message, 'Email envoyé avec succès!', {
                    timeOut: 5000,
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    preventDuplicates: true
                });

                // Changer la couleur du bouton pour indiquer que l'email a été envoyé
                $btn.removeClass('btn-info btn-danger').addClass('btn-success').html('<i class="fas fa-check"></i>');

                // Réactiver le bouton après 3 secondes
                setTimeout(function() {
                    $btn.prop('disabled', false).removeClass('btn-success').addClass('btn-info').html(originalHtml);
                }, 3000);
            } else {
                toastr.error(response.message, 'Erreur d\'envoi', {
                    timeOut: 5000,
                    closeButton: true,
                    progressBar: true
                });
                // Réactiver le bouton en cas d'erreur
                $btn.prop('disabled', false).html(originalHtml);
            }
        },
        error: function(xhr) {
            let errorMessage = 'Erreur lors de l\'envoi du rappel';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            toastr.error(errorMessage, 'Erreur', {
                timeOut: 5000,
                closeButton: true,
                progressBar: true
            });
            // Réactiver le bouton en cas d'erreur
            $btn.prop('disabled', false).html(originalHtml);
        }
    });
}

function envoyerRappelsGroupes() {
    if (confirm('Êtes-vous sûr de vouloir envoyer des rappels à tous les locataires avec échéances proches ?')) {
        $.ajax({
            url: '<?= base_url('admin/paiements-mensuels/rappels-groupes') ?>',
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                toastr.info('Envoi des rappels en cours...', 'Veuillez patienter');
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message, 'Succès');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    toastr.error(response.message, 'Erreur');
                }
            },
            error: function() {
                toastr.error('Erreur lors de l\'envoi des rappels', 'Erreur');
            }
        });
    }
}

function envoyerRappelsRetard() {
    if (confirm('Êtes-vous sûr de vouloir envoyer des rappels de retard à tous les locataires concernés ?')) {
        $.ajax({
            url: '<?= base_url('admin/paiements-mensuels/rappels-retard') ?>',
            type: 'POST',
            dataType: 'json',
            beforeSend: function() {
                toastr.warning('Envoi des rappels de retard en cours...', 'Veuillez patienter');
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message, 'Succès');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    toastr.error(response.message, 'Erreur');
                }
            },
            error: function() {
                toastr.error('Erreur lors de l\'envoi des rappels de retard', 'Erreur');
            }
        });
    }
}

function verifierEcheances() {
    $.ajax({
        url: '<?= base_url('admin/contrats/echeances-proches') ?>',
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
            toastr.info('Vérification des échéances en cours...', 'Veuillez patienter');
        },
        success: function(response) {
            if (response.success) {
                toastr.success('Vérification terminée. ' + (response.echeances?.length || 0) + ' échéance(s) trouvée(s).', 'Succès');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            }
        },
        error: function() {
            toastr.error('Erreur lors de la vérification', 'Erreur');
        }
    });
}

function genererRapport() {
    toastr.info('Génération du rapport en cours...', 'Veuillez patienter');
    window.open('<?= base_url('admin/rapports?type=paiements-mensuels') ?>', '_blank');
}

// Actualiser automatiquement toutes les 5 minutes
setInterval(function() {
    location.reload();
}, 300000);
</script>
<?= $this->endSection() ?>
