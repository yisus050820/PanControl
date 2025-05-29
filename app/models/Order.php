<?php

    namespace app\models;

    class Order extends Model {

        protected $table;
        protected $fillable = [
            'client_id',
            'total',
            'status',
            'delivery_date',
            'active'
        ];

        public $values = [];

        public function __construct(){
            parent::__construct(PC_DB_HOST, PC_DB_NAME, PC_DB_USER, PC_DB_PASS);
            $this -> table = $this -> connect();
        }

        public function getAllOrders(){
            $result = $this -> select( ['o.id','c.name as client_name','o.total',
                                        'o.status','o.delivery_date',
                                        'DATE_FORMAT(o.created_at,"%d/%m/%Y") as fecha']) 
                            -> join( "client c","o.client_id=c.id")
                            -> where( [['o.active',1]] )
                            -> orderBy( [['o.created_at','desc']] )
                            -> get();
            return $result;
        }

        public function getRecentOrders($limit = 10){
            $result = $this -> select( ['o.id','c.name as client_name','o.total',
                                        'o.status','o.delivery_date',
                                        'DATE_FORMAT(o.created_at,"%d/%m/%Y") as fecha']) 
                            -> join( "client c","o.client_id=c.id")
                            -> where( [['o.active',1]] )
                            -> orderBy( [['o.created_at','desc']] )
                            -> limit( $limit )
                            -> get();
            return $result;
        }

        public function createOrder($data = []){
            $this -> values = [
                $data['client_id'],
                $data['total'],
                $data['status'] ?? 'pending',
                $data['delivery_date'] ?? null,
                1
            ];
            return $this -> create();
        }

        public function updateOrderStatus($id, $status){
            $sql = "UPDATE `order` SET status = ? WHERE id = ?";
            $stmt = $this->table->prepare($sql);
            $stmt->bind_param("si", $status, $id);
            return $stmt->execute();
        }

        public function deleteOrder($id){
            $sql = "UPDATE `order` SET active = 0 WHERE id = ?";
            $stmt = $this->table->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }

        public function getTodayEarnings(){
            $sql = "SELECT 
                        COALESCE(SUM(CASE WHEN status = 'completed' THEN total ELSE 0 END), 0) as earnings,
                        COALESCE(SUM(CASE WHEN status = 'cancelled' THEN total ELSE 0 END), 0) as losses
                    FROM `order` 
                    WHERE DATE(created_at) = CURDATE() AND active = 1";
            
            $result = $this->table->query($sql);
            return json_encode($result->fetch_assoc());
        }        public function getMonthlyEarnings(){
            $sql = "SELECT 
                        COALESCE(SUM(CASE WHEN status = 'completed' THEN total ELSE 0 END), 0) as income,
                        COALESCE(SUM(CASE WHEN status = 'cancelled' THEN total ELSE 0 END), 0) as expenses
                    FROM `order` 
                    WHERE MONTH(created_at) = MONTH(CURDATE()) 
                    AND YEAR(created_at) = YEAR(CURDATE()) 
                    AND active = 1";
            
            $result = $this->table->query($sql);
            $data = $result->fetch_assoc();
            $data['net_earnings'] = $data['income'] - $data['expenses'];
            return json_encode($data);
        }

        public function getAllWithDetails(){
            $sql = "SELECT 
                        o.id,
                        c.name as client_name,
                        o.total,
                        o.status,
                        o.delivery_date,
                        DATE_FORMAT(o.created_at,'%d/%m/%Y') as fecha,
                        GROUP_CONCAT(
                            CONCAT(p.name, ' (', od.quantity, ' ', p.unit, ')')
                            SEPARATOR ', '
                        ) as products
                    FROM `order` o
                    LEFT JOIN client c ON o.client_id = c.id
                    LEFT JOIN order_detail od ON o.id = od.order_id
                    LEFT JOIN product p ON od.product_id = p.id
                    WHERE o.active = 1
                    GROUP BY o.id
                    ORDER BY o.created_at DESC";
            
            $result = $this->table->query($sql);
            $orders = [];
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
            return $orders;
        }

        public function getWithDetails($id){
            $sql = "SELECT 
                        o.*,
                        c.name as client_name,
                        c.email as client_email,
                        c.phone as client_phone
                    FROM `order` o
                    LEFT JOIN client c ON o.client_id = c.id
                    WHERE o.id = ? AND o.active = 1";
            
            $stmt = $this->table->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $order = $stmt->get_result()->fetch_assoc();
            
            if ($order) {
                // Obtener detalles del pedido
                $sql = "SELECT 
                            od.*,
                            p.name as product_name,
                            p.unit
                        FROM order_detail od
                        LEFT JOIN product p ON od.product_id = p.id
                        WHERE od.order_id = ?";
                
                $stmt = $this->table->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $details = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                
                $order['details'] = $details;
            }
            
            return $order;
        }

        public function createWithDetails($data){
            // Iniciar transacción
            $this->table->autocommit(false);
            
            try {
                // Crear el pedido
                $this->values = [
                    $data['client_id'],
                    $data['total'] ?? 0,
                    $data['status'] ?? 'pending',
                    $data['delivery_date'] ?? null,
                    1
                ];
                
                $orderId = $this->create();
                
                if (!$orderId) {
                    throw new Exception("Error al crear el pedido");
                }
                
                // Crear los detalles del pedido
                $total = 0;
                foreach ($data['products'] as $product) {
                    $sql = "INSERT INTO order_detail (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                    $stmt = $this->table->prepare($sql);
                    $stmt->bind_param("iidd", $orderId, $product['product_id'], $product['quantity'], $product['price']);
                    
                    if (!$stmt->execute()) {
                        throw new Exception("Error al crear detalle del pedido");
                    }
                    
                    $total += $product['quantity'] * $product['price'];
                }
                
                // Actualizar el total del pedido
                $sql = "UPDATE `order` SET total = ? WHERE id = ?";
                $stmt = $this->table->prepare($sql);
                $stmt->bind_param("di", $total, $orderId);
                
                if (!$stmt->execute()) {
                    throw new Exception("Error al actualizar total del pedido");
                }
                
                // Confirmar transacción
                $this->table->commit();
                $this->table->autocommit(true);
                
                return $orderId;
                
            } catch (Exception $e) {
                // Revertir transacción
                $this->table->rollback();
                $this->table->autocommit(true);
                throw $e;
            }
        }

        public function updateWithDetails($data){
            // Iniciar transacción
            $this->table->autocommit(false);
            
            try {
                // Actualizar el pedido
                $sql = "UPDATE `order` SET client_id = ?, status = ?, delivery_date = ? WHERE id = ?";
                $stmt = $this->table->prepare($sql);
                $stmt->bind_param("issi", $data['client_id'], $data['status'], $data['delivery_date'], $data['id']);
                
                if (!$stmt->execute()) {
                    throw new Exception("Error al actualizar el pedido");
                }
                
                // Eliminar detalles existentes
                $sql = "DELETE FROM order_detail WHERE order_id = ?";
                $stmt = $this->table->prepare($sql);
                $stmt->bind_param("i", $data['id']);
                $stmt->execute();
                
                // Crear nuevos detalles
                $total = 0;
                if (isset($data['products'])) {
                    foreach ($data['products'] as $product) {
                        $sql = "INSERT INTO order_detail (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
                        $stmt = $this->table->prepare($sql);
                        $stmt->bind_param("iidd", $data['id'], $product['product_id'], $product['quantity'], $product['price']);
                        
                        if (!$stmt->execute()) {
                            throw new Exception("Error al crear detalle del pedido");
                        }
                        
                        $total += $product['quantity'] * $product['price'];
                    }
                }
                
                // Actualizar el total del pedido
                $sql = "UPDATE `order` SET total = ? WHERE id = ?";
                $stmt = $this->table->prepare($sql);
                $stmt->bind_param("di", $total, $data['id']);
                
                if (!$stmt->execute()) {
                    throw new Exception("Error al actualizar total del pedido");
                }
                
                // Confirmar transacción
                $this->table->commit();
                $this->table->autocommit(true);
                
                return $data['id'];
                
            } catch (Exception $e) {
                // Revertir transacción
                $this->table->rollback();
                $this->table->autocommit(true);
                throw $e;
            }
        }

        public function updateStatus($id, $status){
            $sql = "UPDATE `order` SET status = ? WHERE id = ?";
            $stmt = $this->table->prepare($sql);
            $stmt->bind_param("si", $status, $id);
            return $stmt->execute();
        }

        public function delete($id){
            $sql = "UPDATE `order` SET active = 0 WHERE id = ?";
            $stmt = $this->table->prepare($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }
    }
