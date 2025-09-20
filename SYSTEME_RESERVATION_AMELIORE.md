# Système de Réservation Amélioré

## Vue d'ensemble

Le système de gestion des réservations a été considérablement amélioré pour permettre aux gestionnaires de créer des réservations téléphoniques avec gestion des clients, calcul automatique des prix et système de réductions.

## Nouvelles fonctionnalités

### 1. Gestion flexible des clients
- **Client existant** : Sélection parmi les locataires déjà enregistrés
- **Nouveau client** : Création automatique d'un nouveau locataire lors de la réservation
- Interface intuitive avec boutons radio pour choisir le type de client
- Validation conditionnelle selon le type de client sélectionné

### 2. Calcul automatique des prix
- **Calcul en temps réel** : Prix basé sur le tarif de l'appartement × nombre de jours
- **Système de réductions** : Réduction en pourcentage avec calcul automatique
- **Affichage détaillé** : Prix original, montant de réduction, prix final
- **Validation** : Vérification de la disponibilité avant calcul

### 3. Interface utilisateur améliorée
- **Formulaire dynamique** : Affichage conditionnel des champs selon le type de client
- **Calcul en temps réel** : Bouton pour calculer le prix instantanément
- **Affichage visuel** : Résultats du calcul dans des cartes colorées
- **Validation en temps réel** : Messages d'erreur contextuels

### 4. Gestion des réductions
- **Réduction en pourcentage** : De 0% à 100%
- **Calcul automatique** : Montant de réduction calculé automatiquement
- **Historique** : Conservation du prix original et du montant de réduction
- **Affichage dans les listes** : Badge indiquant le pourcentage de réduction

## Base de données

### Nouvelles colonnes dans la table `reservations`
```sql
reduction_pourcentage DECIMAL(5,2) DEFAULT 0.00
montant_reduction DECIMAL(10,2) DEFAULT 0.00
prix_original DECIMAL(10,2)
```

## Workflow des réservations téléphoniques

### 1. Démarrage de la réservation
- Cliquer sur "Créer une réservation"
- Choisir le type de client (existant ou nouveau)

### 2. Sélection/Configuration du client
- **Client existant** : Sélectionner dans la liste déroulante
- **Nouveau client** : Saisir nom, email et téléphone

### 3. Configuration de la réservation
- Sélectionner l'appartement
- Choisir les dates de début et fin
- Optionnel : Appliquer une réduction en pourcentage

### 4. Calcul du prix
- Cliquer sur "Calculer le prix"
- Vérifier le détail : prix original, réduction, prix final
- Ajuster la réduction si nécessaire

### 5. Finalisation
- Saisir le montant payé (optionnel)
- Choisir le type de réservation (Téléphonique)
- Ajouter des notes si nécessaire
- Confirmer la création

## Exemples d'utilisation

### Exemple 1 : Nouveau client avec réduction
1. **Client** : Nouveau client
2. **Informations** : Jean Dupont, jean@email.com, +225 XX XX XX XX
3. **Appartement** : Studio Centre-ville (50,000 FCFA/nuit)
4. **Dates** : Du 01/02/2025 au 05/02/2025 (4 nuits)
5. **Réduction** : 10%
6. **Calcul** :
   - Prix original : 200,000 FCFA (50,000 × 4)
   - Réduction : 20,000 FCFA (10%)
   - Prix final : 180,000 FCFA
7. **Paiement** : 50,000 FCFA d'acompte

### Exemple 2 : Client existant sans réduction
1. **Client** : Client existant (Marie Martin)
2. **Appartement** : Appartement 3 pièces (75,000 FCFA/nuit)
3. **Dates** : Du 10/02/2025 au 12/02/2025 (2 nuits)
4. **Réduction** : 0%
5. **Calcul** :
   - Prix original : 150,000 FCFA (75,000 × 2)
   - Réduction : 0 FCFA
   - Prix final : 150,000 FCFA
6. **Paiement** : 150,000 FCFA (paiement complet)

## Contrôles et validations

### Validation des données
- **Client existant** : Obligation de sélectionner un locataire
- **Nouveau client** : Obligation de saisir nom, email et téléphone
- **Appartement** : Vérification de la disponibilité
- **Dates** : Date de fin > date de début
- **Réduction** : Entre 0% et 100%

### Contrôles métier
- **Disponibilité** : Vérification automatique des conflits
- **Prix** : Calcul basé sur le tarif réel de l'appartement
- **Paiements** : Montant payé ≤ prix final
- **Traçabilité** : Conservation de tous les calculs

## Migration et installation

1. Exécuter les migrations :
```bash
php spark migrate
```

2. Exécuter les seeders pour mettre à jour les données existantes :
```bash
php spark db:seed UpdateReservationsDiscountFieldsSeeder
```

## Permissions

- **Admin** : Accès complet à toutes les fonctionnalités
- **Gestionnaire** : Accès aux réservations, appartements, locataires et paiements

## Avantages du système

### Pour les gestionnaires
- **Flexibilité** : Gestion des nouveaux et anciens clients
- **Précision** : Calcul automatique des prix
- **Rapidité** : Interface intuitive et calculs instantanés
- **Traçabilité** : Historique complet des réductions

### Pour l'entreprise
- **Gestion des promotions** : Système de réductions flexible
- **Fidélisation** : Gestion des clients récurrents
- **Contrôle** : Validation automatique des disponibilités
- **Reporting** : Données précises pour les analyses

### Pour les clients
- **Service personnalisé** : Réservation téléphonique facilitée
- **Transparence** : Calcul détaillé des prix
- **Flexibilité** : Paiements partiels acceptés
- **Suivi** : Historique des réservations et paiements

Ce système améliore considérablement l'efficacité de la gestion des réservations téléphoniques tout en maintenant la flexibilité nécessaire pour s'adapter aux différents besoins des clients.
