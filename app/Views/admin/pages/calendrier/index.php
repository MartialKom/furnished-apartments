<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100 text-white" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%); border: none; box-shadow: 0 4px 12px rgba(210, 151, 81, 0.3); border-radius: 10px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-bolder mb-0" id="stat-total">0</h4>
                            <p class="fs-13 fw-medium mb-0 opacity-75">Total Appartements</p>
                        </div>
                        <div class="avatar-text avatar-lg text-white" style="background: rgba(255,255,255,0.2);">
                            <i class="feather-home"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100 text-white" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%); border: none; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3); border-radius: 10px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-bolder mb-0" id="stat-occupes">0</h4>
                            <p class="fs-13 fw-medium mb-0 opacity-75">Occupés</p>
                        </div>
                        <div class="avatar-text avatar-lg text-white" style="background: rgba(255,255,255,0.2);">
                            <i class="feather-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100 text-white" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); border: none; box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3); border-radius: 10px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-bolder mb-0" id="stat-disponibles">0</h4>
                            <p class="fs-13 fw-medium mb-0 opacity-75">Disponibles</p>
                        </div>
                        <div class="avatar-text avatar-lg text-white" style="background: rgba(255,255,255,0.2);">
                            <i class="feather-unlock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100 text-white" style="background: linear-gradient(135deg, #6f42c1 0%, #5a32a3 100%); border: none; box-shadow: 0 4px 12px rgba(111, 66, 193, 0.3); border-radius: 10px;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-bolder mb-0" id="stat-taux">0%</h4>
                            <p class="fs-13 fw-medium mb-0 opacity-75">Taux d'Occupation</p>
                        </div>
                        <div class="avatar-text avatar-lg text-white" style="background: rgba(255,255,255,0.2);">
                            <i class="feather-pie-chart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Calendrier -->
    <div class="row">
        <div class="col-12">
            <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px;">
                <div class="card-header" style="background: linear-gradient(135deg, #d29751 0%, #b8834a 100%); border-radius: 10px 10px 0 0;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0 text-white"><i class="feather-calendar me-2"></i>Calendrier des Occupations</h6>
                        <div class="d-flex gap-2 align-items-center flex-wrap mt-2 mt-md-0">
                            <select class="form-select form-select-sm" id="filtreAppartement" style="width: 200px;">
                                <option value="">Tous les appartements</option>
                                <?php foreach ($appartements as $appart): ?>
                                    <option value="<?= $appart['id'] ?>"><?= esc($appart['adresse']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-light btn-sm" id="btnAujourdhui">
                                <i class="feather-calendar me-1"></i>Aujourd'hui
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Légende -->
                    <div class="mb-3 p-3" style="background: #f8f9fa; border-radius: 8px;">
                        <div class="d-flex flex-wrap gap-3 align-items-center">
                            <small class="fw-bold text-muted me-2">LÉGENDE:</small>
                            <span class="badge" style="background: #28a745;">🏠 Réservation Confirmée</span>
                            <span class="badge" style="background: #17a2b8;">🏠 Réservation En Cours</span>
                            <span class="badge" style="background: #ffc107; color: #333;">🏠 Réservation En Attente</span>
                            <span class="badge" style="background: #d29751;">📋 Contrat Actif</span>
                            <span class="badge" style="background: #ff6b6b;">📋 Contrat Suspendu</span>
                        </div>
                    </div>

                    <!-- Calendrier -->
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détails -->
<div class="modal fade" id="modalDetailsEvenement" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailsTitle">Détails</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalDetailsContent">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Chargement...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="#" id="btnVoirDetails" class="btn btn-primary" target="_blank">
                    <i class="feather-eye me-2"></i>Voir Détails Complets
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet" />

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/fr.global.min.js"></script>

<script>
let calendar;

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        buttonText: {
            month: 'Mois',
            week: 'Semaine',
            list: 'Liste'
        },
        height: 'auto',
        events: function(info, successCallback, failureCallback) {
            const appartementId = $('#filtreAppartement').val();

            $.ajax({
                url: '<?= base_url('admin/calendrier/evenements') ?>',
                type: 'GET',
                data: {
                    start: info.startStr,
                    end: info.endStr,
                    appartement_id: appartementId
                },
                success: function(data) {
                    successCallback(data);
                    // Mettre à jour les statistiques
                    chargerStatistiques();
                },
                error: function() {
                    toastr.error('Erreur lors du chargement des événements');
                    failureCallback();
                }
            });
        },
        eventClick: function(info) {
            afficherDetailsEvenement(info.event);
        },
        eventDidMount: function(info) {
            // Ajouter un tooltip
            const props = info.event.extendedProps;
            let tooltipContent = '';

            if (props.type === 'reservation') {
                tooltipContent = `
                    <strong>Réservation</strong><br>
                    Client: ${props.client}<br>
                    Appartement: ${props.appartement}<br>
                    Montant: ${parseInt(props.montant).toLocaleString()} FCFA<br>
                    Statut: ${props.statut}
                `;
            } else {
                tooltipContent = `
                    <strong>Contrat Long Terme</strong><br>
                    Locataire: ${props.locataire}<br>
                    Appartement: ${props.appartement}<br>
                    Loyer: ${parseInt(props.loyer).toLocaleString()} FCFA/mois<br>
                    Statut: ${props.statut}
                `;
            }

            $(info.el).attr('title', '').tooltip({
                title: tooltipContent,
                html: true,
                placement: 'top',
                container: 'body'
            });
        }
    });

    calendar.render();

    // Charger les statistiques initiales
    chargerStatistiques();
});

