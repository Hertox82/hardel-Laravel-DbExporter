# Hardel-Laravel-Exporter

Export your database quickly and easily as a Laravel Migration (for the structure) and all the data as a Seeder class. 

This can be done via artisan commands or a controller action.

This package is a restructuring of the existing [nWidart/DbExporter](https://github.com/nWidart/DbExporter) library 


| Hardel Exporter | Laravel Supported version | MaatWebsite |
| :-------------: |:-------------------------:| :---------: |
|      ^1.+       |         5.4.27            |    2.1.19   |


PS: please, when updated the library remember to re-publish the vendor 

## Installation

Add `"hadeluca/db-exporter"`* as a requirement to `composer.json`:

```bash
{
    ...
    "require": {
        ...
		"hadeluca/db-exporter": "^1.0"
    },
}

```

Update composer:

```
$ php composer.phar update
```


or via command line:
```bash
$ composer require hadeluca/db-exporter

```


Add the service provider to `app/config/app.php`:

```php
'Hardel\Exporter\DBExporterServiceProvider::class'
```

(Optional) Publish the configuration file.

```
$ php artisan vendor:publish hadeluca/db-exporter
```

*Use `dev-master` as version requirement to be on the cutting edge*


## Documentation

### From the commandline

#### Export database to migration

**Basic usage**

```
$ php artisan dbexp:migration
```

**Specify a database**

```
$ php artisan dbexp:migration databaseName
```

**Ignoring tables**

You can ignore multiple tables by seperating them with a comma.

```
$ php artisan dbexp:migration --ignore="table1,table2"
```

#### Export database table data to seed class
This command will export all your database table data into a seed class.

```
$ php artisan dbexp:seed
```

Also here you can ignore multiple tables:

```
$ php artisan dbexp:seed --ignore="table1,table2"
```


*Important: This **requires your database config file to be updated in `config/database.php`**.*



Next all you have to do is add the call method on the base seed class in `database/seeds/DatabaseSeeder.php`:

```php

$this->call('nameOfYourSeedClass');

```

Now you can run from the commmand line:

* `php artisan db:seed`,
* or, without having to add the call method: `php artisan db:seed --class=nameOfYourSeedClass`

#### Chaining
You can also combine the generation of the migrations & the seed:

```bash

$ php artisan dbexp:all [databaseName] [--ignore="table1,table2"]

```

#### Ignoring tables
By default the migrations table is ignored.


###Export all data in .xlsx (Excel)

Now you can export all data in excel file from the command line

```bash

$ php artisan dbexp:excel-data [databaseName] [path] [--ignore="table1,table2"]

```

by default (in config.php) you can find the path where the library store the database.xlsx 

```php
<?php
return [
    'excel' => [
                'seed' => base_path().'/database/export/excel/seeds/',
                'migrations' => base_path().'/database/export/excel/migration/'
            ]
];

```

also you can override this path passing path to the commmand line

```bash
$ php artisan dbexp:excel-data null ~.Desktop.excelFolder [--ignore="table1,table2"]
```

automatically you can find in ~/Desktop/excelFolder/database.xlsx



## TODO
* ~~Create export data in excel from MySQL~~
* Create Postgres Migrator and Seeder
* Create SQLite Migrator and Seeder
* Create SqlServer Migrator and Seeder




## Credits
Credits to **[@nicolaswidart](http://www.nicolaswidart.com)** for the [original package](https://github.com/nWidart/DbExporte) Which I downloaded but was not compatible with the new version of laravel 5.4 and for these reason i modified it.
## License (MIT)

Copyright (c) 2017 [Hernan Ariel De Luca](https://www.linkedin.com/in/hernan-ariel-de-luca-23842254/) , hadeluca@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


