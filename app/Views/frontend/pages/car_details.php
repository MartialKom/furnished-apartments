<?= $this->extend('frontend/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb Area -->
<section class="breadcrumb-area d-flex align-items-center" style="background-image:url(<?= base_url('assets/frontend/images/slider/slider4.jpg') ?>); background-size: cover; background-position: center;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-12 col-lg-12">
                <div class="breadcrumb-wrap text-center">
                    <div class="breadcrumb-title">
                        <h2><?= esc($voiture['marque'] . ' ' . $voiture['modele']) ?></h2>
                        <div class="breadcrumb-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Accueil</a></li>
                                    <li class="breadcrumb-item"><a href="<?= base_url('/cars') ?>">Voitures</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= esc($voiture['marque'] . ' ' . $voiture['modele']) ?></li>
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

<!-- Car Details Section -->
<section class="car-details-section pt-120 pb-90">
    <div class="container">
        <div class="row">
            <!-- Gallery Section -->
            <div class="col-lg-8">
                <div class="car-gallery mb-40">
                    <?php
                    $photos = !empty($voiture['photos']) ? explode(',', $voiture['photos']) : [];
                    $photos = array_filter($photos); // Supprimer les valeurs vides
                    ?>

                    <?php if (!empty($photos)): ?>
                        <!-- Main Photo -->
                        <div class="main-photo mb-3" style="border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <img id="mainPhoto" src="<?= base_url($photos[0]) ?>" alt="<?= esc($voiture['marque'] . ' ' . $voiture['modele']) ?>"
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
                            <i class="fas fa-car" style="font-size: 4rem; color: #ddd;"></i>
                            <p style="color: #999; margin-top: 20px;">Aucune photo disponible</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Description et caractéristiques -->
                <div class="car-description" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 30px;">
                    <h3 style="color: #333; margin-bottom: 20px; font-size: 1.8rem;">Caractéristiques détaillées</h3>

                    <div class="row">
                        <!-- Colonne 1 -->
                        <div class="col-md-6 mb-4">
                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Marque</h5>
                                <p style="color: #333; font-size: 1.1rem; font-weight: 500; margin: 0;"><?= esc($voiture['marque']) ?></p>
                            </div>

                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Modèle</h5>
                                <p style="color: #333; font-size: 1.1rem; font-weight: 500; margin: 0;"><?= esc($voiture['modele']) ?></p>
                            </div>

                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Année</h5>
                                <p style="color: #333; font-size: 1.1rem; font-weight: 500; margin: 0;"><?= esc($voiture['annee']) ?></p>
                            </div>

                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Immatriculation</h5>
                                <p style="color: #333; font-size: 1.1rem; font-weight: 500; margin: 0;"><?= esc($voiture['immatriculation']) ?></p>
                            </div>

                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Couleur</h5>
                                <p style="color: #333; font-size: 1.1rem; font-weight: 500; margin: 0;"><?= esc($voiture['couleur'] ?? 'N/A') ?></p>
                            </div>

                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Nombre de places</h5>
                                <p style="color: #333; font-size: 1.1rem; font-weight: 500; margin: 0;"><?= esc($voiture['nombre_places']) ?> places</p>
                            </div>
                        </div>

                        <!-- Colonne 2 -->
                        <div class="col-md-6 mb-4">
                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Type de carburant</h5>
                                <p style="color: #333; font-size: 1.1rem; font-weight: 500; margin: 0;"><?= ucfirst($voiture['type_carburant']) ?></p>
                            </div>

                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Transmission</h5>
                                <p style="color: #333; font-size: 1.1rem; font-weight: 500; margin: 0;"><?= ucfirst($voiture['transmission']) ?></p>
                            </div>

                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Kilométrage</h5>
                                <p style="color: #333; font-size: 1.1rem; font-weight: 500; margin: 0;"><?= number_format($voiture['kilometrage'], 0, ',', ' ') ?> km</p>
                            </div>

                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Numéro de châssis</h5>
                                <p style="color: #333; font-size: 1.1rem; font-weight: 500; margin: 0;"><?= esc($voiture['numero_chassis'] ?? 'N/A') ?></p>
                            </div>

                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Visite technique expire le</h5>
                                <p style="color: #333; font-size: 1.1rem; font-weight: 500; margin: 0;">
                                    <?php if (!empty($voiture['visite_technique_expire_le'])): ?>
                                        <?= date('d/m/Y', strtotime($voiture['visite_technique_expire_le'])) ?>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </p>
                            </div>

                            <div class="detail-item" style="border-left: 3px solid #d29751; padding-left: 15px; margin-bottom: 20px;">
                                <h5 style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Assurance expire le</h5>
                                <p style="color: #333; font-size: 1.1rem; font-weight: 500; margin: 0;">
                                    <?php if (!empty($voiture['assurance_expire_le'])): ?>
                                        <?= date('d/m/Y', strtotime($voiture['assurance_expire_le'])) ?>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($voiture['notes'])): ?>
                        <div class="car-notes mt-4" style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                            <h5 style="color: #333; margin-bottom: 10px;"><i class="fas fa-info-circle"></i> Notes supplémentaires</h5>
                            <p style="color: #666; margin: 0; line-height: 1.6;"><?= nl2br(esc($voiture['notes'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar - Réservation -->
            <div class="col-lg-4">
                <div class="booking-sidebar" style="position: sticky; top: 100px;">
                    <!-- Prix -->
                    <div class="price-card" style="background: linear-gradient(135deg, #d29751 0%, #b67a3d 100%); color: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); margin-bottom: 20px; text-align: center;">
                        <h5 style="color: white; opacity: 0.9; margin-bottom: 10px; font-size: 1rem;">Tarif journalier</h5>
                        <h2 style="color: white; font-size: 2.5rem; font-weight: bold; margin: 0;">
                            <?= number_format($voiture['tarif_journalier'], 0, ',', ' ') ?> <span style="font-size: 1.2rem;">FCFA</span>
                        </h2>
                        <p style="color: white; opacity: 0.9; margin-top: 10px; margin-bottom: 0; font-size: 0.9rem;">Par jour</p>
                    </div>

                    <!-- Caution -->
                    <?php if (!empty($voiture['caution']) && $voiture['caution'] > 0): ?>
                        <div class="caution-card" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 20px; text-align: center;">
                            <h5 style="color: #666; margin-bottom: 10px; font-size: 0.9rem;">Caution requise</h5>
                            <h4 style="color: #d29751; font-size: 1.8rem; font-weight: bold; margin: 0;">
                                <?= number_format($voiture['caution'], 0, ',', ' ') ?> <span style="font-size: 1rem;">FCFA</span>
                            </h4>
                        </div>
                    <?php endif; ?>

                    <!-- Statut -->
                    <div class="status-card" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 20px; text-align: center;">
                        <h5 style="color: #666; margin-bottom: 15px; font-size: 0.9rem;">Statut de disponibilité</h5>
                        <?php
                        $statusColors = [
                            'disponible' => '#28a745',
                            'louee' => '#dc3545',
                            'maintenance' => '#ffc107',
                            'indisponible' => '#6c757d'
                        ];
                        $statusLabels = [
                            'disponible' => 'Disponible',
                            'louee' => 'Louée',
                            'maintenance' => 'En maintenance',
                            'indisponible' => 'Indisponible'
                        ];
                        $statusColor = $statusColors[$voiture['statut']] ?? '#6c757d';
                        $statusLabel = $statusLabels[$voiture['statut']] ?? ucfirst($voiture['statut']);
                        ?>
                        <span style="background: <?= $statusColor ?>; color: white; padding: 10px 20px; border-radius: 20px; font-weight: 500; display: inline-block;">
                            <?= $statusLabel ?>
                        </span>
                    </div>

                    <!-- Bouton de contact/réservation -->
                    <div class="action-buttons">
                        <?php if ($voiture['statut'] === 'disponible'): ?>
                            <?php
                            // Préparer le message WhatsApp avec les détails de la voiture
                            $whatsappMessage = "Bonjour, je suis intéressé(e) par la location de la voiture " .
                                             $voiture['marque'] . " " . $voiture['modele'] . " (" . $voiture['annee'] . "). " .
                                             "Tarif: " . number_format($voiture['tarif_journalier'], 0, ',', ' ') . " FCFA/jour. " .
                                             "Pouvez-vous me donner plus d'informations ?";
                            $whatsappUrl = "https://wa.me/237671387969?text=" . urlencode($whatsappMessage);
                            ?>
                            <a href="<?= $whatsappUrl ?>" target="_blank" class="btn btn-primary w-100"
                               style="background: #25D366; border: none; color: white; padding: 15px; border-radius: 8px; font-size: 1.1rem; font-weight: 500; text-decoration: none; display: block; text-align: center; margin-bottom: 10px;">
                                <i class="fab fa-whatsapp"></i> Réserver via WhatsApp
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled
                                    style="background: #6c757d; border: none; color: white; padding: 15px; border-radius: 8px; font-size: 1.1rem; font-weight: 500; margin-bottom: 10px;">
                                <i class="fas fa-times-circle"></i> Non disponible
                            </button>
                        <?php endif; ?>

                        <a href="<?= base_url('/cars') ?>" class="btn btn-outline w-100"
                           style="background: white; border: 2px solid #d29751; color: #d29751; padding: 15px; border-radius: 8px; font-size: 1.1rem; font-weight: 500; text-decoration: none; display: block; text-align: center;">
                            <i class="fas fa-arrow-left"></i> Retour aux voitures
                        </a>
                    </div>

                    <!-- Features quick view -->
                    <div class="quick-features mt-4" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        <h5 style="color: #333; margin-bottom: 20px; font-size: 1rem;">Caractéristiques principales</h5>
                        <div class="feature-item" style="display: flex; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                            <i class="fas fa-users" style="color: #d29751; font-size: 1.3rem; width: 30px;"></i>
                            <span style="color: #666; margin-left: 15px;"><?= esc($voiture['nombre_places']) ?> places</span>
                        </div>
                        <div class="feature-item" style="display: flex; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                            <i class="fas fa-cog" style="color: #d29751; font-size: 1.3rem; width: 30px;"></i>
                            <span style="color: #666; margin-left: 15px;"><?= ucfirst($voiture['transmission']) ?></span>
                        </div>
                        <div class="feature-item" style="display: flex; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                            <i class="fas fa-gas-pump" style="color: #d29751; font-size: 1.3rem; width: 30px;"></i>
                            <span style="color: #666; margin-left: 15px;"><?= ucfirst($voiture['type_carburant']) ?></span>
                        </div>
                        <div class="feature-item" style="display: flex; align-items: center;">
                            <i class="fas fa-tachometer-alt" style="color: #d29751; font-size: 1.3rem; width: 30px;"></i>
                            <span style="color: #666; margin-left: 15px;"><?= number_format($voiture['kilometrage'], 0, ',', ' ') ?> km</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Car Details Section End -->

<script>
// Changer la photo principale
function changeMainPhoto(photoUrl) {
    document.getElementById('mainPhoto').src = photoUrl;

    // Mettre à jour les bordures des thumbnails
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

@media (max-width: 991px) {
    .booking-sidebar {
        position: static !important;
        margin-top: 30px;
    }
}
</style>

<?= $this->endSection() ?>
