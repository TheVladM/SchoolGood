<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookLoanController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\StudentController;
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
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class)->middleware('role:fondateur,admin');
    Route::resource('students', StudentController::class);
    Route::resource('classrooms', ClassroomController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('announcements', AnnouncementController::class);
    Route::post('/announcements/{announcement}/approve', [AnnouncementController::class, 'approve'])
        ->name('announcements.approve');
    Route::post('/announcements/{announcement}/reject', [AnnouncementController::class, 'reject'])
        ->name('announcements.reject');
    Route::resource('timetable-entries', TimetableEntryController::class);
    Route::resource('school-years', SchoolYearController::class);
    Route::post('/school-years/{schoolYear}/prepare-promotions', [SchoolYearController::class, 'preparePromotions'])
        ->name('school-years.prepare-promotions');
    Route::resource('books', BookController::class);
    Route::resource('book-loans', BookLoanController::class);
    Route::post('/book-loans/{bookLoan}/return', [BookLoanController::class, 'returnLoan'])
        ->name('book-loans.return');
    Route::resource('tuition-fees', TuitionFeeController::class)->middleware('role:fondateur');
    Route::resource('homeworks', HomeworkController::class);

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
