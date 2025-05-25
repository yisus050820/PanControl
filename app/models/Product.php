<?php

namespace app\models;

class Product extends Model {
    protected $table;
    protected $fillable = [
        'name',
        'quantity',
        'unit',
        'min_quantity',
        'active'
    ];

    public $values = [];

    public function __construct(){
        parent::__construct();
        $this->table = $this->connect();
    }

    public function getAllProducts() {
        return $this->select(['id', 'name', 'quantity', 'unit', 'min_quantity'])
                    ->where([['active', 1]])
                    ->get();
    }    public function getLowStock() {
        return $this->select(['id', 'name', 'quantity', 'unit'])
                    ->where([['quantity <= min_quantity'], ['active', 1]])
                    ->get();
    }

    public function update($id, $data) {
        $sets = [];
        $values = [];
        foreach($data as $key => $value) {
            $sets[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id;
        
        $sql = "UPDATE " . str_replace("app\\models\\","",get_class($this)) . 
               " SET " . implode(", ", $sets) . 
               " WHERE id = ?";
               
        $stmt = $this->table->prepare($sql);
        $types = str_repeat("s", count($values));
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }
}
