<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Homework;
use App\Models\Payment;
use App\Models\Book;
use App\Models\BookLoan;
use App\Models\Announcement;
use App\Models\TuitionFee;

echo "=== STATISTIQUES DE BASE ===\n";
echo "Total des utilisateurs: " . User::count() . "\n";
echo "Enseignants: " . User::where('role', 'Teacher')->count() . "\n";
echo "Parents: " . User::where('role', 'Parent')->count() . "\n";
echo "Administrateurs: " . User::where('role', 'Admin')->count() . "\n";
echo "\n";

echo "=== DONNÉES SCOLAIRES ===\n";
echo "Total d'étudiants: " . Student::count() . "\n";
echo "Total de classes: " . Classroom::count() . "\n";
echo "Total de cours: " . Course::count() . "\n";
echo "Total de devoirs: " . Homework::count() . "\n";
echo "\n";

echo "=== GESTION FINANCIÈRE ===\n";
echo "Total des paiements: " . Payment::count() . "\n";
echo "Paiements payés: " . Payment::where('status', 'Paid')->count() . "\n";
echo "Paiements en attente: " . Payment::where('status', 'Pending')->count() . "\n";
echo "Montant total payé: " . Payment::where('status', 'Paid')->sum('amount') . " FCFA\n";
echo "Total des frais de scolarité: " . TuitionFee::count() . "\n";
echo "\n";

echo "=== BIBLIOTHÈQUE ===\n";
echo "Total de livres: " . Book::count() . "\n";
echo "Copies de livres (total): " . Book::sum('total_copies') . "\n";
echo "Total des emprunts: " . BookLoan::count() . "\n";
echo "Emprunts actuels: " . BookLoan::whereNull('returned_at')->count() . "\n";
echo "Emprunts retournés: " . BookLoan::whereNotNull('returned_at')->count() . "\n";
echo "\n";

echo "=== COMMUNICATIONS ===\n";
echo "Total des annonces: " . Announcement::count() . "\n";
echo "Annonces approuvées: " . Announcement::where('status', 'Approved')->count() . "\n";
echo "\n";

echo "=== DÉTAIL DES UTILISATEURS ===\n";
$users = User::all();
echo "Liste des utilisateurs:\n";
foreach ($users as $user) {
    echo "- " . $user->name . " (" . $user->role . " - " . $user->email . ")\n";
}
echo "\n";

echo "=== DÉTAIL DES ÉTUDIANTS ET CLASSES ===\n";
$students = Student::with('classroom', 'parent')->get();
echo "Liste des étudiants:\n";
foreach ($students as $student) {
    $parentName = $student->parent ? $student->parent->name : 'N/A';
    $classroomName = $student->classroom ? $student->classroom->name : 'N/A';
    echo "- " . $student->first_name . " " . $student->last_name . " - Classe: " . $classroomName . " - Parent: " . $parentName . "\n";
}
echo "\n";

echo "=== DÉTAIL DES CLASSES ===\n";
$classrooms = Classroom::with('mainTeacher', 'languageTeacher')->get();
echo "Liste des classes:\n";
foreach ($classrooms as $classroom) {
    $mainTeacher = $classroom->mainTeacher ? $classroom->mainTeacher->name : 'N/A';
    $langTeacher = $classroom->languageTeacher ? $classroom->languageTeacher->name : 'N/A';
    echo "- " . $classroom->name . " (" . $classroom->level . " - " . $classroom->section->value . ")\n";
    echo "  Titulaire: " . $mainTeacher . "\n";
    echo "  Enseignant de langue: " . $langTeacher . "\n";
    echo "  Nombre d'étudiants: " . $classroom->students()->count() . "\n";
}
echo "\n";

echo "=== DONNÉES CHARGÉES AVEC SUCCÈS ===\n";
