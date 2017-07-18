<?php
/**
 *
 * @author: hernan ariel de luca
 * Date: 17/07/2017
 * Time: 10:14
 */

return [
    'customs'    => [
        'mysql' => function($action){
                    $action->registerSeeder('excel',new \Hardel\Exporter\Seeder\ExcelMySqlSeeder(config('database.connections.mysql.database')));
                    return $action;
                },
        ]
    ];