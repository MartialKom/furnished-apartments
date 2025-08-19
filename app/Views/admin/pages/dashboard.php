<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: none;">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-1" style="color: #d29751;">Bienvenue, <?= ucfirst(session()->get('user_role') ?? 'Administrateur') ?> !</h5>
                        <p class="fs-12 fw-medium text-muted mb-0">Voici ce qui se passe avec vos appartements meublés aujourd'hui.</p>
                    </div>
                    <div class="avatar-text avatar-lg text-white" style="background: #d29751;">
                        <i class="feather-home"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Appartements Disponibles -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card h-100" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <h4 class="fw-bolder mb-0" style="color: #d29751;"><?= $stats['appartements_disponibles'] ?? 0 ?></h4>
                        <p class="fs-13 fw-medium text-muted mb-0">Appartements Disponibles</p>
                    </div>
                    <div class="avatar-text avatar-md text-white" style="background: #d29751;">
                        <i class="feather-home"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <small class="text-muted">Total: <?= $stats['total_appartements'] ?? 0 ?></small>
                    <small class="text-success">
                        <?php 
                        $pourcentage = $stats['total_appartements'] > 0 ? round(($stats['appartements_disponibles'] / $stats['total_appartements']) * 100, 1) : 0;
                        echo $pourcentage . '%';
                        ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Appartements en Maintenance -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card h-100" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <h4 class="fw-bolder mb-0" style="color: #ffc107;"><?= $stats['appartements_maintenance'] ?? 0 ?></h4>
                        <p class="fs-13 fw-medium text-muted mb-0">En Maintenance</p>
                    </div>
                    <div class="avatar-text avatar-md text-white" style="background: #ffc107;">
                        <i class="feather-tool"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <small class="text-muted">Occupés: <?= $stats['appartements_occupes'] ?? 0 ?></small>
                    <small class="text-warning">Maintenance</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Réservations en Attente -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card h-100" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <h4 class="fw-bolder mb-0" style="color: #17a2b8;"><?= $stats['reservations_en_attente'] ?? 0 ?></h4>
                        <p class="fs-13 fw-medium text-muted mb-0">Réservations en Attente</p>
                    </div>
                    <div class="avatar-text avatar-md text-white" style="background: #17a2b8;">
                        <i class="feather-clock"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <small class="text-muted">Total: <?= $stats['total_reservations'] ?? 0 ?></small>
                    <small class="text-info">En attente</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Réservations Confirmées -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card h-100" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <h4 class="fw-bolder mb-0" style="color: #28a745;"><?= $stats['reservations_confirmees'] ?? 0 ?></h4>
                        <p class="fs-13 fw-medium text-muted mb-0">Réservations Confirmées</p>
                    </div>
                    <div class="avatar-text avatar-md text-white" style="background: #28a745;">
                        <i class="feather-check-circle"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <small class="text-muted">Nouveaux locataires: <?= $stats['nouveaux_locataires'] ?? 0 ?></small>
                    <small class="text-success">Confirmées</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities and Quick Actions -->
<div class="row">
    <div class="col-xl-8">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%); border-radius: 10px 10px 0 0;">
                <h5 class="card-title text-white mb-0">Réservations Récentes</h5>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($recentes_reservations)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background: #f8f9fa;">
                                <tr>
                                    <th class="border-0">Locataire</th>
                                    <th class="border-0">Appartement</th>
                                    <th class="border-0">Date début</th>
                                    <th class="border-0">Date fin</th>
                                    <th class="border-0">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentes_reservations as $reservation): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-text avatar-sm" style="background: #d29751; color: white;">
                                                    <?= strtoupper(substr($reservation['locataire_nom'], 0, 2)) ?>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold"><?= esc($reservation['locataire_nom']) ?></div>
                                                    <small class="text-muted"><?= esc($reservation['email']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="fw-medium"><?= esc($reservation['adresse']) ?></span></td>
                                        <td><span class="fs-12 text-muted"><?= date('d/m/Y', strtotime($reservation['date_debut'])) ?></span></td>
                                        <td><span class="fs-12 text-muted"><?= date('d/m/Y', strtotime($reservation['date_fin'])) ?></span></td>
                                        <td>
                                            <?php 
                                            $badgeClass = '';
                                            $statusText = '';
                                            switch($reservation['statut']) {
                                                case 'en_attente':
                                                    $badgeClass = 'bg-warning';
                                                    $statusText = 'En attente';
                                                    break;
                                                case 'confirmee':
                                                    $badgeClass = 'bg-success';
                                                    $statusText = 'Confirmée';
                                                    break;
                                                case 'annulee':
                                                    $badgeClass = 'bg-danger';
                                                    $statusText = 'Annulée';
                                                    break;
                                                default:
                                                    $badgeClass = 'bg-secondary';
                                                    $statusText = ucfirst($reservation['statut']);
                                            }
                                            ?>
                                            <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="p-4 text-center">
                        <i class="feather-calendar" style="font-size: 48px; color: #d29751; opacity: 0.5;"></i>
                        <p class="text-muted mt-2">Aucune réservation récente</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
            <div class="card-header" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%); border-radius: 10px 10px 0 0;">
                <h5 class="card-title text-white mb-0">Actions Rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="<?= base_url('/admin/apartments/create') ?>" class="btn text-white" style="background: #d29751; border: none; transition: all 0.3s ease;" 
                       onmouseover="this.style.background='#b8834a'" onmouseout="this.style.background='#d29751'">
                        <i class="feather-plus me-2"></i>Ajouter un Appartement
                    </a>
                    <a href="<?= base_url('/admin/reservations') ?>" class="btn btn-outline-primary" style="border-color: #d29751; color: #d29751; transition: all 0.3s ease;"
                       onmouseover="this.style.background='#d29751'; this.style.color='white'" onmouseout="this.style.background='transparent'; this.style.color='#d29751'">
                        <i class="feather-calendar me-2"></i>Voir les Réservations
                    </a>
                    <a href="<?= base_url('/admin/locataires') ?>" class="btn btn-outline-success">
                        <i class="feather-users me-2"></i>Gérer les Locataires
                    </a>
                    <a href="<?= base_url('/admin/reports') ?>" class="btn btn-outline-info">
                        <i class="feather-bar-chart-2 me-2"></i>Voir les Rapports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>