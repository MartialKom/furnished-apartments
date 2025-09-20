<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement - <?= $numero_receipt ?></title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #2c5aa0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .company-details {
            font-size: 11px;
            color: #666;
            margin-top: 10px;
        }
        
        .receipt-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #2c5aa0;
            margin: 20px 0;
            text-transform: uppercase;
        }
        
        .receipt-number {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        
        .info-section {
            margin-bottom: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #2c5aa0;
        }
        
        .info-section h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            font-weight: bold;
            color: #2c5aa0;
            text-transform: uppercase;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .info-item {
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 120px;
        }
        
        .info-value {
            color: #333;
        }
        
        .payment-details {
            background: #e8f4fd;
            border: 2px solid #2c5aa0;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        
        .payment-details h3 {
            text-align: center;
            margin: 0 0 15px 0;
            font-size: 16px;
            color: #2c5aa0;
        }
        
        .amount-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        
        .amount-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
            color: #2c5aa0;
            border-top: 2px solid #2c5aa0;
            margin-top: 10px;
            padding-top: 15px;
        }
        
        .amount-label {
            font-weight: bold;
        }
        
        .amount-value {
            font-weight: bold;
            font-size: 14px;
        }
        
        .partial-payment {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
            text-align: center;
        }
        
        .partial-payment .alert {
            color: #856404;
            font-weight: bold;
        }
        
        .next-payment {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        
        .next-payment h4 {
            margin: 0 0 10px 0;
            color: #0c5460;
            font-size: 14px;
        }
        
        .months-paid {
            margin: 20px 0;
        }
        
        .months-paid h4 {
            color: #2c5aa0;
            margin-bottom: 10px;
        }
        
        .month-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        
        .month-item:last-child {
            border-bottom: none;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #2c5aa0;
            text-align: center;
            font-size: 11px;
            color: #666;
        }
        
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            width: 200px;
            text-align: center;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            height: 40px;
            margin-bottom: 5px;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2c5aa0;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .print-button:hover {
            background: #1e3f73;
        }
        
        @media print {
            .print-button {
                display: none;
            }
            
            .receipt-container {
                box-shadow: none;
                padding: 0;
            }
            
            body {
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="feather-printer"></i> Imprimer
    </button>

    <div class="receipt-container">
        <!-- En-tête -->
        <div class="header">
            <div class="company-name"><?= $structure_nom ?></div>
            <div class="company-details">
                <div><?= $structure_adresse ?></div>
                <div>Tél: <?= $structure_telephone ?> | Email: <?= $structure_email ?></div>
            </div>
        </div>

        <!-- Titre du reçu -->
        <div class="receipt-title">
            Reçu de Paiement de Loyer
        </div>

        <!-- Numéro de reçu -->
        <div class="receipt-number">
            N° <?= $numero_receipt ?>
        </div>

        <!-- Informations générales -->
        <div class="info-grid">
            <div class="info-section">
                <h3>Informations Locataire</h3>
                <div class="info-item">
                    <span class="info-label">Nom:</span>
                    <span class="info-value"><?= esc($locataire_nom) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?= esc($locataire_email) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Téléphone:</span>
                    <span class="info-value"><?= esc($locataire_telephone) ?></span>
                </div>
            </div>

            <div class="info-section">
                <h3>Informations Appartement</h3>
                <div class="info-item">
                    <span class="info-label">Adresse:</span>
                    <span class="info-value"><?= esc($appartement_adresse) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Type:</span>
                    <span class="info-value"><?= ucfirst($appartement_type) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Surface:</span>
                    <span class="info-value"><?= esc($appartement_surface) ?></span>
                </div>
            </div>
        </div>

        <!-- Détails du paiement -->
        <div class="payment-details">
            <h3>Détails du Paiement</h3>
            
            <div class="amount-row">
                <span class="amount-label">Date de paiement:</span>
                <span class="amount-value"><?= date('d/m/Y à H:i', strtotime($date_paiement)) ?></span>
            </div>
            
            <div class="amount-row">
                <span class="amount-label">Mode de paiement:</span>
                <span class="amount-value"><?= ucfirst($mode_paiement ?? 'Non spécifié') ?></span>
            </div>
            
            <?php if (!empty($reference_paiement)): ?>
            <div class="amount-row">
                <span class="amount-label">Référence:</span>
                <span class="amount-value"><?= esc($reference_paiement) ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($type === 'simple'): ?>
                <div class="amount-row">
                    <span class="amount-label">Mois/Année:</span>
                    <span class="amount-value"><?= date('F Y', strtotime($mois_annee . '-01')) ?></span>
                </div>
                
                <div class="amount-row">
                    <span class="amount-label">Montant dû:</span>
                    <span class="amount-value"><?= number_format($montant_du, 0, ',', ' ') ?> FCFA</span>
                </div>
                
                <div class="amount-row">
                    <span class="amount-label">Montant payé:</span>
                    <span class="amount-value"><?= number_format($montant_paye, 0, ',', ' ') ?> FCFA</span>
                </div>
                
                <?php if ($est_paiement_partiel): ?>
                <div class="amount-row">
                    <span class="amount-label">Reste à payer:</span>
                    <span class="amount-value"><?= number_format($reste_a_payer, 0, ',', ' ') ?> FCFA</span>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="amount-row">
                    <span class="amount-label">Nombre de mensualités:</span>
                    <span class="amount-value"><?= $nombre_mensualites ?> mois</span>
                </div>
                
                <div class="amount-row">
                    <span class="amount-label">Montant total payé:</span>
                    <span class="amount-value"><?= number_format($montant_total_paye, 0, ',', ' ') ?> FCFA</span>
                </div>
            <?php endif; ?>
            
            <div class="amount-row">
                <span class="amount-label">Gestionnaire:</span>
                <span class="amount-value"><?= esc($gestionnaire_nom) ?> (<?= esc($gestionnaire_role) ?>)</span>
            </div>
        </div>

        <!-- Paiement partiel -->
        <?php if (isset($est_paiement_partiel) && $est_paiement_partiel): ?>
        <div class="partial-payment">
            <div class="alert">⚠️ Paiement Partiel</div>
            <div>Il reste <strong><?= number_format($reste_a_payer, 0, ',', ' ') ?> FCFA</strong> à régulariser pour ce mois.</div>
        </div>
        <?php endif; ?>

        <!-- Détail des mois payés (paiement multiple) -->
        <?php if ($type === 'multiple' && !empty($mois_payes)): ?>
        <div class="months-paid">
            <h4>Détail des Mensualités Payées</h4>
            <?php foreach ($mois_payes as $mois): ?>
            <div class="month-item">
                <span><?= date('F Y', strtotime($mois['mois_annee'] . '-01')) ?></span>
                <span><?= number_format($mois['montant'], 0, ',', ' ') ?> FCFA</span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Prochaine échéance -->
        <?php if ($prochaine_echeance_date): ?>
        <div class="next-payment">
            <h4>Prochaine Échéance</h4>
            <div>
                <strong>Date:</strong> <?= date('d/m/Y', strtotime($prochaine_echeance_date)) ?> 
                <strong>| Montant:</strong> <?= number_format($prochaine_echeance_montant, 0, ',', ' ') ?> FCFA
            </div>
            <div style="margin-top: 8px; font-size: 11px; color: #666;">
                (Jour de paiement habituel: le <?= $jour_paiement ?> de chaque mois)
            </div>
        </div>
        <?php endif; ?>

        <!-- Notes -->
        <?php if (!empty($notes)): ?>
        <div class="info-section">
            <h3>Notes</h3>
            <div><?= esc($notes) ?></div>
        </div>
        <?php endif; ?>

        <!-- Signature -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div>Signature Locataire</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div>Signature Gestionnaire</div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <div>Reçu généré le <?= date('d/m/Y à H:i:s', strtotime($date_emission)) ?></div>
            <div>Ce reçu fait foi de paiement. Conservez-le précieusement.</div>
            <div><?= $structure_nom ?> - <?= $structure_adresse ?></div>
        </div>
    </div>

    <script>
        // Auto-print option (commenté par défaut)
        // window.onload = function() { window.print(); }
        
        // Gestion de l'impression
        window.addEventListener('beforeprint', function() {
            document.title = 'Reçu_' + '<?= $numero_receipt ?>';
        });
    </script>
</body>
</html>
