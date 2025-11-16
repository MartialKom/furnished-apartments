<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="feather-droplet me-2"></i>
                Gestion des Factures d'Eau
            </h5>
            <div>
                <button type="button" class="btn btn-info me-2" id="btnGenererFactures">
                    <i class="feather-calendar me-2"></i>Générer factures du mois
                </button>
                <button type="button" class="btn text-white" style="background: #d29751;" data-bs-toggle="modal" data-bs-target="#createFactureModal">
                    <i class="feather-plus me-2"></i>Créer une facture
                </button>
            </div>
        </div>

        <!-- Onglets -->
        <ul class="nav nav-tabs mb-4" id="factureTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="attente-tab" data-bs-toggle="tab" data-bs-target="#attente" type="button" role="tab">
                    <i class="feather-clock me-2"></i>En attente
                    <span class="badge bg-warning text-dark ms-2"><?= count($factures['en_attente']) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="retard-tab" data-bs-toggle="tab" data-bs-target="#retard" type="button" role="tab">
                    <i class="feather-alert-circle me-2"></i>En retard
                    <span class="badge bg-danger ms-2"><?= count($factures['en_retard']) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="partiel-tab" data-bs-toggle="tab" data-bs-target="#partiel" type="button" role="tab">
                    <i class="feather-minus-circle me-2"></i>Partiellement payé
                    <span class="badge bg-info ms-2"><?= count($factures['partiellement_paye']) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="paye-tab" data-bs-toggle="tab" data-bs-target="#paye" type="button" role="tab">
                    <i class="feather-check-circle me-2"></i>Payées
                    <span class="badge bg-success ms-2"><?= count($factures['paye']) ?></span>
                </button>
            </li>
        </ul>

        <!-- Contenu des onglets -->
        <div class="tab-content" id="factureTabsContent">

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
                                        <th class="border-0">Consommation</th>
                                        <th class="border-0">Montant</th>
                                        <th class="border-0">Échéance</th>
                                        <th class="border-0 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($factures['en_attente'])): ?>
                                        <?php foreach ($factures['en_attente'] as $facture): ?>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold"><?= esc($facture['locataire_nom']) ?></div>
                                                        <small class="text-muted"><?= esc($facture['locataire_telephone']) ?></small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-medium"><?= esc($facture['appartement_adresse']) ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary"><?= date('m/Y', strtotime($facture['mois_annee'] . '-01')) ?></span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($facture['consommation_m3'])): ?>
                                                        <div>
                                                            <span class="fw-bold"><?= number_format($facture['consommation_m3'], 2) ?> m³</span>
                                                            <?php if (!empty($facture['index_precedent']) && !empty($facture['index_actuel'])): ?>
                                                                <div class="small text-muted">
                                                                    <?= number_format($facture['index_precedent'], 2) ?> → <?= number_format($facture['index_actuel'], 2) ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-primary"><?= number_format($facture['montant'], 0, ',', ' ') ?> FCFA</div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <?= date('d/m/Y', strtotime($facture['date_echeance'])) ?>
                                                        <?php
                                                        $today = new DateTime();
                                                        $echeance = new DateTime($facture['date_echeance']);
                                                        $diff = $today->diff($echeance);
                                                        if ($echeance > $today):
                                                        ?>
                                                            <div class="small text-success">Dans <?= $diff->days ?> jour(s)</div>
                                                        <?php else: ?>
                                                            <div class="small text-warning">Proche</div>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-success me-1" onclick="marquerPayeFacture(<?= $facture['id'] ?>)" title="Marquer comme payé">
                                                        <i class="feather-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary me-1" onclick="voirFacture(<?= $facture['id'] ?>)" title="Voir détails">
                                                        <i class="feather-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-warning me-1" onclick="modifierFacture(<?= $facture['id'] ?>)" title="Modifier">
                                                        <i class="feather-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="supprimerFacture(<?= $facture['id'] ?>)" title="Supprimer">
                                                        <i class="feather-trash-2"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="feather-inbox" style="font-size: 2rem;"></i>
                                                <p class="mt-2">Aucune facture en attente</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onglet En retard -->
            <div class="tab-pane fade" id="retard" role="tabpanel">
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
                                        <th class="border-0">Échéance</th>
                                        <th class="border-0">Retard</th>
                                        <th class="border-0 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($factures['en_retard'])): ?>
                                        <?php foreach ($factures['en_retard'] as $facture): ?>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold"><?= esc($facture['locataire_nom']) ?></div>
                                                        <small class="text-muted"><?= esc($facture['locataire_telephone']) ?></small>
                                                    </div>
                                                </td>
                                                <td><?= esc($facture['appartement_adresse']) ?></td>
                                                <td>
                                                    <span class="badge bg-secondary"><?= date('m/Y', strtotime($facture['mois_annee'] . '-01')) ?></span>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-danger"><?= number_format($facture['montant'], 0, ',', ' ') ?> FCFA</div>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($facture['date_echeance'])) ?></td>
                                                <td>
                                                    <?php
                                                    $today = new DateTime();
                                                    $echeance = new DateTime($facture['date_echeance']);
                                                    $diff = $today->diff($echeance);
                                                    ?>
                                                    <span class="badge bg-danger"><?= $diff->days ?> jour(s)</span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-success me-1" onclick="marquerPayeFacture(<?= $facture['id'] ?>)">
                                                        <i class="feather-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary me-1" onclick="voirFacture(<?= $facture['id'] ?>)">
                                                        <i class="feather-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-warning" onclick="envoyerRelance(<?= $facture['id'] ?>)">
                                                        <i class="feather-mail"></i> Relancer
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="feather-check-circle" style="font-size: 2rem; color: #28a745;"></i>
                                                <p class="mt-2">Aucune facture en retard</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onglet Partiellement payé -->
            <div class="tab-pane fade" id="partiel" role="tabpanel">
                <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th class="border-0">Locataire</th>
                                        <th class="border-0">Appartement</th>
                                        <th class="border-0">Période</th>
                                        <th class="border-0">Montant total</th>
                                        <th class="border-0">Payé</th>
                                        <th class="border-0">Restant</th>
                                        <th class="border-0 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($factures['partiellement_paye'])): ?>
                                        <?php foreach ($factures['partiellement_paye'] as $facture): ?>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold"><?= esc($facture['locataire_nom']) ?></div>
                                                        <small class="text-muted"><?= esc($facture['locataire_telephone']) ?></small>
                                                    </div>
                                                </td>
                                                <td><?= esc($facture['appartement_adresse']) ?></td>
                                                <td>
                                                    <span class="badge bg-secondary"><?= date('m/Y', strtotime($facture['mois_annee'] . '-01')) ?></span>
                                                </td>
                                                <td><?= number_format($facture['montant'], 0, ',', ' ') ?> FCFA</td>
                                                <td class="text-success fw-semibold"><?= number_format($facture['montant_paye'], 0, ',', ' ') ?> FCFA</td>
                                                <td class="text-danger fw-semibold"><?= number_format($facture['montant'] - $facture['montant_paye'], 0, ',', ' ') ?> FCFA</td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-success me-1" onclick="marquerPayeFacture(<?= $facture['id'] ?>)">
                                                        <i class="feather-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-primary" onclick="voirFacture(<?= $facture['id'] ?>)">
                                                        <i class="feather-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="feather-inbox" style="font-size: 2rem;"></i>
                                                <p class="mt-2">Aucune facture partiellement payée</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onglet Payées -->
            <div class="tab-pane fade" id="paye" role="tabpanel">
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
                                        <th class="border-0">Date paiement</th>
                                        <th class="border-0">Mode</th>
                                        <th class="border-0 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($factures['paye'])): ?>
                                        <?php foreach ($factures['paye'] as $facture): ?>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold"><?= esc($facture['locataire_nom']) ?></div>
                                                        <small class="text-muted"><?= esc($facture['locataire_telephone']) ?></small>
                                                    </div>
                                                </td>
                                                <td><?= esc($facture['appartement_adresse']) ?></td>
                                                <td>
                                                    <span class="badge bg-secondary"><?= date('m/Y', strtotime($facture['mois_annee'] . '-01')) ?></span>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-success"><?= number_format($facture['montant'], 0, ',', ' ') ?> FCFA</div>
                                                </td>
                                                <td><?= !empty($facture['date_paiement']) ? date('d/m/Y', strtotime($facture['date_paiement'])) : '-' ?></td>
                                                <td>
                                                    <?php if (!empty($facture['mode_paiement'])): ?>
                                                        <?php
                                                        $modes = [
                                                            'especes' => '<span class="badge bg-success">Espèces</span>',
                                                            'virement' => '<span class="badge bg-primary">Virement</span>',
                                                            'cheque' => '<span class="badge bg-info">Chèque</span>',
                                                            'mobile_money' => '<span class="badge bg-warning text-dark">Mobile Money</span>'
                                                        ];
                                                        echo $modes[$facture['mode_paiement']] ?? '<span class="badge bg-secondary">Autre</span>';
                                                        ?>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-primary" onclick="voirFacture(<?= $facture['id'] ?>)">
                                                        <i class="feather-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="feather-inbox" style="font-size: 2rem;"></i>
                                                <p class="mt-2">Aucune facture payée</p>
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

