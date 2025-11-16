<!-- Modal Voir Détails Voiture -->
<div class="modal fade" id="viewVoitureModal" tabindex="-1" aria-labelledby="viewVoitureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #d29751; color: white;">
                <h5 class="modal-title" id="viewVoitureModalLabel">
                    <i class="feather-eye me-2"></i>Détails de la Voiture
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewVoitureContent">
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
function voirVoiture(id) {
    const modal = new bootstrap.Modal(document.getElementById('viewVoitureModal'));
    const content = document.getElementById('viewVoitureContent');

    // Afficher le spinner
    content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
    `;

    modal.show();

    fetch(`<?= base_url('admin/voitures/get') ?>/${id}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const voiture = data.voiture;

            // Déterminer la couleur du badge selon le statut
            let badgeClass = 'bg-success';
            let statutLabel = 'Disponible';
            switch(voiture.statut) {
                case 'louee':
                    badgeClass = 'bg-warning text-dark';
                    statutLabel = 'Louée';
                    break;
                case 'maintenance':
                    badgeClass = 'bg-info';
                    statutLabel = 'En Maintenance';
                    break;
                case 'indisponible':
                    badgeClass = 'bg-danger';
                    statutLabel = 'Indisponible';
                    break;
            }

            content.innerHTML = `
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">${voiture.marque} ${voiture.modele}</h4>
                            <span class="badge ${badgeClass} fs-6">${statutLabel}</span>
                        </div>
                        <hr>
                    </div>

                    <!-- Informations Générales -->
                    <div class="col-12 mb-3">
                        <h6 class="text-muted"><i class="feather-info me-2"></i>Informations Générales</h6>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Immatriculation:</strong><br>
                        <span class="text-muted">${voiture.immatriculation}</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Année:</strong><br>
                        <span class="text-muted">${voiture.annee}</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Couleur:</strong><br>
                        <span class="text-muted">${voiture.couleur || 'N/A'}</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Numéro de Chassis:</strong><br>
                        <span class="text-muted">${voiture.numero_chassis || 'N/A'}</span>
                    </div>

                    <!-- Caractéristiques Techniques -->
                    <div class="col-12 mb-3 mt-4">
                        <h6 class="text-muted"><i class="feather-settings me-2"></i>Caractéristiques Techniques</h6>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Nombre de Places:</strong><br>
                        <span class="text-muted">${voiture.nombre_places} places</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Type de Carburant:</strong><br>
                        <span class="text-muted">${capitalizeFirst(voiture.type_carburant)}</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Transmission:</strong><br>
                        <span class="text-muted">${capitalizeFirst(voiture.transmission)}</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Kilométrage:</strong><br>
                        <span class="text-muted">${Number(voiture.kilometrage).toLocaleString('fr-FR')} km</span>
                    </div>

                    <!-- Tarification -->
                    <div class="col-12 mb-3 mt-4">
                        <h6 class="text-muted"><i class="feather-dollar-sign me-2"></i>Tarification</h6>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Tarif Journalier:</strong><br>
                        <h5 class="text-primary mb-0">${Number(voiture.tarif_journalier).toLocaleString('fr-FR')} FCFA</h5>
                        <small class="text-muted">/ jour</small>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Caution:</strong><br>
                        <span class="text-muted">${voiture.caution ? Number(voiture.caution).toLocaleString('fr-FR') + ' FCFA' : 'N/A'}</span>
                    </div>

                    <!-- Documents et Échéances -->
                    <div class="col-12 mb-3 mt-4">
                        <h6 class="text-muted"><i class="feather-file-text me-2"></i>Documents et Échéances</h6>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Assurance Expire le:</strong><br>
                        <span class="text-muted">${voiture.assurance_expire_le ? formatDate(voiture.assurance_expire_le) : 'N/A'}</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Visite Technique Expire le:</strong><br>
                        <span class="text-muted">${voiture.visite_technique_expire_le ? formatDate(voiture.visite_technique_expire_le) : 'N/A'}</span>
                    </div>

                    ${voiture.notes ? `
                    <div class="col-12 mb-2 mt-4">
                        <strong>Notes:</strong><br>
                        <p class="text-muted">${voiture.notes}</p>
                    </div>
                    ` : ''}

                    <div class="col-12 mt-4">
                        <small class="text-muted">
                            <i class="feather-clock me-1"></i>Créé le ${formatDate(voiture.created_at)}
                            ${voiture.updated_at ? ` - Modifié le ${formatDate(voiture.updated_at)}` : ''}
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
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function formatDate(dateStr) {
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
