<?php
/**
 * @author: hernan ariel de luca
 * Date: 12/07/2017
 * Time: 15:17
 */

namespace Hardel\Exporter\Action;


use Hardel\Exporter\AbstractAction;
use DB;

class MySqlAction extends AbstractAction
{

    /**
     * @var array
     */
    protected $selects = [
        'column_name as Field',
        'column_type as Type',
        'is_nullable as Null',
        'column_key as Key',
        'column_default as Default',
        'extra as Extra',
        'data_type as Data_Type'
    ];

    public function write()
    {
        // TODO: Implement write() method.
    }

    public function convert($database = null)
    {
        return parent::convert($database); // TODO: Change the autogenerated stub
    }

    protected function compile()
    {
        // TODO: Implement compile() method.
    }

    /**
     * Get all the tables
     * @return mixed
     */
    public function getTables()
    {
        $pdo = DB::connection()->getPdo();
        return $pdo->query('SELECT table_name FROM information_schema.tables WHERE table_schema="' . $this->database . '"');
    }

    public function getTableIndexes($table)
    {
        $pdo = DB::connection()->getPdo();
        return $pdo->query('SHOW INDEX FROM ' . $table . ' WHERE Key_name != "PRIMARY"');
    }

    /**
     * Get all the columns for a given table
     * @param $table
     * @return mixed
     */
    public function getTableDescribes($table)
    {
        return DB::table('information_schema.columns')
            ->where('table_schema', '=', $this->database)
            ->where('table_name', '=', $table)
            ->get($this->selects);
    }

    /**
     * Grab all the table data
     * @param $table
     * @return mixed
     */
    protected function getTableData($table)
    {
        return DB::table($table)->get();
    }
}