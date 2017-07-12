<?php
/**
 * Created by PhpStorm.
 * User: hernan
 * Date: 12/07/2017
 * Time: 16:02
 */

namespace Hardel\Exporter\Seeder;


use Hardel\Exporter\AbstractAction;
use Hardel\Exporter\Exception\NonMethodDefinedException;

class PostgresSeeder extends AbstractAction
{
    public function convert($database = null)
    {
        throw new NonMethodDefinedException('PostgresSeeder->convert() is not implemented');
    }

    protected function compile()
    {
        throw new NonMethodDefinedException('PostgresSeeder->compile() is not implemented');
    }

    public function write()
    {
        throw new NonMethodDefinedException('PostgresSeeder->write() is not implemented');
    }
}