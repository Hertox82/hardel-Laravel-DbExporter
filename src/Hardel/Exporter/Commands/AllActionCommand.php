<?php
/**
 * Created by PhpStorm.
 * User: hernan
 * Date: 12/07/2017
 * Time: 17:32
 */

namespace Hardel\Exporter\Commands;

use Hardel\Exporter\AbstractAction;
use Illuminate\Console\Command;
use Hardel\Exporter\ExporterManager;

class AllActionCommand extends Command
{
    protected $signature = 'dbexp:all {database} {--ignore=}';

    protected $description = 'export all structure and data in a migration and seed class';

    /**
     * @var ExporterManager
     */
    protected $expManager;

    public function __construct(ExporterManager $manager)
    {
        parent::__construct();

        $this->expManager = $manager;
    }

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
            $this->expManager->migrate($database);
        } else {
            $tables = explode(',', str_replace(' ', '', $ignore));

            $this->expManager->ignore($tables)->migrate($this->argument('database'));
            foreach (AbstractAction::$ignore as $table) {
                $this->comment("Ignoring the {$table} table");
            }
        }

        $filename = $this->getFilename();
        $this->info('Success!');
        $this->info('Database migrations generated in: ' . $this->expManager->getMigrationsFilePath());
        $this->info("Database seed class generated in: {$filename}");
    }

    private function getFilename()
    {
        $filename = Str::camel($this->expManager->getDatabaseName()) . "TableSeeder";
        return config('dbexporter.exportPath.seeds')."{$filename}.php";
    }
}