<?php 

    namespace app\controllers;
    use app\classes\Views as View;
    use app\classes\Redirect;
    use app\controllers\auth\SessionController as SC;
    class HomeController extends Controller {

        public function __construct(){
            parent::__construct();
        }

        public function index($params = null){
            $auth = SC::sessionValidate();
            if (!$auth) {
                Redirect::to('Session/iniSession');
            }
            $response = [
                        'ua' => $auth ?? ['sv' => 0],
                        'code' => 200,
                        'title' => 'Dashboard | PanControl'
                        ];
            View::render('dashboard/index', $response);
        }

    }