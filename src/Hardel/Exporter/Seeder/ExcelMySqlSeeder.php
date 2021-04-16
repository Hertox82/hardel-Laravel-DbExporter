<?php
/**
 * @author hernan ariel de luca
 * Date: 17/07/2017
 * Time: 09:38
 */


namespace Hardel\Exporter\Seeder;


use Hardel\Exporter\Action\MySqlAction;
use Hardel\Exporter\Excel\TablesSheetExporter;

class ExcelMySqlSeeder extends MySqlAction
{
    /**
     * Write the seed file
     */

    protected $listOfTables = [];

    protected $customDb = false;

    public function write()
    {
        // Check if convert method was called before
        // If not, call it on default DB
        if (!$this->customDb) {
            $this->convert();
        }
        
        $this->compile();
        
    }

    /**
     * Convert the database tables to something usefull
     * @param null $database
     * @return $this
     */
    public function convert($database = null) {
         
        if(!is_null($database)) {
            $this->database = $database;
        }

        $this->customDb = true;

        return $this;
    }

    /**
     * Compile the current seedingStub with the seed template
     * @return mixed
     */
    protected function compile()
    {
        (new TablesSheetExporter($this))->store($this->storePath.'/database.xlsx');
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