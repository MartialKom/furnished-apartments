<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #333;
        }

        .header .subtitle {
            font-size: 14px;
            color: #666;
        }

        .info-section {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .info-box {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
        }

        .info-box strong {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead {
            background: #333;
            color: white;
        }

        table th,
        table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-section {
            margin-top: 30px;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
        }

        .summary-section h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .summary-row strong {
            font-size: 16px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 11px;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }

            @page {
                margin: 1cm;
            }
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #d29751;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .print-btn:hover {
            background: #b87d3f;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            color: white;
        }

        .badge-appart {
            background: #007bff;
        }

        .badge-commun {
            background: #6c757d;
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">🖨️ Imprimer</button>

    <div class="header">
        <h1>📤 HISTORIQUE DES SORTIES DE STOCK</h1>
        <div class="subtitle">NSENOU TOWER - Appartements Meublés</div>
    </div>

    <div class="info-section">
        <div class="info-box">
            <strong>Période :</strong>
            Du <?= date('d/m/Y', strtotime($date_debut)) ?> au <?= date('d/m/Y', strtotime($date_fin)) ?>
        </div>
        <div class="info-box">
            <strong>Nombre de sorties :</strong>
            <?= $statistiques['total_sorties'] ?>
        </div>
        <div class="info-box">
            <strong>Valeur totale :</strong>
            <?= number_format($statistiques['valeur_totale'], 0, ',', ' ') ?> FCFA
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Produit</th>
                <th class="text-center">Quantité</th>
                <th class="text-right">Prix unitaire</th>
                <th class="text-right">Valeur</th>
                <th>Destination</th>
                <th>Motif</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($sorties)): ?>
                <?php foreach ($sorties as $sortie): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($sortie['date_sortie'])) ?></td>
                        <td><?= esc($sortie['produit_nom']) ?></td>
                        <td class="text-center">
                            <?= number_format($sortie['quantite'], 2) ?> <?= esc($sortie['unite_mesure']) ?>
                        </td>
                        <td class="text-right">
                            <?= number_format($sortie['prix_moyen'], 0, ',', ' ') ?> FCFA
                        </td>
                        <td class="text-right">
                            <?= number_format($sortie['quantite'] * $sortie['prix_moyen'], 0, ',', ' ') ?> FCFA
                        </td>
                        <td>
                            <?php if ($sortie['appartement_nom']): ?>
                                <span class="badge badge-appart">🏠 <?= esc($sortie['appartement_nom']) ?></span>
                            <?php elseif ($sortie['destination']): ?>
                                <span class="badge badge-commun">🏢 <?= esc($sortie['destination']) ?></span>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?= esc($sortie['motif'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">
                        Aucune sortie enregistrée pour cette période
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="summary-section">
        <h3>📊 Résumé de la période</h3>
        <div class="summary-row">
            <span>Nombre total de sorties :</span>
            <strong><?= $statistiques['total_sorties'] ?></strong>
        </div>
        <div class="summary-row">
            <span>Valeur totale des sorties :</span>
            <strong><?= number_format($statistiques['valeur_totale'], 0, ',', ' ') ?> FCFA</strong>
        </div>
        <?php if ($statistiques['total_sorties'] > 0): ?>
            <div class="summary-row">
                <span>Valeur moyenne par sortie :</span>
                <strong><?= number_format($statistiques['valeur_totale'] / $statistiques['total_sorties'], 0, ',', ' ') ?> FCFA</strong>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        <p>Document généré le <?= date('d/m/Y à H:i') ?></p>
        <p>NSENOU TOWER - Système de Gestion de Stock</p>
    </div>

    <script>
        // Auto-print on load (optional)
        // window.onload = () => window.print();
    </script>
</body>
</html>
