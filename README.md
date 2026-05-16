# schoolGood

`schoolGood` est une application de gestion scolaire développée avec Laravel 13 et PHP 8.3.

Elle permet de gérer :
- les utilisateurs et rôles
- les élèves et inscriptions scolaires
- les cours, classes et emplois du temps
- la bibliothèque et les emprunts de livres
- les paiements et relevés financiers
- les annonces internes

## Prérequis

- PHP 8.3+
- Composer
- Node.js + npm
- SQLite (ou tout autre base de données prise en charge par Laravel)

## Installation

1. Cloner le dépôt

```bash
git clone <url-du-repo> schoolGood
cd schoolGood
```

2. Installer les dépendances PHP

```bash
composer install
```

3. Copier le fichier de configuration d'environnement

```bash
copy .env.example .env
```

4. Générer la clé d'application

```bash
php artisan key:generate
```

5. Préparer la base de données SQLite

```bash
if not exist database\database.sqlite type nul > database\database.sqlite
```

6. Lancer les migrations

```bash
php artisan migrate --force
```

7. Installer les dépendances front-end

```bash
npm install
```

8. Compiler les assets

```bash
npm run build
```

## Exécution en développement

Lancer l'application avec Laravel et Vite :

```bash
php artisan serve
npm run dev
```

Vous pouvez aussi utiliser le script npm suivant si vous souhaitez démarrer plusieurs services :

```bash
npm run dev
```

## Commandes utiles

- `php artisan migrate` : exécuter les migrations
- `php artisan db:seed` : exécuter les seeders
- `php artisan test` : lancer les tests
- `npm run build` : compiler les assets pour la production
- `npm run dev` : démarrer Vite en mode développement

## Structure du projet

- `app/Models` : modèles Eloquent
- `app/Http/Controllers` : contrôleurs de l'application
- `database/migrations` : migrations de base de données
- `resources/views` : vues Blade
- `routes/web.php` : routes web
- `vite.config.js` : configuration Vite

## Fonctionnalités principales

- Gestion des rôles utilisateurs (Fondateur, Administrateur, Scolarité, Enseignant, etc.)
- Espace bibliothèque avec création, modification, suppression et emprunts
- Gestion des élèves, des inscriptions et des années scolaires
- Suivi des paiements et validation des transactions
- Publication et affichage d'annonces internes

## Tests

Pour lancer le suite de tests :

```bash
composer test
```

## Contribution

1. Forkez le dépôt
2. Créez une branche feature : `git checkout -b feature/ma-fonctionnalite`
3. Faites vos modifications
4. Soumettez une pull request

## Licence

Ce projet et developpe par des etudiants de la promo 2027 de cybersecurite de l'ecole nationale superieure polytechnique de yaounde.
