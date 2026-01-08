<?php
$session = session();
// Permission helper is loaded globally via Autoload.php
?>

<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="<?= base_url('/admin') ?>" class="b-brand">
                <img src="<?= base_url('assets/frontend/images/logo/logo_t_lodge.png') ?>" alt="Logo" class="logo logo-lg" width="200px" height="60px" />
                <img src="<?= base_url('assets/admin/images/logo-abbr.png') ?>" alt="Logo" class="logo logo-sm" />
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <!-- Navigation Section -->
                <li class="nxl-item nxl-caption">
                    <label>Navigation</label>
                </li>
                <?php if (hasPermission('view_dashboard')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Tableau de Bord</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Gestion des Appartements Section -->
                <?php if (hasAnyPermission(['view_appartements', 'view_reservations', 'view_locataires', 'view_paiements', 'view_contrats', 'view_paiements_mensuels', 'view_factures_eau'])): ?>
                <li class="nxl-item nxl-caption">
                    <label>Gestion des Appartements</label>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_appartements')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/appartements') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-home"></i></span>
                        <span class="nxl-mtext">Appartements</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_appartements')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/calendrier') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-calendar"></i></span>
                        <span class="nxl-mtext">Calendrier Occupations</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_reservations')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/reservations') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-book-open"></i></span>
                        <span class="nxl-mtext">Réservations</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_locataires')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/locataires') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-users"></i></span>
                        <span class="nxl-mtext">Locataires</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_paiements')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/paiements') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-credit-card"></i></span>
                        <span class="nxl-mtext">Paiements</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_contrats')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/contrats') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-file-text"></i></span>
                        <span class="nxl-mtext">Contrats Long Terme</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_paiements_mensuels')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/paiements-mensuels/dashboard') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-dollar-sign"></i></span>
                        <span class="nxl-mtext">Paiements Mensuels</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_factures_eau')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/factures-eau') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-droplet"></i></span>
                        <span class="nxl-mtext">Factures d'Eau</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Location de Voitures Section -->
                <?php if (hasAnyPermission(['view_voitures', 'view_locations_voitures'])): ?>
                <li class="nxl-item nxl-caption">
                    <label>Location de Voitures</label>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_voitures')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/voitures') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-truck"></i></span>
                        <span class="nxl-mtext">Parc Automobile</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_locations_voitures')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/locations-voitures') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-key"></i></span>
                        <span class="nxl-mtext">Locations Voitures</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Gestion du Stock Section -->
                <?php if (hasAnyPermission(['view_stock_dashboard', 'view_produits', 'view_approvisionnements', 'view_sorties', 'view_inventaires', 'view_rapports_stock'])): ?>
                <li class="nxl-item nxl-caption">
                    <label>Gestion du Stock</label>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_stock_dashboard')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/stock') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-package"></i></span>
                        <span class="nxl-mtext">Tableau de Bord Stock</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_produits')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/stock/produits') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-box"></i></span>
                        <span class="nxl-mtext">Produits</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_approvisionnements')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/stock/approvisionnements') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-truck"></i></span>
                        <span class="nxl-mtext">Approvisionnements</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_sorties')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/stock/sorties') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-shopping-cart"></i></span>
                        <span class="nxl-mtext">Sorties</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_inventaires')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/stock/inventaires') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-clipboard"></i></span>
                        <span class="nxl-mtext">Inventaires</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_rapports_stock')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/stock/rapports') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-bar-chart"></i></span>
                        <span class="nxl-mtext">Rapports Stock</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Rapports & Analyse Section -->
                <?php if (hasAnyPermission(['view_rapports', 'view_analytics', 'view_contrats_location'])): ?>
                <li class="nxl-item nxl-caption">
                    <label>Rapports & Analyse</label>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_rapports')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/rapports') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-file-text"></i></span>
                        <span class="nxl-mtext">Rapports</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_analytics')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/analytics') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-bar-chart-2"></i></span>
                        <span class="nxl-mtext">Analytics</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_contrats_location')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/contrats-location') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-file-plus"></i></span>
                        <span class="nxl-mtext">Contrats de Location</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Système Section -->
                <?php if (hasAnyPermission(['view_utilisateurs', 'view_roles', 'view_parametres'])): ?>
                <li class="nxl-item nxl-caption">
                    <label>Système</label>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_utilisateurs')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/utilisateurs') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-user-check"></i></span>
                        <span class="nxl-mtext">Utilisateurs</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_roles')): ?>
                <li class="nxl-item">
                    <a href="<?= base_url('/admin/roles') ?>" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-shield"></i></span>
                        <span class="nxl-mtext">Rôles & Permissions</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (hasPermission('view_parametres')): ?>
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
