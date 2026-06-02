<?php

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

Route::middleware('auth:web')->name('api.')->group(function (): void {
    Route::apiResource('homeworks', HomeworkApiController::class);
    Route::apiResource('classrooms', ClassroomApiController::class);
    Route::apiResource('students', StudentApiController::class);
    Route::apiResource('courses', CourseApiController::class);

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('user');
});