<!-- Modal Créer Facture -->
<?= $this->include('admin/pages/factures_eau/modals/create') ?>

<!-- Modal Marquer Payé -->
<?= $this->include('admin/pages/factures_eau/modals/marquer_paye') ?>

<!-- Modal Voir Détails -->
<?= $this->include('admin/pages/factures_eau/modals/details') ?>

<script>
// Générer les factures du mois
document.getElementById('btnGenererFactures').addEventListener('click', function() {
    if (confirm('Voulez-vous générer les factures d\'eau pour le mois en cours pour tous les contrats actifs ?')) {
        fetch('<?= base_url('admin/factures-eau/generer-mois') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la génération des factures');
        });
    }
});

// Marquer comme payé
function marquerPayeFacture(id) {
    // Afficher le modal de paiement
    $('#marquerPayeModal').modal('show');
    $('#marquerPayeForm').data('facture-id', id);
}

// Voir détails
function voirFacture(id) {
    fetch(`<?= base_url('admin/factures-eau/get') ?>/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Afficher le modal avec les détails
                const facture = data.facture;
                $('#detailsModal').modal('show');
                // Remplir les détails...
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la récupération des détails');
        });
}

// Modifier
function modifierFacture(id) {
    // TODO: Implémenter la modification
    alert('Fonctionnalité en cours de développement');
}

// Supprimer
function supprimerFacture(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette facture ?')) {
        fetch(`<?= base_url('admin/factures-eau/delete') ?>/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        });
    }
}

// Envoyer relance
function envoyerRelance(id) {
    // TODO: Implémenter l'envoi de relance par email
    alert('Relance envoyée par email (fonctionnalité à implémenter)');
}
</script>

<?= $this->endSection() ?>
