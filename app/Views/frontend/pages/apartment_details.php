<?= $this->extend('frontend/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb Area -->
<section class="breadcrumb-area d-flex align-items-center" style="background-image:url(<?= base_url('assets/frontend/images/slider/slider1.png') ?>); background-size: cover; background-position: center;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-12 col-lg-12">
                <div class="breadcrumb-wrap text-center">
                    <div class="breadcrumb-title">
                        <h2><?= esc($appartement['adresse']) ?></h2>
                        <div class="breadcrumb-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Accueil</a></li>
                                    <li class="breadcrumb-item"><a href="<?= base_url('/apartments') ?>">Appartements</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= esc($appartement['adresse']) ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Area End -->

<!-- Apartment Details Section -->
<section class="apartment-details-section pt-120 pb-90">
    <div class="container">
        <div class="row">
            <!-- Gallery Section -->
            <div class="col-lg-8">
                <div class="apartment-gallery mb-40">
                    <?php
                    $photos = !empty($appartement['photos']) ? explode(',', $appartement['photos']) : [];
                    $photos = array_filter($photos);
                    ?>

                    <?php if (!empty($photos)): ?>
                        <!-- Main Photo -->
                        <div class="main-photo mb-3" style="border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <img id="mainPhoto" src="<?= base_url($photos[0]) ?>" alt="<?= esc($appartement['adresse']) ?>"
                                 style="width: 100%; height: 500px; object-fit: cover;">
                        </div>

                        <!-- Thumbnails -->
                        <?php if (count($photos) > 1): ?>
                            <div class="photo-thumbnails" style="display: flex; gap: 15px;">
                                <?php foreach ($photos as $index => $photo): ?>
                                    <div class="thumbnail" onclick="changeMainPhoto('<?= base_url($photo) ?>')"
                                         style="cursor: pointer; border-radius: 8px; overflow: hidden; border: 3px solid <?= $index === 0 ? '#d29751' : '#ddd' ?>; transition: all 0.3s ease; flex: 1;">
                                        <img src="<?= base_url($photo) ?>" alt="Photo <?= $index + 1 ?>"
                                             style="width: 100%; height: 150px; object-fit: cover; display: block;">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="no-photo" style="background: #f8f9fa; border-radius: 10px; padding: 60px; text-align: center;">
                            <i class="fas fa-home" style="font-size: 4rem; color: #ddd;"></i>
                            <p style="color: #999; margin-top: 20px;">Aucune photo disponible</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Description et caracteristiques -->
                <div class="apartment-description" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 30px;">
                    <h3 style="color: #333; margin-bottom: 20px; font-size: 1.8rem;">Caracteristiques detaillees</h3>

                    <div class="row">
                        <!-- Colonne 1 -->
                        <div class="col-md-6 mb-4">
                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Adresse</h5>
                                <p style="color: #333; font-size: 1.1rem; font-weight: 500; margin: 0;"><?= esc($appartement['adresse']) ?></p>
                            </div>

                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Type</h5>
                                <p style="color: #333; font-size: 1.1rem; font-weight: 500; margin: 0;"><?= ucfirst($appartement['type'] ?? 'Meuble') ?></p>
                            </div>

                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Tarif par nuit</h5>
                                <p style="color: #333; font-size: 1.1rem; font-weight: 500; margin: 0;"><?= number_format($appartement['tarifs'], 0, ',', ' ') ?> FCFA</p>
                            </div>
                        </div>

                        <!-- Colonne 2 -->
                        <div class="col-md-6 mb-4">
                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Statut</h5>
                                <?php
                                $statusColors = [
                                    'disponible' => '#28a745',
                                    'occupe' => '#dc3545',
                                    'reserve' => '#ffc107',
                                    'maintenance' => '#6c757d'
                                ];
                                $statusLabels = [
                                    'disponible' => 'Disponible',
                                    'occupe' => 'Occupe',
                                    'reserve' => 'Reserve',
                                    'maintenance' => 'En maintenance'
                                ];
                                $statusColor = $statusColors[$appartement['statut']] ?? '#6c757d';
                                $statusLabel = $statusLabels[$appartement['statut']] ?? ucfirst($appartement['statut']);
                                ?>
                                <span style="background: <?= $statusColor ?>; color: white; padding: 5px 15px; border-radius: 15px; font-size: 0.9rem;">
                                    <?= $statusLabel ?>
                                </span>
                            </div>

                            <?php if (!empty($appartement['proprietaire_nom'])): ?>
                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Proprietaire</h5>
                                <p style="color: #333; font-size: 1.1rem; font-weight: 500; margin: 0;"><?= esc($appartement['proprietaire_nom']) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Equipements -->
                    <?php
                    $equipements = !empty($appartement['equipements']) ? explode(',', $appartement['equipements']) : [];
                    $equipements = array_map('trim', $equipements);
                    $equipements = array_filter($equipements);
                    ?>
                    <?php if (!empty($equipements)): ?>
                        <div class="equipements-section mt-4" style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                            <h5 style="color: #333; margin-bottom: 15px;"><i class="fas fa-check-circle" style="color: #d29751;"></i> Equipements inclus</h5>
                            <div class="row">
                                <?php foreach ($equipements as $equip): ?>
                                    <div class="col-md-4 col-6 mb-2">
                                        <span style="display: flex; align-items: center; font-size: 0.95rem; color: #555;">
                                            <?php
                                            // Determiner l'icone appropriee
                                            $icon = 'fas fa-check';
                                            $equipLower = strtolower($equip);
                                            if (strpos($equipLower, 'wifi') !== false) $icon = 'fas fa-wifi';
                                            elseif (strpos($equipLower, 'clim') !== false) $icon = 'fas fa-snowflake';
                                            elseif (strpos($equipLower, 'cuisine') !== false) $icon = 'fas fa-utensils';
                                            elseif (strpos($equipLower, 'parking') !== false) $icon = 'fas fa-parking';
                                            elseif (strpos($equipLower, 'tv') !== false || strpos($equipLower, 'television') !== false) $icon = 'fas fa-tv';
                                            elseif (strpos($equipLower, 'piscine') !== false) $icon = 'fas fa-swimming-pool';
                                            elseif (strpos($equipLower, 'securite') !== false || strpos($equipLower, 'gardien') !== false) $icon = 'fas fa-shield-alt';
                                            elseif (strpos($equipLower, 'machine') !== false || strpos($equipLower, 'linge') !== false) $icon = 'fas fa-tshirt';
                                            ?>
                                            <i class="<?= $icon ?>" style="color: #d29751; margin-right: 10px; width: 20px;"></i>
                                            <?= esc($equip) ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($appartement['notes'])): ?>
                        <div class="apartment-notes mt-4" style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                            <h5 style="color: #333; margin-bottom: 10px;"><i class="fas fa-info-circle"></i> Notes supplementaires</h5>
                            <p style="color: #666; margin: 0; line-height: 1.6;"><?= nl2br(esc($appartement['notes'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar - Reservation -->
            <div class="col-lg-4">
                <div class="booking-sidebar" style="position: sticky; top: 100px;">
                    <!-- Prix -->
                    <div class="price-card" style="background: linear-gradient(135deg, #d29751 0%, #b67a3d 100%); color: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); margin-bottom: 20px; text-align: center;">
                        <h5 style="color: white; opacity: 0.9; margin-bottom: 10px; font-size: 1rem;">Tarif par nuit</h5>
                        <h2 style="color: white; font-size: 2.5rem; font-weight: bold; margin: 0;">
                            <?= number_format($appartement['tarifs'], 0, ',', ' ') ?> <span style="font-size: 1.2rem;">FCFA</span>
                        </h2>
                        <p style="color: white; opacity: 0.9; margin-top: 10px; margin-bottom: 0; font-size: 0.9rem;">Par nuit</p>
                    </div>

                    <!-- Statut -->
                    <div class="status-card" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 20px; text-align: center;">
                        <h5 style="color: #666; margin-bottom: 15px; font-size: 0.9rem;">Statut de disponibilite</h5>
                        <span style="background: <?= $statusColor ?>; color: white; padding: 10px 20px; border-radius: 20px; font-weight: 500; display: inline-block;">
                            <?= $statusLabel ?>
                        </span>
                    </div>

                    <!-- Bouton de contact/reservation -->
                    <div class="action-buttons">
                        <?php if ($appartement['statut'] === 'disponible'): ?>
                            <?php
                            // Preparer le message WhatsApp avec les details de l'appartement
                            $whatsappMessage = "Bonjour, je suis interesse(e) par l'appartement situe a " .
                                             $appartement['adresse'] . ". " .
                                             "Tarif: " . number_format($appartement['tarifs'], 0, ',', ' ') . " FCFA/nuit. " .
                                             "Pouvez-vous me donner plus d'informations ?";
                            $whatsappUrl = "https://wa.me/237671387969?text=" . urlencode($whatsappMessage);
                            ?>
                            <a href="<?= $whatsappUrl ?>" target="_blank" class="btn btn-primary w-100"
                               style="background: #25D366; border: none; color: white; padding: 15px; border-radius: 8px; font-size: 1.1rem; font-weight: 500; text-decoration: none; display: block; text-align: center; margin-bottom: 10px;">
                                <i class="fab fa-whatsapp"></i> Reserver via WhatsApp
                            </a>

                            <!-- Bouton pour ouvrir le formulaire de reservation -->
                            <button type="button" class="btn btn-reserve w-100" onclick="openReservationModal()"
                                    style="background: #d29751; border: none; color: white; padding: 15px; border-radius: 8px; font-size: 1.1rem; font-weight: 500; margin-bottom: 10px;">
                                <i class="fas fa-calendar-check"></i> Reserver en ligne
                            </button>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled
                                    style="background: #6c757d; border: none; color: white; padding: 15px; border-radius: 8px; font-size: 1.1rem; font-weight: 500; margin-bottom: 10px;">
                                <i class="fas fa-times-circle"></i> Non disponible
                            </button>
                        <?php endif; ?>

                        <a href="<?= base_url('/apartments') ?>" class="btn btn-outline w-100"
                           style="background: white; border: 2px solid #d29751; color: #d29751; padding: 15px; border-radius: 8px; font-size: 1.1rem; font-weight: 500; text-decoration: none; display: block; text-align: center;">
                            <i class="fas fa-arrow-left"></i> Retour aux appartements
                        </a>
                    </div>

                    <!-- Equipements quick view -->
                    <?php if (!empty($equipements)): ?>
                    <div class="quick-features mt-4" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        <h5 style="color: #333; margin-bottom: 20px; font-size: 1rem;">Equipements principaux</h5>
                        <?php foreach (array_slice($equipements, 0, 5) as $equip): ?>
                            <?php
                            $icon = 'fas fa-check';
                            $equipLower = strtolower($equip);
                            if (strpos($equipLower, 'wifi') !== false) $icon = 'fas fa-wifi';
                            elseif (strpos($equipLower, 'clim') !== false) $icon = 'fas fa-snowflake';
                            elseif (strpos($equipLower, 'cuisine') !== false) $icon = 'fas fa-utensils';
                            elseif (strpos($equipLower, 'parking') !== false) $icon = 'fas fa-parking';
                            elseif (strpos($equipLower, 'tv') !== false) $icon = 'fas fa-tv';
                            ?>
                            <div class="feature-item" style="display: flex; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                                <i class="<?= $icon ?>" style="color: #d29751; font-size: 1.3rem; width: 30px;"></i>
                                <span style="color: #666; margin-left: 15px;"><?= esc($equip) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Apartment Details Section End -->

<!-- Reservation Modal -->
<div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #d29751 0%, #b87d3f 100%); color: white;">
                <h5 class="modal-title" id="reservationModalLabel">
                    <i class="fas fa-calendar-alt"></i> Reserver cet Appartement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Apartment Info Display -->
                <div class="selected-apartment-info mb-4 p-3" style="background: #f8f9fa; border-left: 4px solid #d29751; border-radius: 5px;">
                    <h6 class="mb-2" style="color: #d29751;">
                        <i class="fas fa-home"></i> Appartement selectionne
                    </h6>
                    <p class="mb-1" style="font-weight: 600;"><?= esc($appartement['adresse']) ?></p>
                    <p class="mb-0" style="color: #28a745; font-size: 18px; font-weight: bold;">
                        <?= number_format($appartement['tarifs'], 0, ',', ' ') ?> FCFA/Nuit
                    </p>
                </div>

                <!-- Reservation Form -->
                <?= view('frontend/reservation/form_modal') ?>
            </div>
        </div>
    </div>
</div>

<script>
// Changer la photo principale
function changeMainPhoto(photoUrl) {
    document.getElementById('mainPhoto').src = photoUrl;

    // Mettre a jour les bordures des thumbnails
    const thumbnails = document.querySelectorAll('.thumbnail');
    thumbnails.forEach(thumb => {
        const img = thumb.querySelector('img');
        if (img.src === photoUrl) {
            thumb.style.borderColor = '#d29751';
        } else {
            thumb.style.borderColor = '#ddd';
        }
    });
}

// Ouvrir le modal de reservation
function openReservationModal() {
    // Set apartment data for the form
    if (typeof setModalAppartementData === 'function') {
        setModalAppartementData(<?= $appartement['id'] ?>, <?= $appartement['tarifs'] ?>);
    }

    // Open modal
    const modal = new bootstrap.Modal(document.getElementById('reservationModal'));
    modal.show();
}

// Effet hover sur les thumbnails
document.addEventListener('DOMContentLoaded', function() {
    const thumbnails = document.querySelectorAll('.thumbnail');
    thumbnails.forEach(thumb => {
        thumb.addEventListener('mouseenter', function() {
            if (this.style.borderColor !== 'rgb(210, 151, 81)') {
                this.style.borderColor = '#aaa';
            }
        });
        thumb.addEventListener('mouseleave', function() {
            if (this.style.borderColor !== 'rgb(210, 151, 81)') {
                this.style.borderColor = '#ddd';
            }
        });
    });
});
</script>

<style>
.btn:hover {
    opacity: 0.9;
    transform: scale(1.02);
    transition: all 0.3s ease;
}

.thumbnail {
    transition: all 0.3s ease;
}

.thumbnail:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Breadcrumb Area */
.breadcrumb-area {
    padding: 120px 0 80px;
    position: relative;
}

.breadcrumb-area::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}

.breadcrumb-wrap {
    position: relative;
    z-index: 1;
}

.breadcrumb-title h2 {
    color: white;
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 20px;
}

.breadcrumb-wrap .breadcrumb {
    background: transparent;
    justify-content: center;
    margin-bottom: 0;
}

.breadcrumb-item a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
}

.breadcrumb-item.active {
    color: white;
}

.breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255, 255, 255, 0.6);
}

@media (max-width: 991px) {
    .booking-sidebar {
        position: static !important;
        margin-top: 30px;
    }
}
</style>

<?= $this->endSection() ?>
