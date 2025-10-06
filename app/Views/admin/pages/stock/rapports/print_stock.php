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
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        table tbody tr.alert {
            background: #fff3cd;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total-section {
            margin-top: 20px;
            text-align: right;
            font-size: 16px;
            font-weight: bold;
        }

        .badge-alert {
            background: #dc3545;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
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
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">🖨️ Imprimer</button>

    <div class="header">
        <h1>📦 ÉTAT DU STOCK</h1>
        <div class="subtitle">NSENOU TOWER - Appartements Meublés</div>
    </div>

    <div class="info-section">
        <div class="info-box">
            <strong>Date d'édition :</strong>
            <?= $date ?>
        </div>
        <div class="info-box">
            <strong>Valeur totale du stock :</strong>
            <?= number_format($valeur_totale, 0, ',', ' ') ?> FCFA
        </div>
        <div class="info-box">
            <strong>Nombre de produits :</strong>
            <?= count($produits) ?>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Catégorie</th>
                <th>Produit</th>
                <th class="text-center">Stock actuel</th>
                <th class="text-center">Stock minimum</th>
                <th class="text-right">Prix moyen</th>
                <th class="text-right">Valeur</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $currentCategory = '';
            foreach ($produits as $produit):
                $isAlert = $produit['stock_actuel'] <= $produit['stock_minimum'];
                $rowClass = $isAlert ? 'alert' : '';
            ?>
                <tr class="<?= $rowClass ?>">
                    <td>
                        <?php if ($currentCategory != $produit['categorie_nom']): ?>
                            <?= esc($produit['categorie_nom']) ?>
                            <?php $currentCategory = $produit['categorie_nom']; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= esc($produit['nom']) ?>
                        <?php if ($isAlert): ?>
                            <span class="badge-alert">⚠ ALERTE</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?= number_format($produit['stock_actuel'], 2) ?> <?= esc($produit['unite_mesure']) ?>
                    </td>
                    <td class="text-center">
                        <?= number_format($produit['stock_minimum'], 2) ?>
                    </td>
                    <td class="text-right">
                        <?= number_format($produit['prix_moyen'], 0, ',', ' ') ?> FCFA
                    </td>
                    <td class="text-right">
                        <?= number_format($produit['stock_actuel'] * $produit['prix_moyen'], 0, ',', ' ') ?> FCFA
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total-section">
        VALEUR TOTALE : <?= number_format($valeur_totale, 0, ',', ' ') ?> FCFA
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
