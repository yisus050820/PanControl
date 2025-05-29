<?php

    namespace app\models;

    class Product extends Model {

        protected $table;
        protected $fillable = [
            'name',
            'quantity',
            'unit',
            'min_quantity',
            'active'
        ];

        public $values = [];        public function __construct(){
            parent::__construct(PC_DB_HOST, PC_DB_NAME, PC_DB_USER, PC_DB_PASS);
            $this -> table = $this -> connect();
        }

        public function getAllProducts(){
            $result = $this -> select( ['id','name','quantity','unit','min_quantity',
                                        'CASE 
                                            WHEN quantity <= min_quantity THEN "Crítico"
                                            WHEN quantity <= (min_quantity * 1.5) THEN "Bajo"
                                            ELSE "OK"
                                         END as status']) 
                            -> where( [['active',1]] )
                            -> orderBy( [['name','asc']] )
                            -> get();
            return $result;
        }

        public function getLowStockProducts(){
            $result = $this -> select( ['id','name','quantity','unit','min_quantity']) 
                            -> where( [['active',1]] )
                            -> orderBy( [['name','asc']] )
                            -> get();
            $data = json_decode($result);
            $lowStock = [];
            foreach($data as $product){
                if($product->quantity <= $product->min_quantity * 1.5){
                    $lowStock[] = $product;
                }
            }
            return json_encode($lowStock);
        }

        public function createProduct($data = []){
            $this -> values = [
                $data['name'],
                $data['quantity'],
                $data['unit'],
                $data['min_quantity'],
                1
            ];
            return $this -> create();
        }

        public function updateProduct($id, $data = []){
            $sql = "UPDATE product SET name = ?, quantity = ?, unit = ?, min_quantity = ? WHERE id = ?";
            $stmt = $this->table->prepare($sql);
            $stmt->bind_param("sdsdi", $data['name'], $data['quantity'], $data['unit'], $data['min_quantity'], $id);
            return $stmt->execute();
        }

        public function deleteProduct($id){
            $sql = "UPDATE product SET active = 0 WHERE id = ?";
            $stmt = $this->table->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
    }
