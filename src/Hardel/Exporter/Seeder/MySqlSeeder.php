<?php
/**
 * @author hernan ariel de luca
 * Date: 12/07/2017
 * Time: 15:42
 */

namespace Hardel\Exporter\Seeder;


use Hardel\Exporter\Action\MySqlAction;
use Illuminate\Support\Str;
use File;

class MySqlSeeder extends MySqlAction
{
    /**
     * @var String
     */
    protected $seedingStub;

    /**
     * @var bool
     */
    protected $customDb = false;


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

        $seed = $this->compile();
        $filename = Str::camel($this->database) . "TableSeeder";

        File::put(config('dbexporter.exportPath.seeds')."{$filename}.php", $seed);
        //file_put_contents();
    }

    /**
     * This function create seeder stub
     * @param $table
     * @param $stub
     */
    protected function createSeederStub($table,&$stub) {
        $tableName = $table;
        $tableData = $this->getTableData($table);
        $tableDescribes = $this->getTableDescribes($table);
        $insertStub = "";

        foreach ($tableData as $obj) {
            $insertStub .= "
            [\n";
            foreach ($obj as $prop => $value) {
                $insertStub .= $this->insertPropertyAndValue($prop, $value,$this->getDataType($tableDescribes,$prop));
            }

            if (count($tableData) > 1) {
                $insertStub .= "            ],\n";
            } else {
                $insertStub .= "            ]\n";
            }
        }



        if ($this->hasTableData($tableData)) {
            $stub .= "
        DB::table('" . $tableName . "')->insert([
            {$insertStub}
        ]);";
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
            $this->customDb = true;
        }

        // Get the tables for the database
        $tables = $this->getTables();
        $stub = "";
        // Loop over the tables
        foreach ($tables as $key => $value) {
            // Do not export the ignored tables
            if (in_array($value['table_name'], self::$ignore)) {
                continue;
            }

            //check if exists table selected
            if(count(self::$selected)>0) {
                if(in_array($value['table_name'], self::$selected)) {
                    $this->createSeederStub($value['table_name'],$stub);
                }
            }
            else {
                $this->createSeederStub($value['table_name'],$stub);
            }

        }

        $this->seedingStub = $stub;
        
        return $this;
    }

    /**
     * Compile the current seedingStub with the seed template
     * @return mixed
     */
    protected function compile()
    {
        // Grab the template
        $template = File::get(__DIR__ . '/../stubs/seed.txt');

        // Replace the classname
        $template = str_replace('{{namespace}}',config('dbexporter.exportPath.seeder.namespace'),$template);
        $template = str_replace('{{useClass}}',implode("\n",config('dbexporter.exportPath.seeder.useClasses')),$template);
        $template = str_replace('{{className}}', Str::camel($this->database) . "TableSeeder", $template);
        $template = str_replace('{{run}}', $this->seedingStub, $template);

        return $template;
    }

    private function insertPropertyAndValue($prop, $value,$dataType)
    {
        $prop = addslashes($prop);
        if((! is_int($value)) && (! is_bool($value)) && (! is_null($value))) {
            if(strpos('\\',$value) === false)
            {
                $value = addslashes($value);
            }
        }
       
        $stringa = '';

        if($prop == 'codiceEAN');
        switch ($dataType)
        {
            case 'int' :
            case 'smallint':
            case 'bigint' :
            case 'float' :
            case 'double' :
            case 'decimal' :
            case 'tinyint' :
                $stringa = "                '{$prop}' => {$value},\n";
                break;
            case 'char' :
            case 'varchar' :
            case 'date' :
            case 'timestamp' :
            case 'datetime' :
            case 'longtext' :
            case 'mediumtext' :
            case 'text' :
            case 'longblob':
            case 'blob' :
            case 'enum' :
                $stringa = "                '{$prop}' => '{$value}',\n";
                break;
        }
        if (strlen($value) == 0 or is_null($value)) {
            return "                '{$prop}' => NULL,\n";
        } else{
            return $stringa;
        }

    }

    /**
     * @param $tableData
     * @return bool
     */
    public function hasTableData($tableData)
    {
        return count($tableData) >= 1;
    }

    private function getDataType(&$list, $prop)
    {
        foreach ($list as $Data)
        {
            if($Data->Field == $prop)
            {
                return $Data->Data_Type;
            }
        }
    }
}