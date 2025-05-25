<?php

namespace app\models;

class Order extends Model {
    protected $table;
    protected $fillable = [
        'client_id',
        'total',
        'status',
        'created_at',
        'delivery_date',
        'active'
    ];

    public $values = [];

    public function __construct(){
        parent::__construct();
        $this->table = $this->connect();
    }

    public function getClientOrders($clientId) {
        return $this->select(['a.id', 'a.total', 'a.status', 'date_format(a.created_at,"%d/%m/%Y") as fecha', 'date_format(a.delivery_date,"%d/%m/%Y") as delivery_date', 'b.name'])
                    ->join("client b", "a.client_id=b.id")
                    ->where([['a.client_id', $clientId], ['a.active', 1]])
                    ->orderBy([['a.created_at', 'desc']])
                    ->get();
    }

    public function getAllOrders() {
        return $this->select(['a.id', 'a.total', 'a.status', 'date_format(a.created_at,"%d/%m/%Y") as fecha', 'date_format(a.delivery_date,"%d/%m/%Y") as delivery_date', 'b.name'])
                    ->join("client b", "a.client_id=b.id")
                    ->where([['a.active', 1]])
                    ->orderBy([['a.created_at', 'desc']])
                    ->get();
    }
}
