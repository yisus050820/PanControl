<?php

    namespace app\classes;

    class DB {
        //Atributos de configuración de la base de datos
        protected $db_host;
        protected $db_name;
        protected $db_user;
        protected $db_passwd;

        public $conex; //Atributo de conexión

        //Atributos de control para las consultas
        protected $s = " * ";
        protected $c = "";
        protected $j = "";
        protected $w = " 1 ";
        protected $o = "";
        protected $l = "";

        public function __construct($dbh = DB_HOST,$dbn = DB_NAME,$dbu = DB_USER, $dbp = DB_PASS){
            $this->db_host   = $dbh;
            $this->db_name   = $dbn;
            $this->db_user   = $dbu;
            $this->db_passwd = $dbp;
        }

        public function connect(){
            $this->conex = new \mysqli($this->db_host,$this->db_user,$this->db_passwd,$this->db_name);
            if( $this->conex->connect_errno ){
                die("Error al conectarse a la BD " . $this->conex->connect_error);
            }
            $this->conex->set_charset("utf8");
            return $this->conex;
        }

        public function all(){
            return $this;
        }

        public function select($cc = []){
            if( count($cc) > 0){
                $this->s = implode(",",$cc);
            }
            return $this;
        }

        public function count( $c = "*" ){
            $this -> c = ", count(" . $c . ") as tt ";
            return $this;
        }

        public function join( $join = "", $on = "" ){
            if( $join != "" && $on != "" ){
                $this->j .= ' join ' . $join . ' on ' . $on;
            }
            return $this;
        }

        public function where( $conditions = [] ){
            $this->w = "";
            if (count($conditions) > 0) {
                foreach ($conditions as $condition) {
                    if (count($condition) === 3) {
                        $this->w .= "{$condition[0]} {$condition[1]} '{$condition[2]}' AND ";
                    } else {
                        $this->w .= "{$condition[0]} = '{$condition[1]}' AND ";
                    }
                }
            }
            $this->w .= ' 1 ';
            $this->w = ' (' . $this->w . ') ';
            return $this;
        }

        public function orderBy( $ob = []){
            $this->o = "";
            if( count($ob) > 0 ){
                foreach( $ob as $orderBy ){
                    $this->o .= $orderBy[0] . ' ' . $orderBy[1] . ',';
                }
                $this->o = ' order by ' . trim($this->o,',');
            }
            return $this;
        }

        public function limit($l = ""){
            $this->l = "";
            if( $l != "" ){
                $this->l = ' limit ' . $l;
            }
            return $this;
        }

        public function get(){
            $table = strtolower(str_replace("app\\models\\", "", get_class($this)));
            $sql = "SELECT {$this->s} FROM {$table} " .
                   ($this->j != "" ? "a {$this->j} " : "") .
                   "WHERE {$this->w} " .
                   "{$this->o} " .
                   "{$this->l}";
            
            $result = $this->conex->query($sql);
            if (!$result) {
                return json_encode([]);
            }
            
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return json_encode($data);

        }

        public function create(){
            $sql = "insert into " . str_replace("app\\models\\","",get_class( $this )) .
                    ' (' . implode(",",$this->fillable) . ') values (' .
                    trim(str_replace("&","?,",str_pad("",count($this->fillable),"&")),",") . ');';
            $stmt = $this->table->prepare($sql);
            $stmt->bind_param(str_pad("",count($this->fillable),"s"),...$this->values);
            $stmt->execute();
            return $stmt->insert_id;
        }

        public function delete(){
            $sql = 'delete from ' . str_replace("app\\models\\","",get_class( $this )) . 
                    ' where ' . $this->w;
            return $this->table->query( $sql );
        }

    }