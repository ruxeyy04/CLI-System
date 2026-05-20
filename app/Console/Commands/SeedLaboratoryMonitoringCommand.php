<?php

namespace App\Console\Commands;

use Database\Seeders\LaboratoryMonitoringSeeder;
use Illuminate\Console\Command;
use InvalidArgumentException;

class SeedLaboratoryMonitoringCommand extends Command
{
    protected $signature = 'monitoring:seed
                            {--labs= : Comma-separated laboratory names (e.g. HF-201,HF-202). Omit to seed all}
                            {--workstations= : Workstations per laboratory (default: '.LaboratoryMonitoringSeeder::DEFAULT_WORKSTATIONS_PER_LAB.')}
                            {--green= : Healthy (green) workstations per lab}
                            {--red= : Unhealthy (red) workstations per lab. green + red must equal --workstations when both are set}
                            {--list : List available laboratories and defaults}';

    protected $description = 'Seed demo laboratories, workstations, and monitoring metrics';

    public function handle(LaboratoryMonitoringSeeder $seeder): int
    {
        if ($this->option('list')) {
            $this->line('Available laboratories:');
            foreach (array_keys(LaboratoryMonitoringSeeder::laboratoryCatalog()) as $name) {
                $this->line("  - {$name}");
            }
            $default = LaboratoryMonitoringSeeder::DEFAULT_WORKSTATIONS_PER_LAB;
            [$g, $r] = LaboratoryMonitoringSeeder::resolveHealthCounts($default, null, null);
            $this->newLine();
            $this->line("Defaults (per lab): {$default} workstations, {$g} green, {$r} red");
            $this->line('Override with --workstations=10 --green=8 --red=2');

            return self::SUCCESS;
        }

        $labsOption = $this->option('labs');

        if ($labsOption !== null && trim($labsOption) === '') {
            $this->error('The --labs option cannot be empty. Use --list to see available names.');

            return self::FAILURE;
        }

        try {
            if ($labsOption) {
                $seeder->setLaboratories(LaboratoryMonitoringSeeder::parseLaboratoryNames($labsOption));
            }

            $workstations = $this->option('workstations');
            $green = $this->option('green');
            $red = $this->option('red');

            if ($workstations !== null || $green !== null || $red !== null) {
                $perLab = $workstations !== null
                    ? (int) $workstations
                    : LaboratoryMonitoringSeeder::DEFAULT_WORKSTATIONS_PER_LAB;

                $seeder->setWorkstationCounts(
                    $perLab,
                    $green !== null ? (int) $green : null,
                    $red !== null ? (int) $red : null
                );
            }
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $seeder->setCommand($this);
        $seeder->run();

        return self::SUCCESS;
    }
}
