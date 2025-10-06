# 📦 Guide d'Utilisation - Module de Gestion de Stock

## 🎯 Vue d'ensemble

Le module de gestion de stock vous permet de gérer les produits consommables fournis aux locataires (savon, gel douche, papier toilette, etc.) de manière professionnelle et efficace.

## ✅ Fonctionnalités Principales

### 1. **Tableau de Bord Stock**
   - Vue d'ensemble des statistiques
   - Alertes pour les stocks faibles
   - Valeur totale du stock
   - Accès rapide à toutes les fonctionnalités

### 2. **Gestion des Catégories**
   - Créer des catégories de produits (Ex: Hygiène, Nettoyage, Literie)
   - Organiser les produits par type
   - Activer/désactiver des catégories

### 3. **Gestion des Produits**
   - **Créer un produit** : Nom, catégorie, unité de mesure, stock minimum
   - **Stock minimum d'alerte** : Notification visuelle quand le stock est faible
   - **Prix moyen pondéré** : Calculé automatiquement lors des approvisionnements
   - **Stock actuel** : Mis à jour automatiquement

### 4. **Approvisionnements (Entrées de Stock)**
   - Enregistrer les achats de produits
   - Informations : Produit, quantité, prix unitaire, fournisseur, date
   - **Calcul automatique** :
     - Prix total de l'approvisionnement
     - Mise à jour du stock disponible
     - Recalcul du prix moyen pondéré

### 5. **Sorties de Stock**
   - Enregistrer les distributions/utilisations
   - Destination : Appartement spécifique ou autre (salle commune, etc.)
   - **Vérification automatique** du stock disponible
   - Déduction automatique du stock

### 6. **Inventaires**
   - **Création d'inventaire** : Tous les produits actifs sont ajoutés automatiquement
   - **Saisie des quantités physiques** : Interface rapide et intuitive
   - **Calcul des écarts** : Stock théorique vs stock physique
   - **Valorisation** : Écarts valorisés au prix moyen
   - **Validation** : Ajustement automatique des stocks réels

### 7. **Rapports et Statistiques**
   - État du stock actuel avec valorisation
   - Historique des approvisionnements par période
   - Historique des sorties par période
   - Export PDF pour impression
   - Produits en alerte de stock faible

## 🚀 Guide d'Utilisation Rapide

### Étape 1 : Configuration Initiale

1. **Créer les catégories de produits**
   - Aller dans : Stock > Produits > Catégories
   - Exemples : "Hygiène", "Nettoyage", "Linge", "Cuisine"

