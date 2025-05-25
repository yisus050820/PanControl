<?php

    namespace app\controllers;
    
    use app\classes\DB;

    class Controller {
        protected $db;
        protected $table;
        
        public function __construct(){
            $this->db = new DB();
            $this->table = $this->db->connect();
        }
        
        protected function model($model) {
            $modelClass = "app\\models\\" . $model;
            return new $modelClass();
        }
        
        protected function validateRequestMethod($method) {
            return $_SERVER['REQUEST_METHOD'] === strtoupper($method);
        }
        
        protected function getPostData() {
            return json_decode(file_get_contents('php://input'), true) ?? $_POST;
        }
    }