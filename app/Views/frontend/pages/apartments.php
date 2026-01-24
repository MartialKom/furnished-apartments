<?= $this->extend('frontend/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb Area -->
<section class="breadcrumb-area d-flex align-items-center" style="background-image:url(<?= base_url('assets/frontend/images/slider/slider1.png') ?>); background-size: cover; background-position: center;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-12 col-lg-12">
                <div class="breadcrumb-wrap text-center">
                    <div class="breadcrumb-title">
                        <h2><?= $title ?? 'Nos Appartements' ?></h2>
                        <div class="breadcrumb-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Accueil</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Appartements</li>
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

<!-- Apartments Section -->
<section class="apartments-section pt-120 pb-90">
    <div class="container">
        <?php if (!empty($appartements)): ?>
            <div class="row">
                <?php foreach ($appartements as $appartement): ?>
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="apartment-card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden;">
                            <!-- Image -->
                            <div class="apartment-img" style="position: relative;">
                                <?php
                                $photos = !empty($appartement['photos']) ? explode(',', $appartement['photos']) : [];
                                $photos = array_filter($photos);
                                $firstPhoto = !empty($photos) ? base_url($photos[0]) : base_url('assets/frontend/images/default-apartment.jpg');
                                ?>
                                <img src="<?= $firstPhoto ?>" alt="<?= esc($appartement['adresse']) ?>" style="width: 100%; height: 250px; object-fit: cover;">
                                <div class="apartment-price" style="position: absolute; bottom: 15px; right: 15px; background: #d29751; color: white; padding: 10px 20px; border-radius: 5px; font-weight: bold;">
                                    <span><?= number_format($appartement['tarifs'], 0, ',', ' ') ?> FCFA/Nuit</span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="apartment-content" style="padding: 20px;">
                                <h4 class="apartment-title" style="margin-bottom: 15px; font-size: 1.4rem; font-weight: bold;">
                                    <a href="<?= base_url('apartments/' . $appartement['id']) ?>" style="color: #333; text-decoration: none;">
                                        <?= esc($appartement['adresse']) ?>
                                    </a>
                                </h4>

                                <p class="text-muted mb-2" style="font-size: 0.9rem;">
                                    <i class="fas fa-home"></i> Appartement meuble
                                </p>

                                <!-- Apartment Features -->
                                <div class="apartment-features" style="margin: 15px 0;">
                                    <div class="row">
                                        <?php
                                        $equipements = !empty($appartement['equipements']) ? explode(',', $appartement['equipements']) : [];
                                        $equipements = array_map('trim', $equipements);
                                        $equipements = array_filter($equipements);
                                        $displayEquipements = array_slice($equipements, 0, 4);
                                        ?>
                                        <?php if (in_array('Wifi', $equipements) || in_array('WiFi', $equipements) || in_array('wifi', $equipements)): ?>
                                            <div class="col-6 mb-2">
                                                <span style="display: flex; align-items: center; font-size: 0.9rem;">
                                                    <i class="fas fa-wifi" style="color: #d29751; margin-right: 8px;"></i>
                                                    WiFi
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (in_array('Climatisation', $equipements) || in_array('climatisation', $equipements) || in_array('Clim', $equipements)): ?>
                                            <div class="col-6 mb-2">
                                                <span style="display: flex; align-items: center; font-size: 0.9rem;">
                                                    <i class="fas fa-snowflake" style="color: #d29751; margin-right: 8px;"></i>
                                                    Climatisation
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (in_array('Cuisine', $equipements) || in_array('cuisine', $equipements)): ?>
                                            <div class="col-6 mb-2">
                                                <span style="display: flex; align-items: center; font-size: 0.9rem;">
                                                    <i class="fas fa-utensils" style="color: #d29751; margin-right: 8px;"></i>
                                                    Cuisine
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (in_array('Parking', $equipements) || in_array('parking', $equipements)): ?>
                                            <div class="col-6 mb-2">
                                                <span style="display: flex; align-items: center; font-size: 0.9rem;">
                                                    <i class="fas fa-parking" style="color: #d29751; margin-right: 8px;"></i>
                                                    Parking
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (in_array('TV', $equipements) || in_array('tv', $equipements) || in_array('Television', $equipements)): ?>
                                            <div class="col-6 mb-2">
                                                <span style="display: flex; align-items: center; font-size: 0.9rem;">
                                                    <i class="fas fa-tv" style="color: #d29751; margin-right: 8px;"></i>
                                                    TV
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (count($displayEquipements) > 0 && empty(array_intersect(['Wifi', 'WiFi', 'wifi', 'Climatisation', 'climatisation', 'Clim', 'Cuisine', 'cuisine', 'Parking', 'parking', 'TV', 'tv', 'Television'], $equipements))): ?>
                                            <?php foreach (array_slice($displayEquipements, 0, 4) as $equip): ?>
                                                <div class="col-6 mb-2">
                                                    <span style="display: flex; align-items: center; font-size: 0.9rem;">
                                                        <i class="fas fa-check-circle" style="color: #d29751; margin-right: 8px;"></i>
                                                        <?= esc($equip) ?>
                                                    </span>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- View Details Button -->
                                <div class="apartment-btn mt-3">
                                    <a href="<?= base_url('apartments/' . $appartement['id']) ?>" class="btn w-100" style="background: #d29751; color: white; padding: 10px; border-radius: 5px; text-decoration: none; display: block; text-align: center;">
                                        <i class="fas fa-eye"></i> Voir les details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5" style="background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-home" style="font-size: 4rem; color: #d29751; margin-bottom: 20px;"></i>
                        <h3 style="color: #666;">Aucun appartement disponible pour le moment</h3>
                        <p style="color: #999;">Verifiez a nouveau plus tard ou contactez-nous pour plus d'informations.</p>
                        <a href="<?= base_url('/#contact') ?>" class="btn mt-3" style="background: #d29751; color: white; padding: 12px 30px; border-radius: 5px; text-decoration: none;">
                            Nous contacter
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<!-- Apartments Section End -->

<style>
.apartment-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.apartment-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
}

.apartment-title a:hover {
    color: #d29751 !important;
}

.btn:hover {
    opacity: 0.9;
    transform: scale(1.02);
    transition: all 0.3s ease;
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
</style>

<?= $this->endSection() ?>
