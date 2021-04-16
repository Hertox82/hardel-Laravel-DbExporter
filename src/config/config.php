<?php
/**
 * @author hernan ariel de luca
 */
return [
    'exportPath' => [
        'migrations' => base_path().'/database/migrations/',
        'seeds' => base_path().'/database/seeds/',
        'excel' => [
            'seed' => '/excel/seeds/',
            'migrations' => '/excel/migration/'
        ],
        'seeder' => [
            'namespace'  => '',
            'useClasses' => [
                'use Illuminate\Database\Seeder;'
            ]
        ]
    ]
];