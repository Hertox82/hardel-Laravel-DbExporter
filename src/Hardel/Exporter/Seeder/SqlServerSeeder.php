<?php
/**
 * Created by PhpStorm.
 * User: hernan
 * Date: 12/07/2017
 * Time: 16:15
 */

namespace Hardel\Exporter\Seeder;

use Hardel\Exporter\AbstractAction;
use Hardel\Exporter\Exception\NonMethodDefinedException;

class SqlServerSeeder extends AbstractAction
{
    public function convert($database = null)
    {
        throw new NonMethodDefinedException('SqlServerSeeder->convert() is not implemented');
    }

    protected function compile()
    {
        throw new NonMethodDefinedException('SqlServerSeeder->compile() is not implemented');
    }

    public function write()
    {
        throw new NonMethodDefinedException('SqlServerSeeder->write() is not implemented');
    }
}