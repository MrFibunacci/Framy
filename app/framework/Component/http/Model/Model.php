<?php
    namespace app\framework\Component\http\Model;

    use app\framework\Component\Storage\StorageTrait;
    use app\framework\Component\TemplateEngine\TemplateEngine;

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
         * @var \app\framework\Component\Database\Medoo
         */
        private $DB;

        function __consturct()
        {
            if($this->is($connection)){
                // setup connection
                $this->DB = new app\framework\Component\Database\Meodo($this->connection);
            }
        }

        protected function DB($dbname = null){
            if((!$this->instanceOf($this->DB, "\app\framework\Component\Database\Medoo") && !$this->is($connection)) && !$this->is($dbname)){
                if($this->is($dbname)){
                    $this->DB = new \app\framework\Component\Database\Medoo($this->connenction);
                } else {
                    $this->DB = new \app\framework\Component\Database\Medoo($dbname);
                }
                return $this;
            } else {
                throw new \Exception("There is no connection setup!");
            }
        }

        protected function select($join, $columns = null, $where = null, $table = null)
        {
            //TODO: you may have to rewrite every single Core SQL function to achive the "Easy DB Accsess"
            if(!$this->is($table) && $this->is($this->table){
                return $this->DB->select($this->$table, $join, $columns, $where);
            } elseif($this->is($table)){
                return $this->DB->select($table, $join, $columns, $where);
            } else {
                throw new \Exception("You need to specify an table");
            }
        }
    }

    //$this->DB()->select()
