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
                    <div class="col-lg-3 col-md-6 mb-30">
                        <div class="apartment-card">
                            <!-- Image -->
                            <div class="apartment-img">
                                <?php
                                $photos = !empty($appartement['photos']) ? explode(',', $appartement['photos']) : [];
                                $photos = array_filter($photos); // Supprimer les valeurs vides
                                $firstPhoto = !empty($photos) ? base_url($photos[0]) : base_url('assets/frontend/images/default-apartment.jpg');
                                ?>
                                <img src="<?= $firstPhoto ?>" alt="<?= esc($appartement['adresse']) ?>" style="width: 100%; height: 250px; object-fit: cover;">
                                <div class="apartment-price">
                                    <span><?= number_format($appartement['tarifs'], 0, ',', ' ') ?> FCFA/Nuit</span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="apartment-content">
                                <h4 class="apartment-title">
                                    <a href="<?= base_url('apartments/' . $appartement['id']) ?>">
                                        <?= esc($appartement['adresse']) ?>
                                    </a>
                                </h4>

                                <!-- Description (Extract from equipements or create short text) -->
                                <p class="apartment-desc">
                                    <?php
                                    $equipements = !empty($appartement['equipements']) ? explode(',', $appartement['equipements']) : [];
                                    $equipements = array_map('trim', $equipements); // Supprimer les espaces
                                    $equipements = array_filter($equipements); // Supprimer les valeurs vides
                                    if (!empty($equipements)) {
                                        echo 'Équipé de : ' . implode(', ', array_slice($equipements, 0, 3));
                                        if (count($equipements) > 3) {
                                            echo '...';
                                        }
                                    } else {
                                        echo 'Appartement meublé moderne et confortable';
                                    }
                                    ?>
                                </p>

                                <!-- Equipements Icons -->
                                <?php if (!empty($equipements)): ?>
                                    <ul class="apartment-features">
                                        <?php foreach (array_slice($equipements, 0, 3) as $equip): ?>
                                            <li>
                                                <i class="fas fa-check-circle"></i>
                                                <span><?= esc($equip) ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>

                                <!-- Reserve Button -->
                                <div class="apartment-btn">
                                    <button type="button" class="btn btn-reserve" onclick="openReservationModal(<?= $appartement['id'] ?>, '<?= esc($appartement['adresse'], 'js') ?>', <?= $appartement['tarifs'] ?>)">
                                        <i class="fas fa-calendar-check"></i> Réserver
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <h4>Aucun appartement disponible pour le moment</h4>
                        <p>Tous nos appartements sont actuellement occupés. Veuillez réessayer ultérieurement ou nous contacter pour plus d'informations.</p>
                        <a href="<?= base_url('contact') ?>" class="btn btn-primary mt-3">Nous Contacter</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Reservation Modal -->
<div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #d29751 0%, #b87d3f 100%); color: white;">
                <h5 class="modal-title" id="reservationModalLabel">
                    <i class="fas fa-calendar-alt"></i> Réserver un Appartement
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Apartment Info Display -->
                <div class="selected-apartment-info mb-4 p-3" style="background: #f8f9fa; border-left: 4px solid #d29751; border-radius: 5px;">
                    <h6 class="mb-2" style="color: #d29751;">
                        <i class="fas fa-home"></i> Appartement sélectionné
                    </h6>
                    <p class="mb-1" id="selected-apartment-name" style="font-weight: 600;"></p>
                    <p class="mb-0" style="color: #28a745; font-size: 18px; font-weight: bold;">
                        <span id="selected-apartment-price"></span> FCFA/Nuit
                    </p>
                </div>

                <!-- Reservation Form -->
                <?= view('frontend/reservation/form_modal') ?>
            </div>
        </div>
    </div>
</div>

<style>
    /* Apartment Card Styles */
    .apartment-card {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .apartment-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        transform: translateY(-5px);
    }

    .apartment-img {
        position: relative;
        overflow: hidden;
    }

    .apartment-img img {
        transition: transform 0.3s ease;
    }

    .apartment-card:hover .apartment-img img {
        transform: scale(1.1);
    }

    .apartment-price {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #d29751;
        color: white;
        padding: 8px 15px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .apartment-content {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .apartment-title {
        font-size: 18px;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .apartment-title a {
        color: #333;
        text-decoration: none;
        transition: color 0.3s;
    }

    .apartment-title a:hover {
        color: #d29751;
    }

    .apartment-desc {
        color: #666;
        font-size: 14px;
        margin-bottom: 15px;
        line-height: 1.6;
    }

    .apartment-features {
        list-style: none;
        padding: 0;
        margin-bottom: 20px;
    }

    .apartment-features li {
        font-size: 13px;
        color: #555;
        margin-bottom: 8px;
    }

    .apartment-features i {
        color: #d29751;
        margin-right: 8px;
    }

    .apartment-btn {
        margin-top: auto;
    }

    .btn-reserve {
        width: 100%;
        background: #d29751;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 5px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-reserve:hover {
        background: #b87d3f;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(210, 151, 81, 0.3);
    }

    .btn-reserve i {
        margin-right: 8px;
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

<script>
    function openReservationModal(appartementId, appartementName, price) {
        // Set apartment info in modal
        document.getElementById('selected-apartment-name').textContent = appartementName;
        document.getElementById('selected-apartment-price').textContent = new Intl.NumberFormat('fr-FR').format(price);

        // Call the modal form's data setter function
        setModalAppartementData(appartementId, price);

        // Open modal
        const modal = new bootstrap.Modal(document.getElementById('reservationModal'));
        modal.show();
    }
</script>

<?= $this->endSection() ?>
