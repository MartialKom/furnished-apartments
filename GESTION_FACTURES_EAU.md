# Gestion des Factures d'Eau - Documentation

## 📋 Vue d'ensemble

Ce module permet de gérer les **factures d'eau** pour les locataires à long terme. En plus du loyer mensuel, chaque locataire paie une facture d'eau mensuelle basée sur sa consommation.

---

## 🎯 Fonctionnalités

### 1. Création de factures
- **Manuelle** : Créer une facture individuellement pour un contrat
- **Automatique** : Générer toutes les factures du mois pour tous les contrats actifs

### 2. Suivi des factures
- **En attente** : Factures non encore payées
- **En retard** : Factures dont la date d'échéance est dépassée
- **Partiellement payée** : Factures payées en partie
- **Payée** : Factures entièrement réglées

### 3. Gestion des paiements
- Enregistrement des paiements complets ou partiels
- Modes de paiement : Espèces, Virement, Chèque, Mobile Money
- Référence de transaction

### 4. Suivi de la consommation
- Index précédent et actuel du compteur
- Calcul automatique de la consommation en m³
- Historique par locataire

---

## 🗄️ Structure de base de données

### Table : `factures_eau`

| Champ | Type | Description |
|-------|------|-------------|
| `id` | INT | Identifiant unique |
| `contrat_id` | INT | Lien vers le contrat de location |
| `mois_annee` | VARCHAR(7) | Période (format YYYY-MM) |
| `montant` | DECIMAL(10,2) | Montant de la facture |
| `consommation_m3` | DECIMAL(10,2) | Consommation en m³ |
| `index_precedent` | DECIMAL(10,2) | Index du mois précédent |
| `index_actuel` | DECIMAL(10,2) | Index du mois actuel |
| `date_emission` | DATE | Date d'émission |
| `date_echeance` | DATE | Date d'échéance |
| `date_paiement` | DATE | Date de paiement (nullable) |
| `statut` | ENUM | en_attente, paye, en_retard, partiellement_paye |
| `montant_paye` | DECIMAL(10,2) | Montant déjà payé |
| `mode_paiement` | ENUM | especes, virement, cheque, mobile_money |
| `reference_paiement` | VARCHAR(255) | Référence de la transaction |
| `notes` | TEXT | Notes additionnelles |
| `enregistre_par` | INT | ID de l'utilisateur qui a créé |
| `created_at` | TIMESTAMP | Date de création |
| `updated_at` | TIMESTAMP | Date de modification |

**Contraintes** :
- Clé étrangère vers `contrats_locataires`
- Clé étrangère vers `utilisateurs`
- Index unique sur `(contrat_id, mois_annee)` → Une seule facture par contrat par mois

---

## 📁 Fichiers créés

### Migration
```
app/Database/Migrations/2025-11-16-100000_CreateFacturesEauTable.php
```

### Modèle
```
app/Models/FactureEauModel.php
```

**Méthodes principales** :
- `creerFacture($data)` : Créer une facture
- `getFacturesWithDetails()` : Liste avec infos locataire/appartement
- `getFacturesByContrat($contratId)` : Factures d'un contrat
- `getFacturesImpayees()` : Toutes les factures impayées
- `marquerCommePaye($factureId, $data)` : Marquer comme payée
- `genererFacturesMoisActuel()` : Génération automatique
- `updateStatutsEnRetard()` : Mettre à jour les statuts

### Contrôleur
```
app/Controllers/Admin/FactureEauController.php
```

**Routes disponibles** :
- `GET /admin/factures-eau` : Liste des factures
- `GET /admin/factures-eau/create` : Formulaire de création
- `POST /admin/factures-eau/store` : Enregistrer une facture
- `GET /admin/factures-eau/get/{id}` : Détails d'une facture
- `POST /admin/factures-eau/marquer-paye/{id}` : Marquer comme payée
- `POST /admin/factures-eau/update/{id}` : Modifier une facture
- `DELETE /admin/factures-eau/delete/{id}` : Supprimer une facture
- `POST /admin/factures-eau/generer-mois` : Générer factures du mois
- `POST /admin/factures-eau/update-retards` : Mettre à jour les retards

### Vues
```
app/Views/admin/pages/factures_eau/
├── index.php                    # Page principale avec onglets
└── modals/
    ├── create.php              # Formulaire de création
    ├── marquer_paye.php        # Formulaire de paiement
    └── details.php             # Affichage des détails
```

### Routes
```
app/Config/Routes.php (lignes 143-152)
```

---

## 🚀 Installation

### Étape 1 : Exécuter la migration

```bash
cd C:\xampplastversion\htdocs\furnished-apartments
php spark migrate
```

