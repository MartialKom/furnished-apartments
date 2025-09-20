# Système de Gestion des Locataires à Long Terme

## Vue d'ensemble

Ce système permet de gérer les locataires qui louent des appartements pour une durée indéterminée avec des paiements mensuels récurrents. Il inclut un système de notifications automatiques et de suivi des échéances.

## Fonctionnalités principales

### 1. Gestion des Contrats
- **Création de contrats** : Possibilité de créer des contrats pour des locataires existants
- **Contrats indéterminés** : Date de fin optionnelle pour des contrats à long terme
- **Négociation de prix** : Possibilité de définir un loyer mensuel personnalisé
- **Jour de paiement fixe** : Définition du jour du mois pour les échéances
- **Caution** : Gestion de la caution versée par le locataire

### 2. Système de Paiements Mensuels
- **Échéances automatiques** : Génération automatique des échéances mensuelles
- **Paiements partiels** : Enregistrement de paiements partiels
- **Paiements multiples** : Possibilité de payer plusieurs mois en une fois
- **Modes de paiement** : Espèces, virement, chèque, mobile money
- **Suivi des retards** : Détection et suivi automatique des paiements en retard

### 3. Notifications Automatiques
- **Rappels d'échéance** : Notifications 5 jours avant l'échéance
- **Alertes de retard** : Notifications quotidiennes pour les paiements en retard
- **Notifications admin** : Alerte des gestionnaires et administrateurs
- **Configuration personnalisée** : Heure préférée pour recevoir les notifications

### 4. Dashboard et Suivi
- **Tableau de bord** : Vue d'ensemble des échéances et retards
- **Statistiques** : Taux de recouvrement, montants dus/payés
- **Actions rapides** : Envoi de rappels, enregistrement de paiements

## Structure de la Base de Données

### Table `contrats_locataires`
```sql
- id (INT, PK)
- locataire_id (INT, FK vers locataires)
- appartement_id (INT, FK vers appartements)
- date_debut (DATE)
- date_fin (DATE, nullable pour contrats indéterminés)
- loyer_mensuel (DECIMAL)
- jour_paiement (TINYINT, 1-31)
- caution (DECIMAL)
- statut (ENUM: actif, suspendu, termine)
- conditions_particulieres (TEXT)
- created_at, updated_at
```

### Table `paiements_mensuels`
```sql
- id (INT, PK)
- contrat_id (INT, FK vers contrats_locataires)
- mois_annee (VARCHAR, format YYYY-MM)
- montant_du (DECIMAL)
- montant_paye (DECIMAL)
- date_echeance (DATE)
- date_paiement (DATE, nullable)
- statut (ENUM: en_attente, paye, en_retard, partiellement_paye)
- nombre_mois_payes (INT, pour paiements multiples)
- mode_paiement (ENUM: especes, virement, cheque, mobile_money)
- reference_paiement (VARCHAR)
- notes (TEXT)
- enregistre_par (INT, FK vers utilisateurs)
- created_at, updated_at
```

### Table `utilisateurs` (champs ajoutés)
```sql
- notifications_email (BOOLEAN, défaut: true)
- heure_notification (TIME, défaut: 09:00:00)
```

## Utilisation du Système

### 1. Créer un Contrat
1. Aller dans **Contrats Long Terme** > **Nouveau Contrat**
2. Sélectionner le locataire et l'appartement
3. Définir les dates, le loyer mensuel et le jour de paiement
4. Ajouter des conditions particulières si nécessaire
5. Le système génère automatiquement les échéances pour 12 mois

### 2. Enregistrer un Paiement
1. Dans les détails du contrat, cliquer sur **Enregistrer un Paiement**
2. Sélectionner le mois concerné
3. Saisir le montant payé et le nombre de mois couverts
4. Choisir le mode de paiement et ajouter une référence
5. Le système met automatiquement à jour les statuts

### 3. Gestion des Notifications
- **Automatique** : Le système envoie des rappels 5 jours avant l'échéance
- **Manuel** : Possibilité d'envoyer des rappels manuellement depuis le dashboard
- **Configuration** : Chaque utilisateur peut configurer ses préférences de notification

## Commandes Cron

### Vérification des Échéances
```bash
php spark check:echeances
```

Cette commande :
- Met à jour les statuts des paiements en retard
- Envoie les notifications d'échéances proches
- Envoie les alertes de retard
- Génère les échéances pour les nouveaux contrats

### Configuration Cron (Linux/Mac)
```bash
# Vérifier les échéances tous les jours à 9h00
0 9 * * * cd /path/to/project && php spark check:echeances
```

### Configuration Windows (Tâches Planifiées)
1. Ouvrir le Planificateur de tâches Windows
2. Créer une tâche de base
3. Programmer l'exécution de : `php spark check:echeances`
4. Définir la fréquence : Quotidienne à 9h00

## Configuration Email

### Gmail (Recommandé)
1. Activer l'authentification à 2 facteurs
2. Générer un mot de passe d'application
3. Configurer dans `app/Config/Email.php` :
```php
public string $SMTPHost = 'smtp.gmail.com';
public string $SMTPUser = 'votre-email@gmail.com';
public string $SMTPPass = 'votre-mot-de-passe-application';
public int $SMTPPort = 587;
public string $SMTPCrypto = 'tls';
```

### Autres Fournisseurs
- **Outlook** : smtp-mail.outlook.com, port 587
- **Yahoo** : smtp.mail.yahoo.com, port 587
- **Serveur SMTP local** : Configurer selon votre hébergeur

## Sécurité et Permissions

### Rôles d'Accès
- **Admin** : Accès complet à tous les contrats et paiements
- **Gestionnaire** : Accès aux contrats et paiements, enregistrement des paiements

### Filtres de Sécurité
- Authentification requise pour toutes les actions
- Validation des données côté serveur
- Protection CSRF sur tous les formulaires
- Logs des actions importantes

## Maintenance

### Sauvegarde
- Sauvegarder régulièrement la base de données
- Exporter les contrats et paiements pour archivage

### Monitoring
- Vérifier les logs d'erreur régulièrement
- Surveiller les taux de recouvrement
- Analyser les retards de paiement

### Mises à Jour
- Maintenir CodeIgniter et les dépendances à jour
- Tester les nouvelles fonctionnalités en environnement de développement

## Support et Dépannage

### Problèmes Courants
1. **Emails non envoyés** : Vérifier la configuration SMTP
2. **Échéances non générées** : Exécuter manuellement la commande cron
3. **Erreurs de validation** : Vérifier les formats de dates et montants

### Logs
- Logs d'application : `writable/logs/`
- Logs de base de données : Vérifier les erreurs MySQL
- Logs d'email : Configurer le logging dans Email.php

## Évolutions Futures

### Fonctionnalités Prévues
- Génération de rapports PDF
- Intégration avec des systèmes de paiement en ligne
- Application mobile pour les locataires
- Système de relance automatique par SMS
- Historique complet des modifications
- Export des données pour comptabilité

### Optimisations
- Cache des requêtes fréquentes
- Pagination pour les grandes listes
- Recherche avancée dans les contrats
- Filtres par statut et date
