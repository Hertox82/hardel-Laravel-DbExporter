<?php
/**
 * Created by PhpStorm.
 * User: hernan
 * Date: 12/07/2017
 * Time: 12:12
 */

namespace Hardel\Exporter;


use Hardel\Exporter\Commands\AllActionCommand;
use Hardel\Exporter\Commands\ExportExcelDataCommand;
use Hardel\Exporter\Commands\MigrationsCommand;
use Hardel\Exporter\Commands\SeedCommand;
use Hardel\Exporter\Migrator\MySqlMigrator;
use Hardel\Exporter\Migrator\PostgresMigrator;
use Hardel\Exporter\Migrator\SQLiteMigrator;
use Hardel\Exporter\Migrator\SqlServerMigrator;
use Hardel\Exporter\Seeder\MySqlSeeder;
use Hardel\Exporter\Seeder\PostgresSeeder;
use Hardel\Exporter\Seeder\SQLiteSeeder;
use Hardel\Exporter\Seeder\SqlServerSeeder;
use Illuminate\Support\ServiceProvider;

class DBExporterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('dbexporter.php'),
                __DIR__.'/../../config/customAction.php' => config_path('customAction.php')
            ]);

        $this->mergeConfigFrom(__DIR__.'/../../config/config.php','dbexporter');
        $this->mergeConfigFrom(__DIR__.'/../../config/customAction.php','customAction');
    }

    public function register()
    {
        $this->registerAction();

        $this->app->bind('exp.factory',function($app){
           return new ExporterFactory($app);
        });

        $this->app->singleton('exp',function($app){
            return new ExporterManager($app,$app['exp.factory']);
        });

        $this->registerCommand();
    }

    private function registerCommand()
    {
        $this->app->bind('dbexp::seed',function($app){
           return new SeedCommand($app['exp']);
        });
        $this->app->bind('dbexp::migration',function($app){
            return new MigrationsCommand($app['exp']);
        });
        $this->app->bind('dbexp::all',function($app){
            return new AllActionCommand($app['exp']);
        });
        $this->app->bind('dbexp::excel-data',function($app){
           return new ExportExcelDataCommand($app['exp']);
        });
        $this->commands([
            'dbexp::seed',
            'dbexp::migration',
            'dbexp::all',
            'dbexp::excel-data'
        ]);
    }

    private function registerAction()
    {
        // register the Migrator
        $this->registerMigrator();

        // register the Seeder
        $this->registerSeeder();
    }
    private function registerMigrator()
    {
        $this->app->bind('exp.mysql.migrator',function($app){
           return new MySqlMigrator($this->getDatabaseName());
        });

        $this->app->bind('exp.pgsql.migrator',function($app){
           return new PostgresMigrator($this->getDatabaseName());
        });

        $this->app->bind('exp.sqlite.migrator',function ($app){
           return new SQLiteMigrator($this->getDatabaseName());
        });

        $this->app->bind('exp.sqlsrv.migrator',function ($app){
           return new SqlServerMigrator($this->getDatabaseName());
        });
    }

    private function registerSeeder()
    {
        $this->app->bind('exp.mysql.seeder',function($app){
            return new MySqlSeeder($this->getDatabaseName());
        });

        $this->app->bind('exp.pgsql.seeder',function($app){
            return new PostgresSeeder($this->getDatabaseName());
        });

        $this->app->bind('exp.sqlite.seeder',function ($app){
            return new SQLiteSeeder($this->getDatabaseName());
        });

        $this->app->bind('exp.sqlsrv.seeder',function ($app){
            return new SqlServerSeeder($this->getDatabaseName());
        });
    }

    private function getDatabaseName()
    {
        $connType = config('database.default');
        $database = config('database.connections.' .$connType );

        return $database['database'];
    }
}