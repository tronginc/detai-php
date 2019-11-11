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
            $query = "select products.name, products.id, categories.name as categoryName, products.logo from products LEFT JOIN categories ON products.categoryId = categories.id WHERE products.name LIKE '%". $search ."%' LIMIT 10";
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

    function updatePrice(){
        if ($this->method == 'GET') {
            $result = array();
            if (!isset($this->params['apiKey']) || $this->params['apiKey'] != '4c4739d4-dae2-4d79-8fe1-808849d7150d-18183c3f-cb72-4fa6-be31-d6878028a738-a274d092-10e3-428e-8148-2288db46a97e'){
                $object = new stdClass();
                $object->message = 'Forbidden';
                $this->response(403, $object);
            }
            else {
                global $pdo;
                $t_pdo = &$pdo;
                $query = "
                SELECT products.id, products.name, products.logo, products.createdAt, lastValue.price, lastValue.createdAt, 
                       lastValue.productUrl, manufacturerName, manufacturerId, manufacturerProductId, manufacturerShopId
                FROM products 
                LEFT JOIN (
                    SELECT prices.createdAt, price, prices.productId, prices.productUrl, prices.manufacturerId as manufacturerId, 
                           manufacturers.name as manufacturerName, manufacturers.logo as manufacturerLogo,
                           manufacturerProductId, manufacturerShopId
                    FROM (
                        SELECT productId, manufacturerId ,MAX(createdAt) createdAt
                        FROM prices
                        GROUP BY productId, manufacturerId
                     ) latest
                    LEFT JOIN prices
                    ON latest.productId = prices.productId AND prices.createdAt = latest.createdAt AND prices.manufacturerId = latest.manufacturerId
                    LEFT JOIN manufacturers ON prices.manufacturerId = manufacturers.id
                ) lastValue
                ON lastValue.productId = products.id
                WHERE products.id = :productId
            ";
                $stmt = $t_pdo->prepare($query);
                $stmt->bindValue(':productId', $this->params['id']);
                if (!$stmt->execute()){
                    $this->response(500, null);
                }
                else {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $object = new stdClass();
                        if (strstr(parse_url($row['productUrl'],PHP_URL_HOST),'lazada.vn'))
                        {
                            $response = file_get_contents($row['productUrl']);
                            preg_match('/salePrice(.*?)}}/', $response, $match);
                            $priceStr = substr($match[1], strrpos($match[1], ':') + 1, strlen($match[1]));
                            $price = (float) $priceStr;
                            $this->updatePriceOne($row['id'], $price, $row['manufacturerProductId'], $row['manufacturerShopId'], $row['productUrl'], $row['manufacturerId']);
                            $object->manufacturer = $row['manufacturerName'];
                            $object->newPrice = $price;
                        }
                        if (strstr(parse_url($row['productUrl'],PHP_URL_HOST),'tiki.vn'))
                        {
                            $response = file_get_contents($row['productUrl']);
                            preg_match('/price\':\s\'(.*?)\',\s\s\s/', $response, $match);
                            $price = (float) $match[1];
                            $this->updatePriceOne($row['id'], $price, $row['manufacturerProductId'], $row['manufacturerShopId'], $row['productUrl'], $row['manufacturerId']);
                            $object->manufacturer = $row['manufacturerName'];
                            $object->newPrice = $price;
                        }
                        if (strstr(parse_url($row['productUrl'],PHP_URL_HOST),'thegioididong.com'))
                        {
                            $response = file_get_contents($row['productUrl']);
                            preg_match('/price:\s\'(.*?)\'/', $response, $match);
                            $price = (float) $match[1];
                            $this->updatePriceOne($row['id'], $price, $row['manufacturerProductId'], $row['manufacturerShopId'], $row['productUrl'], $row['manufacturerId']);
                            $object->manufacturer = $row['manufacturerName'];
                            $object->newPrice = $price;
                        }
                        $result[] = $object;
                    }
                    $this->response(200, $result);
                }
            }
        }
    }
    function updateAll(){
        if ($this->method == 'GET') {
            $result = array();
            if (!isset($this->params['apiKey']) || $this->params['apiKey'] != '4c4739d4-dae2-4d79-8fe1-808849d7150d-18183c3f-cb72-4fa6-be31-d6878028a738-a274d092-10e3-428e-8148-2288db46a97e'){
                $object = new stdClass();
                $object->message = 'Forbidden';
                $this->response(403, $object);
            }
            else {
                global $pdo;
                $t_pdo = &$pdo;
                $query = "
                SELECT products.id, products.name, products.logo, products.createdAt, lastValue.price, lastValue.createdAt, 
                       lastValue.productUrl, manufacturerName, manufacturerId, manufacturerProductId, manufacturerShopId
                FROM products 
                LEFT JOIN (
                    SELECT prices.createdAt, price, prices.productId, prices.productUrl, prices.manufacturerId as manufacturerId, 
                           manufacturers.name as manufacturerName, manufacturers.logo as manufacturerLogo,
                           manufacturerProductId, manufacturerShopId
                    FROM (
                        SELECT productId, manufacturerId ,MAX(createdAt) createdAt
                        FROM prices
                        GROUP BY productId, manufacturerId
                     ) latest
                    LEFT JOIN prices
                    ON latest.productId = prices.productId AND prices.createdAt = latest.createdAt AND prices.manufacturerId = latest.manufacturerId
                    LEFT JOIN manufacturers ON prices.manufacturerId = manufacturers.id
                ) lastValue
                ON lastValue.productId = products.id
            ";
                $stmt = $t_pdo->prepare($query);
                if (!$stmt->execute()){
                    $this->response(500, null);
                }
                else {
                    $count = 0;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $object = new stdClass();
                        if (strstr(parse_url($row['productUrl'],PHP_URL_HOST),'lazada.vn'))
                        {
                            $response = file_get_contents($row['productUrl']);
                            preg_match('/salePrice(.*?)}}/', $response, $match);
                            $priceStr = substr($match[1], strrpos($match[1], ':') + 1, strlen($match[1]));
                            $price = (float) $priceStr;
                            $this->updatePriceOne($row['id'], $price, $row['manufacturerProductId'], $row['manufacturerShopId'], $row['productUrl'], $row['manufacturerId']);
                            $object->manufacturer = $row['manufacturerName'];
                            $object->newPrice = $price;
                        }
                        if (strstr(parse_url($row['productUrl'],PHP_URL_HOST),'tiki.vn'))
                        {
                            $response = file_get_contents($row['productUrl']);
                            preg_match('/price\':\s\'(.*?)\',\s\s\s/', $response, $match);
                            $price = (float) $match[1];
                            $this->updatePriceOne($row['id'], $price, $row['manufacturerProductId'], $row['manufacturerShopId'], $row['productUrl'], $row['manufacturerId']);
                            $object->manufacturer = $row['manufacturerName'];
                            $object->newPrice = $price;
                        }
                        if (strstr(parse_url($row['productUrl'],PHP_URL_HOST),'sendo.vn'))
                        {
                            $response = file_get_contents($row['productUrl']);
                            preg_match('/\"final_price\":(.*?),\"/', $response, $match);
                            $price = (float) $match[1];
                            $this->updatePriceOne($row['id'], $price, $row['manufacturerProductId'], $row['manufacturerShopId'], $row['productUrl'], $row['manufacturerId']);
                            $object->manufacturer = $row['manufacturerName'];
                            $object->newPrice = $price;
                        }
                        if (strstr(parse_url($row['productUrl'],PHP_URL_HOST),'thegioididong.com'))
                        {
                            $response = file_get_contents($row['productUrl']);
                            preg_match('/price:\s\'(.*?)\'/', $response, $match);
                            $price = (float) $match[1];
                            $this->updatePriceOne($row['id'], $price, $row['manufacturerProductId'], $row['manufacturerShopId'], $row['productUrl'], $row['manufacturerId']);
                            $object->manufacturer = $row['manufacturerName'];
                            $object->newPrice = $price;
                        }
                        $result[] = $object;
                    }
                    $this->response(200, $result);
                }
            }
        }
    }
    private function updatePriceOne($productId, $price, $manufacturerProductId, $manufacturerShopId, $productUrl, $manufacturerId){
        global $pdo;
        $t_pdo = &$pdo;
        $sql = "INSERT INTO prices (productId, price, manufacturerProductId, manufacturerShopId, productUrl, manufacturerId, createdBy, createdAt)
                                values (:productId, :price, :manufacturerProductId, :manufacturerShopId, :productUrl, :manufacturerId, :createdBy, :createdAt)
                        ";
        $stmt = $t_pdo->prepare( $sql);
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':manufacturerProductId', $manufacturerProductId);
        $stmt->bindParam(':manufacturerShopId', $manufacturerShopId);
        $stmt->bindParam(':productUrl', $productUrl);
        $stmt->bindParam(':manufacturerId', $manufacturerId);
        $createdBy = 0;
        $stmt->bindParam(':createdBy', $createdBy);
        $tz = 'Asia/Ho_Chi_Minh';
        $timestamp = time();
        try {
            $dt = new DateTime("now", new DateTimeZone($tz));
        } catch (Exception $e) {
        } //first argument "must" be a string
        $dt->setTimestamp($timestamp); //adjust
        $createdAt = $dt->format("Y-m-d H:i:s");
        $stmt->bindParam(':createdAt', $createdAt);
        $stmt->execute();
    }
}

$product_api = new api();
