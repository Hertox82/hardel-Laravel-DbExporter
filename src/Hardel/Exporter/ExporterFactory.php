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
     * @return MySqlExporter|PostgresExporter|SQLiteExporter|SqlServerExporter
     */
    public function make($driver)
    {
        switch ($driver)
        {
            case 'mysql':
                return new MySqlExporter($this->container['exp.mysql.migrator'],$this->container['exp.mysql.seeder']);
            case 'pgsql':
                return new PostgresExporter($this->container['exp.pgsql.migrator'],$this->container['exp.pgsql.seeder']);
            case 'sqlite':
                return new SQLiteExporter($this->container['exp.sqlite.migrator'],$this->container['exp.sqlite.seeder']);
            case 'sqlsrv':
                return new SqlServerExporter($this->container['exp.sqlsrv.migrator'],$this->container['exp.sqlsrv.seeder']);
        }
    }

}