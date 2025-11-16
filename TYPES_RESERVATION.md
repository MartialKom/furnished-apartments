# Types de Réservation - Documentation

## 📋 Vue d'ensemble

Le système gère maintenant **3 types de réservation** pour mieux suivre l'origine des demandes.

---

## 🎯 Les 3 types de réservation

### 1. 🌐 En ligne (`en_ligne`)
- **Origine** : Site web du client (frontend)
- **Couleur du badge** : Vert (`bg-success`)
- **Processus** :
  - Client remplit le formulaire sur `/reservation`
  - Système envoie automatiquement 2 emails :
    - Email de confirmation au client
    - Email de notification au gestionnaire
  - Statut initial : "En attente"

**Quand utiliser** :
- Automatique pour les réservations via le formulaire frontend
- Ne pas sélectionner manuellement dans l'admin

---

### 2. 📞 Téléphonique (`telephonique`)
- **Origine** : Appel téléphonique du client
- **Couleur du badge** : Bleu clair (`bg-info`)
- **Processus** :
  - Le gestionnaire reçoit un appel
  - Saisie manuelle dans `/admin/reservations`
  - Sélectionne "Téléphonique" dans le formulaire

**Quand utiliser** :
- Client appelle pour réserver
- Client demande des informations par téléphone et réserve directement
- Prise de réservation par le service client

---

### 3. 🏢 Présentiel (`presentiel`)
- **Origine** : Client se présente à l'accueil de l'immeuble
- **Couleur du badge** : Bleu foncé (`bg-primary`)
- **Processus** :
  - Client vient en personne
  - Le gestionnaire crée la réservation sur place
  - Sélectionne "Présentiel (à l'accueil)" dans le formulaire

**Quand utiliser** :
- Client se présente directement à l'accueil
- Visite sur place suivie d'une réservation immédiate
- Walk-in clients

---

## 🎨 Affichage des badges

Les types sont affichés sous forme de badges colorés dans les listes de réservations :

| Type | Badge | Couleur |
|------|-------|---------|
| En ligne | <span style="background: #28a745; color: white; padding: 2px 8px; border-radius: 4px;">En ligne</span> | Vert |
| Téléphonique | <span style="background: #17a2b8; color: white; padding: 2px 8px; border-radius: 4px;">Téléphonique</span> | Bleu clair |
| Présentiel | <span style="background: #007bff; color: white; padding: 2px 8px; border-radius: 4px;">Présentiel</span> | Bleu foncé |

---

## 💻 Utilisation dans l'admin

### Créer une réservation

1. Allez sur `/admin/reservations`
2. Cliquez sur "Créer une réservation"
3. Remplissez le formulaire :
   - **Type de client** : Existant ou Nouveau
   - **Appartement** : Sélectionnez l'appartement disponible
   - **Dates** : Date de début et fin
   - **Réduction** : Optionnel (%)
   - **Type de réservation** :
     - ⚠️ **N'utilisez PAS "En ligne"** pour les réservations manuelles
     - ✅ Choisissez "Téléphonique" pour les appels
     - ✅ Choisissez "Présentiel" pour les clients sur place
   - **Montant payé** : Si acompte versé
   - **Notes** : Informations supplémentaires

---

## 📊 Statistiques et rapports

Vous pouvez maintenant analyser vos réservations par canal :

- **Combien de réservations en ligne ?** → Badge vert
- **Combien par téléphone ?** → Badge bleu clair
- **Combien à l'accueil ?** → Badge bleu foncé

Cela permet de :
- Mesurer l'efficacité du site web
- Évaluer la charge du service téléphonique
- Suivre le trafic à l'accueil

---

## 🗄️ Détails techniques

### Base de données
```sql
-- Champ dans la table 'reservations'
type_reservation ENUM('en_ligne', 'telephonique', 'presentiel') NOT NULL DEFAULT 'en_ligne'
```

### Migration
```
app/Database/Migrations/2025-11-16-000001_AddPresentielToTypeReservation.php
```

### Validation (Contrôleur Admin)
```php
'type_reservation' => 'required|in_list[en_ligne,telephonique,presentiel]'
```

### Fichiers modifiés
| Fichier | Modification |
|---------|-------------|
| `app/Database/Migrations/2025-11-16-000001_AddPresentielToTypeReservation.php` | Nouvelle migration |
| `app/Controllers/Admin/ReservationController.php` | Validation mise à jour (ligne 181) |
| `app/Views/admin/pages/reservations/index.php` | Formulaire et affichage mis à jour |

---

## ✅ Bonnes pratiques

### ✅ À FAIRE
- Toujours sélectionner le bon type lors de la création manuelle
- Utiliser "Téléphonique" pour les appels entrants
- Utiliser "Présentiel" pour les clients sur place
- Former le personnel à distinguer les 3 types

### ❌ À NE PAS FAIRE
- Ne jamais créer manuellement une réservation "En ligne"
- Ne pas mélanger les types (un appel n'est pas du présentiel)
- Ne pas oublier de renseigner le type (champ obligatoire)

---

## 📝 Exemples de scénarios

### Scénario 1 : Client appelle
```
Client : "Bonjour, je souhaite réserver l'appartement A2"
Gestionnaire :
1. Ouvre /admin/reservations
2. Clique "Créer une réservation"
3. Remplit le formulaire
4. Sélectionne "Téléphonique" ✅
5. Sauvegarde
```

### Scénario 2 : Client vient à l'accueil
```
Client : *Se présente physiquement*
Gestionnaire :
1. Accueille le client
2. Montre l'appartement (optionnel)
3. Ouvre /admin/reservations
4. Clique "Créer une réservation"
5. Sélectionne "Présentiel (à l'accueil)" ✅
6. Prend l'acompte si nécessaire
7. Sauvegarde
```

### Scénario 3 : Client réserve via le site
```
Client : *Remplit le formulaire sur le site*
Système :
1. Type automatiquement = "en_ligne" ✅
2. Envoi email client
3. Envoi email gestionnaire
4. Création en base avec statut "En attente"
```

---

## 🔄 Migration effectuée

La migration a déjà été exécutée avec succès :

```bash
php spark migrate
# Running: (App) 2025-11-16-000001_App\Database\Migrations\AddPresentielToTypeReservation
# Migrations complete.
```

Toutes les réservations existantes conservent leur type actuel.

---

## 📞 Support

Si vous avez des questions sur les types de réservation :
- Consultez cette documentation
- Vérifiez les badges de couleur dans l'admin
- En cas de doute, choisissez "Téléphonique" pour les créations manuelles

---

**Dernière mise à jour** : 2025-11-16
**Version** : 1.0
**Statut** : ✅ Opérationnel
