<?php

    namespace app\controllers\auth;

    use app\controllers\Controller;
    use app\classes\Views as View;
    use app\classes\Redirect;
    use app\models\user;

    class SessionController extends Controller {
        public function __construct(){
            parent::__construct();
        }

        public function iniSession($params = null){
            $response = [
                'ua'    => ['sv' => 0],
                'title' => 'Iniciar sesión | ' . APP_NAME,
                'code'  => 200
            ];
            View::render('auth/inisession', $response);
        }        public function userAuth(){
            header('Content-Type: application/json');
            
            try {
                $datos = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
                
                if(!isset($datos['username']) || !isset($datos['passwd'])) {
                    echo json_encode([
                        'r' => false,
                        'msg' => 'Datos incompletos'
                    ]);
                    exit;
                }

                $user = new user();
                $result = $user->where([
                    ["username", "=", $datos["username"]],
                    ["passwd", "=", sha1($datos["passwd"])],
                    ["activo", "=", "1"]
                ])->get();

                $result = json_decode($result);
                  if(!empty($result)) {
                    $_SESSION['user'] = $result[0];                    echo json_encode([
                        'r' => true,
                        'msg' => 'Login exitoso',
                        'redirect' => BASE_URL . 'public/'
                    ]);
                } else {
                    echo json_encode([
                        'r' => false,
                        'msg' => 'Credenciales inválidas'
                    ]);
                }
            } catch (\Exception $e) {
                echo json_encode([
                    'r' => false,
                    'msg' => 'Error del servidor: ' . $e->getMessage()
                ]);
            }
            exit;
        }

        public static function sessionValidate(){
            if(isset($_SESSION['user'])){
                return $_SESSION['user'];
            }
            return false;
        }

        public function logout(){
            session_destroy();
            Redirect::to('Session/iniSession');
        }
    }