// Filtre par appartement
$('#filtreAppartement').change(function() {
    calendar.refetchEvents();
});

// Bouton Aujourd'hui
$('#btnAujourdhui').click(function() {
    calendar.today();
});

// Charger les statistiques
function chargerStatistiques() {
    const currentDate = calendar.getDate();
    const mois = currentDate.toISOString().slice(0, 7);

    $.ajax({
        url: '<?= base_url('admin/calendrier/statistiques') ?>',
        type: 'GET',
        data: { mois: mois },
        success: function(response) {
            if (response.success) {
                const stats = response.stats;
                $('#stat-total').text(stats.total_appartements);
                $('#stat-occupes').text(stats.total_occupes);
                $('#stat-disponibles').text(stats.disponibles);
                $('#stat-taux').text(stats.taux_occupation + '%');
            }
        },
        error: function() {
            console.error('Erreur lors du chargement des statistiques');
        }
    });
}

// Afficher les détails d'un événement
function afficherDetailsEvenement(event) {
    const eventId = event.id;
    const props = event.extendedProps;

    $('#modalDetailsContent').html(`
        <div class="text-center">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p>Chargement des détails...</p>
        </div>
    `);

    $('#modalDetailsEvenement').modal('show');

    $.ajax({
        url: '<?= base_url('admin/calendrier/details') ?>',
        type: 'GET',
        data: { event_id: eventId },
        success: function(response) {
            if (response.success) {
                const data = response.data;
                const type = response.type;

                let html = '';
                let detailsUrl = '';

                if (type === 'reservation') {
                    detailsUrl = '<?= base_url('admin/reservations/show') ?>/' + props.id_original;

                    html = `
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="feather-info me-2"></i>Informations Réservation</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td class="fw-bold">Période:</td>
                                                <td>${new Date(data.date_debut).toLocaleDateString('fr-FR')} - ${new Date(data.date_fin).toLocaleDateString('fr-FR')}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Statut:</td>
                                                <td><span class="badge bg-info">${data.statut}</span></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Type:</td>
                                                <td>${data.type_reservation || 'N/A'}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header" style="background: #d29751; color: white;">
                                        <h6 class="mb-0"><i class="feather-user me-2"></i>Client</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td class="fw-bold">Nom:</td>
                                                <td>${data.client_nom || 'N/A'}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Email:</td>
                                                <td>${data.client_email || 'N/A'}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Téléphone:</td>
                                                <td>${data.client_telephone || 'N/A'}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="feather-dollar-sign me-2"></i>Détails Financiers</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="mb-1 text-muted small">Montant Total</p>
                                        <h5 class="text-primary">${parseInt(data.montant_total).toLocaleString()} FCFA</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1 text-muted small">Montant Payé</p>
                                        <h5 class="text-success">${parseInt(data.montant_paye || 0).toLocaleString()} FCFA</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1 text-muted small">Reste à Payer</p>
                                        <h5 class="text-danger">${parseInt(data.montant_restant || 0).toLocaleString()} FCFA</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    detailsUrl = '<?= base_url('admin/contrats/show') ?>/' + props.id_original;

                    html = `
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header" style="background: #d29751; color: white;">
                                        <h6 class="mb-0"><i class="feather-file-text me-2"></i>Informations Contrat</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td class="fw-bold">Date début:</td>
                                                <td>${new Date(data.date_debut).toLocaleDateString('fr-FR')}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Date fin:</td>
                                                <td>${data.date_fin ? new Date(data.date_fin).toLocaleDateString('fr-FR') : 'Indéterminé'}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Statut:</td>
                                                <td><span class="badge" style="background: #d29751;">${data.statut}</span></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Jour paiement:</td>
                                                <td>Le ${data.jour_paiement} de chaque mois</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="feather-user me-2"></i>Locataire</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td class="fw-bold">Nom:</td>
                                                <td>${data.locataire_nom || 'N/A'}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Email:</td>
                                                <td>${data.locataire_email || 'N/A'}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Téléphone:</td>
                                                <td>${data.locataire_telephone || 'N/A'}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="feather-dollar-sign me-2"></i>Loyer Mensuel</h6>
                            </div>
                            <div class="card-body text-center">
                                <h3 class="text-primary fw-bold">${parseInt(data.loyer_mensuel).toLocaleString()} FCFA</h3>
                                <p class="text-muted mb-0">Caution: ${parseInt(data.caution || 0).toLocaleString()} FCFA</p>
                            </div>
                        </div>
                    `;
                }

                $('#modalDetailsContent').html(html);
                $('#btnVoirDetails').attr('href', detailsUrl);
                $('#modalDetailsTitle').text(type === 'reservation' ? 'Détails Réservation' : 'Détails Contrat');
            } else {
                $('#modalDetailsContent').html(`
                    <div class="alert alert-danger">
                        <i class="feather-x-circle me-2"></i>${response.message}
                    </div>
                `);
            }
        },
        error: function() {
            $('#modalDetailsContent').html(`
                <div class="alert alert-danger">
                    <i class="feather-x-circle me-2"></i>Erreur lors du chargement des détails
                </div>
            `);
        }
    });
}
</script>
<?= $this->endSection() ?>
