<?php

if (isset($_GET['id'])){
    require './server/config.php';
    $sql = "
    SELECT products.id, products.name, products.logo, products.createdBy, products.createdAt, 
           lastValue.manufacturerId, manufacturers.name as manufacturerName, lastValue.price,
           lastValue.createdAt, counts.count, lastValue.productUrl, categories.name as categoryName, 
           categories.id as categoryId
    FROM products 
    LEFT JOIN categories ON products.categoryId = categories.id
    JOIN (
        SELECT prices.createdAt, price, prices.productId, prices.productUrl, prices.manufacturerId
        FROM (SELECT productId, MAX(createdAt) createdAt
             FROM prices
             GROUP BY productId
             ) latest
        JOIN prices
        ON latest.productId = prices.productId AND prices.createdAt = latest.createdAt
    ) lastValue
    ON lastValue.productId = products.id
    LEFT JOIN (
        SELECT productId, COUNT(DISTINCT manufacturerId) AS count FROM prices GROUP BY prices.productId
        ) counts 
    ON counts.productId = products.id
    LEFT JOIN manufacturers ON manufacturerId = manufacturers.id 
    WHERE products.id = :id
    ";
    $stmt = $pdo->prepare($sql);

    //Bind value.
    $stmt->bindValue(':id', $_GET['id']);

    //Execute.
    $stmt->execute();

    //Fetch row.
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "
    SELECT products.id, products.name, products.logo, products.createdBy, products.createdAt, lastValue.price,
           lastValue.createdAt, counts.count, lastValue.productUrl, categories.name as categoryName
    FROM products 
    LEFT JOIN categories ON products.categoryId = categories.id
    JOIN (
        SELECT prices.createdAt, price, prices.productId, prices.productUrl
        FROM (SELECT productId, MAX(createdAt) createdAt
             FROM prices
             GROUP BY productId
             ) latest
        JOIN prices
        ON latest.productId = prices.productId AND prices.createdAt = latest.createdAt
    ) lastValue
    ON lastValue.productId = products.id
    LEFT JOIN (
        SELECT productId, COUNT(DISTINCT manufacturerId) AS count FROM prices GROUP BY prices.productId
        ) counts 
    ON counts.productId = products.id
    WHERE products.categoryId = :categoryId AND products.id <> :productId
    ";

    $stmt = $pdo->prepare($sql);

    //Bind value.
    $stmt->bindValue(':categoryId', $product['categoryId']);
    $stmt->bindValue(':productId', $_GET['id']);

    $sql = "
    SELECT products.id, products.name, products.logo, products.createdAt, lastValue.price, lastValue.createdAt, 
           lastValue.productUrl, manufacturers.name as manufacturerName, manufacturers.logo as manufacturerLogo
    FROM products 
    JOIN (
        SELECT prices.createdAt, price, prices.productId, prices.productUrl, prices.manufacturerId
        FROM (SELECT productId, MAX(createdAt) createdAt
             FROM prices
             GROUP BY productId
             ) latest
        JOIN prices
        ON latest.productId = prices.productId AND prices.createdAt = latest.createdAt
    ) lastValue
    ON lastValue.productId = products.id
    LEFT JOIN manufacturers ON lastValue.manufacturerId = manufacturers.id
    WHERE products.id = :productId
    ";

    $stmtOther = $pdo->prepare($sql);

    //Bind value.
    $stmtOther->bindValue(':productId', $_GET['id']);
}


echo '<title>'. ($product ? $product['name'] : 'Sản phẩm không tồn tại') .'</title>';
echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';
echo '<link rel="stylesheet" href="./assets/index.css">';
echo '<link rel="stylesheet" href="./assets/product.css">';
echo '<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>';
echo '<script src="./assets/index.js"></script>';
echo '<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>';
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>';
?>
<body>
<div class="container">
    <?php include "./header.php"; ?>
    <div class="row">
        <div class="col-8">
            <div class="header-title">
                <h3>Thông tin sản phẩm</h3>
            </div>
            <div class="row product-detail">
                <div class="col-5">
                    <img class="logo" src="<?php echo $product['logo'] ?>" alt="" >
                </div>
                <div class="col-7">
                    <h1 class="name"><?php echo $product['name'] ?></h1>
                    <span class="category"><?php echo $product['categoryName'] ?></span>
                    <br />
                    <span class="price">Có: <?php echo $product['count'] ?> nơi bán</span>
                    <br />
                    <span class="price">Giá tốt nhất: <?php echo number_format($product['price'], 0, ',', '.') . ' đ' ?></span>
                    <br />
                    <span class="price">Nhà cung cấp: <?php echo $product['manufacturerName'] ?></span>
                </div>
            </div>
            <div class="header-title">
                <h3>Nhà cung cấp khác</h3>
                <?php
                if ($stmtOther->execute()) {
                    while ($row = $stmtOther->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="manufacturer">
                            <div class="d-flex justify-content-between align-content-center">    
                                <img class="logo" alt="" src="'. $row['manufacturerLogo'] .'">
                                <span>'. $row['manufacturerName'] .'</span>
                                <span>'. number_format($row['price'], 0, ',', '.') . ' đ' .'</span>                         
                            </div>
                        </div>';
                    }
                }
                ?>
            </div>
        </div>
        <div class="col-4">
            <div class="header-title">
                <h3>Sản phẩm tương tự</h3>
                <?php
                if ($stmt->execute()) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="same-product">
                            <div class="d-flex justify-content-between">    
                                <img class="logo" alt="" src="'. $row['logo'] .'">
                                <span>'. $row['name'] .'</span>
                                <span>'. $row['price'] .'</span>                         
                            </div>
                        </div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>

