<?php
/**
 * @author hernan arie de luca
 * Date: 12/07/2017
 * Time: 16:37
 */

namespace Hardel\Exporter\Commands;


use Hardel\Exporter\AbstractAction;

class SeedCommand extends ExporterCommand
{
    protected $signature = 'dbexp:seed {database?} {--ignore=} {--select=}';

    protected $description = 'export your data from database to a seed class';

    public function handle()
    {
        $this->comment("Preparing the seeder class for database {$this->expManager->getDatabaseName()}");

        // Grab the options
        $database = $this->argument('database');

        $ignore = $this->option('ignore');

        $selected = $this->option('select');

        if (empty($ignore) and empty($selected)) {
            $this->expManager->migrate($database);
        } else {
            if(!empty($ignore) and empty($selected)) {
                $this->makeAction(compact('ignore'),"seed",$this->argument('database'));
            }
            else if(empty($ignore) and !empty($selected)) {
                $this->makeAction(compact('selected'),'seed',$this->argument('database'));
            }
            else {
                $this->error("it is not possible pass selected table and ignored table together");
            }
        }

        $filename = $this->getFilename();

        $this->info('Success!');
        $this->info("Database seed class generated in: {$filename}");
    }
}