<?php
/**
 * Created by PhpStorm.
 * User: hernan
 * Date: 12/07/2017
 * Time: 12:31
 */

namespace Hardel\Exporter;


use Hardel\Exporter\DriverExporter\MySqlExporter;
use Hardel\Exporter\DriverExporter\PostgresExporter;
use Hardel\Exporter\DriverExporter\SQLiteExporter;
use Hardel\Exporter\DriverExporter\SqlServerExporter;
use Illuminate\Container\Container;
use Closure;

class ExporterFactory{

    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    /**
     * @param $driver
     * @param Closure $callback
     * @return MySqlExporter|PostgresExporter|SQLiteExporter|SqlServerExporter
     */
    public function make($driver,Closure $callback = null)
    {
        $exporter = null;
        switch ($driver)
        {
            case 'mysql':
                 $exporter = new MySqlExporter($this->container['exp.mysql.migrator'],$this->container['exp.mysql.seeder']);
                 break;
            case 'pgsql':
                $exporter =  new PostgresExporter($this->container['exp.pgsql.migrator'],$this->container['exp.pgsql.seeder']);
                break;
            case 'sqlite':
                $exporter =  new SQLiteExporter($this->container['exp.sqlite.migrator'],$this->container['exp.sqlite.seeder']);
                break;
            case 'sqlsrv':
                $exporter = new SqlServerExporter($this->container['exp.sqlsrv.migrator'],$this->container['exp.sqlsrv.seeder']);
                break;
        }

        if($callback != null)
            $exporter = $callback($exporter);

        return $exporter;
    }

}