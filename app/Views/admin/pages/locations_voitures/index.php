<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="feather-key me-2"></i>
                Locations de Voitures
            </h5>
            <button type="button" class="btn text-white" style="background: #d29751;" data-bs-toggle="modal" data-bs-target="#createLocationModal">
                <i class="feather-plus me-2"></i>Nouvelle Location
            </button>
        </div>

        <!-- Onglets -->
        <ul class="nav nav-tabs mb-4" id="locationTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="en_cours-tab" data-bs-toggle="tab" data-bs-target="#en_cours" type="button" role="tab">
                    <i class="feather-play-circle me-2"></i>En Cours
                    <span class="badge bg-info ms-2"><?= count($locations['en_cours']) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="terminee-tab" data-bs-toggle="tab" data-bs-target="#terminee" type="button" role="tab">
                    <i class="feather-check-circle me-2"></i>Terminées
                    <span class="badge bg-success ms-2"><?= count($locations['terminee']) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="annulee-tab" data-bs-toggle="tab" data-bs-target="#annulee" type="button" role="tab">
                    <i class="feather-x-circle me-2"></i>Annulées
                    <span class="badge bg-danger ms-2"><?= count($locations['annulee']) ?></span>
                </button>
            </li>
        </ul>

        <!-- Contenu des onglets -->
        <div class="tab-content" id="locationTabsContent">

            <!-- Onglet En Cours -->
            <div class="tab-pane fade show active" id="en_cours" role="tabpanel">
                <div class="row">
                    <?php if (!empty($locations['en_cours'])): ?>
                        <?php foreach ($locations['en_cours'] as $location): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="mb-0"><?= esc($location['voiture_marque']) ?> <?= esc($location['voiture_modele']) ?></h5>
                                            <span class="badge bg-info">En Cours</span>
                                        </div>
                                        <p class="text-muted mb-2"><strong>Client:</strong> <?= esc($location['nom_client']) ?></p>
                                        <p class="text-muted mb-2"><strong>Départ:</strong> <?= date('d/m/Y H:i', strtotime($location['date_debut'])) ?></p>
                                        <p class="text-muted mb-2"><strong>Retour prévu:</strong> <?= date('d/m/Y H:i', strtotime($location['date_fin_prevue'])) ?></p>
                                        <?php if ($location['type_location'] === 'heure'): ?>
                                            <p class="text-muted mb-2"><strong>Durée:</strong> <?= $location['duree_heures'] ?> heure(s)</p>
                                        <?php else: ?>
                                            <p class="text-muted mb-2"><strong>Durée:</strong> <?= $location['nombre_jours'] ?> jour(s)</p>
                                        <?php endif; ?>
                                        <?php if (!empty($location['kilometrage_depart'])): ?>
                                            <p class="text-muted mb-2"><strong>Km départ:</strong> <?= number_format($location['kilometrage_depart'], 0, ',', ' ') ?> km</p>
                                        <?php endif; ?>

                                        <?php
                                        $dateRetourPrevue = new DateTime($location['date_fin_prevue']);
                                        $aujourd_hui = new DateTime();
                                        $diff = $aujourd_hui->diff($dateRetourPrevue);
                                        $joursRestants = $dateRetourPrevue < $aujourd_hui ? -$diff->days : $diff->days;
                                        ?>

                                        <?php if ($joursRestants < 0): ?>
                                            <div class="alert alert-danger p-2 mb-3">
                                                <small><i class="feather-alert-triangle"></i> En retard de <?= abs($joursRestants) ?> jour(s)</small>
                                            </div>
                                        <?php elseif ($joursRestants <= 1): ?>
                                            <div class="alert alert-warning p-2 mb-3">
                                                <small><i class="feather-clock"></i> Retour prévu aujourd'hui/demain</small>
                                            </div>
                                        <?php endif; ?>

                                        <div class="d-flex justify-content-between mt-3">
                                            <button class="btn btn-sm btn-success" onclick="terminerLocation(<?= $location['id'] ?>)" title="Terminer">
                                                <i class="feather-check"></i> Retour
                                            </button>
                                            <button class="btn btn-sm btn-primary" onclick="voirLocation(<?= $location['id'] ?>)" title="Voir">
                                                <i class="feather-eye"></i>
                                            </button>
                                            <?php if ($location['montant_restant'] > 0): ?>
                                            <button class="btn btn-sm btn-warning" onclick="ajouterPaiement(<?= $location['id'] ?>)" title="Paiement">
                                                <i class="feather-dollar-sign"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center text-muted py-5">
                                <i class="feather-play-circle" style="font-size: 3rem;"></i>
                                <p class="mt-3">Aucune location en cours</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Onglet Terminées -->
            <div class="tab-pane fade" id="terminee" role="tabpanel">
                <div class="row">
                    <?php if (!empty($locations['terminee'])): ?>
                        <?php foreach ($locations['terminee'] as $location): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="mb-0"><?= esc($location['voiture_marque']) ?> <?= esc($location['voiture_modele']) ?></h5>
                                            <span class="badge bg-success">Terminée</span>
                                        </div>
                                        <p class="text-muted mb-2"><strong>Client:</strong> <?= esc($location['nom_client']) ?></p>
                                        <p class="text-muted mb-2"><strong>Période:</strong> <?= date('d/m/Y', strtotime($location['date_debut'])) ?> - <?= date('d/m/Y', strtotime($location['date_fin_reelle'])) ?></p>
                                        <p class="text-muted mb-2"><strong>Montant:</strong> <?= number_format($location['montant_total'], 0, ',', ' ') ?> FCFA</p>
                                        <p class="text-muted mb-2">
                                            <strong>Paiement:</strong>
                                            <?php if ($location['montant_restant'] <= 0): ?>
                                                <span class="badge bg-success">Soldé</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Impayé: <?= number_format($location['montant_restant'], 0, ',', ' ') ?> FCFA</span>
                                            <?php endif; ?>
                                        </p>

                                        <div class="d-flex justify-content-end mt-3">
                                            <button class="btn btn-sm btn-primary" onclick="voirLocation(<?= $location['id'] ?>)" title="Voir">
                                                <i class="feather-eye"></i> Détails
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center text-muted py-5">
                                <i class="feather-check-circle" style="font-size: 3rem;"></i>
                                <p class="mt-3">Aucune location terminée</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Onglet Annulées -->
            <div class="tab-pane fade" id="annulee" role="tabpanel">
                <div class="row">
                    <?php if (!empty($locations['annulee'])): ?>
                        <?php foreach ($locations['annulee'] as $location): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="mb-0"><?= esc($location['voiture_marque']) ?> <?= esc($location['voiture_modele']) ?></h5>
                                            <span class="badge bg-danger">Annulée</span>
                                        </div>
                                        <p class="text-muted mb-2"><strong>Client:</strong> <?= esc($location['nom_client']) ?></p>
                                        <p class="text-muted mb-2"><strong>Période prévue:</strong> <?= date('d/m/Y', strtotime($location['date_debut'])) ?> - <?= date('d/m/Y', strtotime($location['date_fin_prevue'])) ?></p>

                                        <div class="d-flex justify-content-end mt-3">
                                            <button class="btn btn-sm btn-primary" onclick="voirLocation(<?= $location['id'] ?>)" title="Voir">
                                                <i class="feather-eye"></i> Détails
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center text-muted py-5">
                                <i class="feather-x-circle" style="font-size: 3rem;"></i>
                                <p class="mt-3">Aucune location annulée</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modals -->
<?= $this->include('admin/pages/locations_voitures/modals/create') ?>
<?= $this->include('admin/pages/locations_voitures/modals/view') ?>
<?= $this->include('admin/pages/locations_voitures/modals/terminer') ?>
<?= $this->include('admin/pages/locations_voitures/modals/annuler') ?>
<?= $this->include('admin/pages/locations_voitures/modals/paiement') ?>

<?= $this->endSection() ?>
