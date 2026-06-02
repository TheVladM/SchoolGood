# Guide utilisateur SchoolGood

**Version application :** Laravel 13 · 2026  
**Public :** fondateur, administrateur, scolarité, enseignants, parents  
**Objectif :** documenter l’ensemble des fonctionnalités accessibles depuis l’interface web.

---

## Table des matières

1. [Présentation](#1-présentation)
2. [Première connexion](#2-première-connexion)
3. [Interface générale](#3-interface-générale)
4. [Les rôles et leurs droits](#4-les-rôles-et-leurs-droits)
5. [Tableau de bord](#5-tableau-de-bord)
6. [Mon profil](#6-mon-profil)
7. [Utilisateurs](#7-utilisateurs)
8. [Élèves](#8-élèves)
9. [Classes](#9-classes)
10. [Salles](#10-salles)
11. [Cours](#11-cours)
12. [Emploi du temps](#12-emploi-du-temps)
13. [Devoirs et rendus](#13-devoirs-et-rendus)
14. [Paiements et scolarité](#14-paiements-et-scolarité)
15. [Messages aux parents](#15-messages-aux-parents)
16. [Bibliothèque et emprunts](#16-bibliothèque-et-emprunts)
17. [Années scolaires et promotions](#17-années-scolaires-et-promotions)
18. [Notifications](#18-notifications)
19. [Référence rapide par rôle](#19-référence-rapide-par-rôle)
20. [FAQ et dépannage](#20-faq-et-dépannage)
21. [Annexes](#21-annexes)

---

## 1. Présentation

**SchoolGood** est une plateforme de gestion scolaire qui centralise :

- la vie scolaire (élèves, classes, cours, emploi du temps, devoirs) ;
- la communication école–familles (messages, notifications) ;
- la scolarité financière (tarifs, paiements, reçus) ;
- la bibliothèque (livres, emprunts, pénalités) ;
- l’administration (utilisateurs, années scolaires, promotions).

L’application est organisée par **rôles** : chaque utilisateur ne voit que les menus et données autorisés pour son profil.

---

## 2. Première connexion

### 2.1 Accéder à l’application

1. Ouvrez l’adresse fournie par l’école (ex. `http://votre-domaine/login`).
2. Saisissez votre **adresse e-mail** et votre **mot de passe**.
3. Cochez « Se souvenir de moi » si vous utilisez un poste personnel de confiance.
4. Cliquez sur **Se connecter**.

### 2.2 Mot de passe oublié

1. Sur la page de connexion, cliquez sur **Mot de passe oublié ?**
2. Entrez votre e-mail.
3. Consultez votre boîte mail et suivez le lien de réinitialisation.
4. Choisissez un nouveau mot de passe et confirmez-le.

### 2.3 Comptes de démonstration (environnement local)

| E-mail | Mot de passe | Rôle |
|--------|--------------|------|
| founder@schoolgood.test | password | Fondateur |
| admin@schoolgood.test | password | Administrateur |
| scolarite@schoolgood.test | password | Scolarité |
| teacher1@schoolgood.test | password | Enseignant |
| parent@schoolgood.test | password | Parent |

> En production, l’administrateur ou le fondateur crée les comptes ; les parents ne s’inscrivent pas seuls.

### 2.4 Déconnexion

Menu latéral → **Déconnexion** (en bas de la barre).

---

## 3. Interface générale

### 3.1 Écran d’accueil (splash)

À la première ouverture de l’onglet dans la session, un écran de chargement affiche le logo **SchoolGood**, puis disparaît automatiquement.

### 3.2 Barre latérale (menu)

Le menu principal est à gauche (sur mobile : bouton menu en haut à gauche). Les entrées visibles dépendent de votre rôle.

Entrées possibles :

| Menu | Description |
|------|-------------|
| Tableau de bord | Vue d’ensemble personnalisée |
| Élèves | Fiches élèves |
| Classes | Groupes pédagogiques |
| Cours | Contenus et séances par classe |
| Emploi du temps | Créneaux par niveau et section |
| Devoirs | Devoirs assignés aux classes |
| Salles | Locaux physiques |
| Frais scolarité | Grilles tarifaires |
| Années scolaires | Cycles et promotions |
| Bibliothèque | Catalogue de livres |
| Emprunts | Sorties et retours |
| Paiements | Registre des règlements |
| Payer en ligne | Paiement mobile (parents) |
| Déclarer paiement | Déclaration manuelle (parents) |
| Messages | Annonces aux familles |
| Utilisateurs | Comptes et rôles |

### 3.3 Barre supérieure

- Fil d’Ariane **SchoolGood**
- Titre de la page en cours
- Date du jour
- Badge de votre rôle

### 3.4 Messages système (flash)

Les confirmations (succès) et erreurs s’affichent en haut du contenu principal après une action (création, modification, validation, etc.).

---

## 4. Les rôles et leurs droits

### 4.1 Fondateur

- Accès quasi complet à l’application.
- **Seul** à valider les paiements en attente.
- Approuve ou refuse les messages rédigés par la scolarité.
- Gère les frais de scolarité avec la scolarité.
- Compte protégé : ne peut pas être modifié ou supprimé par un autre utilisateur.

### 4.2 Administrateur

- Gère utilisateurs (sauf fondateur), classes, salles, élèves, cours, bibliothèque, emploi du temps, années scolaires.
- **Ne gère pas** les paiements (module inaccessible).
- **Ne gère pas** les frais de scolarité.
- Peut approuver les messages comme le fondateur (publication directe si fondateur/admin).

### 4.3 Scolarité

- Crée et met à jour **élèves** et peut créer des **parents** depuis le formulaire élève.
- Enregistre les **paiements** et consulte le registre.
- Gère les **frais de scolarité** (avec le fondateur).
- Gère **bibliothèque** et **emprunts**.
- Rédige des **messages** (soumis à validation du fondateur).
- Gère **années scolaires** et préparation des promotions.
- Ne voit que **ses propres messages** dans la liste (pas toute la messagerie).

### 4.4 Enseignant

- Consulte les élèves de **ses classes** (titulaire ou enseignant de langue).
- Crée et gère **ses cours** et **ses devoirs**.
- Consulte l’**emploi du temps** de ses niveaux.
- **Pas d’accès** aux paiements, aux messages (menu masqué), ni à la gestion des utilisateurs.

### 4.5 Parent

- Consulte **ses enfants**, leurs devoirs, cours, messages approuvés.
- Consulte et **déclare** des paiements ; peut **payer en ligne** (Orange / MTN si configuré).
- Peut **rendre un devoir** pour un enfant et consulter les notes.
- Consulte les **emprunts** bibliothèque de ses enfants.

### 4.2 Matrice des droits (synthèse)

| Module | Fondateur | Admin | Scolarité | Enseignant | Parent |
|--------|:---------:|:-----:|:---------:|:----------:|:------:|
| Utilisateurs | Oui | Oui* | Non | Non | Non |
| Élèves | Oui | Oui | Oui | Lecture** | Ses enfants |
| Classes | Oui | Oui | Oui | Lecture** | Lecture** |
| Salles | Oui | Oui | Non | Non | Non |
| Cours | Oui | Oui | Oui | Les siens | Lecture** |
| Emploi du temps | Oui | Oui | Oui | Oui | Oui*** |
| Devoirs | Oui | Oui | Non | Les siens | Rendu / lecture |
| Frais scolarité | Oui | Non | Oui | Non | Non |
| Paiements | Oui | **Non** | Oui | Non | Déclarer / voir |
| Validation paiement | **Oui** | Non | Non | Non | Non |
| Messages | Oui | Oui | Créer | Non | Lire approuvés |
| Approuver messages | Oui | Oui | Non | Non | Non |
| Bibliothèque | Oui | Oui | Oui | Non | Non |
| Emprunts | Oui | Oui | Oui | Non | Ses enfants |
| Années scolaires | Oui | Oui | Oui | Non | Non |

\* L’administrateur ne peut pas modifier le compte fondateur.  
\** Selon classe / enfant rattaché.  
\*** Emploi du temps visible pour le niveau des enfants.

---

## 5. Tableau de bord

Après connexion, vous arrivez sur le **tableau de bord**, adapté à votre rôle.

### 5.1 Contenu commun

- Bandeau de bienvenue avec message personnalisé.
- **Indicateurs** (cartes statistiques) : effectifs, paiements, devoirs, etc.
- **Activité récente** : derniers élèves, cours, paiements, devoirs.
- **Accès rapides** : raccourcis vers les modules principaux.

### 5.2 Fondateur / scolarité

- File d’attente : paiements à valider, messages en attente (fondateur).
- Compteurs d’élèves, classes, paiements en attente.

### 5.3 Enseignant

- Statistiques limitées à **ses classes** et **ses devoirs**.

### 5.4 Parent

- Bloc **Mes enfants** avec liens rapides (devoirs, paiements, messages).
- Statistiques : nombre d’enfants, paiements payés, devoirs actifs.

---

## 6. Mon profil

**Menu** : Mon profil (bas de la barre latérale)

Vous pouvez modifier :

- votre **nom** ;
- votre **e-mail** ;
- votre **téléphone** (important pour les SMS de paiement ou messages, si activés) ;
- votre **mot de passe**.

Cliquez **Enregistrer** pour sauvegarder.

---

## 7. Utilisateurs

**Accès :** Fondateur, Administrateur  
**Menu :** Utilisateurs

### 7.1 Liste

Recherche et pagination. Colonnes : nom, e-mail, rôle, service.

### 7.2 Créer un utilisateur

1. Cliquez **Nouvel utilisateur**.
2. Renseignez : nom, e-mail, téléphone, mot de passe, **rôle**, service/fonction si applicable.
3. Pour un **enseignant** : précisez la langue enseignée si pertinent (français / anglais).
4. Enregistrez.

### 7.3 Modifier / supprimer

- **Modifier** : mettre à jour les informations (sauf compte fondateur par un non-fondateur).
- **Supprimer** : réservé au fondateur pour les comptes sensibles ; l’administrateur gère les autres comptes selon les règles métier.

### 7.4 Fiche utilisateur

Indicateurs : nombre d’enfants (parent), cours (enseignant), classes titulaires.

---

## 8. Élèves

**Menu :** Élèves

### 8.1 Liste

Filtre par recherche. Accès à la fiche, modification, création selon droits.

### 8.2 Créer un élève

1. **Nouvel élève**.
2. Renseignez : prénom, nom, date de naissance, **classe**, **parent**.
3. **Scolarité / admin** : possibilité de **créer un nouveau parent** inline (nom, e-mail, mot de passe) si le parent n’existe pas encore.
4. Année scolaire courante et statut actif selon le formulaire.
5. Enregistrez.

### 8.3 Fiche élève

La fiche regroupe :

- identité et classe ;
- **paiements** associés ;
- **historique scolaire** (années précédentes, résultats) ;
- **notes / résultats** (saisie par fondateur, admin, scolarité) ;
- **emprunts bibliothèque**.

#### Ajouter une note

1. Sur la fiche, section Notes.
2. Choisissez année, matière, période (ex. Annuel), note sur 20.
3. **Ajouter**.

#### Supprimer une note

Bouton **Supprimer** à droite de la ligne (selon droits).

### 8.4 Modifier / archiver

- **Modifier** : changer classe, parent, informations.
- Un élève **sorti** de l’établissement peut être marqué avec une date de sortie ; l’historique reste consultable.

---

## 9. Classes

**Menu :** Classes

### 9.1 Concepts

- **Niveau** : CM1, CE2, Class 3, Petite section, etc. (listes francophone / anglophone / bilingue).
- **Section** : francophone, anglophone, bilingue.
- **Titulaire** : enseignant principal de la classe.
- **Enseignant de langue** : anglais (francophone) ou français (anglophone), obligatoire pour SIL à CM2 / équivalent.

### 9.2 Créer une classe

1. **Nouvelle classe**.
2. Nom (ex. CM1-A), niveau, section, type de cycle.
3. Salle (physique ou référence), localisation.
4. **Enseignant titulaire** et **enseignant de langue** (deux enseignants **différents** pour primaire FR/EN).
5. Enregistrez.

> Le système empêche qu’un même enseignant soit titulaire de deux classes en violation des règles croisées FR/EN.

### 9.3 Fiche classe

- Liste des **élèves** ;
- Liste des **cours** ;
- **Emploi du temps** du niveau (partagé entre classes du même niveau/section).

### 9.4 Programme titulaire (bouton)

Si un titulaire est défini :

1. Ouvrez la fiche classe.
2. Cliquez **Titulaire = toutes les matières**.
3. L’application :
   - synchronise les cours depuis l’**emploi du temps** du niveau ;
   - crée les matières standard (français, maths, sciences, anglais, etc.) assignées au titulaire ou à l’enseignant de langue.

### 9.5 Modifier / supprimer

Réservé aux profils autorisés (fondateur, admin, scolarité selon policy).

---

## 10. Salles

**Accès :** Fondateur, Administrateur  
**Menu :** Salles

### 10.1 Gestion

- **Créer** : nom, bâtiment, étage, capacité, notes.
- **Modifier / supprimer** : mettre à jour ou retirer une salle.
- Les salles sont **rattachables** aux classes lors de la création de classe.

---

## 11. Cours

**Menu :** Cours

### 11.1 Liste

Tous les cours (filtrés pour enseignant : les siens).

### 11.2 Créer un cours

1. **Nouveau cours**.
2. Intitulé, contenu pédagogique, **enseignant**, **classe**, **jour** (liste des 6 jours ouvrables).
3. Un enseignant connecté voit son nom pré-sélectionné.

### 11.3 Synchronisation avec l’emploi du temps

Lors de la création ou modification d’un **créneau d’emploi du temps**, si l’option **Synchroniser les cours des classes** est cochée (par défaut) :

- un cours est créé ou mis à jour pour **chaque classe** du même niveau et section ;
- l’enseignant est choisi automatiquement (titulaire ou langue selon la matière).

Les cours liés portent la mention « synchronisé » sur la fiche.

### 11.4 Consulter / modifier

- **Fiche cours** : classe, enseignant, jour, contenu.
- **Modifier / supprimer** : enseignant (ses cours), admin, fondateur, scolarité.

---

## 12. Emploi du temps

**Menu :** Emploi du temps

### 12.1 Principe

Un créneau est défini par **niveau + section**, pas par classe individuelle.

Exemple : CM1 francophone → CM1-A, CM1-B et CM1-C partagent le même emploi du temps.

### 12.2 Créer un créneau

1. **Nouvel emploi du temps**.
2. Niveau, section, matière, jour, heure début/fin, notes.
3. Cochez **Synchroniser les cours des classes** pour mettre à jour les cours automatiquement.
4. Enregistrez.

### 12.3 Modifier / supprimer

- La modification resynchronise les cours si l’option est active.
- La **suppression** retire les cours liés à ce créneau.

### 12.4 Visibilité parent

Les parents voient l’emploi du temps correspondant au **niveau** de la classe de leur enfant.

---

## 13. Devoirs et rendus

**Menu :** Devoirs

### 13.1 Liste

Devoirs par classe, avec date limite, matière, enseignant. Les parents voient ceux des classes de leurs enfants.

### 13.2 Créer un devoir (enseignant / admin / fondateur)

1. **Nouveau devoir**.
2. Titre, description, matière, enseignant, **classe**, **date limite**.
3. Enregistrez.

### 13.3 Fiche devoir

- Détails du devoir.
- Liste des **rendus** par élève (parent / enseignant).

### 13.4 Rendu par le parent

1. Ouvrez le devoir.
2. Section rendu : choisissez l’enfant si plusieurs, saisissez commentaire ou contenu.
3. **Envoyer le rendu**.

Statuts possibles : en attente, rendu, noté.

### 13.5 Notation (enseignant)

1. Sur la fiche devoir, ligne de l’élève.
2. Saisissez la **note** (sur 20) et validez.

---

## 14. Paiements et scolarité

### 14.1 Types de paiement

| Type | Description |
|------|-------------|
| Inscription | Frais d’inscription |
| 1ère tranche | Première échéance |
| 2ème tranche | Deuxième échéance |
| 3ème tranche | Troisième échéance |

### 14.2 Frais de scolarité (grilles tarifaires)

**Accès :** Fondateur, Scolarité  
**Menu :** Frais scolarité

- Définir le montant par **niveau** et **section**.
- Détail : inscription + 3 tranches.
- Utilisé pour suggérer le montant lors de l’enregistrement d’un paiement.

> L’**administrateur** n’a **pas** accès à ce module.

### 14.3 Enregistrer un paiement (scolarité / fondateur)

1. **Menu Paiements** → **Nouveau paiement**.
2. Choisissez l’**élève** : le montant suggéré s’affiche selon la grille tarifaire.
3. Type, montant, mode (Orange Money, MTN MoMo, banque), référence, statut.
4. Enregistrez.

### 14.4 Validation (fondateur uniquement)

1. **Paiements** → paiement en statut **En attente**.
2. Ouvrez la fiche → **Valider le paiement**.

Effets :

- statut **Payé** ;
- notification au parent ;
- SMS au parent si activé ;
- numéro de **reçu** généré.

### 14.5 Déclaration manuelle (parent)

1. **Déclarer paiement**.
2. Consultez les **coordonnées de l’école** (Orange, MTN, banque).
3. Renseignez enfant, type, montant, mode, référence transaction.
4. **Envoyer la déclaration** → statut **En attente** jusqu’à validation du fondateur.

### 14.6 Payer en ligne (parent)

1. **Payer en ligne**.
2. Enfant, type, montant, opérateur (Orange / MTN), numéro mobile du payeur.
3. **Lancer le paiement** :
   - en production : redirection ou validation sur le téléphone ;
   - confirmation automatique par **webhook** opérateur si configuré.

Page **En attente** : suivi jusqu’à confirmation.

### 14.7 Reçu PDF

Sur un paiement **Payé** :

- **Menu Paiements** → fiche → **Reçu PDF**.

Contenu : élève, montant, mode, références, numéro de reçu.

### 14.8 Solde et tranches

Sur la fiche paiement ou lors de la création, consultez :

- reste à payer annuel ;
- détail par tranche (dû / payé / reste).

### 14.9 Restrictions importantes

| Action | Fondateur | Scolarité | Admin | Parent |
|--------|:---------:|:---------:|:-----:|:------:|
| Voir paiements | Oui | Oui | **Non** | Ses enfants |
| Créer paiement | Oui | Oui | Non | Déclarer |
| Valider | **Oui** | Non | Non | Non |
| Supprimer | Oui | Non | Non | Non |

---

## 15. Messages aux parents

**Menu :** Messages

### 15.1 Rédiger un message

**Accès :** Fondateur, Admin, Scolarité

1. **Nouveau message**.
2. Titre, contenu, **audience** :
   - tous les parents ;
   - parents d’**une classe** ;
   - **un parent** précis.
3. Pièces jointes possibles (selon formulaire).
4. Enregistrez.

### 15.2 Workflow d’approbation

| Auteur | Comportement |
|--------|--------------|
| Fondateur / Admin | Message **publié** immédiatement |
| Scolarité | Message **en attente** → le fondateur doit **approuver** ou **refuser** |

### 15.3 Approuver / refuser (fondateur)

1. **Messages** → filtre messages en attente ou fiche du message.
2. **Approuver** ou **Refuser** (motif obligatoire si refus).

Après approbation :

- les parents concernés voient le message ;
- notification et **SMS** si configuré ;
- **accusé de lecture** enregistré quand un parent ouvre le message.

### 15.4 Accusés de lecture

Sur la fiche message (fondateur, admin, scolarité) :

- liste des parents destinataires ;
- statut **Lu le …** ou **Non lu**.

### 15.5 Modèles de messages

**Menu :** Modèles messages (si accessible)

- Enregistrer des textes types pour gagner du temps.

### 15.6 Lecture (parent)

Les parents ne voient que les messages **approuvés** les concernant (tous, leur classe, ou eux directement).

---

## 16. Bibliothèque et emprunts

### 16.1 Livres

**Accès :** Fondateur, Admin, Scolarité  
**Menu :** Bibliothèque

- **Ajouter** : titre, auteur, ISBN, catégorie, langue, nombre d’exemplaires, rayon, pénalité/jour.
- **Modifier / supprimer** : mettre à jour le catalogue.
- **Fiche livre** : historique des emprunts.

### 16.2 Emprunts

**Menu :** Emprunts

#### Créer un emprunt

1. **Nouvel emprunt**.
2. Livre, emprunteur (élève ou enseignant), dates emprunt / retour prévu.
3. Enregistrez → le stock disponible diminue.

#### Retourner un livre

1. Fiche emprunt → **Enregistrer le retour**.
2. Les jours de retard sont calculés.

#### Pénalité

Si retard :

1. **Facturer pénalité** sur la fiche emprunt.
2. Une ligne de **paiement** peut être créée pour l’élève concerné.

### 16.3 Parent

Consultation des emprunts liés à ses enfants depuis la fiche élève ou le menu Emprunts (selon accès).

---

## 17. Années scolaires et promotions

**Menu :** Années scolaires

### 17.1 Créer une année

1. Nom (ex. 2025-2026), dates début/fin, remise des diplômes, ouverture promotions.
2. Lier l’**année suivante** pour enchaîner les promotions.

### 17.2 Dossiers élèves

Chaque élève possède un **dossier annuel** : classe, niveau, résultats, statut (promu, redoublant, diplômé, etc.).

### 17.3 Préparer les promotions

1. Fiche année → **Préparer les promotions** (si conditions remplies).
2. Les élèves sont préparés pour passage à l’année/classe suivante selon la configuration.

### 17.4 Promotion automatique

Une tâche planifiée (`school-years:auto-promote`) peut exécuter les promotions automatiques si activées sur l’année (`auto_promote_enabled`).

### 17.5 Historique

Même après changement de classe ou départ, l’**historique scolaire** reste visible sur la fiche élève.

---

## 18. Notifications

Icône / centre de notifications (selon interface) :

- paiement enregistré ou validé ;
- message approuvé ;
- autres événements métier.

Les notifications sont stockées en base ; consultez-les et accédez au lien associé (paiement, message, etc.).

---

## 19. Référence rapide par rôle

### Fondateur · check-list quotidienne

- [ ] Valider les paiements en attente
- [ ] Approuver les messages de la scolarité
- [ ] Consulter le tableau de bord

### Scolarité · check-list

- [ ] Inscrire nouveaux élèves et parents
- [ ] Enregistrer paiements reçus au guichet
- [ ] Gérer emprunts bibliothèque
- [ ] Rédiger messages aux familles

### Enseignant · check-list

- [ ] Publier cours et devoirs
- [ ] Noter les rendus
- [ ] Consulter emploi du temps

### Parent · check-list

- [ ] Consulter devoirs et messages
- [ ] Déclarer ou payer scolarité
- [ ] Télécharger reçus PDF
- [ ] Rendre devoirs en ligne

---

## 20. FAQ et dépannage

### Je ne vois pas le menu Paiements

- **Administrateur** : accès volontairement **interdit** aux paiements.
- **Enseignant** : pas d’accès aux paiements.

### Mon paiement reste « En attente »

- Déclaration parent : le **fondateur** doit valider.
- Paiement en ligne : attendez la confirmation opérateur ; vérifiez la référence.

### Je ne vois pas un message de l’école

- Message rédigé par scolarité **non encore approuvé**.
- Message ciblé une **autre classe** ou un **autre parent**.

### Impossible d’assigner le même enseignant deux fois

- Règle SIL–CM2 : titulaire + langue **distincts**.
- Règle croisée : un titulaire FR/EN ne peut pas enfreindre les contraintes configurées.

### Les styles / le menu ne s’affichent pas correctement

Exécutez côté serveur :

```bash
npm run build
php artisan config:clear
```

### Mot de passe oublié sans e-mail reçu

- Vérifiez les spams.
- Contactez l’administrateur pour réinitialisation manuelle.

---

## 21. Annexes

### A. Niveaux scolaires (rappel)

**Francophone :** Crèche, Petite/Moyenne/Grande section, SIL, CP, CE1, CE2, CM1, CM2.

**Anglophone :** Kindergarten, Nursery 1–3, Class 1–6.

**Sections :** francophone, anglophone, bilingue.

### B. Configuration paiements (.env)

Variables principales :

```env
SCHOOL_ORANGE_MONEY_NUMBER=
SCHOOL_MTN_MOMO_NUMBER=
PAYMENTS_ORANGE_ENABLED=false
PAYMENTS_MTN_ENABLED=false
SMS_ENABLED=false
```

Coordonnées affichées aux parents : `config/school.php`.

### C. Commandes utiles (administrateur technique)

```bash
php artisan migrate          # Mise à jour base
php artisan db:seed          # Données de démo
php artisan test             # Tests automatiques
php artisan schedule:work    # Promotions auto (dev)
php artisan storage:link     # Fichiers publics
```

### D. API REST (intégrations)

Base : `/api/` avec authentification Sanctum.

Ressources : devoirs, classes, élèves, cours (lecture/écriture selon endpoints).

Consultez `README.md` pour le détail des filtres et formats JSON.

### E. Support

Pour toute demande fonctionnelle ou incident :

1. Notez votre **rôle**, l’**URL** de la page, l’**action** effectuée et le **message d’erreur**.
2. Contactez le fondateur ou l’équipe technique de l’école.

---

*Document généré pour SchoolGood · ENSP Yaoundé · Promo cybersécurité 2027.*
