<?php

namespace app\controllers;

use app\classes\Views as View;
use app\controllers\auth\SessionController as SC;
use app\models\Order;

class FinanceController extends Controller {
    
    public function __construct(){
        parent::__construct();
    }

    public function index($params = null){
        $response = [
            'ua' => SC::sessionValidate() ?? ['sv' => 0],
            'code' => 200,
            'title' => 'Finanzas | PanControl'
        ];
        View::render('finance/index', $response);
    }

    public function getFinancialData(){
        $order = new Order();
        $monthlyData = $order->select(['SUM(total) as income', 'MONTH(created_at) as month'])
                            ->where([['active', 1]])
                            ->groupBy(['MONTH(created_at)'])
                            ->orderBy([['month', 'ASC']])
                            ->limit(5)
                            ->get();
        echo $monthlyData;
    }
}
