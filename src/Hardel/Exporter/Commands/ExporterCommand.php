<?php
/**
 * Created by PhpStorm.
 * User: hernan
 * Date: 17/07/2017
 * Time: 10:51
 */

namespace Hardel\Exporter\Commands;


use Illuminate\Console\Command;
use Hardel\Exporter\ExporterManager;

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
}