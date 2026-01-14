<!-- Modal Détails Facture -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="feather-eye me-2"></i>Détails de la Facture d'Eau
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-1">Locataire</h6>
                        <p class="fw-semibold mb-0" id="detail_locataire"></p>
                        <small class="text-muted" id="detail_locataire_email"></small><br>
                        <small class="text-muted" id="detail_locataire_telephone"></small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-1">Appartement</h6>
                        <p class="fw-semibold" id="detail_appartement"></p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted mb-1">Période</h6>
                        <p class="fw-semibold" id="detail_periode"></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted mb-1">Date d'émission</h6>
                        <p class="fw-semibold" id="detail_emission"></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted mb-1">Date d'échéance</h6>
                        <p class="fw-semibold" id="detail_echeance"></p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted mb-1">Index précédent</h6>
                        <p class="fw-semibold" id="detail_index_prec"></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted mb-1">Index actuel</h6>
                        <p class="fw-semibold" id="detail_index_actuel"></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted mb-1">Consommation</h6>
                        <p class="fw-semibold text-primary" id="detail_consommation"></p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-1">Montant de la facture</h6>
                        <p class="fw-bold text-primary fs-4" id="detail_montant"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted mb-1">Statut</h6>
                        <p id="detail_statut"></p>
                    </div>
                </div>

                <div class="row" id="paiement_section" style="display: none;">
                    <div class="col-12">
                        <hr>
                        <h6 class="text-muted mb-3">Informations de paiement</h6>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted mb-1 small">Montant payé</h6>
                        <p class="fw-semibold text-success" id="detail_montant_paye"></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted mb-1 small">Date de paiement</h6>
                        <p class="fw-semibold" id="detail_date_paiement"></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h6 class="text-muted mb-1 small">Mode de paiement</h6>
                        <p class="fw-semibold" id="detail_mode_paiement"></p>
                    </div>
                    <div class="col-md-12 mb-3" id="reference_section" style="display: none;">
                        <h6 class="text-muted mb-1 small">Référence de paiement</h6>
                        <p class="fw-semibold" id="detail_reference_paiement"></p>
                    </div>
                </div>

                <div class="row" id="notes_section" style="display: none;">
                    <div class="col-12">
                        <hr>
                        <h6 class="text-muted mb-1">Notes</h6>
                        <p id="detail_notes"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
// Fonction pour afficher les détails de la facture
function afficherDetailsFacture(facture) {
    // Informations locataire et appartement
    document.getElementById('detail_locataire').textContent = facture.locataire_nom || '-';
    document.getElementById('detail_locataire_email').textContent = facture.locataire_email || '';
    document.getElementById('detail_locataire_telephone').textContent = facture.locataire_telephone || '';
    document.getElementById('detail_appartement').textContent = facture.appartement_adresse || '-';

    // Période et dates
    const moisAnnee = facture.mois_annee ? new Date(facture.mois_annee + '-01') : null;
    const periodeFormatee = moisAnnee ? moisAnnee.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' }) : '-';
    document.getElementById('detail_periode').textContent = periodeFormatee;

    document.getElementById('detail_emission').textContent = facture.date_emission ?
        new Date(facture.date_emission).toLocaleDateString('fr-FR') : '-';
    document.getElementById('detail_echeance').textContent = facture.date_echeance ?
        new Date(facture.date_echeance).toLocaleDateString('fr-FR') : '-';

    // Index et consommation
    document.getElementById('detail_index_prec').textContent = facture.index_precedent ?
        parseFloat(facture.index_precedent).toFixed(2) : '-';
    document.getElementById('detail_index_actuel').textContent = facture.index_actuel ?
        parseFloat(facture.index_actuel).toFixed(2) : '-';
    document.getElementById('detail_consommation').textContent = facture.consommation_m3 ?
        parseFloat(facture.consommation_m3).toFixed(2) + ' m³' : '-';

    // Montant
    document.getElementById('detail_montant').textContent = facture.montant ?
        new Intl.NumberFormat('fr-FR').format(facture.montant) + ' FCFA' : '-';

    // Statut
    const statuts = {
        'en_attente': '<span class="badge bg-warning text-dark">En attente</span>',
        'paye': '<span class="badge bg-success">Payée</span>',
        'en_retard': '<span class="badge bg-danger">En retard</span>',
        'partiellement_paye': '<span class="badge bg-info">Partiellement payée</span>'
    };
    document.getElementById('detail_statut').innerHTML = statuts[facture.statut] ||
        '<span class="badge bg-secondary">Inconnu</span>';

    // Informations de paiement (si payée ou partiellement payée)
    const paiementSection = document.getElementById('paiement_section');
    if (facture.statut === 'paye' || facture.statut === 'partiellement_paye') {
        paiementSection.style.display = 'block';

        document.getElementById('detail_montant_paye').textContent = facture.montant_paye ?
            new Intl.NumberFormat('fr-FR').format(facture.montant_paye) + ' FCFA' : '0 FCFA';

        document.getElementById('detail_date_paiement').textContent = facture.date_paiement ?
            new Date(facture.date_paiement).toLocaleDateString('fr-FR') : '-';

        const modes = {
            'especes': 'Espèces',
            'virement': 'Virement bancaire',
            'cheque': 'Chèque',
            'mobile_money': 'Mobile Money'
        };
        document.getElementById('detail_mode_paiement').textContent = modes[facture.mode_paiement] || '-';

        const referenceSection = document.getElementById('reference_section');
        if (facture.reference_paiement) {
            referenceSection.style.display = 'block';
            document.getElementById('detail_reference_paiement').textContent = facture.reference_paiement;
        } else {
            referenceSection.style.display = 'none';
        }
    } else {
        paiementSection.style.display = 'none';
    }

    // Notes
    const notesSection = document.getElementById('notes_section');
    if (facture.notes) {
        notesSection.style.display = 'block';
        document.getElementById('detail_notes').textContent = facture.notes;
    } else {
        notesSection.style.display = 'none';
    }
}

// Mise à jour de la fonction voirFacture pour utiliser afficherDetailsFacture
function voirFactureAvecDetails(id) {
    fetch(`<?= base_url('admin/factures-eau/get') ?>/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                afficherDetailsFacture(data.facture);
                $('#detailsModal').modal('show');
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la récupération des détails');
        });
}
</script>