2. **Créer les produits**
   - Aller dans : Stock > Produits
   - Pour chaque produit :
     - Nom (Ex: Savon liquide)
     - Catégorie
     - Unité de mesure (pièce, litre, kg, boîte, etc.)
     - Stock minimum (seuil d'alerte)

### Étape 2 : Premier Approvisionnement

1. **Aller dans : Stock > Approvisionnements**
2. **Cliquer sur "Nouvel approvisionnement"**
3. **Remplir le formulaire** :
   - Sélectionner le produit
   - Quantité reçue
   - Prix unitaire d'achat
   - Fournisseur (optionnel)
   - Référence facture (optionnel)
   - Date
4. **Valider** : Le stock est mis à jour automatiquement

### Étape 3 : Enregistrer une Sortie

1. **Aller dans : Stock > Sorties**
2. **Cliquer sur "Nouvelle sortie"**
3. **Remplir** :
   - Produit (le stock disponible s'affiche)
   - Quantité
   - Appartement de destination ou autre destination
   - Motif (Ex: "Arrivée nouveau locataire")
4. **Valider** : Le stock est déduit automatiquement

### Étape 4 : Réaliser un Inventaire

1. **Aller dans : Stock > Inventaires**
2. **Créer un nouvel inventaire**
   - Tous les produits actifs sont ajoutés automatiquement
   - Un numéro unique est généré (Format: INV-YYYYMMDD-XXX)
3. **Saisir les quantités physiques comptées**
   - Les écarts sont calculés automatiquement
   - La valorisation des écarts s'affiche
4. **Terminer l'inventaire** (statut: Terminé)
5. **Valider l'inventaire** (ajuste les stocks réels)

### Étape 5 : Consulter les Rapports

1. **Aller dans : Stock > Rapports**
2. **Filtrer par période** si nécessaire
3. **Visualiser** :
   - Valeur totale du stock
   - Mouvements du mois
   - Produits en alerte
4. **Imprimer** :
   - État du stock (PDF)
   - Historique des sorties (PDF)

## 📊 Exemples d'Utilisation

### Exemple 1 : Achat de savon

**Approvisionnement :**
- Produit : Savon liquide
- Quantité : 20 bouteilles
- Prix unitaire : 500 FCFA
- Fournisseur : Supermarché ABC
- Date : 04/10/2025

➡️ **Résultat** :
- Stock : +20 bouteilles
- Prix moyen : 500 FCFA
- Valeur stock : 10,000 FCFA

### Exemple 2 : Distribution à un appartement

**Sortie :**
- Produit : Savon liquide
- Quantité : 2 bouteilles
- Appartement : Appart 101
- Motif : Arrivée nouveau locataire
- Date : 05/10/2025

➡️ **Résultat** :
- Stock : -2 bouteilles (reste 18)
- Valeur consommée : 1,000 FCFA

### Exemple 3 : Inventaire mensuel

**Inventaire du 31/10/2025 :**
- Savon liquide :
  - Stock théorique : 18 bouteilles
  - Stock physique compté : 17 bouteilles
  - Écart : -1 bouteille (perte/casse)
  - Valeur écart : -500 FCFA

➡️ **Après validation** :
- Stock ajusté à 17 bouteilles

## 🔐 Permissions

Le module Stock est accessible aux :
- ✅ **Administrateurs** : Accès complet
- ✅ **Gestionnaires** : Accès complet (selon configuration)

Pour modifier les permissions, éditer le fichier `app/Filters/AuthFilter.php`

## 🎨 Navigation

**Menu principal (Sidebar) :**
- 📦 Tableau de Bord Stock
- 📦 Produits
- 🚚 Approvisionnements
- 🛒 Sorties
- 📋 Inventaires
- 📊 Rapports Stock

## 💡 Conseils d'Utilisation

1. **Créez des catégories claires** : Facilitent le classement et la recherche
2. **Définissez des stocks minimums** : Pour être alerté à temps
3. **Faites des inventaires réguliers** : Mensuel ou trimestriel
4. **Notez le fournisseur** : Facilite les réapprovisionnements
5. **Utilisez des motifs clairs** : Pour les sorties (traçabilité)

## 🔧 Configuration Technique

### Tables créées :
- `stock_categories` : Catégories de produits
- `stock_produits` : Liste des produits
- `stock_approvisionnements` : Entrées de stock
- `stock_sorties` : Sorties de stock
- `stock_inventaires` : En-têtes d'inventaire
- `stock_inventaire_details` : Détails d'inventaire

### Routes principales :
- `/admin/stock` : Dashboard
- `/admin/stock/produits` : Gestion produits
- `/admin/stock/approvisionnements` : Approvisionnements
- `/admin/stock/sorties` : Sorties
- `/admin/stock/inventaires` : Inventaires
- `/admin/stock/rapports` : Rapports

## 📝 Notes Importantes

⚠️ **Prix de vente** : Les produits n'ont pas de prix de vente car ils sont fournis gratuitement

📈 **Prix moyen pondéré** : Calculé automatiquement à chaque approvisionnement selon la formule :
```
Nouveau prix moyen = (Stock ancien × Prix ancien + Quantité entrée × Prix achat) / Stock total
```

🔄 **Ajustement des stocks** : Se fait uniquement via la validation d'un inventaire

## 🆘 Support

Pour toute question ou problème :
- Vérifiez d'abord ce guide
- Consultez les alertes et messages d'erreur affichés
- Contactez l'administrateur système

---

**Développé pour la gestion des appartements meublés NSENOU TOWER** 🏢
