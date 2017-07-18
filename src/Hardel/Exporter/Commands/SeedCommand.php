<?php
/**
 * @author hernan arie de luca
 * Date: 12/07/2017
 * Time: 16:37
 */

namespace Hardel\Exporter\Commands;


use Hardel\Exporter\AbstractAction;
use Hardel\Exporter\ExporterManager;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SeedCommand extends ExporterCommand
{
    protected $signature = 'dbexp:seed {database?} {--ignore=}';

    protected $description = 'export your data from database to a seed class';

    public function handle()
    {
        $this->comment("Preparing the seeder class for database {$this->expManager->getDatabaseName()}");

        // Grab the options
        $ignore = $this->option('ignore');

        if (empty($ignore)) {
            $this->expManager->seed();
        } else {
            $tables = explode(',', str_replace(' ', '', $ignore));
            $this->expManager->ignore($tables)->seed();
            foreach (AbstractAction::$ignore as $table) {
                $this->comment("Ignoring the {$table} table");
            }
        }

        $filename = $this->getFilename();

        $this->info('Success!');
        $this->info("Database seed class generated in: {$filename}");
    }
}