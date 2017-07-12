<?php
/**
 * User: hernan
 * Date: 12/07/2017
 * Time: 15:38
 */

namespace Hardel\Exporter\Migrator;


use Hardel\Exporter\AbstractAction;
use Hardel\Exporter\Exception\NonMethodDefinedException;

class PostgresMigrator extends AbstractAction
{
    public function convert($database = null)
    {
        throw new NonMethodDefinedException('PostgresMigrator->convert() is not implemented');
    }

    protected function compile()
    {
        throw new NonMethodDefinedException('PosgresMigrator->compile() is not implemented');
    }

    public function write()
    {
       throw new NonMethodDefinedException('PosgresMigrator->write() is not implemented');
    }
}