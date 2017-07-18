<?php
/**
 * @author hernan ariel de luca
 */
return [
    'exportPath' => [
        'migrations' => base_path().'/database/migrations/',
        'seeds' => base_path().'/database/seeds/',
        'excel' => [
            'seed' => base_path().'/database/export/excel/seeds/',
            'migrations' => base_path().'/database/export/excel/migration/'
        ]
    ]
];