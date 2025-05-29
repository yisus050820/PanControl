<?php

    namespace app\controllers\pancontrol;

    use app\controllers\Controller as Controller;
    use app\models\Client as Client;
    use app\models\Order as Order;
    use app\controllers\auth\SessionController as SC;

    class ClientController extends Controller {
        
        public function __construct(){
            parent::__construct();
        }

        public function index(){
            $client = new Client();
            echo $client->getAllClients();
        }

        public function create(){
            $user = SC::sessionValidate();
            if(!$user || $user['tipo'] != 1){
                echo json_encode(["r" => false, "message" => "Sin permisos"]);
                return;
            }

            $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $client = new Client();
            $result = $client->createClient($data);
            echo json_encode(["r" => $result > 0, "id" => $result]);
        }

        public function update($params = null){
            $user = SC::sessionValidate();
            if(!$user || $user['tipo'] != 1){
                echo json_encode(["r" => false, "message" => "Sin permisos"]);
                return;
            }

            $id = $params[2] ?? null;
            if(!$id){
                echo json_encode(["r" => false, "message" => "ID requerido"]);
                return;
            }

            $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $client = new Client();
            $result = $client->updateClient($id, $data);
            echo json_encode(["r" => $result]);
        }

        public function delete($params = null){
            $user = SC::sessionValidate();
            if(!$user || $user['tipo'] != 1){
                echo json_encode(["r" => false, "message" => "Sin permisos"]);
                return;
            }

            $id = $params[2] ?? null;
            if(!$id){
                echo json_encode(["r" => false, "message" => "ID requerido"]);
                return;
            }

            $client = new Client();
            $result = $client->deleteClient($id);
            echo json_encode(["r" => $result]);
        }        public function withOrderStatus(){
            $client = new Client();
            echo $client->getClientWithOrderStatus();
        }

        public function showView(){
            $user = SC::sessionValidate();
            if(!$user || $user['tipo'] != 1){
                $this->redirect('/PanControl/');
                return;
            }

            $client = new Client();
            $order = new Order();
            
            $data = (object)[
                'user' => $user,
                'title' => 'Clientes y Pedidos',
                'clients' => json_decode($client->getClientWithOrderStatus()),
                'orders' => json_decode($order->getAllOrders())
            ];

            $this->panControlView('clientes', $data);
        }
    }
