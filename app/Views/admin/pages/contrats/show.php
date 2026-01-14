<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails du Contrat #<?= $contrat['id'] ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/contrats') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                        <a href="<?= base_url('admin/contrats/edit/' . $contrat['id']) ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Informations du contrat -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Informations du Contrat</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Locataire:</strong></td>
                                            <td><?= $contrat['locataire_nom'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td><?= $contrat['locataire_email'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Téléphone:</strong></td>
                                            <td><?= $contrat['locataire_telephone'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Appartement:</strong></td>
                                            <td><?= $contrat['appartement_adresse'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Loyer mensuel:</strong></td>
                                            <td>
                                                <span class="fw-bold text-success fs-5">
                                                    <?= number_format($contrat['loyer_mensuel'], 0, ',', ' ') ?> FCFA
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Jour de paiement:</strong></td>
                                            <td><?= $contrat['jour_paiement'] ?> de chaque mois</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Caution:</strong></td>
                                            <td><?= number_format($contrat['caution'], 0, ',', ' ') ?> FCFA</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date de début:</strong></td>
                                            <td><?= date('d/m/Y', strtotime($contrat['date_debut'])) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date de fin:</strong></td>
                                            <td><?= $contrat['date_fin'] ? date('d/m/Y', strtotime($contrat['date_fin'])) : 'Indéterminé' ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Statut:</strong></td>
                                            <td>
                                                <?php
                                                $statutClass = match($contrat['statut']) {
                                                    'actif' => 'bg-success',
                                                    'suspendu' => 'bg-warning text-dark',
                                                    'termine' => 'bg-secondary',
                                                    default => 'bg-secondary'
                                                };
                                                $statutText = match($contrat['statut']) {
                                                    'actif' => 'Actif',
                                                    'suspendu' => 'Suspendu',
                                                    'termine' => 'Terminé',
                                                    default => ucfirst($contrat['statut'])
                                                };
                                                $statutIcon = match($contrat['statut']) {
                                                    'actif' => 'feather-check-circle',
                                                    'suspendu' => 'feather-pause-circle',
                                                    'termine' => 'feather-x-circle',
                                                    default => 'feather-circle'
                                                };
                                                ?>
                                                <span class="badge <?= $statutClass ?> d-flex align-items-center">
                                                    <i class="<?= $statutIcon ?> me-1" style="font-size: 12px;"></i>
                                                    <?= $statutText ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Résumé des paiements -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Résumé des Paiements</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="info-box">
                                                <span class="info-box-icon">
                                                    <i class="fas fa-money-bill-wave text-info"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Total dû</span>
                                                    <span class="info-box-number"><?= number_format($resume['total_du'], 0, ',', ' ') ?> FCFA</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-box">
                                                <span class="info-box-icon">
                                                    <i class="fas fa-check-circle text-success"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Total payé</span>
                                                    <span class="info-box-number"><?= number_format($resume['total_paye'], 0, ',', ' ') ?> FCFA</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-box">
                                                <span class="info-box-icon">
                                                    <i class="fas fa-clock text-warning"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">En attente</span>
                                                    <span class="info-box-number"><?= $resume['en_attente'] ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-box">
                                                <span class="info-box-icon">
                                                    <i class="fas fa-exclamation-triangle text-danger"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">En retard</span>
                                                    <span class="info-box-number"><?= $resume['en_retard'] ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php if ($resume['montant_restant'] > 0): ?>
                                        <div class="alert alert-warning mt-3">
                                            <strong>Montant restant:</strong> <?= number_format($resume['montant_restant'], 0, ',', ' ') ?> FCFA
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($contrat['conditions_particulieres'])): ?>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Conditions Particulières</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><?= nl2br($contrat['conditions_particulieres']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Historique des paiements -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Historique des Paiements</h5>
                                    <div class="card-tools">
                                        <button class="btn btn-primary btn-sm" onclick="enregistrerPaiement(<?= $contrat['id'] ?>)">
                                            <i class="fas fa-plus"></i> Enregistrer un Paiement
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($paiements)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Mois/Année</th>
                                                        <th>Montant dû</th>
                                                        <th>Montant payé</th>
                                                        <th>Date échéance</th>
                                                        <th>Date paiement</th>
                                                        <th>Statut</th>
                                                        <th>Mode</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($paiements as $paiement): ?>
                                                        <tr>
                                                            <td><?= $paiement['mois_annee'] ?></td>
                                                            <td>
                                                                <span class="fw-bold text-primary">
                                                                    <?= number_format($paiement['montant_du'], 0, ',', ' ') ?> FCFA
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="fw-bold <?= $paiement['montant_paye'] > 0 ? 'text-success' : 'text-muted' ?>">
                                                                    <?= number_format($paiement['montant_paye'], 0, ',', ' ') ?> FCFA
                                                                </span>
                                                            </td>
                                                            <td><?= date('d/m/Y', strtotime($paiement['date_echeance'])) ?></td>
                                                            <td><?= $paiement['date_paiement'] ? date('d/m/Y', strtotime($paiement['date_paiement'])) : '-' ?></td>
                                                            <td>
                                                                <?php
                                                                $statutClass = match($paiement['statut']) {
                                                                    'paye' => 'bg-success',
                                                                    'en_retard' => 'bg-danger',
                                                                    'partiellement_paye' => 'bg-warning text-dark',
                                                                    'en_attente' => 'bg-info',
                                                                    default => 'bg-secondary'
                                                                };
                                                                $statutText = match($paiement['statut']) {
                                                                    'paye' => 'Payé',
                                                                    'en_retard' => 'En retard',
                                                                    'partiellement_paye' => 'Partiel',
                                                                    'en_attente' => 'En attente',
                                                                    default => ucfirst(str_replace('_', ' ', $paiement['statut']))
                                                                };
                                                                $statutIcon = match($paiement['statut']) {
                                                                    'paye' => 'feather-check-circle',
                                                                    'en_retard' => 'feather-alert-circle',
                                                                    'partiellement_paye' => 'feather-clock',
                                                                    'en_attente' => 'feather-hourglass',
                                                                    default => 'feather-circle'
                                                                };
                                                                ?>
                                                                <span class="badge <?= $statutClass ?> d-flex align-items-center">
                                                                    <i class="<?= $statutIcon ?> me-1" style="font-size: 12px;"></i>
                                                                    <?= $statutText ?>
                                                                </span>
                                                            </td>
                                                            <td><?= $paiement['mode_paiement'] ? ucfirst($paiement['mode_paiement']) : '-' ?></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <?php if ($paiement['statut'] === 'paye'): ?>
                                                                        <a href="<?= base_url('admin/receipts/generate/' . $paiement['id']) ?>" 
                                                                           class="btn btn-success btn-sm" 
                                                                           target="_blank" 
                                                                           title="Imprimer le reçu">
                                                                            <i class="feather-printer"></i>
                                                                        </a>
                                                                    <?php else: ?>
                                                                        <button class="btn btn-success btn-sm" onclick="enregistrerPaiement(<?= $contrat['id'] ?>, '<?= $paiement['mois_annee'] ?>')" title="Enregistrer paiement">
                                                                            <i class="feather-dollar-sign"></i>
                                                                        </button>
                                                                    <?php endif; ?>
                                                                    <button class="btn btn-info btn-sm" onclick="voirDetailsPaiement(<?= $paiement['id'] ?>)" title="Voir détails">
                                                                        <i class="feather-eye"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info text-center">
                                            <i class="fas fa-info-circle"></i> Aucun paiement enregistré pour ce contrat.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails d'un paiement -->
<div class="modal fade" id="modalDetailsPaiement" tabindex="-1" role="dialog" aria-labelledby="modalDetailsPaiementLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailsPaiementLabel">Détails du Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body" id="detailsPaiementContent">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Chargement des détails...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="imprimerRecu" style="display:none;">
                    <i class="feather-printer me-2"></i>Imprimer le Reçu
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour enregistrer un paiement -->
<div class="modal fade" id="modalEnregistrerPaiement" tabindex="-1" role="dialog" aria-labelledby="modalEnregistrerPaiementLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEnregistrerPaiementLabel">Enregistrer un Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form id="enregistrerPaiementForm">
                    <input type="hidden" id="contrat_id_paiement" name="contrat_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mois_annee_paiement">Mois/Année</label>
                                <input type="text" class="form-control" id="mois_annee_paiement" name="mois_annee" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="montant_paye">Montant payé (FCFA)</label>
                                <input type="number" class="form-control" id="montant_paye" name="montant_paye" 
                                       step="100" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre_mois">Nombre de mois payés</label>
                                <input type="number" class="form-control" id="nombre_mois" name="nombre_mois" 
                                       value="1" min="1" max="12" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mode_paiement">Mode de paiement</label>
                                <select class="form-control" id="mode_paiement" name="mode_paiement">
                                    <option value="">Sélectionner...</option>
                                    <option value="especes">Espèces</option>
                                    <option value="virement">Virement</option>
                                    <option value="cheque">Chèque</option>
                                    <option value="mobile_money">Mobile Money</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reference_paiement">Référence</label>
                                <input type="text" class="form-control" id="reference_paiement" name="reference_paiement" 
                                       placeholder="Numéro de transaction, chèque, etc.">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notes_paiement">Notes</label>
                        <textarea class="form-control" id="notes_paiement" name="notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirmEnregistrerPaiement">
                    <i class="feather-save me-2"></i>Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function enregistrerPaiement(contratId, moisAnnee = null) {
    $('#contrat_id_paiement').val(contratId);
    
    // Réinitialiser le formulaire
    $('#enregistrerPaiementForm')[0].reset();
    $('#contrat_id_paiement').val(contratId);
    
    if (moisAnnee) {
        $('#mois_annee_paiement').val(moisAnnee);
    } else {
        // Utiliser le mois actuel
        const now = new Date();
        const moisActuel = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0');
        $('#mois_annee_paiement').val(moisActuel);
    }
    
    // Récupérer le loyer mensuel depuis les données du contrat
    const loyerMensuel = <?= $contrat['loyer_mensuel'] ?>;
    $('#montant_paye').val(loyerMensuel);
    $('#nombre_mois').val(1);
    
    // Calculer le montant total
    calculerMontantTotal();
    
    $('#modalEnregistrerPaiement').modal('show');
}

function voirDetailsPaiement(paiementId) {
    // Réinitialiser le contenu de la modale
    $('#detailsPaiementContent').html(`
        <div class="text-center">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p>Chargement des détails...</p>
        </div>
    `);
    $('#imprimerRecu').hide();

    // Ouvrir la modale
    $('#modalDetailsPaiement').modal('show');

    // Récupérer les détails du paiement via AJAX
    $.ajax({
        url: '<?= base_url('admin/paiements-mensuels/echeance') ?>/' + paiementId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const paiement = response.echeance;
                const contrat = response.contrat;

                // Déterminer les classes de statut
                let statutClass = 'badge-secondary';
                let statutText = 'Inconnu';
                let statutIcon = 'feather-circle';

                switch(paiement.statut) {
                    case 'paye':
                        statutClass = 'badge-success';
                        statutText = 'Payé';
                        statutIcon = 'feather-check-circle';
                        break;
                    case 'en_retard':
                        statutClass = 'badge-danger';
                        statutText = 'En retard';
                        statutIcon = 'feather-alert-circle';
                        break;
                    case 'partiellement_paye':
                        statutClass = 'badge-warning';
                        statutText = 'Partiellement payé';
                        statutIcon = 'feather-clock';
                        break;
                    case 'en_attente':
                        statutClass = 'badge-info';
                        statutText = 'En attente';
                        statutIcon = 'feather-hourglass';
                        break;
                }

                // Construire le HTML des détails
                let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="feather-info me-2"></i>Informations Générales</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="fw-bold">Mois/Année:</td>
                                            <td>${paiement.mois_annee}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Date d'échéance:</td>
                                            <td>${new Date(paiement.date_echeance).toLocaleDateString('fr-FR')}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Statut:</td>
                                            <td>
                                                <span class="badge ${statutClass} d-flex align-items-center" style="width: fit-content;">
                                                    <i class="${statutIcon} me-1" style="font-size: 12px;"></i>
                                                    ${statutText}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="feather-home me-2"></i>Appartement</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="fw-bold">Adresse:</td>
                                            <td>${contrat.appartement_adresse || 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Loyer mensuel:</td>
                                            <td class="text-primary fw-bold">${parseInt(contrat.loyer_mensuel).toLocaleString()} FCFA</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%);">
                                    <h6 class="mb-0 text-white"><i class="feather-dollar-sign me-2"></i>Détails Financiers</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="fw-bold">Montant dû:</td>
                                            <td class="text-primary fw-bold">${parseInt(paiement.montant_du).toLocaleString()} FCFA</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Montant payé:</td>
                                            <td class="text-success fw-bold">${parseInt(paiement.montant_paye || 0).toLocaleString()} FCFA</td>
                                        </tr>
                                        ${paiement.montant_paye > 0 && paiement.montant_paye < paiement.montant_du ? `
                                        <tr>
                                            <td class="fw-bold">Reste à payer:</td>
                                            <td class="text-danger fw-bold">${(parseInt(paiement.montant_du) - parseInt(paiement.montant_paye)).toLocaleString()} FCFA</td>
                                        </tr>
                                        ` : ''}
                                    </table>
                                </div>
                            </div>

                            ${paiement.statut === 'paye' ? `
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="feather-check me-2"></i>Informations de Paiement</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="fw-bold">Date de paiement:</td>
                                            <td>${paiement.date_paiement ? new Date(paiement.date_paiement).toLocaleDateString('fr-FR') : 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Mode de paiement:</td>
                                            <td>${paiement.mode_paiement ? paiement.mode_paiement.charAt(0).toUpperCase() + paiement.mode_paiement.slice(1) : 'N/A'}</td>
                                        </tr>
                                        ${paiement.reference_paiement ? `
                                        <tr>
                                            <td class="fw-bold">Référence:</td>
                                            <td>${paiement.reference_paiement}</td>
                                        </tr>
                                        ` : ''}
                                    </table>
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    </div>

                    ${paiement.notes ? `
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="feather-file-text me-2"></i>Notes</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">${paiement.notes}</p>
                        </div>
                    </div>
                    ` : ''}
                `;

                $('#detailsPaiementContent').html(html);

                // Afficher le bouton d'impression si le paiement est effectué
                if (paiement.statut === 'paye') {
                    $('#imprimerRecu').show().off('click').on('click', function() {
                        window.open('<?= base_url('admin/receipts/generate') ?>/' + paiementId, '_blank');
                    });
                }
            } else {
                $('#detailsPaiementContent').html(`
                    <div class="alert alert-danger">
                        <i class="feather-x-circle me-2"></i>${response.message}
                    </div>
                `);
            }
        },
        error: function(xhr) {
            let errorMessage = 'Erreur lors du chargement des détails';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            $('#detailsPaiementContent').html(`
                <div class="alert alert-danger">
                    <i class="feather-x-circle me-2"></i>${errorMessage}
                </div>
            `);
        }
    });
}

function calculerMontantTotal() {
    const loyerMensuel = <?= $contrat['loyer_mensuel'] ?>;
    const nombreMois = parseInt($('#nombre_mois').val()) || 1;
    const montantTotal = loyerMensuel * nombreMois;
    
    $('#montant_paye').val(montantTotal);
    
    // Mettre à jour l'affichage
    const $montantInput = $('#montant_paye');
    if (nombreMois > 1) {
        $montantInput.attr('placeholder', `Total pour ${nombreMois} mois: ${montantTotal.toLocaleString()} FCFA`);
        $montantInput.attr('title', `Paiement pour ${nombreMois} mois consécutifs`);
    } else {
        $montantInput.attr('placeholder', 'Montant du loyer mensuel');
        $montantInput.attr('title', 'Paiement pour un mois');
    }
}

$(document).ready(function() {
    // Calcul automatique du montant quand le nombre de mois change
    $('#nombre_mois').on('change input', function() {
        calculerMontantTotal();
    });
    
    // Validation du formulaire avant envoi
    $('#confirmEnregistrerPaiement').click(function() {
        const $btn = $(this);
        const montantPaye = parseFloat($('#montant_paye').val());
        const nombreMois = parseInt($('#nombre_mois').val());
        const loyerMensuel = <?= $contrat['loyer_mensuel'] ?>;
        const montantAttendu = loyerMensuel * nombreMois;
        
        // Validation côté client
        if (!montantPaye || montantPaye <= 0) {
            toastr.error('Veuillez saisir un montant valide');
            return;
        }
        
        if (montantPaye < montantAttendu) {
            toastr.error(`Le montant saisi (${montantPaye.toLocaleString()} FCFA) est insuffisant pour ${nombreMois} mois (${montantAttendu.toLocaleString()} FCFA requis)`);
            return;
        }
        
        // Désactiver le bouton pendant le traitement
        $btn.prop('disabled', true).html('<i class="feather-loader me-2"></i>Enregistrement...');
        
        const formData = $('#enregistrerPaiementForm').serialize();
        
        $.ajax({
            url: '<?= base_url('admin/paiements-mensuels/enregistrer') ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    let message = response.message;
                    
                    // Ajouter des détails si disponibles
                    if (response.details) {
                        message += `\n\nDétails du paiement:\n• Montant: ${response.details.montant.toLocaleString()} FCFA\n• Nombre de mois: ${response.details.mois}\n• Période: ${response.details.mois_annee}`;
                        
                        if (response.details.mois > 1) {
                            message += `\n\n✅ Le locataire ne recevra plus de notifications pour les ${response.details.mois} mois payés.`;
                        }
                    }
                    
                    toastr.success(message, 'Paiement enregistré', {timeOut: 8000});
                    $('#modalEnregistrerPaiement').modal('hide');
                    
                    // Proposer d'imprimer le reçu
                    if (response.details && response.details.mois > 1) {
                        setTimeout(function() {
                            if (confirm('Voulez-vous imprimer le reçu de paiement pour ' + response.details.mois + ' mois ?')) {
                                window.open('<?= base_url('admin/receipts/multiple') ?>/' + response.details.contrat_id + '/' + response.details.mois_annee, '_blank');
                            }
                            location.reload();
                        }, 3000);
                    } else {
                        // Recharger la page après un délai pour laisser le temps de lire le message
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                } else {
                    toastr.error(response.message, 'Erreur d\'enregistrement');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Erreur lors de la communication avec le serveur';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage, 'Erreur');
            },
            complete: function() {
                // Réactiver le bouton
                $btn.prop('disabled', false).html('<i class="feather-save me-2"></i>Enregistrer');
            }
        });
    });
    
    // Fermer la modal avec la touche Échap
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#modalEnregistrerPaiement').hasClass('show')) {
            $('#modalEnregistrerPaiement').modal('hide');
        }
    });
    
    // Empêcher la fermeture accidentelle de la modal
    $('#modalEnregistrerPaiement').on('hide.bs.modal', function(e) {
        const $form = $('#enregistrerPaiementForm');
        const hasData = $form.find('input[value!=""], textarea[value!=""]').length > 1; // Plus que le contrat_id
        
        if (hasData) {
            if (!confirm('Êtes-vous sûr de vouloir fermer la modal ? Les données saisies seront perdues.')) {
                e.preventDefault();
            }
        }
    });
});
</script>
<?= $this->endSection() ?>