**Résultat attendu** :
```
Running: 2025-11-16-100000_CreateFacturesEauTable
Migrations complete.
```

### Étape 2 : Vérifier la création de la table

```sql
SHOW TABLES LIKE 'factures_eau';
DESC factures_eau;
```

---

## 📖 Guide d'utilisation

### 1. Accéder au module

Connectez-vous à l'admin et allez sur :
```
http://localhost:8080/admin/factures-eau
```

### 2. Générer les factures du mois

Cliquez sur le bouton **"Générer factures du mois"**

**Ce que ça fait** :
- Récupère tous les contrats actifs
- Crée une facture pour chaque contrat pour le mois en cours
- Montant initial = 0 FCFA (à remplir manuellement)
- Date d'échéance = 25 du mois
- Statut = "En attente"

**Note** : Si une facture existe déjà pour un contrat ce mois-ci, elle ne sera pas recréée.

### 3. Créer une facture manuelle

1. Cliquez sur **"Créer une facture"**
2. Remplissez le formulaire :
   - **Contrat** : Sélectionnez le locataire/appartement
   - **Mois/Année** : YYYY-MM (ex: 2025-11)
   - **Montant** : Montant de la facture en FCFA
   - **Index précédent** : Relevé du mois dernier (optionnel)
   - **Index actuel** : Relevé de ce mois (optionnel)
   - **Consommation** : Se calcule automatiquement si les index sont renseignés
   - **Date d'émission** : Par défaut aujourd'hui
   - **Date d'échéance** : Par défaut le 25 du mois
   - **Notes** : Informations supplémentaires

3. Cliquez sur **"Créer la facture"**

**Validation** :
- Une seule facture par contrat par mois
- Le montant doit être > 0
- Les dates doivent être valides

### 4. Marquer une facture comme payée

1. Dans l'onglet **"En attente"** ou **"En retard"**
2. Cliquez sur le bouton ✓ (vert) à côté de la facture
3. Remplissez :
   - **Montant payé** : Montant reçu
   - **Date de paiement** : Date du paiement
   - **Mode de paiement** : Espèces, Virement, Chèque, Mobile Money
   - **Référence** : Numéro de transaction (optionnel)

4. Cliquez sur **"Confirmer le paiement"**

**Comportement** :
- Si montant payé = montant total → Statut = "Payée"
- Si montant payé < montant total → Statut = "Partiellement payée"

### 5. Voir les détails d'une facture

Cliquez sur le bouton 👁️ (bleu) pour voir :
- Informations du locataire
- Appartement
- Consommation détaillée (index, m³)
- Montant et statut
- Informations de paiement (si payée)

### 6. Modifier une facture

Cliquez sur le bouton ✏️ (jaune)

**Limitation** : Impossible de modifier une facture déjà payée

### 7. Supprimer une facture

Cliquez sur le bouton 🗑️ (rouge)

**Limitation** : Impossible de supprimer une facture déjà payée

---

## 📊 Onglets de l'interface

### En attente (Badge jaune)
Factures non encore payées et dont la date d'échéance n'est pas dépassée

**Actions disponibles** :
- ✓ Marquer comme payé
- 👁️ Voir détails
- ✏️ Modifier
- 🗑️ Supprimer

### En retard (Badge rouge)
Factures non payées dont la date d'échéance est dépassée

**Informations affichées** :
- Nombre de jours de retard
- Bouton "Relancer" pour envoyer un email de rappel

**Actions disponibles** :
- ✓ Marquer comme payé
- 👁️ Voir détails
- ✉️ Relancer (envoyer rappel)

### Partiellement payé (Badge bleu clair)
Factures payées en partie

**Informations affichées** :
- Montant total
- Montant payé
- Montant restant

**Actions disponibles** :
- ✓ Compléter le paiement
- 👁️ Voir détails

### Payées (Badge vert)
Factures entièrement réglées

**Informations affichées** :
- Date de paiement
- Mode de paiement
- Référence de paiement

**Actions disponibles** :
- 👁️ Voir détails

---

## 🔄 Workflow typique

### Début du mois
```
1. Gestionnaire → Cliquez "Générer factures du mois"
   ↓
2. Système crée une facture par contrat actif
   ↓
3. Gestionnaire remplit les index compteurs
   ↓
4. Système calcule la consommation
   ↓
5. Gestionnaire ajuste le montant si nécessaire
   ↓
6. Factures prêtes et en attente de paiement
```

### Paiement d'un locataire
```
1. Locataire paie (espèces, virement, etc.)
   ↓
2. Gestionnaire clique ✓ sur la facture
   ↓
3. Remplit montant + mode + référence
   ↓
4. Valide le paiement
   ↓
5. Statut → "Payée" ou "Partiellement payée"
   ↓
6. Facture déplacée dans l'onglet correspondant
```

