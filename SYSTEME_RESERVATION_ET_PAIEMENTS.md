# Système de Réservation et Paiements

## Vue d'ensemble

Le système de gestion des réservations et paiements a été étendu pour permettre aux gestionnaires de créer des réservations manuelles et de gérer les paiements partiels.

## Nouvelles fonctionnalités

### 1. Types d'appartements
- **Meublé** : Appartement avec meubles
- **Non meublé** : Appartement sans meubles
- Possibilité de transformer un appartement meublé en non meublé (et vice versa)
- Restriction : impossible de modifier le type s'il y a des réservations actives

### 2. Types de réservations
- **En ligne** : Réservation effectuée via le site web
- **Téléphonique** : Réservation effectuée par appel téléphonique et enregistrée par un gestionnaire

### 3. Système de paiements partiels
- Les clients peuvent payer une partie du montant à la réservation
- Possibilité d'ajouter des paiements supplémentaires pendant ou après le séjour
- Suivi complet de tous les paiements pour la traçabilité
- Calcul automatique du montant restant

### 4. Interface de gestion
- Bouton "Créer une réservation" pour les gestionnaires
- Colonnes supplémentaires dans les tableaux :
  - Type d'appartement
  - Paiements (montant payé / montant restant)
  - Type de réservation
- Boutons d'action pour chaque réservation :
  - Ajouter un paiement
  - Voir l'historique des paiements

## Base de données

### Nouvelles colonnes dans la table `appartements`
```sql
type ENUM('meuble', 'non_meuble') DEFAULT 'meuble'
```

### Nouvelles colonnes dans la table `reservations`
```sql
montant_paye DECIMAL(10,2) DEFAULT 0.00
montant_restant DECIMAL(10,2)
type_reservation ENUM('en_ligne', 'telephonique') DEFAULT 'en_ligne'
notes TEXT
```

## Workflow des réservations téléphoniques

1. **Réception de l'appel** : Le client appelle pour réserver
2. **Création manuelle** : Le gestionnaire utilise le bouton "Créer une réservation"
3. **Sélection des données** :
   - Locataire (création automatique si nouveau)
   - Appartement disponible
   - Dates de séjour
   - Montant total calculé automatiquement
   - Montant payé (si le client verse un acompte)
   - Type : "Téléphonique"
   - Notes (informations supplémentaires)
4. **Confirmation** : La réservation est automatiquement confirmée
5. **Paiements ultérieurs** : Possibilité d'ajouter des paiements via le bouton "Ajouter un paiement"

## Contrôles et validations

- **Disponibilité** : Vérification automatique de la disponibilité de l'appartement
- **Paiements** : Impossible de payer plus que le montant restant
- **Types d'appartements** : Impossible de modifier le type avec des réservations actives
- **Traçabilité** : Historique complet de tous les paiements

## Migration et installation

1. Exécuter les migrations :
```bash
php spark migrate
```

2. Exécuter les seeders pour mettre à jour les données existantes :
```bash
php spark db:seed UpdateAppartementsTypeSeeder
php spark db:seed UpdateReservationsPaymentFieldsSeeder
```

## Permissions

- **Admin** : Accès complet à toutes les fonctionnalités
- **Gestionnaire** : Accès aux réservations, appartements, locataires et paiements (pas aux utilisateurs, analytics, etc.)

## Utilisation

### Pour créer une réservation téléphonique :
1. Aller dans "Réservations"
2. Cliquer sur "Créer une réservation"
3. Remplir le formulaire avec les informations du client
4. Sélectionner "Téléphonique" comme type
5. Sauvegarder

### Pour ajouter un paiement :
1. Dans la liste des réservations, cliquer sur l'icône de paiement (💳)
2. Saisir le montant et la date
3. Confirmer

### Pour voir l'historique des paiements :
1. Cliquer sur l'icône d'œil (👁️) dans la liste des réservations
2. Visualiser tous les paiements effectués

### Pour transformer un appartement :
1. Dans la gestion des appartements, cliquer sur l'icône de transformation (🔄)
2. Confirmer la transformation

Cette fonctionnalité améliore considérablement la flexibilité du système et permet une gestion complète des réservations et paiements, que ce soit en ligne ou par téléphone.
