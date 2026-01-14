<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture d'Eau - <?= $numero_facture ?></title>
    <style>
        @page {
            size: A4;
            margin: 8mm 12mm;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 12px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0dcaf0;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .company-info {
            flex: 1;
        }

        .company-logo {
            max-width: 120px;
            max-height: 60px;
            margin-bottom: 8px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #0dcaf0;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .company-details {
            font-size: 9px;
            color: #666;
            line-height: 1.4;
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-title {
            font-size: 22px;
            font-weight: bold;
            color: #0dcaf0;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .invoice-number {
            font-size: 12px;
            color: #333;
            margin-bottom: 3px;
            font-weight: bold;
        }

        .invoice-date {
            font-size: 10px;
            color: #666;
        }

        .client-section {
            background: #f8f9fa;
            padding: 8px;
            border-left: 3px solid #0dcaf0;
            margin-bottom: 10px;
        }

        .client-section h3 {
            margin: 0 0 6px 0;
            font-size: 11px;
            font-weight: bold;
            color: #0dcaf0;
            text-transform: uppercase;
        }

        .client-details {
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }

        .facture-details {
            margin-bottom: 12px;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
        }

        .facture-title {
            font-size: 11px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            border-bottom: 2px solid #0dcaf0;
            padding-bottom: 5px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .detail-item {
            padding: 6px;
            background: #f8f9fa;
            border-radius: 3px;
        }

        .detail-label {
            font-weight: bold;
            color: #666;
            display: block;
            font-size: 9px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .detail-value {
            color: #333;
            font-size: 11px;
            font-weight: 600;
        }

        .consumption-section {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 12px;
            border: 2px solid #0dcaf0;
        }

        .consumption-title {
            font-size: 11px;
            font-weight: bold;
            color: #01579b;
            margin-bottom: 8px;
            text-align: center;
        }

        .consumption-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 8px;
        }

        .consumption-item {
            background: white;
            padding: 8px;
            border-radius: 3px;
            text-align: center;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .consumption-item .label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 4px;
            display: block;
        }

        .consumption-item .value {
            font-size: 14px;
            font-weight: bold;
            color: #0dcaf0;
        }

        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .payment-table thead {
            background: linear-gradient(135deg, #0dcaf0 0%, #0ba5d1 100%);
            color: white;
        }

        .payment-table th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        .payment-table th:last-child {
            text-align: right;
        }

        .payment-table td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }

        .payment-table tbody tr:hover {
            background: #f8f9fa;
        }

        .amount-summary {
            margin: 10px 0;
            padding: 8px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 3px;
            border: 1px solid #dee2e6;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 11px;
        }

        .summary-row:last-child {
            border-top: 2px solid #0dcaf0;
            margin-top: 6px;
            padding-top: 8px;
            font-size: 14px;
            font-weight: bold;
            color: #0dcaf0;
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
            padding: 4px 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 15px;
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

        .status-retard {
            background: #dc3545;
            color: white;
            border: 1px solid #bd2130;
        }

        .footer {
            margin-top: 12px;
            padding-top: 8px;
            border-top: 2px solid #dee2e6;
            text-align: center;
            font-size: 8px;
            color: #666;
        }

        .notes-section {
            margin: 10px 0;
            padding: 8px;
            background: #fff3cd;
            border-left: 3px solid #ffc107;
            border-radius: 3px;
        }

        .notes-section h4 {
            margin: 0 0 5px 0;
            color: #856404;
            font-size: 10px;
            font-weight: bold;
        }

        .notes-section p {
            margin: 0;
            color: #856404;
            font-size: 9px;
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

        small {
            font-size: 8px;
        }

        strong {
            font-weight: 600;
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
                <div class="invoice-title">FACTURE D'EAU</div>
                <div class="invoice-number">N° <?= esc($numero_facture) ?></div>
                <div class="invoice-date">Date d'émission: <?= date('d/m/Y', strtotime($date_emission)) ?></div>
                <div class="invoice-date">Date d'échéance: <?= date('d/m/Y', strtotime($date_echeance)) ?></div>
            </div>
        </div>

        <!-- Informations Client -->
        <div class="client-section">
            <h3>Informations Locataire</h3>
            <div class="client-details">
                <strong>Nom:</strong> <?= esc($locataire_nom) ?><br>
                <strong>Appartement:</strong> <?= esc($appartement_adresse) ?><br>
                <strong>Téléphone:</strong> <?= esc($locataire_telephone) ?><br>
                <?php if (!empty($locataire_email)): ?>
                    <strong>Email:</strong> <?= esc($locataire_email) ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Détails de la Période -->
        <div class="facture-details">
            <div class="facture-title">Période de Facturation</div>
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Période</span>
                    <span class="detail-value"><?= date('F Y', strtotime($mois_annee . '-01')) ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Contrat N°</span>
                    <span class="detail-value"><?= str_pad($contrat_id, 6, '0', STR_PAD_LEFT) ?></span>
                </div>
            </div>
        </div>

        <!-- Section Consommation -->
        <?php if (!empty($consommation_m3) || (!empty($index_precedent) && !empty($index_actuel))): ?>
        <div class="consumption-section">
            <div class="consumption-title">RELEVÉ DE CONSOMMATION D'EAU</div>
            <div class="consumption-grid">
                <?php if (!empty($index_precedent)): ?>
                <div class="consumption-item">
                    <span class="label">Index Précédent</span>
                    <span class="value"><?= number_format($index_precedent, 2) ?> m³</span>
                </div>
                <?php endif; ?>
                <?php if (!empty($index_actuel)): ?>
                <div class="consumption-item">
                    <span class="label">Index Actuel</span>
                    <span class="value"><?= number_format($index_actuel, 2) ?> m³</span>
                </div>
                <?php endif; ?>
                <?php if (!empty($consommation_m3)): ?>
                <div class="consumption-item">
                    <span class="label">Consommation</span>
                    <span class="value"><?= number_format($consommation_m3, 2) ?> m³</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Détails Paiement -->
        <table class="payment-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: center;">Consommation</th>
                    <th style="text-align: right;">Montant (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Consommation d'eau - <?= date('F Y', strtotime($mois_annee . '-01')) ?></strong><br>
                        <small style="color: #666;">Appartement: <?= esc($appartement_adresse) ?></small>
                    </td>
                    <td style="text-align: center;">
                        <?php if (!empty($consommation_m3)): ?>
                            <?= number_format($consommation_m3, 2) ?> m³
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td style="text-align: right; font-weight: bold;"><?= number_format($montant, 0, ',', ' ') ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Résumé des Montants -->
        <div class="amount-summary">
            <div class="summary-row">
                <span class="summary-label">MONTANT TOTAL:</span>
                <span class="summary-value"><?= number_format($montant, 0, ',', ' ') ?> FCFA</span>
            </div>
            <?php if (!empty($montant_paye) && $montant_paye > 0): ?>
            <div class="summary-row" style="border-top: 1px solid #dee2e6; margin-top: 5px; padding-top: 5px;">
                <span class="summary-label">Montant payé:</span>
                <span class="summary-value" style="color: #28a745;"><?= number_format($montant_paye, 0, ',', ' ') ?> FCFA</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">RESTE À PAYER:</span>
                <span class="summary-value" style="<?= $reste_a_payer > 0 ? 'color: #dc3545;' : 'color: #28a745;' ?>">
                    <?= number_format($reste_a_payer, 0, ',', ' ') ?> FCFA
                </span>
            </div>
            <?php endif; ?>
        </div>

        <!-- Notes importantes -->
        <?php if (!empty($notes)): ?>
        <div class="notes-section">
            <h4>📝 Notes</h4>
            <p><?= nl2br(esc($notes)) ?></p>
        </div>
        <?php endif; ?>

        <!-- Pied de page -->
        <div class="footer">
            <p style="font-size: 10px; font-weight: bold; color: #0dcaf0; margin-bottom: 5px;">Merci pour votre confiance !</p>
            <p style="margin-bottom: 3px;">Facture générée le <?= date('d/m/Y à H:i') ?></p>
            <p style="margin-bottom: 3px;">Contact: <strong><?= esc($structure_telephone) ?></strong> | <strong><?= esc($structure_email) ?></strong></p>
            <p style="margin-top: 8px; font-size: 7px; color: #999;">
                Document généré par T-Lodge
            </p>
        </div>
    </div>
</body>
</html>
