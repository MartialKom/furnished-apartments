<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="feather-calendar me-2"></i>
                Gestion des Réservations
            </h5>
            <button type="button" class="btn text-white" style="background: #d29751;" data-bs-toggle="modal" data-bs-target="#createReservationModal">
                <i class="feather-plus me-2"></i>Créer une réservation
            </button>
        </div>
        
        <!-- Onglets -->
        <ul class="nav nav-tabs mb-4" id="reservationTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="attente-tab" data-bs-toggle="tab" data-bs-target="#attente" type="button" role="tab">
                    <i class="feather-clock me-2"></i>En attente
                    <span class="badge bg-warning text-dark ms-2"><?= count($reservations['en_attente']) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="confirmee-tab" data-bs-toggle="tab" data-bs-target="#confirmee" type="button" role="tab">
                    <i class="feather-check-circle me-2"></i>Confirmées
                    <span class="badge bg-success ms-2"><?= count($reservations['confirmee']) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="encours-tab" data-bs-toggle="tab" data-bs-target="#encours" type="button" role="tab">
                    <i class="feather-play-circle me-2"></i>En cours
                    <span class="badge bg-info ms-2"><?= count($reservations['en_cours']) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="terminee-tab" data-bs-toggle="tab" data-bs-target="#terminee" type="button" role="tab">
                    <i class="feather-check me-2"></i>Terminées
                    <span class="badge bg-secondary ms-2"><?= count($reservations['terminee']) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="annulee-tab" data-bs-toggle="tab" data-bs-target="#annulee" type="button" role="tab">
                    <i class="feather-x-circle me-2"></i>Annulées
                    <span class="badge bg-danger ms-2"><?= count($reservations['annulee']) ?></span>
                </button>
            </li>
        </ul>

        <!-- Contenu des onglets -->
        <div class="tab-content" id="reservationTabsContent">
            
            <!-- Onglet En attente -->
            <div class="tab-pane fade show active" id="attente" role="tabpanel">
                <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th class="border-0">Locataire</th>
                                        <th class="border-0">Appartement</th>
                                        <th class="border-0">Période</th>
                                        <th class="border-0">Montant</th>
                                        <th class="border-0">Réduction</th>
                                        <th class="border-0">Paiements</th>
                                        <th class="border-0">Mode paiement</th>
                                        <th class="border-0">Type</th>
                                        <th class="border-0">Date demande</th>
                                        <th class="border-0 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($reservations['en_attente'])): ?>
                                        <?php foreach ($reservations['en_attente'] as $reservation): ?>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold"><?= esc($reservation['nom']) ?></div>
                                                        <small class="text-muted"><?= esc($reservation['telephone']) ?></small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-medium"><?= esc($reservation['adresse']) ?></div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <small class="text-muted">Du</small> <?= date('d/m/Y', strtotime($reservation['date_debut'])) ?><br>
                                                        <small class="text-muted">Au</small> <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div class="fw-bold text-success"><?= number_format($reservation['montant_total'], 0, ',', ' ') ?> FCFA</div>
                                                        <small class="text-muted">Total</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if (isset($reservation['reduction_pourcentage']) && $reservation['reduction_pourcentage'] > 0): ?>
                                                        <div>
                                                            <span class="badge bg-warning text-dark">-<?= $reservation['reduction_pourcentage'] ?>%</span>
                                                            <div class="text-muted small"><?= number_format($reservation['montant_reduction'] ?? 0, 0, ',', ' ') ?> FCFA</div>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">Aucune</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold text-primary"><?= number_format($reservation['montant_paye'] ?? 0, 0, ',', ' ') ?> FCFA</div>
                                                        <div class="text-muted small">Restant: <?= number_format($reservation['montant_restant'] ?? $reservation['montant_total'], 0, ',', ' ') ?> FCFA</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php
                                                    $modePaiementClass = match($reservation['mode_paiement'] ?? 'especes') {
                                                        'especes' => 'bg-secondary',
                                                        'orange_money' => 'bg-warning text-dark',
                                                        'momo' => 'bg-info',
                                                        'carte_bancaire' => 'bg-primary',
                                                        'virement' => 'bg-success',
                                                        default => 'bg-secondary'
                                                    };
                                                    $modePaiementText = match($reservation['mode_paiement'] ?? 'especes') {
                                                        'especes' => 'Espèces',
                                                        'orange_money' => 'Orange Money',
                                                        'momo' => 'MOMO',
                                                        'carte_bancaire' => 'Carte bancaire',
                                                        'virement' => 'Virement',
                                                        default => 'Espèces'
                                                    };
                                                    ?>
                                                    <span class="badge <?= $modePaiementClass ?>"><?= $modePaiementText ?></span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $typeClass = match($reservation['type_reservation'] ?? 'en_ligne') {
                                                        'en_ligne' => 'bg-success',
                                                        'telephonique' => 'bg-info',
                                                        'presentiel' => 'bg-primary',
                                                        default => 'bg-secondary'
                                                    };
                                                    $typeText = match($reservation['type_reservation'] ?? 'en_ligne') {
                                                        'en_ligne' => 'En ligne',
                                                        'telephonique' => 'Téléphonique',
                                                        'presentiel' => 'Présentiel',
                                                        default => 'En ligne'
                                                    };
                                                    ?>
                                                    <span class="badge <?= $typeClass ?>"><?= $typeText ?></span>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($reservation['created_at'])) ?></small>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-success confirm-reservation" 
                                                                data-reservation-id="<?= $reservation['id'] ?>"
                                                                data-bs-toggle="tooltip" title="Confirmer">
                                                            <i class="feather-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-primary add-payment" 
                                                                data-reservation-id="<?= $reservation['id'] ?>"
                                                                data-bs-toggle="tooltip" title="Ajouter un paiement">
                                                            <i class="feather-credit-card"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-info view-payments" 
                                                                data-reservation-id="<?= $reservation['id'] ?>"
                                                                data-bs-toggle="tooltip" title="Voir les paiements">
                                                            <i class="feather-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger cancel-reservation" 
                                                                data-reservation-id="<?= $reservation['id'] ?>"
                                                                data-bs-toggle="tooltip" title="Annuler">
                                                            <i class="feather-x"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
                                                <i class="feather-clock" style="font-size: 48px; color: #d29751; opacity: 0.5;"></i>
                                                <p class="text-muted mt-2">Aucune réservation en attente</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onglet Confirmées -->
            <div class="tab-pane fade" id="confirmee" role="tabpanel">
                <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th class="border-0">Locataire</th>
                                        <th class="border-0">Appartement</th>
                                        <th class="border-0">Période</th>
                                        <th class="border-0">Montant</th>
                                        <th class="border-0">Réduction</th>
                                        <th class="border-0">Paiements</th>
                                        <th class="border-0">Mode paiement</th>
                                        <th class="border-0">Type</th>
                                        <th class="border-0">Date confirmation</th>
                                        <th class="border-0 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($reservations['confirmee'])): ?>
                                        <?php foreach ($reservations['confirmee'] as $reservation): ?>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold"><?= esc($reservation['nom']) ?></div>
                                                        <small class="text-muted"><?= esc($reservation['telephone']) ?></small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-medium"><?= esc($reservation['adresse']) ?></div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <small class="text-muted">Du</small> <?= date('d/m/Y', strtotime($reservation['date_debut'])) ?><br>
                                                        <small class="text-muted">Au</small> <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div class="fw-bold text-success"><?= number_format($reservation['montant_total'], 0, ',', ' ') ?> FCFA</div>
                                                        <small class="text-muted">Total</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if (isset($reservation['reduction_pourcentage']) && $reservation['reduction_pourcentage'] > 0): ?>
                                                        <div>
                                                            <span class="badge bg-warning text-dark">-<?= $reservation['reduction_pourcentage'] ?>%</span>
                                                            <div class="text-muted small"><?= number_format($reservation['montant_reduction'] ?? 0, 0, ',', ' ') ?> FCFA</div>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">Aucune</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold text-primary"><?= number_format($reservation['montant_paye'] ?? 0, 0, ',', ' ') ?> FCFA</div>
                                                        <div class="text-muted small">Restant: <?= number_format($reservation['montant_restant'] ?? $reservation['montant_total'], 0, ',', ' ') ?> FCFA</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php
                                                    $modePaiementClass = match($reservation['mode_paiement'] ?? 'especes') {
                                                        'especes' => 'bg-secondary',
                                                        'orange_money' => 'bg-warning text-dark',
                                                        'momo' => 'bg-info',
                                                        'carte_bancaire' => 'bg-primary',
                                                        'virement' => 'bg-success',
                                                        default => 'bg-secondary'
                                                    };
                                                    $modePaiementText = match($reservation['mode_paiement'] ?? 'especes') {
                                                        'especes' => 'Espèces',
                                                        'orange_money' => 'Orange Money',
                                                        'momo' => 'MOMO',
                                                        'carte_bancaire' => 'Carte bancaire',
                                                        'virement' => 'Virement',
                                                        default => 'Espèces'
                                                    };
                                                    ?>
                                                    <span class="badge <?= $modePaiementClass ?>"><?= $modePaiementText ?></span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $typeClass = match($reservation['type_reservation'] ?? 'en_ligne') {
                                                        'en_ligne' => 'bg-success',
                                                        'telephonique' => 'bg-info',
                                                        'presentiel' => 'bg-primary',
                                                        default => 'bg-secondary'
                                                    };
                                                    $typeText = match($reservation['type_reservation'] ?? 'en_ligne') {
                                                        'en_ligne' => 'En ligne',
                                                        'telephonique' => 'Téléphonique',
                                                        'presentiel' => 'Présentiel',
                                                        default => 'En ligne'
                                                    };
                                                    ?>
                                                    <span class="badge <?= $typeClass ?>"><?= $typeText ?></span>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($reservation['updated_at'])) ?></small>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-primary add-payment"
                                                                data-reservation-id="<?= $reservation['id'] ?>"
                                                                data-bs-toggle="tooltip" title="Ajouter un paiement">
                                                            <i class="feather-credit-card"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-info view-payments"
                                                                data-reservation-id="<?= $reservation['id'] ?>"
                                                                data-bs-toggle="tooltip" title="Voir les paiements">
                                                            <i class="feather-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger cancel-reservation"
                                                                data-reservation-id="<?= $reservation['id'] ?>"
                                                                data-bs-toggle="tooltip" title="Annuler">
                                                            <i class="feather-x"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
                                                <i class="feather-check-circle" style="font-size: 48px; color: #d29751; opacity: 0.5;"></i>
                                                <p class="text-muted mt-2">Aucune réservation confirmée</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onglet En cours -->
            <div class="tab-pane fade" id="encours" role="tabpanel">
                <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th class="border-0">Locataire</th>
                                        <th class="border-0">Appartement</th>
                                        <th class="border-0">Période</th>
                                        <th class="border-0">Montant</th>
                                        <th class="border-0">Réduction</th>
                                        <th class="border-0">Paiements</th>
                                        <th class="border-0">Mode paiement</th>
                                        <th class="border-0">Type</th>
                                        <th class="border-0">Date début</th>
                                        <th class="border-0 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($reservations['en_cours'])): ?>
                                        <?php foreach ($reservations['en_cours'] as $reservation): ?>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold"><?= esc($reservation['nom']) ?></div>
                                                        <small class="text-muted"><?= esc($reservation['telephone']) ?></small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-medium"><?= esc($reservation['adresse']) ?></div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <small class="text-muted">Du</small> <?= date('d/m/Y', strtotime($reservation['date_debut'])) ?><br>
                                                        <small class="text-muted">Au</small> <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div class="fw-bold text-success"><?= number_format($reservation['montant_total'], 0, ',', ' ') ?> FCFA</div>
                                                        <small class="text-muted">Total</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if (isset($reservation['reduction_pourcentage']) && $reservation['reduction_pourcentage'] > 0): ?>
                                                        <div>
                                                            <span class="badge bg-warning text-dark">-<?= $reservation['reduction_pourcentage'] ?>%</span>
                                                            <div class="text-muted small"><?= number_format($reservation['montant_reduction'] ?? 0, 0, ',', ' ') ?> FCFA</div>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">Aucune</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold text-primary"><?= number_format($reservation['montant_paye'] ?? 0, 0, ',', ' ') ?> FCFA</div>
                                                        <div class="text-muted small">Restant: <?= number_format($reservation['montant_restant'] ?? $reservation['montant_total'], 0, ',', ' ') ?> FCFA</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php
                                                    $modePaiementClass = match($reservation['mode_paiement'] ?? 'especes') {
                                                        'especes' => 'bg-secondary',
                                                        'orange_money' => 'bg-warning text-dark',
                                                        'momo' => 'bg-info',
                                                        'carte_bancaire' => 'bg-primary',
                                                        'virement' => 'bg-success',
                                                        default => 'bg-secondary'
                                                    };
                                                    $modePaiementText = match($reservation['mode_paiement'] ?? 'especes') {
                                                        'especes' => 'Espèces',
                                                        'orange_money' => 'Orange Money',
                                                        'momo' => 'MOMO',
                                                        'carte_bancaire' => 'Carte bancaire',
                                                        'virement' => 'Virement',
                                                        default => 'Espèces'
                                                    };
                                                    ?>
                                                    <span class="badge <?= $modePaiementClass ?>"><?= $modePaiementText ?></span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $typeClass = match($reservation['type_reservation'] ?? 'en_ligne') {
                                                        'en_ligne' => 'bg-success',
                                                        'telephonique' => 'bg-info',
                                                        'presentiel' => 'bg-primary',
                                                        default => 'bg-secondary'
                                                    };
                                                    $typeText = match($reservation['type_reservation'] ?? 'en_ligne') {
                                                        'en_ligne' => 'En ligne',
                                                        'telephonique' => 'Téléphonique',
                                                        'presentiel' => 'Présentiel',
                                                        default => 'En ligne'
                                                    };
                                                    ?>
                                                    <span class="badge <?= $typeClass ?>"><?= $typeText ?></span>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?= date('d/m/Y', strtotime($reservation['date_debut'])) ?></small>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-primary add-payment"
                                                                data-reservation-id="<?= $reservation['id'] ?>"
                                                                data-bs-toggle="tooltip" title="Ajouter un paiement">
                                                            <i class="feather-credit-card"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-info view-payments"
                                                                data-reservation-id="<?= $reservation['id'] ?>"
                                                                data-bs-toggle="tooltip" title="Voir les paiements">
                                                            <i class="feather-eye"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
                                                <i class="feather-play-circle" style="font-size: 48px; color: #d29751; opacity: 0.5;"></i>
                                                <p class="text-muted mt-2">Aucune réservation en cours</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onglet Terminées -->
            <div class="tab-pane fade" id="terminee" role="tabpanel">
                <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th class="border-0">Locataire</th>
                                        <th class="border-0">Appartement</th>
                                        <th class="border-0">Période</th>
                                        <th class="border-0">Montant</th>
                                        <th class="border-0">Réduction</th>
                                        <th class="border-0">Paiements</th>
                                        <th class="border-0">Mode paiement</th>
                                        <th class="border-0">Type</th>
                                        <th class="border-0">Date fin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($reservations['terminee'])): ?>
                                        <?php foreach ($reservations['terminee'] as $reservation): ?>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold"><?= esc($reservation['nom']) ?></div>
                                                        <small class="text-muted"><?= esc($reservation['telephone']) ?></small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-medium"><?= esc($reservation['adresse']) ?></div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <small class="text-muted">Du</small> <?= date('d/m/Y', strtotime($reservation['date_debut'])) ?><br>
                                                        <small class="text-muted">Au</small> <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div class="fw-bold text-success"><?= number_format($reservation['montant_total'], 0, ',', ' ') ?> FCFA</div>
                                                        <small class="text-muted">Total</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if (isset($reservation['reduction_pourcentage']) && $reservation['reduction_pourcentage'] > 0): ?>
                                                        <div>
                                                            <span class="badge bg-warning text-dark">-<?= $reservation['reduction_pourcentage'] ?>%</span>
                                                            <div class="text-muted small"><?= number_format($reservation['montant_reduction'] ?? 0, 0, ',', ' ') ?> FCFA</div>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">Aucune</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold text-primary"><?= number_format($reservation['montant_paye'] ?? 0, 0, ',', ' ') ?> FCFA</div>
                                                        <div class="text-muted small">Restant: <?= number_format($reservation['montant_restant'] ?? $reservation['montant_total'], 0, ',', ' ') ?> FCFA</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php
                                                    $modePaiementClass = match($reservation['mode_paiement'] ?? 'especes') {
                                                        'especes' => 'bg-secondary',
                                                        'orange_money' => 'bg-warning text-dark',
                                                        'momo' => 'bg-info',
                                                        'carte_bancaire' => 'bg-primary',
                                                        'virement' => 'bg-success',
                                                        default => 'bg-secondary'
                                                    };
                                                    $modePaiementText = match($reservation['mode_paiement'] ?? 'especes') {
                                                        'especes' => 'Espèces',
                                                        'orange_money' => 'Orange Money',
                                                        'momo' => 'MOMO',
                                                        'carte_bancaire' => 'Carte bancaire',
                                                        'virement' => 'Virement',
                                                        default => 'Espèces'
                                                    };
                                                    ?>
                                                    <span class="badge <?= $modePaiementClass ?>"><?= $modePaiementText ?></span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $typeClass = match($reservation['type_reservation'] ?? 'en_ligne') {
                                                        'en_ligne' => 'bg-success',
                                                        'telephonique' => 'bg-info',
                                                        'presentiel' => 'bg-primary',
                                                        default => 'bg-secondary'
                                                    };
                                                    $typeText = match($reservation['type_reservation'] ?? 'en_ligne') {
                                                        'en_ligne' => 'En ligne',
                                                        'telephonique' => 'Téléphonique',
                                                        'presentiel' => 'Présentiel',
                                                        default => 'En ligne'
                                                    };
                                                    ?>
                                                    <span class="badge <?= $typeClass ?>"><?= $typeText ?></span>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?= date('d/m/Y', strtotime($reservation['date_fin'])) ?></small>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <i class="feather-check" style="font-size: 48px; color: #d29751; opacity: 0.5;"></i>
                                                <p class="text-muted mt-2">Aucune réservation terminée</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onglet Annulées -->
            <div class="tab-pane fade" id="annulee" role="tabpanel">
                <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th class="border-0">Locataire</th>
                                        <th class="border-0">Appartement</th>
                                        <th class="border-0">Période</th>
                                        <th class="border-0">Montant</th>
                                        <th class="border-0">Réduction</th>
                                        <th class="border-0">Paiements</th>
                                        <th class="border-0">Mode paiement</th>
                                        <th class="border-0">Type</th>
                                        <th class="border-0">Motif d'annulation</th>
                                        <th class="border-0">Date annulation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($reservations['annulee'])): ?>
                                        <?php foreach ($reservations['annulee'] as $reservation): ?>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold"><?= esc($reservation['nom']) ?></div>
                                                        <small class="text-muted"><?= esc($reservation['telephone']) ?></small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-medium"><?= esc($reservation['adresse']) ?></div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <small class="text-muted">Du</small> <?= date('d/m/Y', strtotime($reservation['date_debut'])) ?><br>
                                                        <small class="text-muted">Au</small> <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div class="fw-bold text-muted"><?= number_format($reservation['montant_total'], 0, ',', ' ') ?> FCFA</div>
                                                        <small class="text-muted">Total</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold text-primary"><?= number_format($reservation['montant_paye'] ?? 0, 0, ',', ' ') ?> FCFA</div>
                                                        <div class="text-muted small">Restant: <?= number_format($reservation['montant_restant'] ?? $reservation['montant_total'], 0, ',', ' ') ?> FCFA</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php
                                                    $modePaiementClass = match($reservation['mode_paiement'] ?? 'especes') {
                                                        'especes' => 'bg-secondary',
                                                        'orange_money' => 'bg-warning text-dark',
                                                        'momo' => 'bg-info',
                                                        'carte_bancaire' => 'bg-primary',
                                                        'virement' => 'bg-success',
                                                        default => 'bg-secondary'
                                                    };
                                                    $modePaiementText = match($reservation['mode_paiement'] ?? 'especes') {
                                                        'especes' => 'Espèces',
                                                        'orange_money' => 'Orange Money',
                                                        'momo' => 'MOMO',
                                                        'carte_bancaire' => 'Carte bancaire',
                                                        'virement' => 'Virement',
                                                        default => 'Espèces'
                                                    };
                                                    ?>
                                                    <span class="badge <?= $modePaiementClass ?>"><?= $modePaiementText ?></span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $typeClass = match($reservation['type_reservation'] ?? 'en_ligne') {
                                                        'en_ligne' => 'bg-success',
                                                        'telephonique' => 'bg-info',
                                                        'presentiel' => 'bg-primary',
                                                        default => 'bg-secondary'
                                                    };
                                                    $typeText = match($reservation['type_reservation'] ?? 'en_ligne') {
                                                        'en_ligne' => 'En ligne',
                                                        'telephonique' => 'Téléphonique',
                                                        'presentiel' => 'Présentiel',
                                                        default => 'En ligne'
                                                    };
                                                    ?>
                                                    <span class="badge <?= $typeClass ?>"><?= $typeText ?></span>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?= esc($reservation['motif_annulation'] ?? 'Non spécifié') ?></small>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($reservation['updated_at'])) ?></small>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
                                                <i class="feather-x-circle" style="font-size: 48px; color: #d29751; opacity: 0.5;"></i>
                                                <p class="text-muted mt-2">Aucune réservation annulée</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Modal d'annulation -->
