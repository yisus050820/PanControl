<?php

namespace app\controllers;

use app\classes\Views as View;
use app\controllers\auth\SessionController as SC;
use app\models\Client;
use app\models\Order;

class ClientController extends Controller {
    
    public function __construct(){
        parent::__construct();
    }

    public function index($params = null){
        $response = [
            'ua' => SC::sessionValidate() ?? ['sv' => 0],
            'code' => 200,
            'title' => 'Clientes | PanControl'
        ];
        View::render('clients/index', $response);
    }

    public function getClients(){
        $client = new Client();
        echo $client->getAllClients();
    }

    public function getOrders($params = null){
        $order = new Order();
        if(isset($params[2])) {
            echo $order->getClientOrders($params[2]);
        } else {
            echo $order->getAllOrders();
        }
    }

    public function addClient(){
        $client = new Client();
        $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        $client->values = [
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['address'],
            1
        ];
        echo json_encode(["r" => $client->create()]);
    }
}
