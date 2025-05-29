<?php

    namespace app\controllers\pancontrol;

    use app\controllers\Controller as Controller;
    use app\models\Product as Product;
    use app\controllers\auth\SessionController as SC;

    class ProductController extends Controller {
        
        public function __construct(){
            parent::__construct();
        }

        public function index(){
            $product = new Product();
            echo $product->getAllProducts();
        }

        public function create(){
            $user = SC::sessionValidate();
            if(!$user || $user['tipo'] != 1){
                echo json_encode(["r" => false, "message" => "Sin permisos"]);
                return;
            }

            $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $product = new Product();
            $result = $product->createProduct($data);
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
            $product = new Product();
            $result = $product->updateProduct($id, $data);
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

            $product = new Product();
            $result = $product->deleteProduct($id);
            echo json_encode(["r" => $result]);
        }

        public function lowStock(){
            $product = new Product();
            echo $product->getLowStockProducts();
        }
    }
