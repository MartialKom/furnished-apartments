# Système d'Email et Notifications - Documentation Complète

## 📧 Vue d'ensemble

Le système d'email est maintenant entièrement fonctionnel avec des templates HTML professionnels et des notifications automatiques pour tous les événements importants.

---

## ✅ Configuration SMTP

### Fichier `.env`
```env
SMTP_HOST = smtp-fr.securemail.pro
SMTP_USER = contact@nsenoutower.com
SMTP_PASS = Nsenoutower@2025
SMTP_PORT = 465
SMTP_CRYPTO = ssl
SMTP_USER_GESTIONNAIRE = gestionnaire@nsenoutower.com
```

### Fichier `app/Config/Email.php`
- Charge automatiquement les paramètres depuis `.env`
- Support SSL sur le port 465
- Templates HTML activés par défaut
- Timeout de 60 secondes

---

## 📨 Points d'envoi d'emails

### 1. Réservations depuis le site web

**Déclencheur** : Lorsqu'un client crée une réservation via `/reservation`

**Emails envoyés** :
1. **Email au client** (`client_email`)
   - Sujet : "Confirmation de votre réservation - [Nom de l'entreprise]"
   - Contenu : Confirmation de réception avec détails de la réservation
   - Template HTML : Vert avec détails complets

2. **Email au gestionnaire** (`gestionnaire@nsenoutower.com`)
   - Sujet : "Nouvelle réservation en ligne - [Nom du client]"
   - Contenu : Notification avec toutes les informations du client
   - Template HTML : Bleu avec lien vers l'admin
   - Bouton : "Voir dans l'admin"

**Fichier** : `app/Controllers/ReservationController.php` (ligne 111-127)

---

### 2. Relances de paiement pour locataires (Échéances proches)

**Déclencheur** :
- Bouton "Envoyer rappel" dans l'admin (`/admin/paiements-mensuels`)
- Commande automatique : `php spark check:echeances`

**Email envoyé au locataire** :
- Sujet : "Rappel de paiement - Échéance du [date]"
- Contenu : Rappel amical avec compte à rebours
- Template HTML : Orange avec détails du paiement
- Informations affichées :
  - Nom du locataire
  - Adresse de l'appartement
  - Période de location
  - Montant dû
  - Date d'échéance
  - Jours restants avant l'échéance

**Email copie au gestionnaire** :
- Notification qu'un rappel a été envoyé
- Informations du locataire pour suivi

**Fichier** : `app/Libraries/NotificationService.php` (ligne 141-183)

---

### 3. Relances de retard de paiement

**Déclencheur** :
- Bouton "Envoyer rappel" dans l'admin (pour paiements en retard)
- Commande automatique : `php spark check:echeances`

**Email envoyé au locataire** :
- Sujet : "URGENT: Retard de paiement - Action requise"
- Contenu : Alerte de retard avec incitation à régulariser
- Template HTML : Rouge avec message d'urgence
- Informations affichées :
  - Nom du locataire
  - Adresse de l'appartement
  - Période de location
  - Montant dû
  - Date d'échéance passée
  - **Nombre de jours de retard**
  - Message d'encouragement à contacter en cas de difficultés

**Email copie au gestionnaire** :
- Notification de retard avec détails complets
- Informations de contact du locataire

**Fichier** : `app/Libraries/NotificationService.php` (ligne 188-230)

---

## 🎨 Templates HTML

Tous les emails utilisent des templates HTML professionnels avec :

### En-tête standard
- Logo et nom de l'entreprise (depuis paramètres)
- Titre personnalisé par type d'email
- Couleur adaptée au type de message :
  - 🟢 Vert : Confirmations
  - 🟠 Orange : Rappels
  - 🔴 Rouge : Urgences/retards
  - 🔵 Bleu : Notifications admin

### Corps du message
- Design responsive (mobile-friendly)
- Tableaux de données clairs
- Encadrés colorés pour les informations importantes
- Boutons d'action (quand applicable)

### Pied de page
- Informations de l'entreprise (modifiables)
- Coordonnées de contact
- Message automatique

**Fichiers** :
- `getEmailHeader()` : ligne 541-550
- `getEmailFooter()` : ligne 555-584
- Templates spécifiques : lignes 277-371, 451-536

---

## ⚙️ Paramètres de l'entreprise

Les informations de l'entreprise dans les emails sont **modifiables** via la base de données :

### Table `structure_params`

Paramètres disponibles :
- `structure_name` : Nom de l'entreprise
- `structure_address` : Adresse complète
- `structure_phone` : Téléphone
- `structure_email` : Email de contact
- `structure_website` : Site web
- `structure_logo` : Logo (à implémenter)
- `structure_rc` : Registre de commerce
- `structure_nif` : NIF

### Modifier les paramètres

**Via l'admin** (recommandé) :
```
http://localhost:8080/admin/parametres
```

**Via SQL** :
```sql
UPDATE structure_params SET param_value = 'Nouvelle valeur' WHERE param_key = 'structure_name';
```

**Via le modèle** :
```php
$structureModel = new \App\Models\StructureParamModel();
$structureModel->setParam('structure_name', 'Mon Entreprise');
```

**Fichier** : `app/Models/StructureParamModel.php`

---

## 🧪 Tests

### 1. Test d'envoi d'email simple

**Interface web** :
```
http://localhost:8080/admin/test-email
```

**Ligne de commande** :
```bash
php spark test:email votre-email@example.com
```

### 2. Test de réservation

