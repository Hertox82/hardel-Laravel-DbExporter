<?php
/**
 * @author hernan ariel de luca
 * Date: 12/07/2017
 * Time: 17:32
 */

namespace Hardel\Exporter\Commands;

use Illuminate\Console\Command;
use Hardel\Exporter\ExporterManager;

class AllActionCommand extends ExporterCommand
{
    protected $signature = 'dbexp:all {database?} {--ignore=} {--select=}';

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

        $selected = $this->option('select');

        if (empty($ignore) and empty($selected)) {
            $this->expManager->migrateAndSeed($database);
        } else {
            if(!empty($ignore) and empty($selected)) {
               $this->makeAction(compact('ignore'),'migrateAndSeed');
            } else if(empty($ignore) and !empty($selected)) {
               $this->makeAction(compact('selected'), 'migrateAndSeed');
            }
            else {
                $this->error("it is not possible pass selected table and ignored table together");
            }
        }

        $filename = $this->getFilename();
        $this->info('Success!');
        $this->info('Database migrations generated in: ' . $this->expManager->getMigrationsFilePath());
        $this->info("Database seed class generated in: {$filename}");
    }

}