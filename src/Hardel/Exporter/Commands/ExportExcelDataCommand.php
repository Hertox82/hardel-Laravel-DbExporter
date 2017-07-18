<?php
/**
 * @author hernan
 * Date: 17/07/2017
 * Time: 16:48
 */

namespace Hardel\Exporter\Commands;


class ExportExcelDataCommand extends ExporterCommand
{
    protected $signature = 'dbexp:excel-data {database?} {--path=} {--ignore=}';

    protected $description = 'export your data from database to a excel file';

    protected $path;

    public function handle()
    {
        $this->comment("Preparing the seeder class for database {$this->expManager->getDatabaseName()}");

        // Grab the options
        $ignore = $this->option('ignore');

        $path = $this->option('path');

        if(!empty($path))
        {
            $this->comment("Storing the path");
            $this->path = str_replace('.','/',$path);
            pr($this->path,1);
        }

        if (empty($ignore)) {
            $this->expManager->seed(null,'excel');
        } else {
            $tables = explode(',', str_replace(' ', '', $ignore));
            $this->expManager->ignore($tables)->seed(null,'excel');
            foreach (AbstractAction::$ignore as $table) {
                $this->comment("Ignoring the {$table} table");
            }
        }

        $filename = $this->getFilename();

        $this->info('Success!');
        $this->info("Database seed class generated in: {$filename}");
    }
}