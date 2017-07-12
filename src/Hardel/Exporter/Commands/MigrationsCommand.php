<?php
/**
 * Created by PhpStorm.
 * User: hernan
 * Date: 12/07/2017
 * Time: 16:57
 */

namespace Hardel\Exporter\Commands;


use Hardel\Exporter\AbstractAction;
use Illuminate\Console\Command;
use Hardel\Exporter\ExporterManager;

class MigrationsCommand extends Command
{
    protected $signature = 'dbexp:migration {database} {--ignore=}';

    protected $description = 'export your data from database to a seed class';

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
            $this->comment("Preparing the migrations for database: {$this->expManager->getDatabaseName()}");
        } else {
            $this->comment("Preparing the migrations for database {$database}");
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
        $this->info('Success!');
        $this->info('Database migrations generated in: ' . $this->expManager->getMigrationsFilePath());
    }
}