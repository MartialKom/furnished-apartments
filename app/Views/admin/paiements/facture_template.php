<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture - <?= $numero_facture ?></title>
    <style>
        @page {
            size: A4;
            margin: 10mm 15mm;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #d29751;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .company-info {
            flex: 1;
        }

        .company-logo {
            max-width: 150px;
            max-height: 80px;
            margin-bottom: 15px;
        }

        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #d29751;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .company-details {
            font-size: 11px;
            color: #666;
            line-height: 1.6;
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #d29751;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .invoice-number {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .invoice-date {
            font-size: 12px;
            color: #666;
        }

        .client-section {
            background: #f8f9fa;
            padding: 12px;
            border-left: 3px solid #d29751;
            margin-bottom: 15px;
        }

        .client-section h3 {
            margin: 0 0 12px 0;
            font-size: 14px;
            font-weight: bold;
            color: #d29751;
            text-transform: uppercase;
        }

        .client-details {
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .reservation-details {
            margin-bottom: 15px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 12px;
        }

        .reservation-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            border-bottom: 2px solid #d29751;
            padding-bottom: 8px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .detail-item {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .detail-label {
            font-weight: bold;
            color: #666;
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #333;
            font-size: 13px;
            font-weight: 600;
        }

        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .payment-table thead {
            background: linear-gradient(135deg, #d29751 0%, #b8834a 100%);
            color: white;
        }

        .payment-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }

        .payment-table th:last-child {
            text-align: right;
        }

        .payment-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }

        .payment-table tbody tr:hover {
            background: #f8f9fa;
        }

        .amount-summary {
            margin: 15px 0;
            padding: 12px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 13px;
        }

        .summary-row:last-child {
            border-top: 2px solid #d29751;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 18px;
            font-weight: bold;
            color: #d29751;
        }

        .summary-label {
            font-weight: 600;
            color: #333;
        }

        .summary-value {
            font-weight: bold;
            color: #333;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 20px;
        }

        .status-paye {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-partiel {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
        }

        .status-impaye {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .footer {
            margin-top: 20px;
            padding-top: 12px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .notes-section {
            margin: 20px 0;
            padding: 15px;
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 5px;
        }

        .notes-section h4 {
            margin: 0 0 10px 0;
            color: #856404;
            font-size: 13px;
            font-weight: bold;
        }

        .notes-section p {
            margin: 0;
            color: #856404;
            font-size: 12px;
        }

        @media print {
            .invoice-container {
                box-shadow: none;
                padding: 0;
            }

            body {
                margin: 0;
            }

            @page {
                size: A4;
                margin: 10mm 15mm;
            }

            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mt-20 {
            margin-top: 20px;
        }
    </style>
    <?php if (!isset($is_pdf_generation) || !$is_pdf_generation): ?>
    <script>
        // Déclencher l'impression automatiquement au chargement de la page
        window.addEventListener('load', function() {
            // Petit délai pour s'assurer que la page est complètement chargée
            setTimeout(function() {
                window.print();
            }, 500);
        });

        // Optionnel: fermer la fenêtre après l'impression (si ouverte en popup)
        window.addEventListener('afterprint', function() {
            // Vérifier si c'est une fenêtre popup
            if (window.opener) {
                window.close();
            }
        });
    </script>
    <?php endif; ?>
</head>
<body>
    <div class="invoice-container">
        <!-- En-tête -->
        <div class="header">
            <div class="company-info">
                <?php if (!empty($structure_logo) && file_exists(FCPATH . $structure_logo)): ?>
                    <img src="<?= base_url($structure_logo) ?>" alt="Logo" class="company-logo">
                <?php endif; ?>
                <div class="company-name"><?= esc($structure_nom) ?></div>
                <div class="company-details">
                    <?= esc($structure_adresse) ?><br>
                    Tél: <?= esc($structure_telephone) ?><br>
                    Email: <?= esc($structure_email) ?>
                    <?php if (!empty($structure_rc)): ?>
                        <br>RC: <?= esc($structure_rc) ?>
                    <?php endif; ?>
                    <?php if (!empty($structure_nif)): ?>
                        | NIF: <?= esc($structure_nif) ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="invoice-info">
                <div class="invoice-title">FACTURE</div>
                <div class="invoice-number">N° <?= esc($numero_facture) ?></div>
                <div class="invoice-date">Date: <?= date('d/m/Y', strtotime($date_emission)) ?></div>
                <div class="invoice-date">Heure: <?= date('H:i', strtotime($date_emission)) ?></div>
            </div>
        </div>

        <!-- Informations Client -->
        <div class="client-section">
            <h3>Informations Client</h3>
            <div class="client-details">
                <strong>Nom:</strong> <?= esc($client_nom) ?><br>
                <?php if (!empty($client_adresse)): ?>
                    <strong>Adresse:</strong> <?= esc($client_adresse) ?><br>
                <?php endif; ?>
                <strong>Téléphone:</strong> <?= esc($client_telephone) ?><br>
                <strong>Email:</strong> <?= esc($client_email) ?>
            </div>
        </div>

        <!-- Détails de la Réservation -->
        <div class="reservation-details">
            <div class="reservation-title">Détails de la Réservation</div>
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Bien immobilier</span>
                    <span class="detail-value"><?= esc($bien_adresse) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date d'arrivée</span>
                    <span class="detail-value"><?= date('d/m/Y', strtotime($date_arrivee)) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date de départ</span>
                    <span class="detail-value"><?= date('d/m/Y', strtotime($date_depart)) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Durée du séjour</span>
                    <span class="detail-value"><?= $duree_sejour ?> jour(s)</span>
                </div>
            </div>
        </div>

        <!-- Détails Paiement -->
        <table class="payment-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: center;">Quantité</th>
                    <th style="text-align: right;">Montant (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Location appartement meublé</strong><br>
                        <small style="color: #666;"><?= esc($bien_adresse) ?></small>
                    </td>
                    <td style="text-align: center;"><?= $duree_sejour ?> jour(s)</td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($montant_total, 0, ',', ' ') ?></td>
                </tr>
                <?php if (!empty($reduction) && $reduction > 0): ?>
                <tr style="background: #fff3cd;">
                    <td colspan="2"><strong>Réduction accordée</strong></td>
                    <td style="text-align: right; font-weight: bold; color: #28a745;">-<?= number_format($reduction, 0, ',', ' ') ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Résumé des Montants -->
        <div class="amount-summary">
            <?php if (!empty($reduction) && $reduction > 0): ?>
            <div class="summary-row">
                <span class="summary-label">Sous-total:</span>
                <span class="summary-value"><?= number_format($montant_total, 0, ',', ' ') ?> FCFA</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Réduction:</span>
                <span class="summary-value" style="color: #28a745;">-<?= number_format($reduction, 0, ',', ' ') ?> FCFA</span>
            </div>
            <?php endif; ?>
            <div class="summary-row">
                <span class="summary-label">MONTANT TOTAL:</span>
                <span class="summary-value"><?= number_format($montant_total - ($reduction ?? 0), 0, ',', ' ') ?> FCFA</span>
            </div>
            <div class="summary-row" style="border-top: 1px solid #dee2e6; margin-top: 10px; padding-top: 10px;">
                <span class="summary-label">Montant payé:</span>
                <span class="summary-value" style="color: #28a745;"><?= number_format($montant_paye, 0, ',', ' ') ?> FCFA</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">RESTE À PAYER:</span>
                <span class="summary-value" style="<?= $reste_a_payer > 0 ? 'color: #dc3545;' : 'color: #28a745;' ?>">
                    <?= number_format($reste_a_payer, 0, ',', ' ') ?> FCFA
                </span>
            </div>
        </div>


        <!-- Pied de page -->
        <div class="footer">
            <p style="font-size: 13px; font-weight: bold; color: #d29751; margin-bottom: 10px;">Merci pour votre confiance !</p>
            <p style="margin-bottom: 5px;">Facture générée automatiquement le <?= date('d/m/Y à H:i') ?></p>
            <p style="margin-bottom: 5px;">Pour toute question concernant cette facture, veuillez nous contacter :</p>
            <p><strong><?= esc($structure_telephone) ?></strong> | <strong><?= esc($structure_email) ?></strong></p>
            <p style="margin-top: 15px; font-size: 10px; color: #999;">
                Document généré par le système de gestion T-Lodge
            </p>
        </div>
    </div>
</body>
</html>
