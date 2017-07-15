<?php
/**
 * Framey Framework
 *
 * @copyright Copyright Framey
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\http\Model;

    use app\framework\Component\StdLib\StdLibTrait;
    use app\framework\Component\Storage\StorageTrait;
    use app\framework\Component\Database\Medoo as Database;


    /**
     * <code>Basic Model 1.1
     *   with Easy Database Access 1.2
     * </code>
     *
     * @package app\framework\Component\http\Model
     */
    class Model //implements \ArrayAccess, \JsonSerializable
    {
        use StorageTrait,StdLibTrait;

        /**
         * The table associated with the model.
         *
         * @var string
         */
        protected $table;

        /**
         * The connection name for the model.
         *
         * @var string
         */
        protected $connection;

        /**
         * Contains the Database instance
         *
         * @var \app\framework\Component\Database\Medoo|null
         */
        private $DB;

        function __construct()
        {
            if($this->is($this->connection)){
                // setup connection
                $this->DB = new Database($this->connection);
            }
        }

        protected function DB($dbName = null){
            if(($this->isInstanceOf($this->DB, "\\app\\framework\\Component\\Database\\Medoo") && $this->is($this->connection)) && $this->isNull($dbName)){
                if($this->isNull($dbName)){
                    $this->DB = new Database($this->connection);
                } else {
                    $this->DB = new Database($dbName);
                }
                return $this;
            } else {
                throw new \Exception("There is no connection setup!");
            }
        }

        protected function select($columns = null, $where = null, $table = null)
        {
            if($this->isNull($table) && $this->is($this->table)){
                return $this->DB->select($this->table, $columns, $where);
            } elseif($this->is($table)){
                return $this->DB->select($table, $columns, $where);
            } else {
                throw new \Exception("You need to specify an table");
            }
        }
    }