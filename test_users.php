<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Users: " . App\Models\User::count() . "\n";
echo "Students: " . App\Models\Student::count() . "\n";
echo "Classrooms: " . App\Models\Classroom::count() . "\n";
