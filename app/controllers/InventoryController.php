<?php

namespace app\controllers;

use app\classes\Views as View;
use app\controllers\auth\SessionController as SC;
use app\models\Product;

class InventoryController extends Controller {
    
    public function __construct(){
        parent::__construct();
    }

    public function index($params = null){
        $response = [
            'ua' => SC::sessionValidate() ?? ['sv' => 0],
            'code' => 200,
            'title' => 'Inventario | PanControl'
        ];
        View::render('inventory/index', $response);
    }

    public function getProducts(){
        $product = new Product();
        echo $product->getAllProducts();
    }

    public function getLowStock(){
        $product = new Product();
        echo $product->getLowStock();
    }

    public function addProduct(){
        $product = new Product();
        $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        $product->values = [
            $data['name'],
            $data['quantity'],
            $data['unit'],
            $data['min_quantity'],
            1
        ];
        echo json_encode(["r" => $product->create()]);
    }
}
