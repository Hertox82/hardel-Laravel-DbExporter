<?php
/**
 * @author hernan ariel de luca
 * Date: 12/07/2017
 * Time: 16:57
 */

namespace Hardel\Exporter\Commands;


use Hardel\Exporter\AbstractAction;

class MigrationsCommand extends ExporterCommand
{
    protected $signature = 'dbexp:migration {database?} {--ignore=} {--select=}';

    protected $description = 'export your table structur from database to a migration';


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

        $selected = $this->option('select');

        if (empty($ignore) and empty($selected)) {
            $this->expManager->migrate($database);
        } else {
            if(!empty($ignore) and empty($selected)) {
                $this->makeAction(compact('ignore'),"migrate",$this->argument('database'));
            }
            else if(empty($ignore) and !empty($selected)) {
               $this->makeAction(compact('selected'),'migrate',$this->argument('database'));
            }
            else {
                $this->error("it is not possible pass selected table and ignored table together");
            }
        }
        $this->info('Success!');
        $this->info('Database migrations generated in: ' . $this->expManager->getMigrationsFilePath());
    }
}