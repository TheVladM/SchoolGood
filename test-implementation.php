#!/usr/bin/env php
<?php

/**
 * Script de vérification post-implémentation
 * Exécutez avec : php artisan tinker < test-implementation.php
 */

echo "=== Vérification de l'implémentation des 3 fonctionnalités ===\n\n";

// 1. Vérifier les colonnes dans les tables
echo "📋 Vérification des colonnes...\n";

$elevesPhotoExists = \Schema::hasColumn('eleves', 'photoURL');
echo ($elevesPhotoExists ? "✅" : "❌") . " Table 'eleves' : colonne 'photoURL'\n";

$personnesPhotoExists = \Schema::hasColumn('personnes', 'photoURL');
echo ($personnesPhotoExists ? "✅" : "❌") . " Table 'personnes' : colonne 'photoURL'\n";

$adminsPhotoExists = \Schema::hasColumn('admins', 'photoURL');
echo ($adminsPhotoExists ? "✅" : "❌") . " Table 'admins' : colonne 'photoURL'\n";

// 2. Vérifier les modèles
echo "\n📦 Vérification des modèles...\n";

$eleveModelExists = class_exists(\App\Models\Eleve::class);
echo ($eleveModelExists ? "✅" : "❌") . " Modèle Eleve existe\n";

$personneModelExists = class_exists(\App\Models\Personne::class);
echo ($personneModelExists ? "✅" : "❌") . " Modèle Personne existe\n";

$parentModelExists = class_exists(\App\Models\ParentEleve::class);
echo ($parentModelExists ? "✅" : "❌") . " Modèle ParentEleve existe\n";

// Vérifier les scopes et traits
$eleveModel = new \App\Models\Eleve();
$hasPhotoFillable = in_array('photoURL', $eleveModel->getFillable());
echo ($hasPhotoFillable ? "✅" : "❌") . " Eleve::photoURL est remplissable\n";

$personneModel = new \App\Models\Personne();
$hasParentScope = method_exists($personneModel, 'scopeParents');
echo ($hasParentScope ? "✅" : "❌") . " Personne::scopeParents() existe\n";

// 3. Vérifier les contrôleurs
echo "\n🎮 Vérification des contrôleurs...\n";

$eleveCtrlExists = class_exists(\App\Http\Controllers\Pedagogie\EleveController::class);
echo ($eleveCtrlExists ? "✅" : "❌") . " EleveController existe\n";

$personneCtrlExists = class_exists(\App\Http\Controllers\Pedagogie\PersonneController::class);
echo ($personneCtrlExists ? "✅" : "❌") . " PersonneController existe\n";

$adminCtrlExists = class_exists(\App\Http\Controllers\Administration\AdminController::class);
echo ($adminCtrlExists ? "✅" : "❌") . " AdminController existe\n";

$parentsCtrlExists = class_exists(\App\Http\Controllers\Administration\ParentsController::class);
echo ($parentsCtrlExists ? "✅" : "❌") . " ParentsController existe\n";

// Vérifier les méthodes de recherche
$eleveCtrl = new \App\Http\Controllers\Pedagogie\EleveController();
$hasEleveSearch = method_exists($eleveCtrl, 'search');
echo ($hasEleveSearch ? "✅" : "❌") . " EleveController::search() existe\n";

$personneCtrl = new \App\Http\Controllers\Pedagogie\PersonneController();
$hasPersonneSearch = method_exists($personneCtrl, 'search');
echo ($hasPersonneSearch ? "✅" : "❌") . " PersonneController::search() existe\n";

$adminCtrl = new \App\Http\Controllers\Administration\AdminController();
$hasListePdf = method_exists($adminCtrl, 'listePdf');
echo ($hasListePdf ? "✅" : "❌") . " AdminController::listePdf() existe\n";

$hasListeExcel = method_exists($adminCtrl, 'listeExcel');
echo ($hasListeExcel ? "✅" : "❌") . " AdminController::listeExcel() existe\n";

