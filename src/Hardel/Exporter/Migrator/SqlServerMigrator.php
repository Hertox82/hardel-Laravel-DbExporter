<?php
/**
 * User: hernan
 * Date: 12/07/2017
 * Time: 16:13
 */

namespace Hardel\Exporter\Migrator;


use Hardel\Exporter\AbstractAction;
use Hardel\Exporter\Exception\NonMethodDefinedException;

class SqlServerMigrator extends AbstractAction
{
    public function convert($database = null)
    {
        throw new NonMethodDefinedException('SqlServerMigrator->convert() is not implemented');
    }

    protected function compile()
    {
        throw new NonMethodDefinedException('SqlServerMigrator->compile() is not implemented');
    }

    public function write()
    {
        throw new NonMethodDefinedException('SqlServerMigrator->write() is not implemented');
    }
}