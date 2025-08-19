<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="feather-calendar me-2"></i>
                Gestion des Réservations
            </h5>
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
                                                    <span class="fw-bold text-success"><?= number_format($reservation['montant_total'], 0, ',', ' ') ?> FCFA</span>
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
                                            <td colspan="6" class="text-center py-5">
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
                                                    <span class="fw-bold text-success"><?= number_format($reservation['montant_total'], 0, ',', ' ') ?> FCFA</span>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($reservation['updated_at'])) ?></small>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-danger cancel-reservation" 
                                                            data-reservation-id="<?= $reservation['id'] ?>"
                                                            data-bs-toggle="tooltip" title="Annuler">
                                                        <i class="feather-x"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
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
                                                    <span class="fw-bold text-muted"><?= number_format($reservation['montant_total'], 0, ',', ' ') ?> FCFA</span>
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
                                            <td colspan="6" class="text-center py-5">
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

/* Modal fixes are handled in modal-fix.css */
</style>

<script>
$(document).ready(function() {
    // Initialiser les tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
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