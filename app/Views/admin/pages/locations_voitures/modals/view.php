<!-- Modal Voir Détails Location -->
<div class="modal fade" id="viewLocationModal" tabindex="-1" aria-labelledby="viewLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #d29751; color: white;">
                <h5 class="modal-title" id="viewLocationModalLabel">
                    <i class="feather-eye me-2"></i>Détails de la Location
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewLocationContent">
                <div class="text-center py-5">
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

<script>
function voirLocation(id) {
    const modal = new bootstrap.Modal(document.getElementById('viewLocationModal'));
    const content = document.getElementById('viewLocationContent');

    // Afficher le spinner
    content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
    `;

    modal.show();

    fetch(`<?= base_url('admin/locations-voitures/get') ?>/${id}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const location = data.location;

            // Déterminer la couleur du badge selon le statut
            let badgeClass = 'bg-warning text-dark';
            let statutLabel = 'En Attente';
            switch(location.statut) {
                case 'en_cours':
                    badgeClass = 'bg-info';
                    statutLabel = 'En Cours';
                    break;
                case 'terminee':
                    badgeClass = 'bg-success';
                    statutLabel = 'Terminée';
                    break;
                case 'annulee':
                    badgeClass = 'bg-danger';
                    statutLabel = 'Annulée';
                    break;
            }

            // Calculer le pourcentage de paiement
            const pourcentagePaye = (location.montant_total > 0) ? (location.montant_paye / location.montant_total * 100) : 0;

            content.innerHTML = `
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">Location #${location.id}</h4>
                            <span class="badge ${badgeClass} fs-6">${statutLabel}</span>
                        </div>
                        <hr>
                    </div>

                    <!-- Informations Voiture -->
                    <div class="col-12 mb-3">
                        <h6 class="text-muted"><i class="feather-truck me-2"></i>Véhicule</h6>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Voiture:</strong><br>
                        <span class="text-muted">${location.marque} ${location.modele}</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Immatriculation:</strong><br>
                        <span class="text-muted">${location.immatriculation}</span>
                    </div>

                    <!-- Informations Client -->
                    <div class="col-12 mb-3 mt-4">
                        <h6 class="text-muted"><i class="feather-user me-2"></i>Client</h6>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Nom:</strong><br>
                        <span class="text-muted">${location.nom_client}</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Téléphone:</strong><br>
                        <span class="text-muted">${location.telephone_client || 'N/A'}</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Email:</strong><br>
                        <span class="text-muted">${location.email_client || 'N/A'}</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Permis:</strong><br>
                        <span class="text-muted">${location.client_permis || 'N/A'}</span>
                    </div>

                    <!-- Période de Location -->
                    <div class="col-12 mb-3 mt-4">
                        <h6 class="text-muted"><i class="feather-calendar me-2"></i>Période</h6>
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Date de Début:</strong><br>
                        <span class="text-muted">${formatDate(location.date_debut)}</span>
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Date de Fin Prévue:</strong><br>
                        <span class="text-muted">${formatDate(location.date_fin_prevue)}</span>
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Durée:</strong><br>
                        <span class="text-muted">${location.nombre_jours} jour(s)</span>
                    </div>
                    ${location.date_fin_reelle ? `
                    <div class="col-md-12 mb-2">
                        <strong>Date de Retour Effective:</strong><br>
                        <span class="text-muted">${formatDate(location.date_fin_reelle)}</span>
                    </div>
                    ` : ''}

                    <!-- Kilométrage -->
                    ${(location.kilometrage_depart || location.kilometrage_retour) ? `
                    <div class="col-12 mb-3 mt-4">
                        <h6 class="text-muted"><i class="feather-activity me-2"></i>Kilométrage</h6>
                    </div>
                    ${location.kilometrage_depart ? `
                    <div class="col-md-4 mb-2">
                        <strong>Km Départ:</strong><br>
                        <span class="text-muted">${Number(location.kilometrage_depart).toLocaleString('fr-FR')} km</span>
                    </div>
                    ` : ''}
                    ${location.kilometrage_retour ? `
                    <div class="col-md-4 mb-2">
                        <strong>Km Retour:</strong><br>
                        <span class="text-muted">${Number(location.kilometrage_retour).toLocaleString('fr-FR')} km</span>
                    </div>
                    <div class="col-md-4 mb-2">
                        <strong>Distance Parcourue:</strong><br>
                        <span class="text-muted">${Number(location.kilometrage_retour - location.kilometrage_depart).toLocaleString('fr-FR')} km</span>
                    </div>
                    ` : ''}
                    ` : ''}

                    <!-- Tarification -->
                    <div class="col-12 mb-3 mt-4">
                        <h6 class="text-muted"><i class="feather-dollar-sign me-2"></i>Tarification</h6>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Tarif Journalier:</strong><br>
                        <span class="text-muted">${Number(location.tarif_journalier).toLocaleString('fr-FR')} FCFA/jour</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Montant Total:</strong><br>
                        <h5 class="text-primary mb-0">${Number(location.montant_total).toLocaleString('fr-FR')} FCFA</h5>
                    </div>
                    <div class="col-12 mb-2 mt-3">
                        <strong>Paiement:</strong><br>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar ${pourcentagePaye >= 100 ? 'bg-success' : 'bg-warning'}" role="progressbar" style="width: ${pourcentagePaye}%">
                                ${pourcentagePaye.toFixed(0)}%
                            </div>
                        </div>
                        <small class="text-muted">
                            Payé: ${Number(location.montant_paye).toLocaleString('fr-FR')} FCFA /
                            Restant: ${Number(location.montant_restant).toLocaleString('fr-FR')} FCFA
                        </small>
                    </div>
                    ${location.mode_paiement ? `
                    <div class="col-md-6 mb-2 mt-2">
                        <strong>Mode de Paiement:</strong><br>
                        <span class="text-muted">${capitalizeFirst(location.mode_paiement.replace('_', ' '))}</span>
                    </div>
                    ` : ''}

                    <!-- Caution -->
                    <div class="col-12 mb-3 mt-4">
                        <h6 class="text-muted"><i class="feather-shield me-2"></i>Caution</h6>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Caution Versée:</strong><br>
                        <span class="text-muted">${location.caution_versee ? Number(location.caution_versee).toLocaleString('fr-FR') + ' FCFA' : 'N/A'}</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Caution Restituée:</strong><br>
                        <span class="badge ${location.caution_restituee ? 'bg-success' : 'bg-warning text-dark'}">
                            ${location.caution_restituee ? 'Oui' : 'Non'}
                        </span>
                    </div>

                    ${(location.etat_depart || location.etat_retour) ? `
                    <!-- État du Véhicule -->
                    <div class="col-12 mb-3 mt-4">
                        <h6 class="text-muted"><i class="feather-check-square me-2"></i>État du Véhicule</h6>
                    </div>
                    ${location.etat_depart ? `
                    <div class="col-md-6 mb-2">
                        <strong>État au Départ:</strong><br>
                        <span class="text-muted">${capitalizeFirst(location.etat_depart)}</span>
                    </div>
                    ` : ''}
                    ${location.etat_retour ? `
                    <div class="col-md-6 mb-2">
                        <strong>État au Retour:</strong><br>
                        <span class="text-muted">${capitalizeFirst(location.etat_retour)}</span>
                    </div>
                    ` : ''}
                    ` : ''}

                    ${location.notes ? `
                    <div class="col-12 mb-2 mt-4">
                        <strong>Notes:</strong><br>
                        <p class="text-muted">${location.notes}</p>
                    </div>
                    ` : ''}

                    ${location.statut === 'annulee' && location.motif_annulation ? `
                    <div class="col-12 mb-2 mt-4">
                        <div class="alert alert-danger">
                            <strong>Motif d'Annulation:</strong><br>
                            ${location.motif_annulation}
                        </div>
                    </div>
                    ` : ''}

                    <div class="col-12 mt-4">
                        <small class="text-muted">
                            <i class="feather-clock me-1"></i>Créé le ${formatDate(location.created_at)}
                            ${location.updated_at ? ` - Modifié le ${formatDate(location.updated_at)}` : ''}
                        </small>
                    </div>
                </div>
            `;
        } else {
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="feather-alert-circle me-2"></i>${data.message || 'Erreur lors du chargement des détails'}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        content.innerHTML = `
            <div class="alert alert-danger">
                <i class="feather-alert-circle me-2"></i>Erreur lors du chargement des données
            </div>
        `;
    });
}

function capitalizeFirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function formatDate(dateStr) {
    if (!dateStr) return 'N/A';
    const date = new Date(dateStr);
    return date.toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: dateStr.includes(':') ? '2-digit' : undefined,
        minute: dateStr.includes(':') ? '2-digit' : undefined
    });
}
</script>
