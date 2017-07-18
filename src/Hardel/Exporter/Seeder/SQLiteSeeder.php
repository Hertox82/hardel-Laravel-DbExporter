<?php
/**
 * @author hernan ariel de luca
 * Date: 12/07/2017
 * Time: 16:03
 */

namespace Hardel\Exporter\Seeder;


use Hardel\Exporter\AbstractAction;
use Hardel\Exporter\Exception\NonMethodDefinedException;

class SQLiteSeeder extends AbstractAction
{
    public function convert($database = null)
    {
        throw new NonMethodDefinedException('SQLiteSeeder->convert() is not implemented');
    }

    protected function compile()
    {
        throw new NonMethodDefinedException('SQLiteSeeder->compile() is not implemented');
    }

    public function write()
    {
        throw new NonMethodDefinedException('SQLiteSeeder->write() is not implemented');
    }
}