<?= $this->extend('frontend/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb Area -->
<section class="breadcrumb-area d-flex align-items-center" style="background-image:url(<?= base_url('assets/frontend/images/slider/slider4.jpg') ?>); background-size: cover; background-position: center;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-12 col-lg-12">
                <div class="breadcrumb-wrap text-center">
                    <div class="breadcrumb-title">
                        <h2><?= $title ?? 'Location de Voitures' ?></h2>
                        <div class="breadcrumb-wrap">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Accueil</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Voitures</li>
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

<!-- Cars Section -->
<section class="cars-section pt-120 pb-90">
    <div class="container">
        <?php if (!empty($voitures)): ?>
            <div class="row">
                <?php foreach ($voitures as $voiture): ?>
                    <div class="col-lg-4 col-md-6 mb-30">
                        <div class="car-card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden;">
                            <!-- Image -->
                            <div class="car-img" style="position: relative;">
                                <?php
                                $photos = !empty($voiture['photos']) ? explode(',', $voiture['photos']) : [];
                                $photos = array_filter($photos); // Supprimer les valeurs vides
                                $firstPhoto = !empty($photos) ? base_url($photos[0]) : base_url('assets/frontend/images/default-car.jpg');
                                ?>
                                <img src="<?= $firstPhoto ?>" alt="<?= esc($voiture['marque'] . ' ' . $voiture['modele']) ?>" style="width: 100%; height: 250px; object-fit: cover;">
                                <div class="car-price" style="position: absolute; bottom: 15px; right: 15px; background: #d29751; color: white; padding: 10px 20px; border-radius: 5px; font-weight: bold;">
                                    <span><?= number_format($voiture['tarif_journalier'], 0, ',', ' ') ?> FCFA/Jour</span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="car-content" style="padding: 20px;">
                                <h4 class="car-title" style="margin-bottom: 15px; font-size: 1.4rem; font-weight: bold;">
                                    <a href="<?= base_url('cars/' . $voiture['id']) ?>" style="color: #333; text-decoration: none;">
                                        <?= esc($voiture['marque']) ?> <?= esc($voiture['modele']) ?>
                                    </a>
                                </h4>

                                <p class="text-muted mb-2" style="font-size: 0.9rem;">
                                    <i class="fas fa-calendar-alt"></i> Année: <?= esc($voiture['annee']) ?>
                                </p>

                                <!-- Car Details -->
                                <div class="car-features" style="margin: 15px 0;">
                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <span style="display: flex; align-items: center; font-size: 0.9rem;">
                                                <i class="fas fa-users" style="color: #d29751; margin-right: 8px;"></i>
                                                <?= esc($voiture['nombre_places']) ?> places
                                            </span>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <span style="display: flex; align-items: center; font-size: 0.9rem;">
                                                <i class="fas fa-cog" style="color: #d29751; margin-right: 8px;"></i>
                                                <?= ucfirst($voiture['transmission']) ?>
                                            </span>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <span style="display: flex; align-items: center; font-size: 0.9rem;">
                                                <i class="fas fa-gas-pump" style="color: #d29751; margin-right: 8px;"></i>
                                                <?= ucfirst($voiture['type_carburant']) ?>
                                            </span>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <span style="display: flex; align-items: center; font-size: 0.9rem;">
                                                <i class="fas fa-palette" style="color: #d29751; margin-right: 8px;"></i>
                                                <?= esc($voiture['couleur'] ?? 'N/A') ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reserve Button -->
                                <div class="car-btn mt-3">
                                    <a href="<?= base_url('cars/' . $voiture['id']) ?>" class="btn w-100" style="background: #d29751; color: white; padding: 10px; border-radius: 5px; text-decoration: none; display: block; text-align: center;">
                                        <i class="fas fa-car"></i> Voir les détails
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
                        <i class="fas fa-car" style="font-size: 4rem; color: #d29751; margin-bottom: 20px;"></i>
                        <h3 style="color: #666;">Aucune voiture disponible pour le moment</h3>
                        <p style="color: #999;">Vérifiez à nouveau plus tard ou contactez-nous pour plus d'informations.</p>
                        <a href="<?= base_url('/contact') ?>" class="btn mt-3" style="background: #d29751; color: white; padding: 12px 30px; border-radius: 5px; text-decoration: none;">
                            Nous contacter
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<!-- Cars Section End -->

<style>
.car-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.car-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
}

.car-title a:hover {
    color: #d29751 !important;
}

.btn:hover {
    opacity: 0.9;
    transform: scale(1.02);
    transition: all 0.3s ease;
}
</style>

<?= $this->endSection() ?>
