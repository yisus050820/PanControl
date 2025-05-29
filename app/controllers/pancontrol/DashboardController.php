<?php

    namespace app\controllers\pancontrol;

    use app\controllers\Controller as Controller;
    use app\classes\Views as View;
    use app\controllers\auth\SessionController as SC;
    use app\models\Product as Product;
    use app\models\Client as Client;
    use app\models\Order as Order;

    class DashboardController extends Controller {
        
        public function __construct(){
            parent::__construct();
        }

        public function index(){
            $user = SC::sessionValidate();
            if(!$user){
                header('Location: /PanControl/login');
                exit();
            }

            $product = new Product();
            $order = new Order();
            
            $lowStockProducts = json_decode($product->getLowStockProducts());
            $todayEarnings = json_decode($order->getTodayEarnings());

            $response = [
                'ua' => $user,
                'title' => 'Dashboard | PanControl',
                'lowStockProducts' => $lowStockProducts,
                'todayEarnings' => $todayEarnings,
                'code' => 200
            ];
            
            View::render('pancontrol/dashboard', $response);
        }

        public function inventario(){
            $user = SC::sessionValidate();
            if(!$user){
                header('Location: /PanControl/login');
                exit();
            }

            $product = new Product();
            $products = json_decode($product->getAllProducts());

            $response = [
                'ua' => $user,
                'title' => 'Inventario | PanControl',
                'products' => $products,
                'code' => 200
            ];
            
            View::render('pancontrol/inventario', $response);
        }

        public function clientes(){
            $user = SC::sessionValidate();
            if(!$user){
                header('Location: /PanControl/login');
                exit();
            }

            $client = new Client();
            $order = new Order();
            
            $clients = json_decode($client->getClientWithOrderStatus());
            $recentOrders = json_decode($order->getRecentOrders(5));

            $response = [
                'ua' => $user,
                'title' => 'Clientes y Pedidos | PanControl',
                'clients' => $clients,
                'orders' => $recentOrders,
                'code' => 200
            ];
            
            View::render('pancontrol/clientes', $response);
        }

        public function finanzas(){
            $user = SC::sessionValidate();
            if(!$user){
                header('Location: /PanControl/login');
                exit();
            }

            $order = new Order();
            $monthlyEarnings = json_decode($order->getMonthlyEarnings());

            $response = [
                'ua' => $user,
                'title' => 'Finanzas | PanControl',
                'monthlyEarnings' => $monthlyEarnings,
                'code' => 200
            ];
            
            View::render('pancontrol/finanzas', $response);
        }
    }
