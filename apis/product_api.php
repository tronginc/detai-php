<?php
require 'restful_api.php';
require '../server/config.php';

class api extends restful_api {
    function __construct(){
        parent::__construct();
    }

    function search(){
        if ($this->method == 'GET'){
            global $pdo;
            $t_pdo = &$pdo;
            $search = $this->params['search'] ? htmlspecialchars($this->params['search'], ENT_QUOTES) : '';
            $query = "select products.name, products.id, categories.name as categoryName, products.logo from products LEFT JOIN categories ON products.categoryId = categories.id WHERE products.name LIKE '%". $search ."%' LIMIT 5";
            $stmt = $t_pdo->prepare($query);
            if (!$stmt->execute()){
                $this->response(500, null);
            }
            $data = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            $this->response(200, $data);
        }
    }
}

$product_api = new api();
