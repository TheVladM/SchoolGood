<?php

namespace App\Console\Commands;

use App\Services\SchoolYearPromotionService;
use Illuminate\Console\Command;

class PromoteSchoolYearsCommand extends Command
{
    protected $signature = 'school-years:auto-promote';

    protected $description = 'Prépare les promotions pour les années scolaires dont la date est atteinte';

    public function handle(SchoolYearPromotionService $service): int
    {
        $count = $service->runDueAutoPromotions();

        $this->info("Promotions préparées pour {$count} élève(s).");

        return self::SUCCESS;
    }
}
