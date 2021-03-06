<?php
/**
 * @author hernan ariel de luca
 * Date: 12/07/2017
 * Time: 12:21
 */

namespace Hardel\Exporter;

use Hardel\Exporter\DriverExporter\DriverExporter;
use Illuminate\Container\Container;
use Closure;

class ExporterManager
{
    /**
     * @var Container
     */
    protected $app;

    /**
     * @var ExporterFactory
     */
    protected $factory;

    /**
     * @var DriverExporter
     */
    protected $exporter;

    /**
     * ExporterManager constructor.
     * @param Container $container
     * @param ExporterFactory $factory
     */
    public function __construct(Container $container,ExporterFactory $factory)
    {
        $this->app = $container;

        $this->factory = $factory;

        $this->exporter = $this->registerExporter();
    }


    private function registerExporter()
    {
        $connType = $this->app['config']['database.default'];
        $key = 'customAction.customs.'.$connType;
        if(isset($this->app['config'][$key]))
        {
            $closure = $this->app['config'][$key];
            return $this->factory->make($this->getConfigDefault(),$closure);
        }
        else
        {
            return $this->factory->make($this->getConfigDefault());
        }
    }

    /**
     * @param null $database
     * @param null $custom
     * @return $this
     */
    public function migrate($database = null,$custom = null)
    {
        if(is_null($custom))
            $this->exporter->migrator()->convert($database)->write();
        else {
            $exporter = $this->exporter->migrationCustom($custom);
            if(isset($this->app['config']['dbexporter.exportPath.'.$custom.'.migration']))
            {
                if($exporter->isEmptyStorePath())
                    $exporter = $exporter->setStorePath($this->app['config']['dbexporter.exportPath.' . $custom.'.migration']);
            }
            $exporter->convert($database)->write();
        }
        return $this;
    }

    /**
     * @param null $database
     * @param null $custom
     * @return $this
     */
    public function seed($database = null,$custom = null)
    {
        if(is_null($custom))
            $this->exporter->seeder()->convert($database)->write();
        else {

            $exporter = $this->exporter->seederCustom($custom);
            if(isset($this->app['config']['dbexporter.exportPath.'.$custom.'.seed'])) {
                if($exporter->isEmptyStorePath())
                    $exporter = $exporter->setStorePath($this->app['config']['dbexporter.exportPath.' . $custom.'.seed']);
            }
            $exporter->convert($database)->write();

        }

        return $this;
    }

    /**
     * @param null $database
     * @param null $custom
     * @return $this
     */
    public function migrateAndSeed($database = null, $custom = null)
    {
        $this->migrate($database,$custom)->seed($database,$custom);

        return $this;
    }

    /**
     * @param $path
     * @param $custom null
     * @return $this
     */
    public function setSeederPath($path,$custom = null)
    {
        if(is_null($custom))
            $this->exporter->seeder()->setStorePath($path);
        else
            $this->exporter->seederCustom($custom)->setStorePath($path);

        return $this;
    }

    /**
     * @param $path
     * @param null $custom
     * @return $this
     */
    public function setMigratorPath($path,$custom = null)
    {
        if(is_null($custom))
            $this->exporter->migrator()->setStorePath($path);
        else
            $this->exporter->seederCustom($custom)->setStorePath($path);

        return $this;
    }

    /**
     * @param $table
     * @return $this
     */
    public function ignore($table)
    {
        $this->exporter->ignore($table);

        return $this;
    }

    public function selected($table)
    {
        $this->exporter->selected($table);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfigDefault()
    {
        return $this->app['config']['database.default'];
    }

    /**
     * @return mixed
     */
    public function getMigrationsFilePath()
    {
        return $this->exporter->migrator()->filePath;
    }

    public function getDatabaseName()
    {
        $connType = config('database.default');
        $database = config('database.connections.' .$connType );

        return $database['database'];
    }
}