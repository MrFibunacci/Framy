<?php
/**
 * Framy Framework
 *
 * @copyright Copyright Framy
 * @Author Marco Bier <mrfibunacci@gmail.com>
 */

    namespace app\framework\Component\http\Model;

    use app\framework\Component\Cookie\CookieTrait;
    use app\framework\Component\EventManager\EventManagerTrait;
    use app\framework\Component\StdLib\StdLibTrait;
    use app\framework\Component\Storage\StorageTrait;
    use app\framework\Component\Database\Medoo as Database;


    /**
     * <code>Basic Model 1.2
     *   with Easy Database Access 1.2
     * </code>
     *
     * @package app\framework\Component\http\Model
     */
    class Model //implements \ArrayAccess, \JsonSerializable
    {
        use StorageTrait,StdLibTrait,EventManagerTrait,CookieTrait;

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
         * Contains Cookie Instances in side an Array. Easy accessible by using the methods provided by CookieTrait.
         *
         * @var \app\framework\Component\Cookie\Cookie array
         */
        protected $cookieJar;

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

        /**
         * Select data from database
         *
         * @param null $columns
         * @param null $where
         * @param null $table
         *
         * @return mixed
         * @throws \Exception
         */
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

        /**
         * Insert new records in table
         *
         * @param      $data
         * @param null $table
         *
         * @return mixed
         * @throws \Exception
         */
        protected function insert($data, $table = null)
        {
            if($this->isNull($table) && $this->is($this->table)){
                return $this->DB->insert($this->table, $data);
            } elseif($this->is($table)){
                return $this->DB->insert($table, $data);
            } else {
                throw new \Exception("You need to specify an table");
            }
        }

        /**
         * Modify data in table
         *
         * @param      $data
         * @param null $table
         * @param null $where
         *
         * @return mixed
         * @throws \Exception
         */
        protected function update($data, $table = null, $where = null)
        {
            if($this->isNull($table) && $this->is($this->table)){
                return $this->DB->update($this->table, $data, $where);
            } elseif($this->is($table)){
                return $this->DB->update($table, $data, $where);
            } else {
                throw new \Exception("You need to specify an table");
            }
        }

        /**
         * Delete data from table
         *
         * @param      $where
         * @param null $table
         *
         * @return mixed
         * @throws \Exception
         */
        protected function delete($where, $table = null)
        {
            if($this->isNull($table) && $this->is($this->table)){
                return $this->DB->delete($this->table, $where);
            } elseif($this->is($table)){
                return $this->DB->delete($table, $where);
            } else {
                throw new \Exception("You need to specify an table");
            }
        }

        /**
         * Replace old data into new one
         *
         * @param      $column
         * @param      $search
         * @param      $replace
         * @param null $table
         * @param null $where
         *
         * @return mixed
         * @throws \Exception
         */
        protected function replace($column, $search, $replace, $table = null, $where = null)
        {
            if($this->isNull($table) && $this->is($this->table)){
                return $this->DB->replace($this->table, $column, $search, $replace, $where);
            } elseif($this->is($table)){
                return $this->DB->replace($table, $column, $search, $replace, $where);
            } else {
                throw new \Exception("You need to specify an table");
            }
        }

        /**
         * Get only one record from table
         *
         * @param      $columns
         * @param      $where
         * @param null $table
         *
         * @return mixed
         * @throws \Exception
         */
        protected function get($columns, $where, $table = null)
        {
            if($this->isNull($table) && $this->is($this->table)){
                return $this->DB->get($this->table, $columns, $where);
            } elseif($this->is($table)){
                return $this->DB->get($table, $columns, $where);
            } else {
                throw new \Exception("You need to specify an table");
            }
        }

        /**
         * Determine whether the target data existed
         *
         * @param      $where
         * @param null $table
         *
         * @return mixed
         * @throws \Exception
         */
        protected function has($where, $table = null)
        {
            if($this->isNull($table) && $this->is($this->table)){
                return $this->DB->has($this->table, $where);
            } elseif($this->is($table)){
                return $this->DB->has($table, $where);
            } else {
                throw new \Exception("You need to specify an table");
            }
        }

        /**
         * Counts the number of rows
         * @param      $where
         * @param null $table
         *
         * @return mixed
         * @throws \Exception
         */
        protected function count($where, $table = null)
        {
            if($this->isNull($table) && $this->is($this->table)){
                return $this->DB->count($this->table, $where);
            } elseif($this->is($table)){
                return $this->DB->count($table, $where);
            } else {
                throw new \Exception("You need to specify an table");
            }
        }

        /**
         * Get the maximum value for the column
         *
         * @param      $column
         * @param      $where
         * @param null $table
         *
         * @return mixed
         * @throws \Exception
         */
        protected function max($column, $where, $table = null)
        {
            if($this->isNull($table) && $this->is($this->table)){
                return $this->DB->max($this->table, $column, $where);
            } elseif($this->is($table)){
                return $this->DB->max($table, $column, $where);
            } else {
                throw new \Exception("You need to specify an table");
            }
        }

        /**
         * Get the minimum value for the column
         *
         * @param      $column
         * @param      $where
         * @param null $table
         *
         * @return mixed
         * @throws \Exception
         */
        protected function min($column, $where, $table = null)
        {
            if($this->isNull($table) && $this->is($this->table)){
                return $this->DB->min($this->table, $column, $where);
            } elseif($this->is($table)){
                return $this->DB->min($table, $column, $where);
            } else {
                throw new \Exception("You need to specify an table");
            }
        }

        /**
         * Get the average value for the column
         *
         * @param      $column
         * @param      $where
         * @param null $table
         *
         * @return mixed
         * @throws \Exception
         */
        protected function avg($column, $where, $table = null)
        {
            if($this->isNull($table) && $this->is($this->table)){
                return $this->DB->avg($this->table, $column, $where);
            } elseif($this->is($table)){
                return $this->DB->avg($table, $column, $where);
            } else {
                throw new \Exception("You need to specify an table");
            }
        }

        /**
         * Get the total value for the column
         *
         * @param      $column
         * @param      $where
         * @param null $table
         *
         * @return mixed
         * @throws \Exception
         */
        protected function sum($column, $where, $table = null)
        {
            if($this->isNull($table) && $this->is($this->table)){
                return $this->DB->sum($this->table, $column, $where);
            } elseif($this->is($table)){
                return $this->DB->sum($table, $column, $where);
            } else {
                throw new \Exception("You need to specify an table");
            }
        }
    }