<?php
/**
 * @author hernan ariel de luca
 * Date: 12/07/2017
 * Time: 14:33
 */

namespace Hardel\Exporter;


use Hardel\Exporter\Exception\InvalidDatabaseException;

abstract class AbstractAction
{

    /**
     * @var array
     */
    public static $ignore = ['migrations'];

    /**
     * @var
     */
    public static $remote;

    /**
     * @var
     */
    public $filePath;

    /**
     * @var string name of Database
     */
    protected $database;

    /**
     * @var $storePath -path to store file
     */
    protected  $storePath = '';

    /**
     * AbstractAction constructor.
     * @param $database
     * @throws InvalidDatabaseException
     */
    function __construct($database)
    {
        if(empty($database))
        {
            throw new InvalidDatabaseException('No database set in config/database.php');
        }

        $this->database = $database;
    }

    /**
     * @return mixed
     */
    abstract public function write();

    /**
     * @param null $database
     * @return $this
     */
    public function convert($database = null)
    {
        return $this;
    }

    /**
     * @return mixed
     */
    abstract protected function compile();

    public function setStorePath($path)
    {
        $this->storePath=$path;

        return $this;
    }

    public function isEmptyStorePath()
    {
        return (strlen($this->storePath) == 0);
    }
}