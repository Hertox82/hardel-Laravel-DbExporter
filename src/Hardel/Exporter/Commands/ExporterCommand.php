<?php
/**
 * @authro hernan ariel de luca
 * Date: 17/07/2017
 * Time: 10:51
 */

namespace Hardel\Exporter\Commands;


use Illuminate\Console\Command;
use Hardel\Exporter\ExporterManager;
use Illuminate\Support\Str;
use Hardel\Exporter\AbstractAction;

class ExporterCommand extends Command
{

    /**
     * @var ExporterManager
     */
    protected $expManager;

    public function __construct(ExporterManager $manager)
    {
        parent::__construct();

        $this->expManager = $manager;
    }


    protected function getFilename()
    {
        $filename = Str::camel($this->expManager->getDatabaseName()) . "TableSeeder";
        return config('dbexporter.exportPath.seeds')."{$filename}.php";
    }


    /**
     * This function make generic action
     * @param array $option
     * @param $function
     * @param null $database
     * @param null $custom
     */
    protected function makeAction(array $option, $function,$database = null, $custom = null) {
        $key = array_keys($option)[0];
        $value = $option[$key];
        $tables = explode(',', str_replace(' ', '', $value));
        $this->expManager->{$key}($tables)->{$function}($database,$custom);
        $listOfTables = [];
        switch ($key) {
            case 'ignore' : $listOfTables = AbstractAction::$ignore;
                break;

            case 'selected': $listOfTables = AbstractAction::$selected;
                break;
        }
        foreach ($listOfTables as $table) {
            $this->comment("{$key} this {$table} table");
        }
    }
}