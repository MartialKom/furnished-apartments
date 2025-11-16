# Documentation - Module de Gestion de Location de Voitures

## Table des Matières
1. [Vue d'ensemble](#vue-densemble)
2. [Structure de la Base de Données](#structure-de-la-base-de-données)
3. [Fonctionnalités Principales](#fonctionnalités-principales)
4. [Guide d'Utilisation](#guide-dutilisation)
5. [Routes API](#routes-api)
6. [Workflow de Location](#workflow-de-location)
7. [Alertes et Maintenance](#alertes-et-maintenance)

---

## Vue d'ensemble

Le module de gestion de location de voitures permet de gérer un parc automobile et les locations associées. Il offre :

- **Gestion du parc automobile** : Enregistrement et suivi des véhicules
- **Location flexible** : Location à des clients existants ou nouveaux
- **Suivi complet** : Gestion des paiements, kilométrage, état des véhicules
- **Alertes** : Notifications pour assurance/visite technique à renouveler

### Technologies
- **Framework** : CodeIgniter 4
- **Base de données** : MySQL
- **Frontend** : Bootstrap 5 + Feather Icons
- **Communication** : AJAX/JSON

---

## Structure de la Base de Données

### Table `voitures`

Stocke les informations sur les véhicules du parc automobile.

```sql
CREATE TABLE voitures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(100) NOT NULL,                    -- Marque (Toyota, Peugeot, etc.)
    modele VARCHAR(100) NOT NULL,                    -- Modèle (Corolla, 308, etc.)
    annee INT(4) NOT NULL,                           -- Année de fabrication
    immatriculation VARCHAR(50) UNIQUE NOT NULL,     -- Plaque d'immatriculation
    couleur VARCHAR(50),                             -- Couleur du véhicule
    nombre_places INT NOT NULL,                      -- Nombre de places
    type_carburant ENUM('essence','diesel','electrique','hybride') NOT NULL,
    transmission ENUM('manuelle','automatique') NOT NULL,
    kilometrage INT NOT NULL DEFAULT 0,              -- Kilométrage actuel
    tarif_journalier DECIMAL(10,2) NOT NULL,         -- Prix de location par jour
    caution DECIMAL(10,2),                           -- Montant de la caution
    statut ENUM('disponible','louee','maintenance','indisponible') NOT NULL DEFAULT 'disponible',
    photo VARCHAR(255),                              -- URL de la photo
    numero_chassis VARCHAR(100),                     -- Numéro de chassis
    assurance_expire_le DATE,                        -- Date d'expiration assurance
    visite_technique_expire_le DATE,                 -- Date d'expiration visite technique
    notes TEXT,                                      -- Notes diverses
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**Statuts possibles** :
- `disponible` : Voiture prête à être louée
- `louee` : Voiture actuellement en location
- `maintenance` : Voiture en réparation/entretien
- `indisponible` : Voiture hors service

### Table `locations_voitures`

Gère les locations de véhicules.

```sql
CREATE TABLE locations_voitures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voiture_id INT NOT NULL,                         -- Référence à la voiture
    locataire_id INT,                                -- Client existant (nullable)
    client_nom VARCHAR(255),                         -- Nom du nouveau client (nullable)
    client_email VARCHAR(255),                       -- Email du nouveau client
    client_telephone VARCHAR(50),                    -- Téléphone du nouveau client
    client_permis VARCHAR(100),                      -- Numéro de permis de conduire

    -- Dates
    date_debut DATE NOT NULL,                        -- Date de début de location
    date_fin_prevue DATE NOT NULL,                   -- Date de fin prévue
    date_fin_reelle DATE,                            -- Date de retour effective
    nombre_jours INT NOT NULL,                       -- Durée en jours

    -- Tarification
    tarif_journalier DECIMAL(10,2) NOT NULL,         -- Tarif du jour (copié depuis voiture)
    montant_total DECIMAL(10,2) NOT NULL,            -- Montant total = tarif × jours
    caution_versee DECIMAL(10,2),                    -- Montant de caution versé
    caution_restituee BOOLEAN DEFAULT FALSE,         -- Caution rendue ?
    montant_paye DECIMAL(10,2) DEFAULT 0,            -- Montant déjà payé
    montant_restant DECIMAL(10,2) DEFAULT 0,         -- Montant restant à payer
    mode_paiement ENUM('especes','virement','cheque','mobile_money'),

    -- Kilométrage et état
    kilometrage_depart INT,                          -- Km au départ
    kilometrage_retour INT,                          -- Km au retour
    etat_depart ENUM('excellent','bon','moyen','mauvais'),
    etat_retour ENUM('excellent','bon','moyen','mauvais'),

    -- Statut et notes
    statut ENUM('en_attente','en_cours','terminee','annulee') NOT NULL DEFAULT 'en_attente',
    notes TEXT,
    motif_annulation TEXT,                           -- Raison si annulée
    enregistre_par INT,                              -- ID de l'utilisateur

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (voiture_id) REFERENCES voitures(id) ON DELETE RESTRICT,
    FOREIGN KEY (locataire_id) REFERENCES locataires(id) ON DELETE SET NULL,
    FOREIGN KEY (enregistre_par) REFERENCES utilisateurs(id) ON DELETE SET NULL
);
```

**Statuts de location** :
- `en_attente` : Location créée, en attente du départ
- `en_cours` : Véhicule entre les mains du client
- `terminee` : Location terminée, véhicule retourné
- `annulee` : Location annulée

---

## Fonctionnalités Principales

### 1. Gestion du Parc Automobile

#### Ajouter une Voiture
- Formulaire complet avec validation
- Champs obligatoires : marque, modèle, année, immatriculation, places, carburant, transmission, kilométrage, tarif
- Champs optionnels : couleur, chassis, photo, caution, dates d'expiration, notes

#### Modifier une Voiture
- Mise à jour de toutes les informations
- Changement de statut (disponible, maintenance, indisponible)
- Mise à jour du kilométrage

#### Voir les Détails
- Affichage complet de toutes les informations
- Indicateurs visuels pour le statut
- Alertes si assurance/visite technique proche de l'expiration

#### Supprimer une Voiture
- Suppression uniquement si jamais louée
- Confirmation requise

#### Organisation par Statut
- **Onglet Disponibles** : Voitures prêtes à louer (badge vert)
- **Onglet Louées** : Voitures actuellement en location (badge jaune)
- **Onglet Maintenance** : Voitures en réparation (badge bleu)
- **Onglet Indisponibles** : Voitures hors service (badge rouge)

### 2. Gestion des Locations

#### Créer une Location

**Choix du client** :
- **Client existant** : Sélection depuis la liste des locataires
- **Nouveau client** : Saisie nom, téléphone, email

**Informations requises** :
- Voiture (avec affichage du tarif)
- Dates de début et fin
- Numéro de permis de conduire (optionnel)
- Montant initial payé
- Mode de paiement
- Caution versée

**Calcul automatique** :
- Nombre de jours = (date_fin - date_debut) + 1
- Montant total = tarif_journalier × nombre_jours
- Montant restant = montant_total - montant_payé

**Validation** :
- Vérifie que la voiture est disponible
- Vérifie qu'il n'y a pas de conflit de dates avec d'autres locations

#### Démarrer une Location
Passage de `en_attente` → `en_cours`

**Informations saisies** :
- Kilométrage au départ (obligatoire)
- État du véhicule au départ (excellent/bon/moyen/mauvais)

**Actions automatiques** :
- Changement du statut de la location
- Changement du statut de la voiture à `louee`

#### Terminer une Location (Retour)
Passage de `en_cours` → `terminee`

**Informations saisies** :
- Date de retour effective
- Kilométrage au retour (obligatoire)
- État du véhicule au retour
- Caution restituée (oui/non)

**Calculs automatiques** :
- Distance parcourue = km_retour - km_depart

**Actions automatiques** :
- Mise à jour du kilométrage de la voiture
- Changement du statut de la voiture à `disponible`
- Changement du statut de la location à `terminee`

#### Annuler une Location
Passage de `en_attente` ou `en_cours` → `annulee`

**Informations requises** :
- Motif d'annulation (obligatoire)

**Actions automatiques** :
- Changement du statut de la voiture à `disponible`
- Enregistrement du motif

#### Ajouter un Paiement
Pour les locations en cours avec solde restant

**Informations requises** :
- Montant du paiement
- Mode de paiement

**Calculs automatiques** :
- montant_paye += nouveau_paiement
- montant_restant = montant_total - montant_paye

#### Voir les Détails d'une Location

Affichage complet :
- Informations véhicule (marque, modèle, immatriculation)
- Informations client (nom, téléphone, email, permis)
- Période de location (début, fin prévue, fin réelle, durée)
- Kilométrage (départ, retour, distance parcourue)
- Tarification (tarif/jour, total, payé, restant)
- Progression du paiement (barre de progression visuelle)
- Caution (versée, restituée)
- État du véhicule (départ, retour)
- Notes et motif d'annulation si applicable

### 3. Organisation des Locations par Statut

#### Onglet "En Attente"
- Locations créées mais pas encore démarrées
- **Actions disponibles** :
  - Démarrer la location
  - Voir les détails
  - Annuler

#### Onglet "En Cours"
- Locations actuellement actives
- Affichage des alertes de retard
- **Actions disponibles** :
  - Enregistrer le retour
  - Voir les détails
  - Ajouter un paiement (si solde restant)

**Alertes de retard** :
- 🔴 En retard : Si date actuelle > date_fin_prevue
- 🟡 Retour imminent : Si retour prévu aujourd'hui ou demain

#### Onglet "Terminées"
- Locations complétées
- Indication du statut de paiement (soldé / impayé)
- **Actions disponibles** :
  - Voir les détails

#### Onglet "Annulées"
- Locations annulées
- Affichage du motif d'annulation
- **Actions disponibles** :
  - Voir les détails

---

## Guide d'Utilisation

### Scénario 1 : Ajouter un Véhicule

1. Aller dans **Location de Voitures > Parc Automobile**
2. Cliquer sur **"Ajouter une voiture"**
3. Remplir le formulaire :
   - Marque : Toyota
   - Modèle : Corolla
   - Année : 2020
   - Immatriculation : AB-1234-CD
   - Couleur : Blanc
   - Places : 5
   - Carburant : Essence
   - Transmission : Automatique
   - Kilométrage : 45000
   - Tarif journalier : 25000 FCFA
   - Caution : 100000 FCFA
4. Cliquer sur **"Enregistrer"**

### Scénario 2 : Créer une Location pour un Nouveau Client

1. Aller dans **Location de Voitures > Locations Voitures**
2. Cliquer sur **"Nouvelle Location"**
3. Sélectionner la voiture
4. Choisir **"Nouveau Client"**
5. Remplir les informations client :
   - Nom : Jean Dupont
   - Téléphone : +225 07 12 34 56 78
   - Email : jean@example.com
   - Permis : ABC123456
6. Sélectionner les dates :
   - Début : 16/11/2025
   - Fin : 20/11/2025
   - → Calcul auto : 5 jours × 25000 = 125000 FCFA
7. Saisir le paiement initial :
   - Montant payé : 50000 FCFA
   - Mode : Espèces
   - Caution versée : 100000 FCFA
8. Cliquer sur **"Enregistrer"**

### Scénario 3 : Workflow Complet d'une Location

#### Étape 1 : Création (Statut : En Attente)
- Location créée avec dates et montants
- Voiture reste `disponible`

#### Étape 2 : Démarrage (Statut : En Cours)
1. Dans l'onglet **"En Attente"**, cliquer sur **"Démarrer"**
2. Saisir le kilométrage : 45000 km
3. Sélectionner l'état : Bon
4. Confirmer
- ✅ Voiture passe à `louee`
- ✅ Location passe à `en_cours`

#### Étape 3 : Paiement additionnel (Optionnel)
1. Dans l'onglet **"En Cours"**, cliquer sur l'icône paiement 💰
2. Saisir montant : 50000 FCFA
3. Mode : Virement
4. Confirmer
- ✅ Total payé : 100000 FCFA
- ✅ Restant : 25000 FCFA

#### Étape 4 : Retour (Statut : Terminée)
1. Dans l'onglet **"En Cours"**, cliquer sur **"Retour"**
2. Date de retour : 20/11/2025
3. Kilométrage : 45350 km (350 km parcourus)
4. État : Bon
5. Cocher **"Caution restituée"**
6. Confirmer
- ✅ Kilométrage voiture mis à jour : 45350 km
- ✅ Voiture passe à `disponible`
- ✅ Location passe à `terminee`

### Scénario 4 : Annuler une Location

1. Dans l'onglet **"En Attente"** ou **"En Cours"**, cliquer sur **"Annuler"** ❌
2. Saisir le motif : "Client a changé d'avis"
3. Confirmer
- ✅ Voiture passe à `disponible`
- ✅ Location passe à `annulee`

---

## Routes API

### Routes Voitures

| Méthode | Route | Description |
|---------|-------|-------------|
| GET | `/admin/voitures` | Afficher la liste des voitures |
| POST | `/admin/voitures/store` | Créer une nouvelle voiture |
| GET | `/admin/voitures/get/{id}` | Récupérer une voiture |
| POST | `/admin/voitures/update/{id}` | Modifier une voiture |
| DELETE | `/admin/voitures/delete/{id}` | Supprimer une voiture |
| POST | `/admin/voitures/changer-statut/{id}` | Changer le statut |

#### Exemple : Créer une voiture

**Requête** :
```javascript
POST /admin/voitures/store
Content-Type: application/json

{
    "marque": "Toyota",
    "modele": "Corolla",
    "annee": 2020,
    "immatriculation": "AB-1234-CD",
    "couleur": "Blanc",
    "nombre_places": 5,
    "type_carburant": "essence",
    "transmission": "automatique",
    "kilometrage": 45000,
    "tarif_journalier": 25000,
    "caution": 100000,
    "statut": "disponible"
}
```

**Réponse** :
```json
{
    "success": true,
    "message": "Voiture ajoutée avec succès !"
}
```

### Routes Locations

| Méthode | Route | Description |
|---------|-------|-------------|
| GET | `/admin/locations-voitures` | Liste des locations |
| POST | `/admin/locations-voitures/store` | Créer une location |
| GET | `/admin/locations-voitures/get/{id}` | Détails d'une location |
| POST | `/admin/locations-voitures/demarrer/{id}` | Démarrer une location |
| POST | `/admin/locations-voitures/terminer/{id}` | Terminer une location |
| POST | `/admin/locations-voitures/annuler/{id}` | Annuler une location |
| POST | `/admin/locations-voitures/paiement/{id}` | Enregistrer un paiement |

#### Exemple : Créer une location (nouveau client)

**Requête** :
```javascript
POST /admin/locations-voitures/store
Content-Type: application/json

{
    "voiture_id": 1,
    "client_type": "nouveau",
    "client_nom": "Jean Dupont",
    "client_telephone": "+225 07 12 34 56 78",
    "client_email": "jean@example.com",
    "client_permis": "ABC123456",
    "date_debut": "2025-11-16",
    "date_fin_prevue": "2025-11-20",
    "montant_paye": 50000,
    "mode_paiement": "especes",
    "caution_versee": 100000,
    "notes": "Client préfère assurance tous risques"
}
```

**Réponse** :
```json
{
    "success": true,
    "message": "Location créée avec succès !"
}
```

#### Exemple : Démarrer une location

**Requête** :
```javascript
POST /admin/locations-voitures/demarrer/5
Content-Type: application/json

{
    "kilometrage_depart": 45000,
    "etat_depart": "bon"
}
```

**Réponse** :
```json
{
    "success": true,
    "message": "Location démarrée avec succès !"
}
```

---

## Workflow de Location

```
┌─────────────────┐
│  CRÉATION       │
│  (en_attente)   │
│  Voiture:       │
│  disponible     │
└────────┬────────┘
         │
         │ Démarrer
         ▼
┌─────────────────┐
│  EN COURS       │
│  (en_cours)     │
│  Voiture: louee │
└────────┬────────┘
         │
         ├─── Ajouter paiement (optionnel)
         │
         │ Terminer / Annuler
         ▼
┌─────────────────┐
│  TERMINÉE /     │
│  ANNULÉE        │
│  Voiture:       │
│  disponible     │
└─────────────────┘
```

---

## Alertes et Maintenance

### Alertes Automatiques

#### 1. Alertes de Documents
La méthode `VoitureModel::getAlertes()` retourne les véhicules dont :
- L'assurance expire dans les 30 prochains jours
- La visite technique expire dans les 30 prochains jours

**Utilisation recommandée** :
```php
$voitureModel = new VoitureModel();
$alertes = $voitureModel->getAlertes();

foreach ($alertes as $voiture) {
    // Envoyer notification / email
    echo "⚠️ {$voiture['marque']} {$voiture['modele']} - ";
    if (strtotime($voiture['assurance_expire_le']) < strtotime('+30 days')) {
        echo "Assurance expire le {$voiture['assurance_expire_le']}";
    }
}
```

#### 2. Alertes de Retard
Les locations en cours dont la `date_fin_prevue` est dépassée sont marquées visuellement dans l'interface.

### Maintenance Régulière

#### Mettre une Voiture en Maintenance
1. Dans **Parc Automobile**, cliquer sur **"Modifier"**
2. Changer le statut à **"En Maintenance"**
3. Ajouter une note expliquant la raison
4. Sauvegarder

La voiture n'apparaîtra plus dans les voitures disponibles pour location.

#### Retour de Maintenance
1. Ouvrir la modification de la voiture
2. Mettre à jour le kilométrage si nécessaire
3. Changer le statut à **"Disponible"**
4. Sauvegarder

### Vérifications Recommandées

**Quotidiennes** :
- ✅ Vérifier les locations en retard
- ✅ Vérifier les paiements en attente

**Hebdomadaires** :
- ✅ Vérifier les alertes d'assurance/visite technique
- ✅ Mettre à jour les kilométrages

**Mensuelles** :
- ✅ Analyser le taux d'occupation des véhicules
- ✅ Vérifier les véhicules peu/pas utilisés

---

## Modèles et Méthodes Clés

### VoitureModel

```php
// Récupérer les voitures disponibles
$voitures = $voitureModel->getVoituresDisponibles();

// Récupérer par statut
$enMaintenance = $voitureModel->getByStatut('maintenance');

// Vérifier disponibilité pour dates
$dispo = $voitureModel->estDisponible($voitureId, '2025-11-16', '2025-11-20');

// Marquer comme louée
$voitureModel->marquerCommeLouee($voitureId);

// Marquer comme disponible
$voitureModel->marquerCommeDisponible($voitureId);

// Mettre à jour kilométrage
$voitureModel->updateKilometrage($voitureId, 45350);

// Obtenir alertes
$alertes = $voitureModel->getAlertes();
```

### LocationVoitureModel

```php
// Créer une location
$data = [
    'voiture_id' => 1,
    'locataire_id' => null,
    'client_nom' => 'Jean Dupont',
    'date_debut' => '2025-11-16',
    'date_fin_prevue' => '2025-11-20',
    // ... autres champs
];
$locationId = $locationModel->creerLocation($data);

// Démarrer une location
$locationModel->demarrerLocation($locationId, [
    'kilometrage_depart' => 45000,
    'etat_depart' => 'bon'
]);

// Terminer une location
$locationModel->terminerLocation($locationId, [
    'date_fin_reelle' => '2025-11-20',
    'kilometrage_retour' => 45350,
    'etat_retour' => 'bon',
    'caution_restituee' => true
]);

// Annuler une location
$locationModel->annulerLocation($locationId, 'Client a annulé');

// Enregistrer un paiement
$locationModel->enregistrerPaiement($locationId, 25000, 'especes');

// Récupérer locations avec détails
$locations = $locationModel->getLocationsWithDetails();

// Récupérer locations en retard
$enRetard = $locationModel->getLocationsEnRetard();
```

---

## Règles de Gestion

### Règles de Disponibilité
1. ✅ Une voiture ne peut être louée que si son statut est `disponible`
2. ✅ On ne peut pas créer deux locations qui se chevauchent pour la même voiture
3. ✅ Une voiture en maintenance/indisponible ne peut pas être louée

### Règles de Paiement
1. ✅ Le montant total est automatiquement calculé : `tarif × jours`
2. ✅ Le montant payé ne peut pas dépasser le montant total
3. ✅ Le montant restant est recalculé à chaque paiement

### Règles de Statut
1. ✅ Une location `en_attente` peut être démarrée ou annulée
2. ✅ Une location `en_cours` peut être terminée ou annulée
3. ✅ Une location `terminee` ou `annulee` ne peut plus être modifiée

### Règles de Kilométrage
1. ✅ Le kilométrage de retour doit être >= kilométrage de départ
2. ✅ Le kilométrage de la voiture est mis à jour au retour

---

## Sécurité

### Authentification
- ✅ Toutes les routes sont protégées par le filtre `auth`
- ✅ Seuls les utilisateurs avec droits `reservations` peuvent gérer les locations

### Validation des Données
- ✅ Validation côté serveur (models)
- ✅ Validation côté client (JavaScript)
- ✅ Protection XSS avec `esc()`
- ✅ Requêtes préparées (protection SQL injection)

### Gestion des Erreurs
- ✅ Messages d'erreur clairs et informatifs
- ✅ Logs des erreurs importantes
- ✅ Rollback en cas d'échec de transaction

---

## Améliorations Futures

### Court Terme
- [ ] Génération automatique de contrats de location PDF
- [ ] Envoi d'email de confirmation au client
- [ ] Rappels automatiques avant la date de retour
- [ ] Import/Export des données (Excel/CSV)

### Moyen Terme
- [ ] Tableau de bord avec statistiques
  - Taux d'occupation par véhicule
  - Revenus par période
  - Clients les plus fidèles
- [ ] Historique complet des locations par véhicule
- [ ] Gestion des dommages et frais supplémentaires
- [ ] Module de facturation avancé

### Long Terme
- [ ] Interface client pour réservation en ligne
- [ ] Calendrier de disponibilité visuel
- [ ] Intégration paiement en ligne
- [ ] Application mobile
- [ ] Système de notation client/véhicule

---

## Support et Contact

Pour toute question ou problème :
- 📧 Email : support@example.com
- 📞 Téléphone : +225 XX XX XX XX XX
- 📝 Documentation : Voir ce fichier

---

**Version** : 1.0
**Date** : 16 Novembre 2025
**Auteur** : Système de Gestion Furnished Apartments
