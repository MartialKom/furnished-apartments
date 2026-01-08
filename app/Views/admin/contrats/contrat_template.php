<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrat de Location - <?= $contrat_number ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.6;
            color: #000;
            background-color: #fff;
        }
        
        .contract-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            background-color: #fff;
        }
        
        .contract-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .company-logo {
            max-width: 150px;
            max-height: 80px;
            margin-bottom: 15px;
        }

        .contract-title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        
        .contract-subtitle {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .contract-info {
            margin-bottom: 30px;
        }
        
        .contract-section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 16px;
            text-decoration: underline;
            margin-bottom: 10px;
        }
        
        .contract-text {
            text-align: justify;
            margin-bottom: 15px;
        }
        
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            width: 45%;
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        
        .highlight {
            font-weight: bold;
            text-decoration: underline;
        }
        
        .amount {
            font-weight: bold;
            font-size: 18px;
        }
        
        .terms-list {
            list-style-type: decimal;
            padding-left: 20px;
        }
        
        .terms-list li {
            margin-bottom: 10px;
        }
        
        @media print {
            body {
                background-color: #fff;
            }
            
            .contract-container {
                box-shadow: none;
                border: none;
                margin: 0;
                max-width: none;
                padding: 20px;
            }
            
            .btn-print {
                display: none;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <div class="contract-container">
        <div class="contract-header">
            <?php if (!empty($structure_logo) && file_exists(FCPATH . $structure_logo)): ?>
                <img src="<?= base_url($structure_logo) ?>" alt="Logo" class="company-logo">
            <?php endif; ?>
            <div class="contract-title"><?= esc($structure_name) ?></div>
            <div><?= esc($structure_address) ?></div>
            <div>Tél: <?= esc($structure_phone) ?> | Email: <?= esc($structure_email) ?></div>
            <div class="contract-subtitle">CONTRAT DE LOCATION D'APPARTEMENT</div>
            <div><strong>N° Contrat:</strong> <?= esc($contrat_number) ?></div>
        </div>

        <div class="contract-info">
            <p><strong>Date:</strong> <?= esc($date_creation) ?></p>
        </div>

        <div class="contract-section">
            <div class="section-title">ENTRE LES SOUSSIGNÉS</div>
            <div class="contract-text">
                <p><strong>LE BAILLEUR :</strong> <?= esc($structure_name) ?>, société de gestion immobilière, ayant son siège social à <?= esc($structure_address) ?>, représentée par <strong><?= esc($admin['prenom'] . ' ' . $admin['nom']) ?></strong>, <?= esc($admin['role']) ?>, dûment habilité à cet effet.</p>
                
                <p><strong>D'UNE PART,</strong></p>
                
                <p><strong>ET</strong></p>
                
                <p><strong>LE PRENEUR :</strong> <strong><?= esc($locataire['nom']) ?></strong>, de nationalité <?= esc($locataire['nationalite'] ?? 'non renseignée') ?>, né(e) le <?= $locataire['date_naissance'] ? date('d/m/Y', strtotime($locataire['date_naissance'])) : 'non renseignée' ?><?= !empty($locataire['lieu_naissance']) ? ' à ' . esc($locataire['lieu_naissance']) : '' ?>, titulaire de la carte d'identité nationale N° <?= esc($locataire['numero_piece'] ?? 'non renseigné') ?>, téléphone <?= esc($locataire['telephone']) ?>, email <?= esc($locataire['email']) ?>.</p>
                
                <p><strong>D'AUTRE PART,</strong></p>
            </div>
        </div>

        <div class="contract-section">
            <div class="section-title">IL A ÉTÉ CONVENU CE QUI SUIT</div>
            
            <div class="contract-text">
                <p><strong>Article 1 - OBJET DU CONTRAT</strong></p>
                <p>Le présent contrat a pour objet la location de l'appartement situé à l'adresse suivante :</p>
                <p class="highlight"><?= esc($appartement['adresse']) ?></p>
                <p>Appartement de type <strong><?= esc(ucfirst($appartement['type'])) ?></strong><?= !empty($appartement['superficie']) ? ', d\'une superficie de ' . esc($appartement['superficie']) . ' m²' : '' ?>.</p>
            </div>
        </div>

        <div class="contract-section">
            <div class="section-title">Article 2 - DURÉE DU CONTRAT</div>
            <div class="contract-text">
                <p>Le présent contrat de location est conclu pour une durée <strong><?= $contrat['date_fin'] ? 'déterminée' : 'indéterminée' ?></strong>.</p>
                <?php if ($contrat['date_fin']): ?>
                    <p>Il prend effet le <strong><?= date('d/m/Y', strtotime($contrat['date_debut'])) ?></strong> et expire le <strong><?= date('d/m/Y', strtotime($contrat['date_fin'])) ?></strong>.</p>
                <?php else: ?>
                    <p>Il prend effet le <strong><?= date('d/m/Y', strtotime($contrat['date_debut'])) ?></strong> et est conclu pour une durée indéterminée.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="contract-section">
            <div class="section-title">Article 3 - LOYER ET CHARGES</div>
            <div class="contract-text">
                <p>Le loyer mensuel est fixé à la somme de :</p>
                <p class="amount"><?= number_format($contrat['loyer_mensuel'], 0, ',', ' ') ?> FCFA</p>
                <p>Le loyer est payable le <strong><?= $contrat['jour_paiement'] ?></strong> de chaque mois.</p>
                <p>En cas de retard de paiement, des pénalités de retard pourront être appliquées selon les conditions générales.</p>
            </div>
        </div>

        <div class="contract-section">
            <div class="section-title">Article 4 - CAUTION</div>
            <div class="contract-text">
                <p>Le preneur verse au bailleur une caution d'un montant de :</p>
                <p class="amount"><?= number_format($contrat['caution'], 0, ',', ' ') ?> FCFA</p>
                <p>Cette caution sera restituée au preneur dans un délai de 30 jours après la fin du contrat, déduction faite des éventuels dommages et charges impayées.</p>
            </div>
        </div>

        <div class="contract-section">
            <div class="section-title">Article 5 - OBLIGATIONS DU PRENEUR</div>
            <div class="contract-text">
                <p>Le preneur s'engage à :</p>
                <ol class="terms-list">
                    <li>Payer le loyer aux échéances convenues</li>
                    <li>Occuper personnellement les lieux et ne pas les sous-louer sans autorisation écrite du bailleur</li>
                    <li>Entretenir l'appartement et effectuer les réparations locatives</li>
                    <li>Respecter le règlement intérieur de l'immeuble</li>
                    <li>Donner un préavis de 1 mois avant de quitter les lieux</li>
                    <li>Permettre au bailleur de visiter l'appartement avec préavis de 24h</li>
                </ol>
            </div>
        </div>

        <div class="contract-section">
            <div class="section-title">Article 6 - OBLIGATIONS DU BAILLEUR</div>
            <div class="contract-text">
                <p>Le bailleur s'engage à :</p>
                <ol class="terms-list">
                    <li>Livrer l'appartement en bon état d'habitation</li>
                    <li>Effectuer les réparations de grosses réparations</li>
                    <li>Respecter la quiétude du preneur dans la jouissance des lieux</li>
                    <li>Maintenir l'appartement conforme à sa destination</li>
                </ol>
            </div>
        </div>

        <div class="contract-section">
            <div class="section-title">Article 7 - RÉSILIATION</div>
            <div class="contract-text">
                <p>Le contrat peut être résilié :</p>
                <ul>
                    <li>Par le preneur avec un préavis de 1 mois</li>
                    <li>Par le bailleur pour non-paiement du loyer après mise en demeure</li>
                    <li>D'un commun accord entre les parties</li>
                </ul>
            </div>
        </div>

        <div class="contract-section">
            <div class="section-title">Article 8 - DIVERS</div>
            <div class="contract-text">
                <p>Tout litige relatif à l'exécution du présent contrat sera soumis aux tribunaux compétents d'Abidjan.</p>
                <p>Le présent contrat est établi en deux exemplaires originaux, un pour chaque partie.</p>
            </div>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <p><strong>LE BAILLEUR</strong></p>
                <p><?= esc($admin['prenom'] . ' ' . $admin['nom']) ?></p>
                <p><?= esc($admin['role']) ?></p>
                <p>Date: <?= esc($signature_date) ?></p>
            </div>
            <div class="signature-box">
                <p><strong>LE PRENEUR</strong></p>
                <p><?= esc($locataire['nom']) ?></p>
                <p>Date: <?= esc($signature_date) ?></p>
            </div>
        </div>

        <div style="margin-top: 30px; font-size: 12px; text-align: center; color: #666;">
            <p>Contrat généré le <?= date('d/m/Y H:i:s') ?> par <?= esc($admin['prenom'] . ' ' . $admin['nom']) ?></p>
        </div>
    </div>

    <div class="text-center mt-3 mb-5">
        <button class="btn btn-primary btn-lg btn-print" onclick="window.print()">
            <i class="feather-printer me-2"></i>Imprimer le Contrat
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
