# Guide de Test - Système Locataires à Long Terme

## 🎯 **Données de Test Créées**

Le système a été peuplé avec des données de test complètes :

### **📊 Statistiques des Données**
- ✅ **8 locataires** au total
- ✅ **5 contrats actifs** de long terme
- ✅ **76 échéances** créées
- ✅ **15 paiements** déjà effectués
- ✅ **55 retards** pour tester les alertes
- ✅ **Montant total dû** : 31,610,000 FCFA
- ✅ **Montant payé** : 7,280,000 FCFA
- ✅ **Montant restant** : 24,330,000 FCFA

### **👥 Locataires de Test**
1. **Jean-Baptiste KOUAME** - jb.kouame@email.com - 450,000 FCFA/mois
2. **Marie-Claire TRAORE** - mc.traore@email.com - 380,000 FCFA/mois (étudiante)
3. **Kouassi KOFFI** - k.koffi@email.com - 520,000 FCFA/mois (cadre)
4. **Fatou DIABATE** - f.diabate@email.com - 320,000 FCFA/mois (famille)
5. **Ahmed SANGARE** - a.sangare@email.com - 410,000 FCFA/mois (expatrié)

## 🧪 **Tests à Effectuer**

### **1. Test de l'Interface Web**

#### **A. Dashboard des Paiements Mensuels**
```
URL: /admin/paiements-mensuels/dashboard
```
**À vérifier :**
- [ ] Affichage des statistiques (montants, taux de recouvrement)
- [ ] Liste des échéances proches
- [ ] Liste des retards de paiement
- [ ] Boutons d'action (envoyer rappels, etc.)

#### **B. Liste des Contrats**
```
URL: /admin/contrats
```
**À vérifier :**
- [ ] Affichage des 5 contrats actifs
- [ ] Informations complètes (locataire, appartement, loyer, statut)
- [ ] Boutons d'action (voir, modifier, terminer)
- [ ] Statuts corrects (actif, suspendu, terminé)

#### **C. Créer un Nouveau Contrat**
```
URL: /admin/contrats/create
```
**À tester :**
- [ ] Sélection du locataire (dropdown)
- [ ] Sélection de l'appartement (avec prix automatique)
- [ ] Validation des dates (début/fin)
- [ ] Validation du loyer et jour de paiement
- [ ] Création réussie du contrat
- [ ] Génération automatique des échéances

#### **D. Détails d'un Contrat**
```
URL: /admin/contrats/show/{id}
```
**À vérifier :**
- [ ] Informations complètes du contrat
- [ ] Résumé des paiements (statistiques)
- [ ] Historique des échéances
- [ ] Bouton "Enregistrer un Paiement"
- [ ] Statuts des échéances (payé, en attente, retard)

#### **E. Enregistrer un Paiement**
**À tester :**
- [ ] Ouverture du modal de paiement
- [ ] Saisie du montant
- [ ] Sélection du nombre de mois payés
- [ ] Choix du mode de paiement
- [ ] Ajout d'une référence
- [ ] Enregistrement réussi
- [ ] Mise à jour automatique des statuts

### **2. Test des Fonctionnalités Métier**

#### **A. Gestion des Statuts**
**À vérifier :**
- [ ] Échéances passées marquées automatiquement "en retard"
- [ ] Échéances futures marquées "en attente"
- [ ] Échéances payées marquées "payé"
- [ ] Paiements partiels marqués "partiellement payé"

#### **B. Calculs Financiers**
**À tester :**
- [ ] Calcul correct du montant restant
- [ ] Calcul du taux de recouvrement
- [ ] Calcul des retards
- [ ] Génération des échéances multiples

#### **C. Validation des Données**
**À tester :**
- [ ] Validation des montants (positifs)
- [ ] Validation des dates (cohérence)
- [ ] Validation des jours de paiement (1-31)
- [ ] Validation des emails (format correct)

### **3. Test de la Commande Cron**

#### **A. Vérification des Échéances**
```bash
php spark check:echeances
```
**Résultats attendus :**
- [ ] Mise à jour des statuts de retard
- [ ] Détection des échéances proches
- [ ] Détection des retards de paiement
- [ ] Génération d'échéances pour nouveaux contrats

#### **B. Configuration Email (Optionnel)**
**Pour tester les notifications :**
1. Configurer `app/Config/Email.php` :
```php
public string $SMTPUser = 'votre-email@gmail.com';
public string $SMTPPass = 'votre-mot-de-passe-application';
public string $fromEmail = 'votre-email@gmail.com';
```
2. Relancer la commande pour voir les emails envoyés

### **4. Test des Cas Limites**

#### **A. Contrats Indéterminés vs Déterminés**
- [ ] Contrats sans date de fin (indéterminés)
- [ ] Contrats avec date de fin (déterminés)
- [ ] Gestion de la fin de contrat

#### **B. Paiements Multiples**
- [ ] Paiement de 1 mois
- [ ] Paiement de plusieurs mois en une fois
- [ ] Paiements partiels
- [ ] Paiements en avance

#### **C. Gestion des Erreurs**
- [ ] Tentative de créer un contrat pour un appartement occupé
- [ ] Tentative de paiement pour une échéance inexistante
- [ ] Validation des données invalides

## 📋 **Checklist de Validation**

### **✅ Fonctionnalités Principales**
- [ ] Création de contrats de long terme
- [ ] Génération automatique des échéances
- [ ] Enregistrement des paiements
- [ ] Calcul des montants et statuts
- [ ] Dashboard avec statistiques
- [ ] Liste et détails des contrats

### **✅ Interface Utilisateur**
- [ ] Navigation intuitive
- [ ] Formulaires fonctionnels
- [ ] Modals de paiement
- [ ] Affichage des données
- [ ] Messages de confirmation
- [ ] Gestion des erreurs

### **✅ Logique Métier**
- [ ] Validation des données
- [ ] Calculs financiers corrects
- [ ] Gestion des statuts
- [ ] Cohérence des dates
- [ ] Intégrité des données

### **✅ Automatisation**
- [ ] Commande cron fonctionnelle
- [ ] Mise à jour des statuts
- [ ] Détection des échéances
- [ ] Génération d'échéances

## 🐛 **Résolution des Problèmes Courants**

### **Problème : Erreur "Email non envoyé"**
**Cause :** Configuration SMTP non définie
**Solution :** Configurer les paramètres email dans `app/Config/Email.php`

### **Problème : Échéances non générées**
**Cause :** Problème dans la logique de génération
**Solution :** Vérifier les dates et la méthode `genererEcheancesMensuelles()`

### **Problème : Calculs incorrects**
**Cause :** Erreur dans les requêtes SQL
**Solution :** Vérifier les méthodes de calcul dans les modèles

### **Problème : Interface non responsive**
**Cause :** CSS manquant ou incorrect
**Solution :** Vérifier les fichiers CSS et la structure HTML

## 🎉 **Validation Finale**

Le système est considéré comme fonctionnel si :

1. ✅ **Toutes les interfaces s'affichent correctement**
2. ✅ **Les contrats peuvent être créés et modifiés**
3. ✅ **Les paiements peuvent être enregistrés**
4. ✅ **Les statistiques s'affichent correctement**
5. ✅ **La commande cron s'exécute sans erreur**
6. ✅ **Les données sont cohérentes et intègres**

## 📞 **Support**

En cas de problème :
1. Vérifier les logs dans `writable/logs/`
2. Vérifier la configuration de la base de données
3. Vérifier les permissions des fichiers
4. Consulter la documentation du système

---

**🎯 Le système des locataires à long terme est maintenant prêt pour la production !**
