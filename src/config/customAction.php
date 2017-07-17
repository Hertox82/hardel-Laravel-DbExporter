<?php
/**
 * Created by PhpStorm.
 * User: hernan
 * Date: 17/07/2017
 * Time: 10:14
 */

return [
    'customs'    => [
        'mysql' => function($action){
                    $action->registerSeeder('excel',new \Hardel\Exporter\Seeder\ExcelMySqlSeeder($this->app['config']['database.connections.mysql.database']));
                    return $action;
                },
        ]
    ];