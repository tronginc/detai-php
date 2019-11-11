<?php
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
echo '<title>So sánh giá</title>';
echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';
echo '<link rel="stylesheet" href="./assets/index.css">';
echo '<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>';
echo '<script src="./assets/index.js"></script>';
echo '<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>';
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>';

require './server/config.php';
$products = array();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$sql = "
    SELECT products.id, products.name, products.logo, minPrice.price, totalManufacturer, productUrl, minPrice.createdAt
    FROM products
    LEFT JOIN (
        SELECT productId, COUNT(DISTINCT manufacturerId) AS totalManufacturer
        FROM prices
        GROUP BY prices.productId
    ) counts ON counts.productId = products.id
    JOIN (
        SELECT prices.* FROM (
            SELECT MAX(id) id, productId FROM (
                SELECT prices.* FROM (
                    SELECT productId, MIN(lastValue.price) price
                    FROM (
                        SELECT prices.*
                            FROM (
                                SELECT productId, manufacturerId ,MAX(createdAt) createdAt
                                FROM prices
                                GROUP BY productId, manufacturerId
                            ) latest
                            LEFT JOIN prices ON latest.productId = prices.productId AND prices.createdAt = latest.createdAt AND prices.manufacturerId = latest.manufacturerId               
                        ) lastValue 
                    GROUP BY lastValue.productId
                ) latestMinPrice
                LEFT JOIN prices on prices.price = latestMinPrice.price 
            ) maxId
            GROUP BY productId
        ) minPriceAndMaxId
        LEFT JOIN prices on minPriceAndMaxId.id = prices.id
    ) minPrice
    ON minPrice.productId = products.id
    WHERE products.categoryId = :categoryId
   LIMIT 8
";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':categoryId', $_GET['id']);

?>
<body>
    <div class="container">
        <?php include "./header.php"; ?>
        <div class="row">
            <?php include "./list_category.php"; ?>
            <div class="col">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="https://cf.shopee.vn/file/1a760e49e6f046dfef4ea4450bb84ae7_xxhdpi" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="https://cf.shopee.vn/file/9aab3503f66bd1c4179842b49c7df838_xxhdpi" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="https://cf.shopee.vn/file/94a693e2aad7c61db097ff6f6f706a2b_xxhdpi" class="d-block w-100" alt="...">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
        <hr />
        <p class="title"><?php
            if ($_GET['id'] == 1){
                echo 'ĐIỆN THOẠI';
            }
            if ($_GET['id'] == 2){
                echo 'LAPTOP';
            }
            if ($_GET['id'] == 11){
                echo 'MÁY TÍNH BẢNG';
            }
            if ($_GET['id'] == 12){
                echo 'ĐỒNG HỒ';
            }
            if ($_GET['id'] == 13){
                echo 'PHỤ KIỆN';
            }
            ?></p>
        <div class="row list-product-container">
            <?php
            if ($stmt->execute()) {
                $index = 0;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $index += 1;
                    echo '<div class="col-md-3">
                            <figure class="card card-product">
                                <div class="img-wrap"><img src="'. $row['logo'] .'" alt=""></div>
                                <figcaption class="info-wrap">
                                    <a href="product.php?id='. $row['id'] .'"><h5 class="title">'. $row['name'] .'</h5></a>
                                    <div class="rating-wrap">
                                        <div class="label-rating">'. $row['totalManufacturer'] .' nơi bán</div>
                                    </div> <!-- rating-wrap.// -->
                                </figcaption>
                                <div class="bottom-wrap">
                                    <a target="_blank" href="'. $row['productUrl'] .'" class="btn btn-sm btn-primary float-right">Đến nơi bán</a>
                                    <div class="price-wrap">
                                        <span class="price-new">'. number_format($row['price'], 0, ',', '.') . ' đ' .'</span>
                                    </div> <!-- price-wrap.// -->
                                </div> <!-- bottom-wrap.// -->
                            </figure>
                        </div> <!-- col // -->';
                }
            }
            ?>
        </div> <!-- row.// -->
    </div>
</body>