// 4. Vérifier les Form Requests
echo "\n✅ Vérification des validations...\n";

$storeEleveReqExists = class_exists(\App\Http\Requests\StoreEleveRequest::class);
echo ($storeEleveReqExists ? "✅" : "❌") . " StoreEleveRequest existe\n";

$storeAdminReqExists = class_exists(\App\Http\Requests\StoreAdminRequest::class);
echo ($storeAdminReqExists ? "✅" : "❌") . " StoreAdminRequest existe\n";

$storeParentReqExists = class_exists(\App\Http\Requests\StoreParentRequest::class);
echo ($storeParentReqExists ? "✅" : "❌") . " StoreParentRequest existe\n";

// 5. Vérifier les Exports
echo "\n📊 Vérification des exports...\n";

$adminsExportExists = class_exists(\App\Exports\AdminsExport::class);
echo ($adminsExportExists ? "✅" : "❌") . " AdminsExport existe\n";

$elevesExportExists = class_exists(\App\Exports\ElevesExport::class);
echo ($elevesExportExists ? "✅" : "❌") . " ElevesExport existe\n";

// 6. Vérifier le trait
echo "\n🔧 Vérification du trait partagé...\n";

$traitExists = trait_exists(\App\Http\Traits\ReusablePhotoUpload::class);
echo ($traitExists ? "✅" : "❌") . " ReusablePhotoUpload trait existe\n";

// 7. Vérifier les fichiers JavaScript
echo "\n🌐 Vérification des fichiers JavaScript...\n";

$photoJsExists = file_exists('resources/js/api/elevePhoto.js');
echo ($photoJsExists ? "✅" : "❌") . " resources/js/api/elevePhoto.js existe\n";

$parentLinkJsExists = file_exists('resources/js/api/parentLink.js');
echo ($parentLinkJsExists ? "✅" : "❌") . " resources/js/api/parentLink.js existe\n";

$printListsJsExists = file_exists('resources/js/api/printLists.js');
echo ($printListsJsExists ? "✅" : "❌") . " resources/js/api/printLists.js existe\n";

// 8. Vérifier les vues
echo "\n📄 Vérification des vues Blade...\n";

$listeAdminsExists = file_exists('resources/views/admin/liste-admins.blade.php');
echo ($listeAdminsExists ? "✅" : "❌") . " resources/views/admin/liste-admins.blade.php existe\n";

$listeElevesExists = file_exists('resources/views/admin/liste-eleves.blade.php');
echo ($listeElevesExists ? "✅" : "❌") . " resources/views/admin/liste-eleves.blade.php existe\n";

$demoBrowserExists = file_exists('resources/views/integration-demo.blade.php');
echo ($demoBrowserExists ? "✅" : "❌") . " resources/views/integration-demo.blade.php existe\n";

// 9. Résumé
echo "\n=== Résumé ===\n";
$allOk = $elevesPhotoExists && $personnesPhotoExists && $adminsPhotoExists &&
         $eleveCtrlExists && $personneCtrlExists && $adminCtrlExists && $parentsCtrlExists &&
         $hasEleveSearch && $hasPersonneSearch && $hasListePdf && $hasListeExcel &&
         $storeEleveReqExists && $storeAdminReqExists && $storeParentReqExists &&
         $adminsExportExists && $elevesExportExists &&
         $traitExists &&
         $photoJsExists && $parentLinkJsExists && $printListsJsExists &&
         $listeAdminsExists && $listeElevesExists && $demoBrowserExists;

if ($allOk) {
    echo "✅ Toutes les vérifications ont réussi !\n";
    echo "\n🚀 Prochaines étapes :\n";
    echo "1. Exécuter : php artisan migrate\n";
    echo "2. Exécuter : php artisan storage:link\n";
    echo "3. Visiter : http://127.0.0.1:8000/integration-demo\n";
    exit(0);
} else {
    echo "❌ Certains fichiers ou configurations manquent.\n";
    echo "Vérifiez les éléments marqués en ❌ ci-dessus.\n";
    exit(1);
}
