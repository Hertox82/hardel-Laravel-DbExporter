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
}