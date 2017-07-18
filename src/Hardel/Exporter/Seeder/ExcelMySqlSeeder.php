<?php
/**
 * @author hernan ariel de luca
 * Date: 17/07/2017
 * Time: 09:38
 */


namespace Hardel\Exporter\Seeder;


use Hardel\Exporter\Action\MySqlAction;

class ExcelMySqlSeeder extends MySqlAction
{
    /**
     * Write the seed file
     */
    public function write()
    {
        // Check if convert method was called before
        // If not, call it on default DB
        if (!$this->customDb) {
            $this->convert();
        }

        pr('roma merda');
        //file_put_contents();
    }

    /**
     * Convert the database tables to something usefull
     * @param null $database
     * @return $this
     */
    public function convert($database = null)
    {
        if (!is_null($database)) {
            $this->database = $database;
            $this->customDb = true;
        }

        // Get the tables for the database
        $tables = $this->getTables();
        // Loop over the tables
        foreach ($tables as $key => $value) {
            // Do not export the ignored tables
            if (in_array($value['table_name'], self::$ignore)) {
                continue;
            }
            $tableName = $value['table_name'];
            $tableData = $this->getTableData($value['table_name']);
            $tableDescribes = $this->getTableDescribes($value['table_name']);

            foreach ($tableData as $obj) {
                pr($obj,1);
            }

            if ($this->hasTableData($tableData)) {

            }
        }

        return $this;
    }

    /**
     * Compile the current seedingStub with the seed template
     * @return mixed
     */
    protected function compile()
    {
        //da fare
    }


    /**
     * @param $tableData
     * @return bool
     */
    public function hasTableData($tableData)
    {
        return count($tableData) >= 1;
    }
}