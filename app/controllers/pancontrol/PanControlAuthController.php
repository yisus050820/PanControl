<?php

    namespace app\controllers\pancontrol;

    use app\controllers\Controller as Controller;
    use app\classes\Views as View;
    use app\controllers\auth\SessionController as SC;

    class PanControlAuthController extends Controller {
        
        public function __construct(){
            parent::__construct();
        }

        public function login(){
            $response = [
                'ua' => ['sv' => 0],
                'title' => 'Iniciar Sesión | PanControl',
                'code' => 200
            ];
            View::render('pancontrol/login', $response);
        }

        public function authenticate(){
            $datos = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            // Utilizamos el mismo sistema de autenticación del proyecto principal
            $sessionController = new SC();
            
            // Simulamos llamar al método userAuth pero devolvemos JSON
            $user = new \app\models\user();
            $result = $user -> where([["username",$datos["username"]],
                                      ["passwd",sha1($datos["passwd"])]])
                            ->get();
            
            if( count( json_decode($result)) > 0){
                $sessionData = json_decode($result)[0];
                // Solo permitir usuarios tipo 1 (admin) en PanControl
                if($sessionData->tipo == 1){
                    echo $this->sessionRegister($result);
                } else {
                    echo json_encode(["r" => false, "message" => "Sin permisos para acceder a PanControl"]);
                }
            }else{
                echo json_encode(["r" => false, "message" => "Credenciales incorrectas"]);
            }
        }

        private function sessionRegister( $r ){
            $datos = json_decode( $r );
            session_start();
            $_SESSION['sv']       = true;
            $_SESSION['IP']       = $_SERVER['REMOTE_ADDR'];
            $_SESSION['id']       = $datos[0]->id;
            $_SESSION['username'] = $datos[0]->username;
            $_SESSION['passwd']   = $datos[0]->passwd;
            $_SESSION['tipo']     = $datos[0]->tipo;
            session_write_close();
            return json_encode( ["r" => true ]);
        }

        public function logout(){
            SC::sessionDestroy();
            header('Location: /PanControl/login');
            exit();
        }
    }
