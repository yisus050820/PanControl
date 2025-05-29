<?php

    namespace app\models;

    use app\classes\DB as DB;

    class Model extends DB{
        protected $dbType;
        protected $dbPath;
        
        public function __construct($host = null, $database = null, $user = null, $password = null){
            if ($host && $database && $user !== null && $password !== null) {
                // Usar configuración personalizada (para PanControl)
                $this->dbType = 'mysql';
                parent::__construct($host, $database, $user, $password);
            } else {
                // Usar configuración por defecto
                parent::__construct();
            }
        }
        
        // Método para conectar a SQLite si es necesario
        public function connectSQLite($dbPath) {
            try {
                $this->connection = new \PDO("sqlite:$dbPath");
                $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                return $this->connection;
            } catch (\PDOException $e) {
                die("Error de conexión SQLite: " . $e->getMessage());
            }
        }
    }