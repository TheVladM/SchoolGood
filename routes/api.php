<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeworkApiController;
use App\Http\Controllers\Api\ClassroomApiController;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\CourseApiController;

Route::middleware('auth:sanctum')->group(function () {
    // Homeworks API
    Route::apiResource('homeworks', HomeworkApiController::class);

    // Classrooms API
    Route::apiResource('classrooms', ClassroomApiController::class);

    // Students API
    Route::apiResource('students', StudentApiController::class);

    // Courses API
    Route::apiResource('courses', CourseApiController::class);

    // User profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
