<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= isset($title) ? $title : 'T-Lodge - Connexion Admin' ?></title>
    <meta name="description" content="Connexion administrateur T-Lodge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/frontend/images/favicon.ico') ?>">

    <link rel="stylesheet" href="<?= base_url('template/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/css/animate.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/css/fontawesome-all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/css/default.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/css/responsive.css') ?>">

    <style>
        /* Reset global */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('<?= base_url('assets/admin/images/bg_login.jpg') ?>') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Overlay semi-transparent pour améliorer la lisibilité */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: -1;
        }

        /* Navbar simplifiée */
        .auth-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 20px 0;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .auth-navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .auth-logo img {
            height: 50px;
            width: auto;
        }

        /* Sélecteur de langue */
        .language-selector {
            position: relative;
        }

        .language-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            color: #495057;
        }

        .language-btn:hover {
            background: #e9ecef;
            border-color: #d29751;
        }

        .language-btn img {
            width: 24px;
            height: 18px;
            border-radius: 2px;
        }

        .language-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: none;
            min-width: 160px;
            z-index: 1000;
        }

        .language-dropdown.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .language-dropdown a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: #495057;
            text-decoration: none;
            transition: background 0.2s ease;
        }

        .language-dropdown a:first-child {
            border-radius: 8px 8px 0 0;
        }

        .language-dropdown a:last-child {
            border-radius: 0 0 8px 8px;
        }

        .language-dropdown a:hover {
            background: #f8f9fa;
        }

        .language-dropdown img {
            width: 24px;
            height: 18px;
            border-radius: 2px;
        }

        /* Contenu principal */
        .auth-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        /* Footer simplifié */
        .auth-footer {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 -2px 20px rgba(0, 0, 0, 0.1);
        }

        .auth-footer p {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-logo img {
                height: 40px;
            }

            .language-btn {
                padding: 8px 15px;
                font-size: 14px;
            }

            .auth-footer p {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Header simplifié avec logo et langue -->
    <header class="auth-header">
        <div class="auth-navbar">
            <div class="auth-logo">
                <a href="<?= base_url('/home') ?>">
                    <img src="<?= base_url('assets/frontend/images/logo/logo_t_lodge.png') ?>" alt="T-Lodge">
                </a>
            </div>

            <div class="language-selector">
                <button class="language-btn" onclick="toggleLanguageDropdown()">
                    <?php $currentLang = session()->get('language') ?? 'fr'; ?>
                    <?php if($currentLang == 'fr'): ?>
                        <img src="<?= base_url('assets/frontend/images/flags/fr.svg') ?>" alt="Français">
                        <span>FR</span>
                    <?php else: ?>
                        <img src="<?= base_url('assets/frontend/images/flags/en.svg') ?>" alt="English">
                        <span>EN</span>
                    <?php endif; ?>
                    <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                </button>

                <div class="language-dropdown" id="languageDropdown">
                    <a href="<?= base_url('language/switch/en') ?>">
                        <img src="<?= base_url('assets/frontend/images/flags/en.svg') ?>" alt="English">
                        <span>English</span>
                    </a>
                    <a href="<?= base_url('language/switch/fr') ?>">
                        <img src="<?= base_url('assets/frontend/images/flags/fr.svg') ?>" alt="Français">
                        <span>Français</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="auth-content">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer simplifié -->
    <footer class="auth-footer">
        <p>&copy; <?= date('Y') ?> T-Lodge. All Rights Reserved.</p>
    </footer>

    <!-- Scripts -->
    <script src="<?= base_url('assets/frontend/js/vendor/jquery-1.12.4.min.js') ?>"></script>
    <script src="<?= base_url('assets/frontend/js/bootstrap.min.js') ?>"></script>

    <script>
        // Toggle language dropdown
        function toggleLanguageDropdown() {
            const dropdown = document.getElementById('languageDropdown');
            dropdown.classList.toggle('show');
        }

        // Fermer le dropdown si on clique en dehors
        document.addEventListener('click', function(event) {
            const selector = document.querySelector('.language-selector');
            const dropdown = document.getElementById('languageDropdown');

            if (!selector.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'all 0.3s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 300);
            });
        }, 5000);
    </script>
</body>
</html>
