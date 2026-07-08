<?php

use App\Http\Controllers\Administration\AdminController;
use App\Http\Controllers\Communication\MessageController;
use App\Http\Controllers\Finance\ModeController;
use App\Http\Controllers\Finance\PaiementController as FinancePaiementController;
use App\Http\Controllers\Finance\ScolariteController;
use App\Http\Controllers\Finance\TrancheController;
use App\Http\Controllers\Pedagogie\AnneeAcademiqueController;
use App\Http\Controllers\Pedagogie\ClasseController;
use App\Http\Controllers\Pedagogie\CycleController;
use App\Http\Controllers\Pedagogie\CoursController;
use App\Http\Controllers\Pedagogie\EleveController;
use App\Http\Controllers\Pedagogie\EmploiDuTempsController;
use App\Http\Controllers\Pedagogie\EpreuveController;
use App\Http\Controllers\Pedagogie\EvaluationController;
use App\Http\Controllers\Pedagogie\LivreController;
use App\Http\Controllers\Pedagogie\NatureEpreuveController;
use App\Http\Controllers\Pedagogie\PersonneController;
use App\Http\Controllers\Pedagogie\SalleController;
use App\Http\Controllers\Pedagogie\SessionController;
use App\Http\Controllers\Pedagogie\SpecialiteController;
use App\Http\Controllers\Pedagogie\TitulaireController;
use App\Http\Controllers\Pedagogie\TrimestreController;
use App\Http\Controllers\Administration\DisciplineController;
use App\Http\Controllers\Administration\ParentsController;
use App\Http\Controllers\Administration\QuartierController;
use App\Http\Controllers\Administration\ResidentController;
use App\Http\Controllers\Administration\VilleNaissanceController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\Api\ClassroomApiController;
use App\Http\Controllers\Api\CourseApiController;
use App\Http\Controllers\Api\HomeworkApiController;
use App\Http\Controllers\Api\StudentApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('webhooks/payments')->name('webhooks.payments.')->group(function (): void {
    Route::post('/orange', [PaymentWebhookController::class, 'orange'])->name('orange');
    Route::post('/mtn', [PaymentWebhookController::class, 'mtn'])->name('mtn');
});

Route::middleware('auth:sanctum')->name('api.')->group(function (): void {
    Route::apiResource('homeworks', HomeworkApiController::class);
    Route::apiResource('classrooms', ClassroomApiController::class);
    Route::apiResource('students', StudentApiController::class);
    Route::apiResource('courses', CourseApiController::class);

    Route::get('eleves/search', [EleveController::class, 'search'])->name('eleves.search');
    Route::apiResource('eleves', EleveController::class);
    Route::apiResource('annee-academiques', AnneeAcademiqueController::class);
    Route::apiResource('trimestres', TrimestreController::class);
    Route::apiResource('salles', SalleController::class);
    Route::apiResource('classes', ClasseController::class);
    Route::apiResource('cycles', CycleController::class);
    Route::get('personnes/search', [PersonneController::class, 'search'])->name('personnes.search');
    Route::apiResource('cours', CoursController::class);
    Route::apiResource('livres', LivreController::class);
    Route::apiResource('specialites', SpecialiteController::class);
    Route::apiResource('personnes', PersonneController::class);
    Route::apiResource('titulaires', TitulaireController::class);
    Route::apiResource('enseignants', App\Http\Controllers\Pedagogie\EnseignantController::class);
    Route::apiResource('epreuves', EpreuveController::class);
    Route::apiResource('nature-epreuves', NatureEpreuveController::class);
    Route::apiResource('evaluations', EvaluationController::class);
    Route::apiResource('sessions', SessionController::class);
    Route::apiResource('emploi-du-temps', EmploiDuTempsController::class);
    Route::apiResource('rapports', App\Http\Controllers\Administration\RapportController::class);

    Route::apiResource('scolarites', ScolariteController::class);
    Route::apiResource('tranches', TrancheController::class);
    Route::apiResource('modes', ModeController::class);
    Route::apiResource('paiements', FinancePaiementController::class);

    Route::apiResource('admins', AdminController::class);
    Route::apiResource('ville-naissances', VilleNaissanceController::class);
    Route::apiResource('parents', ParentsController::class);
    Route::apiResource('disciplines', DisciplineController::class);
    Route::apiResource('quartiers', QuartierController::class);
    Route::apiResource('residents', ResidentController::class);
    Route::apiResource('messages', MessageController::class);

    Route::get('personnes/search', [PersonneController::class, 'search'])->name('personnes.search');
    Route::get('eleves/search', [EleveController::class, 'search'])->name('eleves.search');
    Route::delete('eleves/{eleve}/photo', [EleveController::class, 'deletePhoto'])->name('eleves.photo.delete');
    Route::get('admin/liste-pdf', [AdminController::class, 'listePdf'])->name('admin.liste-pdf');
    Route::get('admin/liste-excel', [AdminController::class, 'listeExcel'])->name('admin.liste-excel');

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('user');
});