<div class="modal fade" id="cancelReservationModal" tabindex="-1" aria-labelledby="cancelReservationModalLabel" aria-hidden="true" style="z-index: 99999 !important;">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%);">
                <h5 class="modal-title text-white" id="cancelReservationModalLabel">
                    <i class="feather-x-circle me-2"></i>Annuler la réservation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="cancelReservationForm">
                <input type="hidden" id="cancelReservationId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="motif_annulation" class="form-label">Motif d'annulation <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="motif_annulation" name="motif_annulation" rows="4" required placeholder="Veuillez indiquer le motif de l'annulation..."></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="feather-x me-2"></i>Confirmer l'annulation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de création de réservation -->
<div class="modal fade" id="createReservationModal" tabindex="-1" aria-labelledby="createReservationModalLabel" aria-hidden="true" style="z-index: 99999 !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%);">
                <h5 class="modal-title text-white" id="createReservationModalLabel">
                    <i class="feather-plus me-2"></i>Créer une réservation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/reservations/create') ?>" method="POST" id="createReservationForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <!-- Type de client -->
                    <div class="mb-4">
                        <label class="form-label">Type de client <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="client_type" id="client_existant" value="existant" checked>
                                    <label class="form-check-label" for="client_existant">
                                        Client existant
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="client_type" id="client_nouveau" value="nouveau">
                                    <label class="form-check-label" for="client_nouveau">
                                        Nouveau client
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Client existant -->
                    <div id="client-existant-section">
                        <div class="mb-3">
                            <label for="locataire_id" class="form-label">Rechercher un locataire <span class="text-danger">*</span></label>
                            <select class="form-select select2-ajax" id="locataire_id" name="locataire_id" style="width: 100%;">
                                <option value="">Tapez pour rechercher un client...</option>
                            </select>
                            <small class="form-text text-muted">Tapez le nom, l'email ou le téléphone du client</small>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Section Nouveau client -->
                    <div id="client-nouveau-section" style="display: none;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="client_nom" class="form-label">Nom complet <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="client_nom" name="client_nom" placeholder="Jean Dupont">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="client_email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="client_email" name="client_email" placeholder="jean@example.com">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="client_telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="client_telephone" name="client_telephone" placeholder="+225 XX XX XX XX">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="appartement_id" class="form-label">Appartement <span class="text-danger">*</span></label>
                                <select class="form-select" id="appartement_id" name="appartement_id" required>
                                    <option value="">Sélectionner un appartement</option>
                                    <?php 
                                    $appartementModel = new \App\Models\AppartementModel();
                                    $appartements = $appartementModel->where('statut', 'disponible')->findAll();
                                    foreach ($appartements as $appartement): 
                                    ?>
                                        <option value="<?= $appartement['id'] ?>"><?= esc($appartement['adresse']) ?> - <?= number_format($appartement['tarifs'], 0, ',', ' ') ?> FCFA/nuit</option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="daterange" class="form-label">
                                    <i class="feather-calendar me-1"></i>
                                    Période de réservation <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="feather-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control" id="daterange" name="daterange" required
                                           placeholder="Cliquez pour sélectionner les dates"
                                           style="background-color: white; cursor: pointer;"
                                           autocomplete="off">
                                </div>
                                <input type="hidden" id="date_debut" name="date_debut" required>
                                <input type="hidden" id="date_fin" name="date_fin" required>
                                <small class="form-text text-muted">
                                    <i class="feather-info"></i> Cliquez sur le champ pour ouvrir le calendrier
                                </small>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Section Calcul automatique du prix -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <h6 class="mb-3"><i class="feather-calculator me-2"></i>Calcul automatique du prix</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reduction_pourcentage" class="form-label">Réduction (%)</label>
                                    <input type="number" class="form-control" id="reduction_pourcentage" name="reduction_pourcentage" min="0" max="100" step="0.01" placeholder="0" value="0">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <button type="button" class="btn btn-outline-primary mt-4" id="calculatePriceBtn">
                                        <i class="feather-calculator me-2"></i>Calculer le prix
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Affichage du calcul -->
                        <div id="price-calculation-result" style="display: none;">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center p-2 bg-white rounded">
                                        <small class="text-muted">Prix original</small>
                                        <div class="fw-bold text-primary" id="prix-original">0 FCFA</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-2 bg-white rounded">
                                        <small class="text-muted">Réduction</small>
                                        <div class="fw-bold text-danger" id="montant-reduction">0 FCFA</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-2 bg-white rounded">
                                        <small class="text-muted">Prix final</small>
                                        <div class="fw-bold text-success" id="prix-final">0 FCFA</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="montant_paye" class="form-label">Montant payé (FCFA)</label>
                                <input type="number" class="form-control" id="montant_paye" name="montant_paye" min="0" step="0.01" placeholder="0" value="0">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mode_paiement" class="form-label">Mode de paiement <span class="text-danger">*</span></label>
                                <select class="form-select" id="mode_paiement" name="mode_paiement" required>
                                    <option value="">Sélectionner le mode</option>
                                    <option value="especes">Espèces</option>
                                    <option value="orange_money">Orange Money</option>
                                    <option value="momo">Mobile Money (MOMO)</option>
                                    <option value="carte_bancaire">Carte bancaire</option>
                                    <option value="virement">Virement bancaire</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type_reservation" class="form-label">Type de réservation <span class="text-danger">*</span></label>
                                <select class="form-select" id="type_reservation" name="type_reservation" required>
                                    <option value="">Sélectionner le type</option>
                                    <option value="en_ligne">En ligne</option>
                                    <option value="telephonique">Téléphonique</option>
                                    <option value="presentiel">Présentiel (à l'accueil)</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Notes supplémentaires..."></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn text-white" style="background: #d29751;">
                        <i class="feather-save me-2"></i>Créer la réservation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'ajout de paiement -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true" style="z-index: 99999 !important;">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%);">
                <h5 class="modal-title text-white" id="addPaymentModalLabel">
                    <i class="feather-credit-card me-2"></i>Ajouter un paiement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addPaymentForm">
                <input type="hidden" id="paymentReservationId" name="reservation_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="payment_montant" class="form-label">Montant (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="payment_montant" name="montant" required min="0" step="0.01" placeholder="50000">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="payment_date" class="form-label">Date du paiement <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="payment_date" name="date" required value="<?= date('Y-m-d') ?>">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="payment_mode" class="form-label">Mode de paiement <span class="text-danger">*</span></label>
                        <select class="form-select" id="payment_mode" name="mode_paiement" required>
                            <option value="">Sélectionner le mode</option>
                            <option value="especes">Espèces</option>
                            <option value="orange_money">Orange Money</option>
                            <option value="momo">Mobile Money (MOMO)</option>
                            <option value="carte_bancaire">Carte bancaire</option>
                            <option value="virement">Virement bancaire</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="alert alert-info">
                        <i class="feather-info me-2"></i>
                        <span id="paymentInfo">Montant restant: <strong>0 FCFA</strong></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn text-white" style="background: #d29751;">
                        <i class="feather-save me-2"></i>Enregistrer le paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de visualisation des paiements -->
<div class="modal fade" id="viewPaymentsModal" tabindex="-1" aria-labelledby="viewPaymentsModalLabel" aria-hidden="true" style="z-index: 99999 !important;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 10px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%);">
                <h5 class="modal-title text-white" id="viewPaymentsModalLabel">
                    <i class="feather-eye me-2"></i>Historique des paiements
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="paymentsList">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<style>
.nav-tabs .nav-link {
    border: none;
    border-radius: 10px 10px 0 0;
    color: #6b7885;
    font-weight: 600;
    padding: 12px 20px;
    margin-right: 5px;
}

.nav-tabs .nav-link.active {
    background: #d29751;
    color: white;
}

.nav-tabs .nav-link:hover {
    background: rgba(210, 151, 81, 0.1);
    color: #d29751;
}

.table tbody tr:hover {
    background-color: rgba(210, 151, 81, 0.05);
}

.btn:hover {
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

/* Daterangepicker fixes */
.daterangepicker {
    z-index: 99999 !important;
    font-family: inherit;
}

.daterangepicker .ranges li.active {
    background-color: #d29751 !important;
}

.daterangepicker td.active,
.daterangepicker td.active:hover {
    background-color: #d29751 !important;
}

.daterangepicker .btn-primary {
    background-color: #d29751 !important;
    border-color: #d29751 !important;
}

.daterangepicker .btn-primary:hover {
    background-color: #b8834a !important;
    border-color: #b8834a !important;
}

#daterange {
    cursor: pointer !important;
}

#daterange:focus {
    border-color: #d29751 !important;
    box-shadow: 0 0 0 0.2rem rgba(210, 151, 81, 0.25) !important;
}

