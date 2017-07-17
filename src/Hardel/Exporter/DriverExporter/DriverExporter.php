<?php
/**
 * Created by PhpStorm.
 * User: hernan
 * Date: 12/07/2017
 * Time: 14:15
 */


namespace Hardel\Exporter\DriverExporter;

use Hardel\Exporter\AbstractAction;

abstract class DriverExporter
{
    /**
     * @var AbstractAction
     */
    protected $migrator;

    /**
     * @var AbstractAction
     */
    protected $seeder;

    /**
     * @var array
     */
    protected $customSeeder = [];

    /**
     * @var array
     */
    protected $customMigrations = [];

    /**
     * DriverExporter constructor.
     * @param AbstractAction $migrator
     * @param AbstractAction $seeder
     */
    public function __construct(AbstractAction $migrator, AbstractAction $seeder)
    {
        $this->migrator = $migrator;

        $this->seeder = $seeder;
    }

    /**
     * @param $tables
     * @return $this
     */
    public function ignore($tables)
    {
        AbstractAction::$ignore = array_merge(AbstractAction::$ignore,(array)$tables);

        return $this;
    }

    /**
     * @return AbstractAction migrator
     */
    public function migrator()
    {
        return $this->migrator;
    }

    /**
     * @return AbstractAction seeder
     */
    public function seeder()
    {
        return $this->seeder;
    }

    /**
     * @param $key
     * @param AbstractAction $value as Seeder
     */
    public function registerSeeder($key,AbstractAction $value)
    {
        $this->customSeeder[$key] = $value;
    }

    /**
     * @param $key
     * @return AbstractAction|null
     */
    public function seederCustom($key)
    {
        return (isset($this->customSeeder[$key])) ? $this->customSeeder[$key] : null;
    }

    /**
     * @param $key
     * @param AbstractAction $value as Migration
     */
    public function registerMigration($key,AbstractAction $value)
    {
        $this->customMigrations[$key] = $value;
    }

    /**
     * @param $key
     * @return AbstractAction|null
     */
    public function migrationCustom($key)
    {
        return (isset($this->customMigrations[$key])) ? $this->customMigrations[$key] : null;
    }
}