1. Allez sur le site frontend : `http://localhost:8080/reservation`
2. Remplissez le formulaire de réservation
3. Vérifiez les 2 emails :
   - Email du client (adresse saisie dans le formulaire)
   - Email du gestionnaire (`gestionnaire@nsenoutower.com`)

### 3. Test de rappel de paiement

1. Allez sur : `http://localhost:8080/admin/paiements-mensuels`
2. Cliquez sur le bouton "📧" (Envoyer rappel) à côté d'une échéance
3. Vérifiez l'email du locataire concerné

### 4. Test automatique des échéances

**Commande** :
```bash
php spark check:echeances
```

Cette commande :
- Met à jour les statuts de retard
- Envoie les rappels pour échéances proches (5 jours)
- Envoie les notifications de retard
- Génère les nouvelles échéances

**Automatisation (Cron)** :
```cron
# Vérifier les échéances tous les jours à 9h
0 9 * * * cd /chemin/vers/projet && php spark check:echeances
```

---

## 📋 Checklist de vérification

### ✅ Configuration
- [x] SMTP configuré dans `.env`
- [x] Email gestionnaire défini (`SMTP_USER_GESTIONNAIRE`)
- [x] Paramètres de l'entreprise renseignés dans `structure_params`
- [x] Test d'envoi réussi

### ✅ Réservations
- [x] Email client envoyé lors de nouvelle réservation
- [x] Email gestionnaire envoyé lors de nouvelle réservation
- [x] Templates HTML s'affichent correctement

### ✅ Relances locataires
- [x] Boutons de rappel fonctionnels dans l'admin
- [x] Emails de rappel d'échéance envoyés
- [x] Emails de retard envoyés
- [x] Notifications au gestionnaire envoyées

### ✅ Commandes automatiques
- [x] `php spark check:echeances` fonctionne
- [ ] Cron job configuré (optionnel)

---

## 🔧 Dépannage

### Email non reçu ?

1. **Vérifier les logs** :
   ```bash
   tail -f writable/logs/log-*.php | grep -i email
   ```

2. **Vérifier le dossier spam**

3. **Tester la configuration SMTP** :
   ```bash
   php spark test:email votre-email@example.com
   ```

4. **Vérifier la table des notifications** :
   ```sql
   SELECT * FROM notifications_log ORDER BY created_at DESC LIMIT 10;
   ```

### Erreur "SMTP non configuré" ?

Vérifiez que le fichier `.env` contient bien :
```env
SMTP_HOST = smtp-fr.securemail.pro
SMTP_USER = contact@nsenoutower.com
SMTP_PASS = Nsenoutower@2025
```

### Erreur de connexion SSL ?

Vérifiez la configuration :
- Port : 465
- Crypto : ssl
- Timeout : 60

Si le problème persiste, essayez :
- Port : 587
- Crypto : tls

---

## 📍 Emplacements des fichiers importants

| Fichier | Emplacement | Rôle |
|---------|-------------|------|
| Configuration Email | `app/Config/Email.php` | Config SMTP |
| Service de notifications | `app/Libraries/NotificationService.php` | Logique d'envoi |
| Contrôleur réservations | `app/Controllers/ReservationController.php` | Emails lors de réservation |
| Contrôleur paiements | `app/Controllers/Admin/PaiementMensuelController.php` | Rappels de paiement |
| Commande vérification | `app/Commands/CheckEcheances.php` | Vérification automatique |
| Test email | `app/Controllers/Admin/TestEmailController.php` | Interface de test |
| Paramètres entreprise | `app/Models/StructureParamModel.php` | Gestion des paramètres |
| Variables d'environnement | `.env` | Configuration SMTP |

---

## 🚀 Fonctionnalités avancées

### Personnalisation des templates

Pour modifier les templates HTML, éditez :
```php
app/Libraries/NotificationService.php
```

Méthodes concernées :
- `genererTemplateConfirmationReservation()` : ligne 451
- `genererTemplateNouvelleReservation()` : ligne 490
- `genererMessageEcheance()` : ligne 277
- `genererMessageRetard()` : ligne 323

### Ajouter de nouveaux types de notifications

1. Créer une nouvelle méthode dans `NotificationService.php`
2. Créer le template HTML correspondant
3. Appeler la méthode depuis le contrôleur approprié

**Exemple** :
```php
public function envoyerConfirmationPaiement($locataire, $paiement) {
    $sujet = "Paiement reçu - Merci !";
    $message = $this->genererTemplateConfirmationPaiement($locataire, $paiement);

    return $this->sendEmail($locataire['email'], $sujet, $message, true);
}
```

### Logs et traçabilité

Tous les emails sont loggés dans :
- **Logs applicatifs** : `writable/logs/log-*.php`
- **Table BDD** : `notifications_log`

**Consulter les logs** :
```sql
SELECT
    date,
    recipient_email,
    subject,
    status,
    created_at
FROM notifications_log
ORDER BY created_at DESC
LIMIT 20;
```

---

## 📞 Support

Si vous rencontrez des problèmes :

1. Vérifiez cette documentation
2. Consultez les logs
3. Testez avec l'interface de test : `/admin/test-email`
4. Vérifiez la configuration SMTP dans `.env`

---

## 📝 Notes importantes

- ⚠ **Ne jamais commiter le fichier `.env`** avec les mots de passe SMTP
- ✅ **Tester en environnement de développement** avant la production
- 📧 **Vérifier le dossier spam** lors des premiers tests
- 🔒 **Utiliser des App Passwords** pour Gmail (pas le mot de passe principal)
- 🕐 **Configurer un cron job** pour l'envoi automatique des rappels

---

**Dernière mise à jour** : 2025-11-16
**Version** : 1.0
**Statut** : ✅ Production Ready
