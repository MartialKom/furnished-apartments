<!-- Modale de détails Client/Locataire -->
<div class="modal fade" id="clientDetailsModal" tabindex="-1" aria-labelledby="clientDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="clientDetailsModalLabel">
                    <i class="feather-user me-2"></i>Détails du Client
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="clientDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modale de détails Réservation -->
<div class="modal fade" id="reservationDetailsModal" tabindex="-1" aria-labelledby="reservationDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%);">
                <h5 class="modal-title text-white" id="reservationDetailsModalLabel">
                    <i class="feather-calendar me-2"></i>Détails de la Réservation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reservationDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border" style="color: #d29751;" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modale de détails Paiement -->
<div class="modal fade" id="paiementDetailsModal" tabindex="-1" aria-labelledby="paiementDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="paiementDetailsModalLabel">
                    <i class="feather-credit-card me-2"></i>Détails du Paiement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="paiementDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">Chargement...</span>
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
// Fonction globale pour afficher les détails d'un client
function showClientDetails(clientId) {
    $('#clientDetailsModal').modal('show');
    $('#clientDetailsContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
    `);

    $.ajax({
        url: '<?php echo base_url('admin/locataires/get'); ?>/' + clientId,
        type: 'GET',
        success: function(response) {
            if (response.success && response.locataire) {
                const client = response.locataire;
                const html = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="feather-user me-2"></i>Informations Personnelles</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>Nom:</strong>
                                        <span class="float-end">${client.nom || 'N/A'}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Email:</strong>
                                        <span class="float-end">${client.email || 'N/A'}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Téléphone:</strong>
                                        <span class="float-end">${client.telephone || 'N/A'}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Date de naissance:</strong>
                                        <span class="float-end">${client.date_naissance ? new Date(client.date_naissance).toLocaleDateString('fr-FR') : 'N/A'}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Lieu de naissance:</strong>
                                        <span class="float-end">${client.lieu_naissance || 'N/A'}</span>
                                    </div>
                                    <div class="mb-0">
                                        <strong>Nationalité:</strong>
                                        <span class="float-end">${client.nationalite || 'N/A'}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="feather-file-text me-2"></i>Pièce d'Identité</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>Type:</strong>
                                        <span class="float-end">
                                            ${client.type_piece === 'CNI' ? 'Carte Nationale d\'Identité' :
                                              client.type_piece === 'PASSPORT' ? 'Passeport' :
                                              client.type_piece === 'CARTE_SEJOUR' ? 'Carte de Séjour' :
                                              client.type_piece || 'N/A'}
                                        </span>
                                    </div>
                                    <div class="mb-0">
                                        <strong>Numéro:</strong>
                                        <span class="float-end">${client.numero_piece || 'N/A'}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="feather-info me-2"></i>Informations Système</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>Date d'inscription:</strong>
                                        <span class="float-end">${new Date(client.created_at).toLocaleDateString('fr-FR')}</span>
                                    </div>
                                    <div class="mb-0">
                                        <strong>Dernière modification:</strong>
                                        <span class="float-end">${new Date(client.updated_at).toLocaleDateString('fr-FR')}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#clientDetailsContent').html(html);
            } else {
                $('#clientDetailsContent').html('<div class="alert alert-danger">Erreur lors du chargement des détails du client.</div>');
            }
        },
        error: function() {
            $('#clientDetailsContent').html('<div class="alert alert-danger">Erreur lors du chargement des détails.</div>');
        }
    });
}

// Fonction globale pour afficher les détails d'une réservation
function showReservationDetails(reservationId) {
    $('#reservationDetailsModal').modal('show');
    $('#reservationDetailsContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border" style="color: #d29751;" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
    `);

    $.ajax({
        url: '<?php echo base_url('admin/reservations/get'); ?>/' + reservationId,
        type: 'GET',
        success: function(response) {
            if (response.success && response.reservation) {
                const res = response.reservation;

                // Calculer la durée
                const dateDebut = new Date(res.date_debut);
                const dateFin = new Date(res.date_fin);
                const duree = Math.ceil((dateFin - dateDebut) / (1000 * 60 * 60 * 24));

                // Badge de statut
                const statutColors = {
                    'en_attente': 'warning',
                    'confirmee': 'success',
                    'en_cours': 'info',
                    'terminee': 'secondary',
                    'annulee': 'danger'
                };

                const statutLabels = {
                    'en_attente': 'En attente',
                    'confirmee': 'Confirmée',
                    'en_cours': 'En cours',
                    'terminee': 'Terminée',
                    'annulee': 'Annulée'
                };

                const html = `
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="feather-info me-2" style="font-size: 24px;"></i>
                                <div>
                                    <strong>Statut:</strong>
                                    <span class="badge bg-${statutColors[res.statut]} ms-2">${statutLabels[res.statut]}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-header" style="background-color: #f8f9fa;">
                                    <h6 class="mb-0"><i class="feather-user me-2"></i>Client</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2"><strong>Nom:</strong> <span class="float-end">${res.locataire_nom || 'N/A'}</span></div>
                                    <div class="mb-2"><strong>Email:</strong> <span class="float-end">${res.locataire_email || 'N/A'}</span></div>
                                    <div class="mb-0"><strong>Téléphone:</strong> <span class="float-end">${res.locataire_telephone || 'N/A'}</span></div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-header" style="background-color: #f8f9fa;">
                                    <h6 class="mb-0"><i class="feather-home me-2"></i>Bien Loué</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-0"><strong>Adresse:</strong><br>${res.appartement_adresse || 'N/A'}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-header" style="background-color: #f8f9fa;">
                                    <h6 class="mb-0"><i class="feather-calendar me-2"></i>Période</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2"><strong>Date d'arrivée:</strong> <span class="float-end">${dateDebut.toLocaleDateString('fr-FR')}</span></div>
                                    <div class="mb-2"><strong>Date de départ:</strong> <span class="float-end">${dateFin.toLocaleDateString('fr-FR')}</span></div>
                                    <div class="mb-0"><strong>Durée:</strong> <span class="float-end badge bg-info">${duree} jours</span></div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm">
                                <div class="card-header" style="background-color: #f8f9fa;">
                                    <h6 class="mb-0"><i class="feather-dollar-sign me-2"></i>Montants</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2"><strong>Montant total:</strong> <span class="float-end fw-bold text-primary">${new Intl.NumberFormat('fr-FR').format(res.montant_total)} FCFA</span></div>
                                    ${res.reduction ? `<div class="mb-2"><strong>Réduction:</strong> <span class="float-end text-warning">-${new Intl.NumberFormat('fr-FR').format(res.reduction)} FCFA</span></div>` : ''}
                                    <div class="mb-2"><strong>Montant payé:</strong> <span class="float-end text-success">${new Intl.NumberFormat('fr-FR').format(res.montant_paye || 0)} FCFA</span></div>
                                    <div class="mb-0 border-top pt-2"><strong>Reste à payer:</strong> <span class="float-end fw-bold text-danger">${new Intl.NumberFormat('fr-FR').format((res.montant_total - (res.reduction || 0) - (res.montant_paye || 0)))} FCFA</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#reservationDetailsContent').html(html);
            } else {
                $('#reservationDetailsContent').html('<div class="alert alert-danger">Erreur lors du chargement des détails de la réservation.</div>');
            }
        },
        error: function() {
            $('#reservationDetailsContent').html('<div class="alert alert-danger">Erreur lors du chargement des détails.</div>');
        }
    });
}

// Fonction globale pour afficher les détails d'un paiement
function showPaiementDetails(paiementId) {
    $('#paiementDetailsModal').modal('show');
    $('#paiementDetailsContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
    `);

    $.ajax({
        url: '<?php echo base_url('admin/paiements/get'); ?>/' + paiementId,
        type: 'GET',
        success: function(response) {
            if (response.success && response.paiement) {
                const p = response.paiement;

                const statutColors = {
                    'en_attente': 'warning',
                    'paye': 'success',
                    'rembourse': 'info',
                    'annule': 'danger'
                };

                const html = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="feather-info me-2"></i>Informations Générales</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2"><strong>Statut:</strong> <span class="float-end badge bg-${statutColors[p.statut]}">${p.statut.toUpperCase()}</span></div>
                                    <div class="mb-2"><strong>Date de paiement:</strong> <span class="float-end">${new Date(p.date_paiement || p.created_at).toLocaleDateString('fr-FR')}</span></div>
                                    <div class="mb-0"><strong>Mode de paiement:</strong> <span class="float-end">${p.mode_paiement || 'N/A'}</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="feather-dollar-sign me-2"></i>Montants</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2"><strong>Montant payé:</strong> <span class="float-end fw-bold text-success">${new Intl.NumberFormat('fr-FR').format(p.montant_paye)} FCFA</span></div>
                                    ${p.montant_restant ? `<div class="mb-0"><strong>Reste à payer:</strong> <span class="float-end text-danger">${new Intl.NumberFormat('fr-FR').format(p.montant_restant)} FCFA</span></div>` : ''}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <a href="<?php echo base_url('admin/paiements/generer-facture'); ?>/${paiementId}" target="_blank" class="btn btn-primary">
                                    <i class="feather-printer me-2"></i>Imprimer la facture
                                </a>
                            </div>
                        </div>
                    </div>
                `;
                $('#paiementDetailsContent').html(html);
            } else {
                $('#paiementDetailsContent').html('<div class="alert alert-danger">Erreur lors du chargement des détails du paiement.</div>');
            }
        },
        error: function() {
            $('#paiementDetailsContent').html('<div class="alert alert-danger">Erreur lors du chargement des détails.</div>');
        }
    });
}
</script>

<style>
.modal-body .card {
    transition: transform 0.2s;
}

.modal-body .card:hover {
    transform: translateY(-2px);
}
</style>
