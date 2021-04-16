<?php

namespace Hardel\Exporter\Excel;

use Hardel\Exporter\Action\MySqlAction;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TablesSheetExporter implements WithMultipleSheets {

    use Exportable;

    /**
     * @param Hardel\Exporter\Action\MySqlAction $action
     */
    protected $action = null;

    public function __construct(MySqlAction $action)
    {
        $this->tables = $action->getTables();
        $this->ignored = $action::$ignore;
        $this->selected = $action::$selected;
        $this->action = $action;
    }

    public function sheets(): array
    {
        $sheets = [];
        $tables = $this->action->getTables();
        foreach($tables as $k => $t) {

            if(in_array($t['table_name'],$this->action::$ignore)) {
                continue;
            }
            if(count($this->selected) > 0) {
                if(in_array($t['table_name'],$this->action::selected))
                    $sheets [] = new TableSheetData ($t['table_name'],$this->action);
            } else {
                $sheets[] = new TableSheetData($t['table_name'],$this->action);
            }
        }
        return $sheets;
    }
}