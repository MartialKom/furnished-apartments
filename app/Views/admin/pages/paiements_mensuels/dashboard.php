<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Statistiques -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?= number_format($stats['total_du_mois'], 0, ',', ' ') ?></h3>
                    <p>Total dû ce mois</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= number_format($stats['total_paye_mois'], 0, ',', ' ') ?></h3>
                    <p>Total payé ce mois</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= $stats['en_retard'] ?></h3>
                    <p>Paiements en retard</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?= $stats['taux_recouvrement'] ?>%</h3>
                    <p>Taux de recouvrement</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Échéances proches -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Échéances Proches (5 jours)</h3>
                    <div class="card-tools">
                        <button class="btn btn-primary btn-sm" onclick="envoyerRappelsGroupes()">
                            <i class="fas fa-envelope"></i> Envoyer tous les rappels
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
                                                <button class="btn btn-sm btn-info" onclick="envoyerRappel(<?= $echeance['id'] ?>)">
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Retards de Paiement</h3>
                    <div class="card-tools">
                        <button class="btn btn-danger btn-sm" onclick="envoyerRappelsRetard()">
                            <i class="fas fa-exclamation-triangle"></i> Rappels retard
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
                                                <button class="btn btn-sm btn-danger" onclick="envoyerRappel(<?= $retard['id'] ?>)">
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions Rapides</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="<?= base_url('admin/paiements-mensuels') ?>" class="btn btn-info btn-block">
                                <i class="fas fa-list"></i> Voir tous les paiements
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= base_url('admin/contrats') ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-file-contract"></i> Gérer les contrats
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-warning btn-block" onclick="verifierEcheances()">
                                <i class="fas fa-sync"></i> Vérifier les échéances
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success btn-block" onclick="genererRapport()">
                                <i class="fas fa-file-pdf"></i> Générer rapport
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function envoyerRappel(paiementId) {
    $.ajax({
        url: '<?= base_url('admin/paiements-mensuels/rappel') ?>/' + paiementId,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        },
        error: function() {
            toastr.error('Erreur lors de l\'envoi du rappel');
        }
    });
}

function envoyerRappelsGroupes() {
    if (confirm('Envoyer des rappels à tous les locataires avec échéances proches ?')) {
        // TODO: Implémenter l'envoi groupé
        toastr.info('Fonctionnalité en cours de développement');
    }
}

function envoyerRappelsRetard() {
    if (confirm('Envoyer des rappels à tous les locataires en retard ?')) {
        // TODO: Implémenter l'envoi groupé
        toastr.info('Fonctionnalité en cours de développement');
    }
}

function verifierEcheances() {
    $.ajax({
        url: '<?= base_url('admin/contrats/echeances-proches') ?>',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                toastr.info('Vérification terminée. ' + response.echeances.length + ' échéances trouvées.');
                location.reload();
            }
        },
        error: function() {
            toastr.error('Erreur lors de la vérification');
        }
    });
}

function genererRapport() {
    // TODO: Implémenter la génération de rapport
    toastr.info('Fonctionnalité en cours de développement');
}

// Actualiser automatiquement toutes les 5 minutes
setInterval(function() {
    location.reload();
}, 300000);
</script>
<?= $this->endSection() ?>
