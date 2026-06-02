# Manuel Technique de Maintenance - SchoolGood

## Table des matières

1. [Introduction](#introduction)
2. [Architecture du système](#architecture-du-système)
3. [Configuration de l'environnement](#configuration-de-lenvironnement)
4. [Base de données](#base-de-données)
5. [Dépendances et packages](#dépendances-et-packages)
6. [Procédures de maintenance](#procédures-de-maintenance)
7. [Dépannage](#dépannage)
8. [Sécurité](#sécurité)
9. [Sauvegardes](#sauvegardes)
10. [Mises à jour](#mises-à-jour)

---

## Introduction

SchoolGood est une application web de gestion scolaire développée avec Laravel 13.7 et PHP 8.3. Ce manuel fournit les instructions techniques pour la maintenance, le dépannage et l'évolution de l'application.

### Technologies principales

- **Backend**: Laravel 13.7 (PHP 8.3)
- **Frontend**: Blade Templates, Vite, TailwindCSS 4.0
- **Base de données**: SQLite (configurable pour MySQL/PostgreSQL)
- **PDF Generation**: DomPDF
- **Authentification**: Laravel Auth avec rôles personnalisés

---

## Architecture du système

### Structure des répertoires

```
SchoolGood/
├── app/                    # Application core
│   ├── Http/Controllers/   # Contrôleurs
│   ├── Models/            # Modèles Eloquent
│   ├── Services/          # Services métier
│   ├── Enums/             # Énumérations PHP
│   └── Policies/          # Politiques d'autorisation
├── config/                # Fichiers de configuration
├── database/
│   ├── migrations/        # Migrations de base de données
│   └── seeders/          # Seeders de données
├── resources/
│   ├── views/            # Templates Blade
│   ├── css/              # Feuilles de style
│   └── js/               # Scripts JavaScript
├── routes/               # Définition des routes
├── storage/              # Stockage (logs, cache, uploads)
└── public/               # Point d'entrée public
```

### Principaux composants

#### Contrôleurs

- `PaymentController`: Gestion des paiements et validation
- `StudentController`: Gestion des élèves
- `CourseController`: Gestion des cours
- `BookLoanController`: Gestion des emprunts de bibliothèque
- `PaymentReceiptController`: Génération des reçus PDF

#### Services

- `PaymentReceiptService`: Génération des reçus PDF
- `StudentTuitionService`: Calcul des frais de scolarité
- `BookLoanPenaltyService`: Gestion des pénalités de retard

#### Modèles

- `Student`: Élève
- `Payment`: Paiement
- `Course`: Cours
- `BookLoan`: Emprunt de livre
- `StudentSchoolGrade`: Notes des élèves

---

## Configuration de l'environnement

### Variables d'environnement (.env)

```bash
# Application
APP_NAME=SchoolGood
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

# Base de données
DB_CONNECTION=sqlite
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=schoolgood
# DB_USERNAME=root
# DB_PASSWORD=

# Paiements
SCHOOL_NAME=SchoolGood
PAYMENTS_ORANGE_ENABLED=false
PAYMENTS_MTN_ENABLED=false
PAYMENTS_SIMULATE_WEBHOOKS=true
```

### Installation initiale

```bash
# Installation des dépendances PHP
composer install

# Installation des dépendances Node.js
npm install

# Configuration de l'environnement
cp .env.example .env
php artisan key:generate

# Migration de la base de données
php artisan migrate --seed

# Build des assets
npm run build
```

### Démarrage en développement

```bash
# Serveur Laravel
php artisan serve

# Vite (assets)
npm run dev

# Ou tout ensemble
composer run dev
```

---

## Base de données

### Migrations

Les migrations sont situées dans `database/migrations/`. Pour créer une nouvelle migration:

```bash
php artisan make:migration create_table_name
```

Pour exécuter les migrations:

```bash
php artisan migrate
```

Pour annuler la dernière migration:

```bash
php artisan migrate:rollback
```

### Modèles principaux

#### Student (Élève)

```php
// Relations
- classroom(): BelongsTo Classroom
- parent(): BelongsTo User
- payments(): HasMany Payment
- schoolYearRecords(): HasMany StudentSchoolYearRecord
- bookLoans(): HasMany BookLoan
- schoolGrades(): HasMany StudentSchoolGrade
```

#### Payment (Paiement)

```php
// Relations
- student(): BelongsTo Student
- receivedBy(): BelongsTo User
- validatedBy(): BelongsTo User
```

#### Course (Cours)

```php
// Relations
- teacher(): BelongsTo User
- classroom(): BelongsTo Classroom
```

### Seeders

Pour peupler la base de données avec des données de test:

```bash
php artisan db:seed
```

---

## Dépendances et packages

### Dépendances PHP (composer.json)

```json
{
  "require": {
    "php": "^8.3",
    "barryvdh/laravel-dompdf": "^3.1",
    "laravel/framework": "^13.7",
    "laravel/tinker": "^3.0"
  }
}
```

### Dépendances Node.js (package.json)

```json
{
  "devDependencies": {
    "@tailwindcss/vite": "^4.0.0",
    "laravel-vite-plugin": "^3.1",
    "tailwindcss": "^4.0.0",
    "vite": "^8.0.0"
  }
}
```

### Mise à jour des dépendances

```bash
# PHP
composer update

# Node.js
npm update
```

---

## Procédures de maintenance

### Cache

```bash
# Vider le cache de l'application
php artisan cache:clear

# Vider le cache de configuration
php artisan config:clear

# Vider le cache de routes
php artisan route:clear

# Vider le cache de vues
php artisan view:clear

# Tout vider
php artisan optimize:clear
```

### Optimisation

```bash
# Optimiser pour la production
php artisan optimize

# Optimiser les assets
npm run build
```

### Logs

Les logs sont stockés dans `storage/logs/laravel.log`.

Pour surveiller les logs en temps réel:

```bash
tail -f storage/logs/laravel.log
```

### Tâches planifiées

Configurer le cron job pour exécuter les tâches planifiées Laravel:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## Dépannage

### Problèmes courants

#### 1. Erreur lors de la validation de paiement

**Symptôme**: Erreur lors du clic sur "Valider le paiement"

**Causes possibles**:
- Problème avec le service `PaymentReceiptService`
- Base de données non synchronisée
- Permissions insuffisantes

**Solution**:
```bash
# Vérifier les logs
tail -f storage/logs/laravel.log

# Vider le cache
php artisan cache:clear

# Vérifier les permissions
php artisan storage:link
```

#### 2. Erreur lors de la génération du PDF

**Symptôme**: Erreur lors du téléchargement du reçu PDF

**Causes possibles**:
- Configuration DomPDF manquante
- Police non disponible
- Permissions d'écriture

**Solution**:
```bash
# Publier la configuration DomPDF
php artisan vendor:publish --tag=dompdf

# Vérifier le fichier config/dompdf.php
# S'assurer que le répertoire storage/fonts existe et est accessible en écriture
```

#### 3. Les notes des élèves ne s'affichent pas

**Symptôme**: La section "Notes / résultats" est vide dans la fiche élève

**Causes possibles**:
- Aucune note enregistrée dans la base de données
- Relation non chargée
- Permission insuffisante

**Solution**:
```bash
# Vérifier que les notes existent dans la base de données
php artisan tinker
>>> App\Models\StudentSchoolGrade::all();

# Vérifier les permissions dans app/Policies/StudentSchoolGradePolicy.php
```

#### 4. Problèmes avec les assets CSS/JS

**Symptôme**: Les pages ne s'affichent pas correctement (pas de styles)

**Causes possibles**:
- Assets non compilés
- Vite non configuré
- Cache navigateur

**Solution**:
```bash
# Recompiler les assets
npm run build

# Ou en mode développement
npm run dev

# Vider le cache
php artisan view:clear
```

### Environnement PHP

Si vous rencontrez des erreurs liées aux extensions PHP (phpredis, phpmongodb, etc.):

```bash
# Vérifier la version de PHP
php -v

# Vérifier les extensions installées
php -m

# Réinstaller les extensions si nécessaire
# (dépend de votre environnement: XAMPP, Laragon, etc.)
```

---

## Sécurité

### Mises à jour de sécurité

```bash
# Vérifier les vulnérabilités
composer audit
npm audit

# Mettre à jour les dépendances
composer update
npm update
```

### Variables sensibles

- Ne jamais committer `.env`
- Utiliser des variables d'environnement pour les données sensibles
- Changer régulièrement les clés API et mots de passe

### Permissions

```bash
# Permissions recommandées pour Linux/Mac
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Windows: s'assurer que IIS/IUSR a les droits d'écriture sur storage/
```

---

## Sauvegardes

### Base de données

```bash
# SQLite
cp database/database.sqlite backups/database-$(date +%Y%m%d).sqlite

# MySQL
mysqldump -u root -p schoolgood > backups/schoolgood-$(date +%Y%m%d).sql

# PostgreSQL
pg_dump schoolgood > backups/schoolgood-$(date +%Y%m%d).sql
```

### Fichiers

```bash
# Sauvegarder storage/
tar -czf backups/storage-$(date +%Y%m%d).tar.gz storage/

# Sauvegarder l'application complète
tar -czf backups/schoolgood-$(date +%Y%m%d).tar.gz \
  app/ config/ database/ resources/ routes/ composer.json composer.lock package.json
```

### Restauration

```bash
# SQLite
cp backups/database-20240101.sqlite database/database.sqlite

# MySQL
mysql -u root -p schoolgood < backups/schoolgood-20240101.sql

# Fichiers
tar -xzf backups/storage-20240101.tar.gz
```

---

## Mises à jour

### Mise à jour de Laravel

```bash
# Vérifier les mises à jour disponibles
composer outdated

# Mettre à jour Laravel
composer update laravel/framework

# Suivre le guide de mise à jour: https://laravel.com/docs/upgrade
```

### Mise à jour des dépendances

```bash
# Mettre à jour toutes les dépendances
composer update
npm update

# Tester après mise à jour
composer test
npm test
```

---

## Contact et support

Pour toute question technique ou problème non résolu par ce manuel:

1. Consulter la documentation Laravel: https://laravel.com/docs
2. Vérifier les logs dans `storage/logs/laravel.log`
3. Consulter le fichier `GUIDE_UTILISATEUR.md` pour les questions d'utilisation

---

## Annexe: Structure des rôles utilisateurs

L'application utilise un système de rôles basé sur des énumérations PHP:

- **Founder (Fondateur)**: Accès complet à toutes les fonctionnalités
- **Admin**: Accès administratif complet
- **Scolarité**: Gestion des paiements, années scolaires, bibliothèque
- **Teacher (Enseignant)**: Gestion des cours, devoirs, notes de ses classes
- **Parent**: Consultation des informations de ses enfants, paiements, devoirs

Les politiques d'autorisation sont définies dans `app/Policies/`.

---

**Version du manuel**: 1.0  
**Dernière mise à jour**: Juin 2026  
**Application**: SchoolGood v1.0
