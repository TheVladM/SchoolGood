<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\Book;
use App\Models\BookLoan;
use App\Models\Classroom;
use App\Models\Homework;
use App\Models\Payment;
use App\Models\TuitionFee;
use App\Models\User;
use App\Policies\AnnouncementPolicy;
use App\Policies\BookLoanPolicy;
use App\Policies\BookPolicy;
use App\Policies\ClassroomPolicy;
use App\Policies\HomeworkPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\TuitionFeePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Payment::class => PaymentPolicy::class,
        Announcement::class => AnnouncementPolicy::class,
        Book::class => BookPolicy::class,
        BookLoan::class => BookLoanPolicy::class,
        Classroom::class => ClassroomPolicy::class,
        TuitionFee::class => TuitionFeePolicy::class,
        Homework::class => HomeworkPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