### Gestion des retards
```
1. Système : Mise à jour auto des statuts en retard
   ↓
2. Gestionnaire voit les factures en retard
   ↓
3. Clique "Relancer" pour envoyer email
   ↓
4. Locataire reçoit rappel de paiement
   ↓
5. Suivi jusqu'au paiement
```

---

## ⚙️ Automatisations possibles

### Mise à jour automatique des retards

Créer un cron job pour exécuter quotidiennement :

```bash
php spark factures-eau:update-retards
```

Cette commande mettra à jour le statut des factures dont l'échéance est dépassée.

### Génération automatique début de mois

Créer un cron job pour le 1er de chaque mois :

```bash
php spark factures-eau:generer-mois
```

---

## 🔒 Sécurité et permissions

**Accès requis** : `auth:reservations`

Les utilisateurs avec permission de gestion des réservations peuvent :
- Voir toutes les factures
- Créer des factures
- Marquer comme payées
- Modifier (sauf payées)
- Supprimer (sauf payées)

---

## 💡 Bonnes pratiques

### ✅ À FAIRE
- Remplir les index compteur chaque mois pour tracer la consommation
- Vérifier la cohérence entre consommation et montant
- Toujours indiquer la référence de paiement pour les virements
- Utiliser la génération automatique pour gagner du temps
- Envoyer des relances pour les factures en retard

### ❌ À ÉVITER
- Ne jamais modifier une facture déjà payée
- Ne pas créer plusieurs factures pour le même mois/contrat
- Ne pas supprimer des factures avec historique de paiement
- Ne pas oublier de renseigner le mode de paiement

---

## 📈 Statistiques et rapports

### Données disponibles
- Total des factures en attente
- Total des factures en retard
- Total des factures payées ce mois
- Consommation moyenne par appartement
- Taux de paiement à temps
- Locataires avec retards récurrents

### Exports possibles
- Liste des factures impayées
- Historique de consommation par locataire
- Rapport mensuel des recettes eau
- Liste des retards de paiement

---

## 🐛 Résolution de problèmes

### Erreur : "Une facture existe déjà pour ce contrat et ce mois"

**Cause** : Une facture a déjà été créée pour cette période

**Solution** : Vérifiez dans les onglets si la facture existe déjà. Si besoin, modifiez-la au lieu d'en créer une nouvelle.

### Impossible de modifier/supprimer une facture

**Cause** : La facture est déjà marquée comme "payée"

**Solution** : Les factures payées ne peuvent être modifiées pour préserver l'intégrité comptable.

### La consommation ne se calcule pas

**Cause** : Index précédent ou actuel manquant

**Solution** : Remplissez les deux champs "Index précédent" et "Index actuel". Le calcul se fait automatiquement.

---

## 🔮 Améliorations futures

1. **Notifications par email** : Envoi automatique des factures aux locataires
2. **Relances automatiques** : Système de rappels programmés
3. **Export PDF** : Génération de factures PDF imprimables
4. **Graphiques** : Visualisation de la consommation dans le temps
5. **Tarification progressive** : Calcul automatique selon barème de consommation
6. **Paiement en ligne** : Intégration avec services de paiement mobile

---

## 📝 Notes techniques

### Calcul de la consommation

```php
if (index_actuel && index_precedent) {
    consommation_m3 = index_actuel - index_precedent
}
```

### Détermination du statut

- **en_attente** : Créée, non payée, échéance non dépassée
- **en_retard** : Non payée, date_echeance < aujourd'hui
- **partiellement_paye** : montant_paye > 0 ET montant_paye < montant
- **paye** : montant_paye >= montant

### Unicité des factures

Une contrainte `UNIQUE(contrat_id, mois_annee)` empêche les doublons.

---

## 🎉 Résumé

✅ **Module complet de gestion des factures d'eau installé avec succès !**

Vous pouvez maintenant :
- Créer des factures manuellement ou automatiquement
- Suivre les paiements et les retards
- Gérer la consommation d'eau par locataire
- Générer des rapports et statistiques

---

**Date de création** : 2025-11-16
**Version** : 1.0
**Statut** : ✅ Prêt à utiliser (après exécution de la migration)

---

## 📞 Prochaines étapes

1. **Exécutez la migration** :
   ```bash
   php spark migrate
   ```

2. **Testez le module** :
   - Allez sur `/admin/factures-eau`
   - Cliquez "Générer factures du mois"
   - Créez une facture manuelle
   - Testez le marquage comme payé

3. **Configurez les notifications** (optionnel) :
   - Ajoutez les templates d'email pour les factures
   - Configurez les rappels automatiques

Bon usage ! 🚀
