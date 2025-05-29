<?php

    namespace app\models;

    class Client extends Model {

        protected $table;
        protected $fillable = [
            'name',
            'email',
            'phone',
            'address',
            'active'
        ];

        public $values = [];

        public function __construct(){
            parent::__construct(PC_DB_HOST, PC_DB_NAME, PC_DB_USER, PC_DB_PASS);
            $this -> table = $this -> connect();
        }

        public function getAllClients(){
            $result = $this -> select( ['id','name','email','phone','address','created_at']) 
                            -> where( [['active',1]] )
                            -> orderBy( [['name','asc']] )
                            -> get();
            return $result;
        }

        public function createClient($data = []){
            $this -> values = [
                $data['name'],
                $data['email'] ?? '',
                $data['phone'] ?? '',
                $data['address'] ?? '',
                1
            ];
            return $this -> create();
        }

        public function updateClient($id, $data = []){
            $sql = "UPDATE client SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?";
            $stmt = $this->table->prepare($sql);
            $stmt->bind_param("ssssi", $data['name'], $data['email'], $data['phone'], $data['address'], $id);
            return $stmt->execute();
        }

        public function deleteClient($id){
            $sql = "UPDATE client SET active = 0 WHERE id = ?";
            $stmt = $this->table->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }

        public function getClientWithOrderStatus(){
            $sql = "SELECT c.id, c.name, c.email, c.phone,
                           CASE 
                               WHEN o.status = 'pending' THEN 'Pedido pendiente'
                               WHEN o.status = 'processing' THEN 'Pedido en proceso'
                               WHEN o.status = 'completed' THEN 'Pedido entregado'
                               ELSE 'Sin pedidos activos'
                           END as status,
                           o.created_at as last_order_date
                    FROM client c
                    LEFT JOIN (
                        SELECT client_id, status, created_at,
                               ROW_NUMBER() OVER (PARTITION BY client_id ORDER BY created_at DESC) as rn
                        FROM `order`
                        WHERE active = 1
                    ) o ON c.id = o.client_id AND o.rn = 1
                    WHERE c.active = 1
                    ORDER BY c.name ASC";
            
            $result = $this->table->query($sql);
            $data = [];
            while($row = $result->fetch_assoc()){
                $data[] = $row;
            }
            return json_encode($data);
        }
    }
