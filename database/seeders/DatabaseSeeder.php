<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $seeder = new LaboratoryMonitoringSeeder();

        if ($labList = env('SEED_LABS')) {
            $seeder->setLaboratories(explode(',', $labList));
        }

        if (env('SEED_WORKSTATIONS') || env('SEED_GREEN') || env('SEED_RED')) {
            $seeder->setWorkstationCounts(
                (int) (env('SEED_WORKSTATIONS') ?: LaboratoryMonitoringSeeder::DEFAULT_WORKSTATIONS_PER_LAB),
                env('SEED_GREEN') !== null ? (int) env('SEED_GREEN') : null,
                env('SEED_RED') !== null ? (int) env('SEED_RED') : null
            );
        }

        $seeder->setCommand($this->command);
        $seeder->run();
    }
}
