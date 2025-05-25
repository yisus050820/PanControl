<?php

namespace app\models;

class Client extends Model {
    protected $table;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'active'
    ];

    public $values = [];

    public function __construct(){
        parent::__construct();
        $this->table = $this->connect();
    }

    public function getAllClients() {
        return $this->select(['id', 'name', 'email', 'phone'])
                    ->where([['active', 1]])
                    ->get();
    }
}
