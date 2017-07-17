<?php
/**
 * Created by PhpStorm.
 * User: hernan
 * Date: 17/07/2017
 * Time: 14:53
 */

namespace Tests;

use Illuminate\Container\Container;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Container\Container
     */
    public function createApplication()
    {
        $app = new Container();

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}