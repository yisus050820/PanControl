<?php

    namespace app\models;

    use app\classes\DB;

    class Model extends DB {
        protected $table;
        protected $primaryKey = 'id';
        protected $fillable = [];
        protected $hidden = ['passwd'];
        
        public function __construct() {
            parent::__construct();
            if (empty($this->table)) {
                // Obtener el nombre de la clase y convertirlo a tabla
                $className = get_class($this);
                $className = explode('\\', $className);
                $this->table = strtolower(end($className));
            }
        }
        
        public function find($id) {
            return $this->where([[$this->primaryKey, '=', $id]])->first();
        }
        
        public function findOrFail($id) {
            $result = $this->find($id);
            if (!$result) {
                throw new \Exception("Record not found");
            }
            return $result;
        }
        
        public function first() {
            $result = $this->get();
            return !empty($result) ? json_decode($result)[0] : null;
        }
        
        public function paginate($perPage = 10) {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $perPage;
            $this->limit("$offset, $perPage");
            return $this->get();
        }
        
        protected function hide($data) {
            if (empty($this->hidden)) return $data;
            
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    foreach ($this->hidden as $hidden) {
                        unset($data[$key][$hidden]);
                    }
                }
            } else {
                foreach ($this->hidden as $hidden) {
                    unset($data->$hidden);
                }
            }
            return $data;
        }
    }