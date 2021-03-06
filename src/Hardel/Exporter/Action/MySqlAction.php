<?php
/**
 * @author: hernan ariel de luca
 * Date: 12/07/2017
 * Time: 15:17
 */

namespace Hardel\Exporter\Action;


use Hardel\Exporter\AbstractAction;
use DB;
use stdClass;

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

    protected $viewForeign = [
        'constraint_name',
        'column_name',
        'referenced_column_name',
        'referenced_table_name'
    ];

    protected $viewForeignAction = [
        'update_rule',
        'delete_rule'
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

    /**
     * This function return all foreign key on table
     * @param string $table
     * @return \Illuminate\Support\Collection
     */
    public function getTableForeign($table) {
        $query = DB::table('information_schema.key_column_usage')
        ->where('table_name','=',$table)
        ->where('referenced_table_schema','!=','')
        ->get($this->viewForeign);
        if($query->count() > 0) {
            $q = $query->map(function($item)use($table){
                $where = [];
                $where[] = ['table_name','=',$table];
                $where[] = ['referenced_table_name','=',$item->referenced_table_name];
                $where[] = ['constraint_name','=',$item->constraint_name];
                $t = DB::table('information_schema.referential_constraints')
                ->where($where)
                ->get($this->viewForeignAction)->first();
                $obj = new stdClass();
                $obj->constraint_name = $item->constraint_name;
                $obj->column_name = $item->column_name;
                $obj->referenced_column_name = $item->referenced_column_name;
                $obj->referenced_table_name = $item->referenced_table_name;
                $obj->update_rule = $t->update_rule;
                $obj->delete_rule = $t->delete_rule;
                return $obj;
            });
            return $q;
        } else {
            return new \Illuminate\Support\Collection();
        }
    }

    public function getTableIndexes($table, $notIn = [])
    {
        $pdo = DB::connection()->getPdo();
        if(count($notIn)>0) {
            $notIn[] = "PRIMARY";
            $pdo = $pdo->prepare('SHOW INDEX FROM ' . $table . " WHERE Key_name NOT IN ('". implode("', '" , $notIn ) ."')");
        } else {
            $pdo = $pdo->prepare('SHOW INDEX FROM ' . $table . ' WHERE Key_name != "PRIMARY"');
        }
        $pdo->execute();
        return $pdo->fetchAll(); 
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
    public function getTableData($table)
    {
        return DB::table($table)->get();
    }
}