<?php

namespace app\controllers\pancontrol;

use app\classes\Controller;
use app\models\Order;
use app\models\Client;
use app\models\Product;

class OrderController extends Controller {
    
    public function __construct() {
        parent::__construct();
        // Verificar autenticación y permisos de administrador
        if (!$this->isAuthenticated() || !$this->isAdmin()) {
            $this->redirect('/PanControl/');
            return;
        }
    }
    
    private function isAuthenticated() {
        return isset($_SESSION['sv']) && $_SESSION['sv'] === true;
    }
    
    private function isAdmin() {
        return isset($_SESSION['tipo']) && $_SESSION['tipo'] == 1;
    }
    
    public function index() {
        $this->loadOrders();
    }
    
    public function loadOrders() {
        try {
            $order = new Order();
            $orders = $order->getAllWithDetails();
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $orders
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al cargar pedidos: ' . $e->getMessage()
            ]);
        }
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido'
            ]);
            return;
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['client_id']) || !isset($data['products']) || empty($data['products'])) {
                throw new Exception('Datos incompletos para crear el pedido');
            }
            
            $order = new Order();
            $result = $order->createWithDetails($data);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Pedido creado exitosamente',
                'data' => $result
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al crear pedido: ' . $e->getMessage()
            ]);
        }
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido'
            ]);
            return;
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['id'])) {
                throw new Exception('ID del pedido requerido');
            }
            
            $order = new Order();
            $result = $order->updateWithDetails($data);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Pedido actualizado exitosamente',
                'data' => $result
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar pedido: ' . $e->getMessage()
            ]);
        }
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido'
            ]);
            return;
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['id'])) {
                throw new Exception('ID del pedido requerido');
            }
            
            $order = new Order();
            $order->delete($data['id']);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Pedido eliminado exitosamente'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al eliminar pedido: ' . $e->getMessage()
            ]);
        }
    }
    
    public function getOrderDetails($params = null) {
        try {
            $orderId = $params[3] ?? null;
            if (!$orderId) {
                throw new Exception('ID del pedido requerido');
            }
            
            $order = new Order();
            $orderData = $order->getWithDetails($orderId);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $orderData
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener detalles del pedido: ' . $e->getMessage()
            ]);
        }
    }
    
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido'
            ]);
            return;
        }
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['id']) || !isset($data['status'])) {
                throw new Exception('ID y status del pedido requeridos');
            }
            
            $validStatuses = ['pending', 'processing', 'completed', 'cancelled'];
            if (!in_array($data['status'], $validStatuses)) {
                throw new Exception('Status inválido');
            }
            
            $order = new Order();
            $order->updateStatus($data['id'], $data['status']);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Status del pedido actualizado exitosamente'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar status: ' . $e->getMessage()
            ]);
        }
    }
    
    public function getFormData() {
        try {
            $client = new Client();
            $product = new Product();
            
            $clients = $client->getAll();
            $products = $product->getAll();
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => [
                    'clients' => $clients,
                    'products' => $products
                ]
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Error al cargar datos del formulario: ' . $e->getMessage()
            ]);
        }
    }
}
?>