/* Select2 dans modal fix */
.select2-container .select2-dropdown,
.select2-container--bootstrap-5 .select2-dropdown {
    z-index: 99999 !important;
}

.select2-container .select2-selection--single,
.select2-container--bootstrap-5 .select2-selection--single {
    height: 38px !important;
    display: flex !important;
    align-items: center !important;
}

.select2-container .select2-selection__rendered,
.select2-container--bootstrap-5 .select2-selection__rendered {
    padding-left: 12px !important;
    padding-right: 40px !important;
    line-height: 36px !important;
    color: #212529 !important;
    font-weight: 500 !important;
}

.select2-container .select2-selection__placeholder,
.select2-container--bootstrap-5 .select2-selection__placeholder {
    color: #6c757d !important;
    font-weight: 400 !important;
}

.select2-container .select2-search__field,
.select2-container--bootstrap-5 .select2-search__field {
    outline: none !important;
    height: 36px !important;
}

.select2-container.select2-container--focus .select2-selection,
.select2-container.select2-container--open .select2-selection,
.select2-container--bootstrap-5.select2-container--focus .select2-selection,
.select2-container--bootstrap-5.select2-container--open .select2-selection {
    border-color: #d29751 !important;
    box-shadow: 0 0 0 0.2rem rgba(210, 151, 81, 0.25) !important;
}

