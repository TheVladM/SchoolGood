<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AnnouncementTemplateController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookLoanController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\HomeworkSubmissionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMobileController;
use App\Http\Controllers\PaymentReceiptController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentSchoolGradeController;
use App\Http\Controllers\TimetableEntryController;
use App\Http\Controllers\TuitionFeeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/mot-de-passe-oublie', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/mot-de-passe-oublie', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reinitialiser-mot-de-passe/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reinitialiser-mot-de-passe', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('users', UserController::class)->middleware('role:fondateur,admin');
    Route::resource('students', StudentController::class);
    Route::post('/students/{student}/grades', [StudentSchoolGradeController::class, 'store'])->name('students.grades.store');
    Route::delete('/students/{student}/grades/{grade}', [StudentSchoolGradeController::class, 'destroy'])->name('students.grades.destroy');
    Route::resource('classrooms', ClassroomController::class);
    Route::post('/classrooms/{classroom}/programme-titulaire', [ClassroomController::class, 'setupTitularCourses'])
        ->name('classrooms.setup-titular-courses');
    Route::resource('rooms', RoomController::class)->middleware('role:fondateur,admin');
    Route::resource('courses', CourseController::class);

    Route::get('/paiements/declarer', [PaymentController::class, 'declareForm'])->name('payments.declare');
    Route::post('/paiements/declarer', [PaymentController::class, 'declareStore'])->name('payments.declare.store');
    Route::get('/paiements/payer-en-ligne', [PaymentMobileController::class, 'create'])->name('payments.mobile.create');
    Route::post('/paiements/payer-en-ligne', [PaymentMobileController::class, 'store'])->name('payments.mobile.store');
    Route::get('/paiements/{payment}/en-attente', [PaymentMobileController::class, 'pending'])->name('payments.mobile.pending');
    Route::get('/paiements/{payment}/retour-operateur', [PaymentMobileController::class, 'return'])->name('payments.mobile.return');
    Route::get('/paiements/{payment}/recu', PaymentReceiptController::class)->name('payments.receipt');
    Route::get('/paiements/eleves/{student}/tarifs', [PaymentController::class, 'tuitionSummary'])
        ->name('payments.tuition-summary');
    Route::post('/paiements/{payment}/valider', [PaymentController::class, 'validatePayment'])
        ->name('payments.validate');
    Route::resource('payments', PaymentController::class);

    Route::resource('announcements', AnnouncementController::class);
    Route::post('/announcements/{announcement}/approve', [AnnouncementController::class, 'approve'])
        ->name('announcements.approve');
    Route::post('/announcements/{announcement}/reject', [AnnouncementController::class, 'reject'])
        ->name('announcements.reject');
    Route::get('/modeles-messages', [AnnouncementTemplateController::class, 'index'])->name('announcement-templates.index');
    Route::post('/modeles-messages', [AnnouncementTemplateController::class, 'store'])->name('announcement-templates.store');
    Route::delete('/modeles-messages/{template}', [AnnouncementTemplateController::class, 'destroy'])->name('announcement-templates.destroy');

    Route::resource('timetable-entries', TimetableEntryController::class);
    Route::resource('school-years', SchoolYearController::class);
    Route::post('/school-years/{schoolYear}/prepare-promotions', [SchoolYearController::class, 'preparePromotions'])
        ->name('school-years.prepare-promotions');
    Route::resource('books', BookController::class);
    Route::resource('book-loans', BookLoanController::class);
    Route::post('/book-loans/{bookLoan}/return', [BookLoanController::class, 'returnLoan'])
        ->name('book-loans.return');
    Route::post('/book-loans/{bookLoan}/penalite', [BookLoanController::class, 'chargePenalty'])
        ->name('book-loans.charge-penalty');
    Route::resource('tuition-fees', TuitionFeeController::class);
    Route::resource('homeworks', HomeworkController::class);
    Route::post('/homeworks/{homework}/rendus', [HomeworkSubmissionController::class, 'store'])
        ->name('homeworks.submissions.store');
    Route::post('/homeworks/{homework}/rendus/{submission}/noter', [HomeworkSubmissionController::class, 'grade'])
        ->name('homeworks.submissions.grade');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
