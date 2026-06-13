# Guide des données de test - SchoolGood

## Vue d'ensemble

Le projet SchoolGood contient maintenant un ensemble enrichi de données de test pour faciliter le développement et les tests. Les données incluent des utilisateurs, des étudiants, des classes, des cours, des paiements, et bien plus.

## Données de base chargées via le seeder

Quand vous exécutez `php artisan db:seed`, les données suivantes sont créées automatiquement:

### Utilisateurs de base
- **Founder** (founder@schoolgood.test) - Fondateur de l'établissement
- **Admin** (admin@schoolgood.test) - Administrateur
- **Scolarité** (scolarite@schoolgood.test) - Agent de scolarité
- **Teachers** (teacher1-5@schoolgood.test) - 5 enseignants
  - Marie Essomba (Titulaire francophone)
  - John Nfor (Enseignant de langue)
  - Martine Fotso (Sciences et histoire)
  - Robert Ekeng (Titulaire anglophone)
  - Amandine Sop (Culture générale)
- **Parents** (parent@schoolgood.test, parent2-4) - 4 parents

### Structures scolaires
- **Classes**: 5 classes
  - CM1 A (Francophone)
  - CM1 B (Francophone)
  - CM1 Anglophone
  - CM2 A (Francophone)
  - CM2 Anglophone

- **Étudiants**: 5 étudiants
  - Kevin Ndzi (CM1 A)
  - Yannick Talla (CM1 A)
  - Arianne Kamgno (CM1 B)
  - Ethan Mube (CM1 Anglophone)
  - Chiara Ndzi (CM1 Anglophone)

### Données financières
- **Paiements**: 12 paiements pour les étudiants
- **Frais de scolarité**: 4 structures de frais (CM1-CM2, Francophone-Anglophone)

### Ressources pédagogiques
- **Cours**: 6 cours pour les différentes classes
- **Devoirs**: 10 devoirs pour les différentes matières
- **Livres**: 5 livres dans la bibliothèque
- **Emprunts**: 6 emprunts actifs et retournés

### Communications
- **Annonces**: 5 annonces approuvées
- **Emploi du temps**: Entrées d'emploi du temps pour les cours

## Utiliser les factories pour générer plus de données

### Générer des utilisateurs supplémentaires
```php
$users = User::factory(50)->create();
```

### Générer des étudiants avec leurs enregistrements
```php
$students = Student::factory(20)->create();
$classrooms = Classroom::all();
foreach ($students as $student) {
    StudentSchoolYearRecord::factory()->create([
        'student_id' => $student->id,
        'classroom_id' => $classrooms->random()->id,
    ]);
}
```

### Générer des paiements
```php
// Paiements payés
Payment::factory(30)->paid()->create();

// Paiements en attente
Payment::factory(15)->pending()->create();
```

### Générer des annonces
```php
// Pour tous les parents
Announcement::factory(10)->approved()->forAllParents()->create();

// Pour des classes spécifiques
Announcement::factory(5)->forClassroom()->create();
```

### Générer des devoirs avec soumissions
```php
$homeworks = Homework::factory(20)->create();
$homeworks->each(function ($homework) {
    HomeworkSubmission::factory(5)
        ->submitted()
        ->create(['homework_id' => $homework->id]);
});
```

### Générer des emprunts de livres
```php
// Emprunts actuels
BookLoan::factory(15)->active()->create();

// Emprunts retournés
BookLoan::factory(10)->returned()->create();
```

### Générer des grades d'étudiants
```php
$students = Student::all();
$subjects = ['Mathématiques', 'Français', 'Anglais', 'Sciences', 'Histoire'];

$students->each(function ($student) use ($subjects) {
    foreach ($subjects as $subject) {
        StudentSchoolGrade::factory()
            ->excellent()
            ->create([
                'student_id' => $student->id,
                'subject' => $subject,
            ]);
    }
});
```

## Vérifier les données de test

Exécutez le script de test pour voir les statistiques:
```bash
php test_users.php
```

Cela affichera:
- Nombre total d'utilisateurs par rôle
- Statistiques des étudiants et classes
- Informations financières
- Détails de la bibliothèque
- Liste complète des utilisateurs, étudiants et classes

## Commandes utiles

### Rafraîchir la base de données avec nouvelles données
```bash
php artisan migrate:fresh --seed
```

### Créer des données supplémentaires via Tinker
```bash
php artisan tinker
```

Puis dans Tinker:
```php
User::factory(50)->create();
Student::factory(30)->create();
Payment::factory(100)->paid()->create();
```

## Mots de passe par défaut

Tous les utilisateurs créés ont le mot de passe: `password`

## Structure des factories

Les factories suivantes sont disponibles:
- `UserFactory` - Génère des utilisateurs variés
- `StudentFactory` - Génère des étudiants
- `ClassroomFactory` - Génère des classes
- `CourseFactory` - Génère des cours
- `HomeworkFactory` - Génère des devoirs
- `BookFactory` - Génère des livres
- `BookLoanFactory` - Génère des emprunts de livres
- `PaymentFactory` - Génère des paiements
- `AnnouncementFactory` - Génère des annonces
- `TuitionFeeFactory` - Génère les structures de frais
- `RoomFactory` - Génère des salles
- `HomeworkSubmissionFactory` - Génère des soumissions de devoirs
- `AnnouncementReadFactory` - Génère des lectures d'annonces
- `StudentSchoolGradeFactory` - Génère des notes d'étudiants
