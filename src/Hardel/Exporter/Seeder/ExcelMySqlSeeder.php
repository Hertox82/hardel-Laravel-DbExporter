<?php
/**
 * @author hernan ariel de luca
 * Date: 17/07/2017
 * Time: 09:38
 */


namespace Hardel\Exporter\Seeder;


use Hardel\Exporter\Action\MySqlAction;
use Excel;

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

    protected function createExcelSeederStub($table) {

        $tableName = $table;
        $tableData = $this->getTableData($table);
        $tableDescribes = $this->getTableDescribes($table);
        foreach ($tableData as $obj) {
            $data = [];
            foreach ($tableDescribes as $field)
            {
                $nameField = $field->Field;
                $data[$nameField] = $obj->$nameField;
            }
            $this->listOfTables[$tableName][] = $data;
        }
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

        }
        $this->customDb = true;

        // Get the tables for the database
        $tables = $this->getTables();
        // Loop over the tables
        foreach ($tables as $key => $value) {
            // Do not export the ignored tables
            if (in_array($value['table_name'], self::$ignore)) {
                continue;
            }

            if(count(self::$selected)> 0) {
                if(in_array($value['table_name'],self::$selected)) {

                    $this->createExcelSeederStub($value['table_name']);
                }
            }
            else {
                $this->createExcelSeederStub($value['table_name']);
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
        $lista = $this->listOfTables;
            Excel::create('database',function($excel)use($lista){
                foreach ($lista as $key => $dataList)
                {
                    $excel->sheet($key,function($sheet)use($dataList){

                        $sheet->fromArray($dataList);
                    });
                }
            })->store('xlsx',$this->storePath);
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