<?php

namespace Hardel\Exporter\Excel;

use Hardel\Exporter\Action\MySqlAction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use stdClass;

class TableSheetData implements FromCollection, WithTitle, WithHeadings {

    protected $tableName = '';

    protected $tableDescribes = null;
    
    /**
     * @param Hardel\Exporter\Action\MySqlAction $action
     */
    protected $action = null;

    public function __construct($tableName, MySqlAction $action)
    {
        $this->tableName = $tableName;
        $this->action = $action;
        $this->tableDescribes = $this->action->getTableDescribes($this->tableName);
    }


    public function collection() {

        $tableData = $this->action->getTableData($this->tableName);
       

        $data = collect([]);

        foreach($tableData as $g) {
            $obj = new stdClass();
            foreach($this->tableDescribes as $field) {
                $attribute = $field->Field;
                $obj->$attribute =$g->$attribute; 
            }
            $data->push($obj);
        }

        return $data;
    }

    public function headings(): array
    {
        $data = [];
        foreach($this->tableDescribes as $field) {
            $data[] = $field->Field;
        }
        return $data;
    }

    public function title(): string
    {
        return $this->tableName;
    }
}