/* Améliorer l'affichage des résultats */
.select2-result-locataire__title {
    font-weight: 600;
    color: #212529;
    margin-bottom: 4px;
}

.select2-result-locataire__description {
    color: #6c757d;
    font-size: 0.875rem;
}

/* S'assurer que le texte sélectionné est visible */
.select2-selection__rendered {
    width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Modal fixes are handled in modal-fix.css */
</style>

<script>
$(document).ready(function() {
    // Initialiser les tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Fix pour permettre le focus dans les Select2 à l'intérieur des modals Bootstrap 5
    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });

    // Empêcher le modal de bloquer le focus sur Select2
    $('#createReservationModal').on('shown.bs.modal', function() {
        // Réinitialiser Select2 si nécessaire
        if ($('#locataire_id').hasClass('select2-hidden-accessible')) {
            $('#locataire_id').select2('destroy');
        }

        // Initialiser à nouveau Select2 quand le modal est ouvert
        initSelect2ForLocataires();
    });

    // Fonction d'initialisation de Select2 pour les locataires
    function initSelect2ForLocataires() {
    $('#locataire_id').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#createReservationModal'),
        placeholder: 'Tapez pour rechercher un client...',
        allowClear: true,
        minimumInputLength: 2,
        language: {
            inputTooShort: function() {
                return "Tapez au moins 2 caractères pour rechercher...";
            },
            searching: function() {
                return "Recherche en cours...";
            },
            noResults: function() {
                return "Aucun client trouvé";
            }
        },
        ajax: {
            url: '<?= base_url('admin/locataires/search') ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    term: params.term, // Terme de recherche
                    page: params.page || 1
                };
            },
            processResults: function (data) {
                return {
                    results: data.results
                };
            },
            cache: true
        },
        templateResult: formatLocataireResult,
        templateSelection: formatLocataireSelection
    });
    }

    // Initialiser Select2 au chargement de la page
    initSelect2ForLocataires();

    // Gérer l'événement de sélection pour afficher les infos dans la console (debug)
    $('#locataire_id').on('select2:select', function(e) {
        var data = e.params.data;
        console.log('Client sélectionné:', data);

        // S'assurer que le champ affiche bien le nom
        if (data && data.nom) {
            // Le formatter devrait gérer ça automatiquement
            console.log('Affichage:', data.nom + ' (' + data.telephone + ')');
        }
    });

    // Formatter pour les résultats de recherche
    function formatLocataireResult(locataire) {
        if (locataire.loading) {
            return locataire.text;
        }

        var $container = $(
            "<div class='select2-result-locataire clearfix'>" +
                "<div class='select2-result-locataire__meta'>" +
                    "<div class='select2-result-locataire__title'></div>" +
                    "<div class='select2-result-locataire__description'></div>" +
                "</div>" +
            "</div>"
        );

        $container.find(".select2-result-locataire__title").text(locataire.nom || locataire.text);
        $container.find(".select2-result-locataire__description").html(
            '<small>' + (locataire.email || '') + ' • ' + (locataire.telephone || '') + '</small>'
        );

        return $container;
    }

    // Formatter pour la sélection
    function formatLocataireSelection(locataire) {
        // Si c'est le placeholder
        if (!locataire.id) {
            return locataire.text;
        }

        // Afficher le nom du client sélectionné avec ses coordonnées
        if (locataire.nom) {
            return locataire.nom + ' (' + (locataire.telephone || '') + ')';
        }

        // Fallback sur le texte par défaut
        return locataire.text || 'Client sélectionné';
    }

    // Vérifier que daterangepicker est disponible
    if (typeof $.fn.daterangepicker === 'undefined') {
        console.error('Daterangepicker plugin not loaded!');
        showToast('error', 'Le calendrier n\'est pas chargé correctement. Veuillez recharger la page.');
        return;
    }

    console.log('Initializing daterangepicker...');

    // Configurer moment.js en français
    if (typeof moment !== 'undefined') {
        moment.locale('fr', {
            months: 'janvier_février_mars_avril_mai_juin_juillet_août_septembre_octobre_novembre_décembre'.split('_'),
            monthsShort: 'janv._févr._mars_avr._mai_juin_juil._août_sept._oct._nov._déc.'.split('_'),
            weekdays: 'dimanche_lundi_mardi_mercredi_jeudi_vendredi_samedi'.split('_'),
            weekdaysShort: 'dim._lun._mar._mer._jeu._ven._sam.'.split('_'),
            weekdaysMin: 'Di_Lu_Ma_Me_Je_Ve_Sa'.split('_'),
            longDateFormat: {
                LT: 'HH:mm',
                LTS: 'HH:mm:ss',
                L: 'DD/MM/YYYY',
                LL: 'D MMMM YYYY',
                LLL: 'D MMMM YYYY HH:mm',
                LLLL: 'dddd D MMMM YYYY HH:mm'
            },
            week: {
                dow: 1,
                doy: 4
            }
        });
    }

    // Initialiser le daterangepicker pour les dates de réservation
    try {
        $('#daterange').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'Appliquer',
                cancelLabel: 'Annuler',
                fromLabel: 'De',
                toLabel: 'À',
                customRangeLabel: 'Personnalisé',
                weekLabel: 'S',
                daysOfWeek: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
                firstDay: 1
            },
            // Pas de restriction de date pour permettre l'enregistrement de données historiques
            autoApply: false,
            showDropdowns: true,
            autoUpdateInput: false,
            opens: 'center',
            drops: 'auto',
            buttonClasses: 'btn btn-sm',
            applyButtonClasses: 'btn-primary',
            cancelClass: 'btn-secondary'
        });

        console.log('Daterangepicker initialized successfully');

        // Forcer l'ouverture au clic (au cas où readonly causerait un problème)
        $('#daterange, .input-group-text').on('click', function(e) {
            e.preventDefault();
            console.log('Daterange clicked, opening calendar...');
            $('#daterange').data('daterangepicker').show();
        });

        // Empêcher la saisie manuelle
        $('#daterange').on('keydown', function(e) {
            e.preventDefault();
            return false;
        });

    } catch (error) {
        console.error('Error initializing daterangepicker:', error);
        showToast('error', 'Erreur lors de l\'initialisation du calendrier');
    }

    // Mettre à jour les champs quand une plage de dates est sélectionnée
    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
        console.log('Date range applied:', picker.startDate.format('DD/MM/YYYY'), '-', picker.endDate.format('DD/MM/YYYY'));
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
        var displayDate = picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY');

        $(this).val(displayDate);
        $('#date_debut').val(startDate);
        $('#date_fin').val(endDate);

        // Calculer le nombre de jours
        var days = picker.endDate.diff(picker.startDate, 'days') + 1;

        // Afficher un indicateur visuel
        if (days > 0) {
            $(this).css('border-color', '#28a745');
        }
    });

    // Réinitialiser si l'utilisateur annule
    $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $('#date_debut').val('');
        $('#date_fin').val('');
        $(this).css('border-color', '');
    });

    // Modal fixes are now handled globally in main layout

    // Gérer la confirmation de réservation
    $(document).on('click', '.confirm-reservation', function() {
        const reservationId = $(this).data('reservation-id');
        
        if (confirm('Êtes-vous sûr de vouloir confirmer cette réservation ?')) {
            $.ajax({
                url: `<?= base_url("/admin/reservations/confirmer") ?>/${reservationId}`,
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        showToast('success', response.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function() {
                    showToast('error', 'Erreur lors de la confirmation de la réservation.');
                }
            });
        }
    });

    // Gérer l'annulation de réservation
    $(document).on('click', '.cancel-reservation', function() {
        const reservationId = $(this).data('reservation-id');
        $('#cancelReservationId').val(reservationId);
        $('#cancelReservationModal').modal('show');
    });

    // Gérer le formulaire d'annulation
    $('#cancelReservationForm').on('submit', function(e) {
        e.preventDefault();
        
        const reservationId = $('#cancelReservationId').val();
        const formData = new FormData(this);
        
        $.ajax({
            url: `<?= base_url("/admin/reservations/annuler") ?>/${reservationId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#cancelReservationModal').modal('hide');
                    showToast('success', response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showToast('error', response.message);
                }
            },
            error: function() {
                showToast('error', 'Une erreur est survenue lors de l\'annulation.');
            }
        });
    });

    // Reset form on modal hide
    $('#cancelReservationModal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').text('');
    });

    // Gérer l'ajout de paiement
    $(document).on('click', '.add-payment', function() {
        const reservationId = $(this).data('reservation-id');
        $('#paymentReservationId').val(reservationId);
        
        // Charger les informations de la réservation pour afficher le montant restant
        $.ajax({
            url: `<?= base_url("/admin/reservations/get") ?>/${reservationId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const reservation = response.reservation;
                    const montantRestant = reservation.montant_restant || reservation.montant_total;
                    $('#paymentInfo').html(`Montant restant: <strong>${new Intl.NumberFormat('fr-FR').format(montantRestant)} FCFA</strong>`);
                    $('#payment_montant').attr('max', montantRestant);
                }
            }
        });
        
        $('#addPaymentModal').modal('show');
    });

    // Gérer la visualisation des paiements
    $(document).on('click', '.view-payments', function() {
        const reservationId = $(this).data('reservation-id');
        
        $.ajax({
            url: `<?= base_url("/admin/reservations/payments") ?>/${reservationId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    let html = '<div class="table-responsive"><table class="table table-hover">';
                    html += '<thead><tr><th>Date</th><th>Montant</th><th>Statut</th><th class="text-center">Actions</th></tr></thead><tbody>';

                    if (response.paiements && response.paiements.length > 0) {
                        response.paiements.forEach(function(paiement) {
                            const statutClass = paiement.statut === 'paye' ? 'bg-success' : 'bg-warning';
                            html += `<tr>
                                <td>${new Date(paiement.date).toLocaleDateString('fr-FR')}</td>
                                <td class="fw-bold">${new Intl.NumberFormat('fr-FR').format(paiement.montant)} FCFA</td>
                                <td><span class="badge ${statutClass}">${paiement.statut}</span></td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?= base_url('admin/paiements/generer-facture') ?>/${paiement.id}"
                                           class="btn btn-sm btn-primary"
                                           target="_blank"
                                           data-bs-toggle="tooltip"
                                           title="Imprimer la facture">
                                            <i class="feather-printer"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>`;
                        });
                    } else {
                        html += '<tr><td colspan="4" class="text-center text-muted">Aucun paiement enregistré</td></tr>';
                    }

                    html += '</tbody></table></div>';
                    $('#paymentsList').html(html);

                    // Réinitialiser les tooltips
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                } else {
                    $('#paymentsList').html('<div class="alert alert-danger">Erreur lors du chargement des paiements.</div>');
                }
            },
            error: function() {
                $('#paymentsList').html('<div class="alert alert-danger">Erreur lors du chargement des paiements.</div>');
            }
        });
        
        $('#viewPaymentsModal').modal('show');
    });

    // Gérer le formulaire d'ajout de paiement
    $('#addPaymentForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const reservationId = $('#paymentReservationId').val();
        
        $.ajax({
            url: `<?= base_url("/admin/reservations/add-payment") ?>/${reservationId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#addPaymentModal').modal('hide');
                    showToast('success', response.message);

                    // Ouvrir automatiquement la facture pour impression
                    if (response.paiement_id) {
                        const factureUrl = `<?= base_url('admin/paiements/generer-facture') ?>/${response.paiement_id}`;
                        window.open(factureUrl, '_blank');
                    }

                    // Recharger la page après un court délai
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    if (response.errors) {
                        displayFormErrors('#addPaymentForm', response.errors);
                    } else {
                        showToast('error', response.message);
                    }
                }
            },
            error: function() {
                showToast('error', 'Erreur lors de l\'enregistrement du paiement.');
            }
        });
    });

    // Gérer le changement de type de client
    $('input[name="client_type"]').on('change', function() {
        const clientType = $(this).val();
        
        if (clientType === 'nouveau') {
            $('#client-existant-section').hide();
            $('#client-nouveau-section').show();
            $('#locataire_id').prop('required', false);
            $('#client_nom, #client_email, #client_telephone').prop('required', true);
        } else {
            $('#client-existant-section').show();
            $('#client-nouveau-section').hide();
            $('#locataire_id').prop('required', true);
            $('#client_nom, #client_email, #client_telephone').prop('required', false);
        }
    });

    // Gérer le calcul automatique du prix
    $('#calculatePriceBtn').on('click', function() {
        const appartementId = $('#appartement_id').val();
        const dateDebut = $('#date_debut').val();
        const dateFin = $('#date_fin').val();
        const reductionPourcentage = $('#reduction_pourcentage').val() || 0;

        if (!appartementId || !dateDebut || !dateFin) {
            showToast('error', 'Veuillez sélectionner un appartement et les dates.');
            return;
        }

        $.ajax({
            url: '<?= base_url("/admin/reservations/calculate-price") ?>',
            type: 'POST',
            data: {
                appartement_id: appartementId,
                date_debut: dateDebut,
                date_fin: dateFin,
                reduction_pourcentage: reductionPourcentage
            },
            success: function(response) {
                if (response.success) {
                    const prix = response.prix;
                    $('#prix-original').text(new Intl.NumberFormat('fr-FR').format(prix.prix_original) + ' FCFA');
                    $('#montant-reduction').text(new Intl.NumberFormat('fr-FR').format(prix.montant_reduction) + ' FCFA');
                    $('#prix-final').text(new Intl.NumberFormat('fr-FR').format(prix.montant_total) + ' FCFA');
                    $('#price-calculation-result').show();
                } else {
                    showToast('error', response.message);
                }
            },
            error: function() {
                showToast('error', 'Erreur lors du calcul du prix.');
            }
        });
    });

    // Gérer le formulaire de création de réservation
    $('#createReservationForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '<?= base_url("admin/reservations/create") ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#createReservationModal').modal('hide');
                    showToast('success', response.message);

                    // Ouvrir automatiquement la facture si un paiement a été effectué
                    if (response.paiement_id) {
                        const factureUrl = `<?= base_url('admin/paiements/generer-facture') ?>/${response.paiement_id}`;
                        window.open(factureUrl, '_blank');
                    }

                    // Recharger la page après un court délai
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    if (response.errors) {
                        displayFormErrors('#createReservationForm', response.errors);
                    } else {
                        showToast('error', response.message);
                    }
                }
            },
            error: function() {
                showToast('error', 'Erreur lors de la création de la réservation.');
            }
        });
    });

    // Fonction pour afficher les erreurs de formulaire
    function displayFormErrors(formSelector, errors) {
        // Reset previous errors
        $(formSelector + ' .is-invalid').removeClass('is-invalid');
        $(formSelector + ' .invalid-feedback').text('');
        
        // Display new errors
        for (const field in errors) {
            const input = $(formSelector + ` [name="${field}"]`);
            if (input.length) {
                input.addClass('is-invalid');
                input.siblings('.invalid-feedback').text(errors[field]);
            }
        }
    }
});

// Fonction pour afficher les toasts
function showToast(type, message) {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="feather-${type === 'success' ? 'check-circle' : 'alert-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    // Create toast container if it doesn't exist
    if (!$('#toast-container').length) {
        $('body').append('<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>');
    }
    
    const $toast = $(toastHtml);
    $('#toast-container').append($toast);
    
    const toast = new bootstrap.Toast($toast[0]);
    toast.show();
    
    // Remove toast element after it's hidden
    $toast.on('hidden.bs.toast', function() {
        $(this).remove();
    });
}
</script>

<?= $this->endSection() ?>