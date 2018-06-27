<?php
/**
 * @author hernan ariel de luca
 * Date: 12/07/2017
 * Time: 17:32
 */

namespace Hardel\Exporter\Commands;

use Hardel\Exporter\AbstractAction;
use Illuminate\Console\Command;
use Hardel\Exporter\ExporterManager;

class AllActionCommand extends ExporterCommand
{
    protected $signature = 'dbexp:all {database?} {--ignore=}';

    protected $description = 'export all structure and data in a migration and seed class';

    public function handle()
    {
        $database = $this->argument('database');

        // Display some helpfull info
        if (empty($database)) {
            $this->comment("Preparing the migrations and seed for database: {$this->expManager->getDatabaseName()}");
        } else {
            $this->comment("Preparing the migrations and seed for database {$database}");
        }

        // Grab the options
        $ignore = $this->option('ignore');

        if (empty($ignore)) {
            $this->expManager->migrateAndSeed($database);
        } else {
            $tables = explode(',', str_replace(' ', '', $ignore));

            $this->expManager->ignore($tables)->migrateAndSeed($this->argument('database'));
            foreach (AbstractAction::$ignore as $table) {
                $this->comment("Ignoring the {$table} table");
            }
        }

        $filename = $this->getFilename();
        $this->info('Success!');
        $this->info('Database migrations generated in: ' . $this->expManager->getMigrationsFilePath());
        $this->info("Database seed class generated in: {$filename}");
    }


}