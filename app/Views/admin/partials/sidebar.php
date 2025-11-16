<?php
$session = session();
$userRole = $session->get('user_role');
?>

<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="<?= base_url('/admin') ?>" class="b-brand">
                <img src="<?= base_url('assets/frontend/images/logo/nsenou.png') ?>" alt="Logo" class="logo logo-lg" />
                <img src="<?= base_url('assets/admin/images/logo-abbr.png') ?>" alt="Logo" class="logo logo-sm" />
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Navigation</label>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>
                
                <li class="nxl-item nxl-caption">
                    <label>Gestion des Appartements</label>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/appartements') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-home"></i></span>
                        <span class="nxl-mtext">Appartements</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/reservations') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-calendar"></i></span>
                        <span class="nxl-mtext">Réservations</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/locataires') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-users"></i></span>
                        <span class="nxl-mtext">Locataires</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/paiements') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-credit-card"></i></span>
                        <span class="nxl-mtext">Paiements</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/contrats') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-file-text"></i></span>
                        <span class="nxl-mtext">Contrats Long Terme</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/paiements-mensuels/dashboard') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-dollar-sign"></i></span>
                        <span class="nxl-mtext">Paiements Mensuels</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/factures-eau') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-droplet"></i></span>
                        <span class="nxl-mtext">Factures d'Eau</span>
                    </a>
                </li>

                <li class="nxl-item nxl-caption">
                    <label>Location de Voitures</label>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/voitures') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-truck"></i></span>
                        <span class="nxl-mtext">Parc Automobile</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/locations-voitures') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-key"></i></span>
                        <span class="nxl-mtext">Locations Voitures</span>
                    </a>
                </li>

                <li class="nxl-item nxl-caption">
                    <label>Gestion du Stock</label>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/stock') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-package"></i></span>
                        <span class="nxl-mtext">Tableau de Bord Stock</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/stock/produits') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-box"></i></span>
                        <span class="nxl-mtext">Produits</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/stock/approvisionnements') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-truck"></i></span>
                        <span class="nxl-mtext">Approvisionnements</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/stock/sorties') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-shopping-cart"></i></span>
                        <span class="nxl-mtext">Sorties</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/stock/inventaires') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-clipboard"></i></span>
                        <span class="nxl-mtext">Inventaires</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/stock/rapports') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-bar-chart"></i></span>
                        <span class="nxl-mtext">Rapports Stock</span>
                    </a>
                </li>

                <li class="nxl-item nxl-caption">
                    <label>Rapports & Analyse</label>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/rapports') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-file-text"></i></span>
                        <span class="nxl-mtext">Rapports</span>
                    </a>
                </li>
                <?php if ($userRole === 'admin'): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/analytics') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-bar-chart-2"></i></span>
                        <span class="nxl-mtext">Analytics</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($userRole === 'admin'): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/contrats-location') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-file-plus"></i></span>
                        <span class="nxl-mtext">Contrats de Location</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($userRole === 'admin'): ?>
                <li class="nxl-item nxl-caption">
                    <label>Système</label>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/utilisateurs') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-user-check"></i></span>
                        <span class="nxl-mtext">Utilisateurs</span>
                    </a>
                </li>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/settings') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-settings"></i></span>
                        <span class="nxl-mtext">Paramètres</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>