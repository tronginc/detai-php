<?php

if (isset($_GET['id'])) {
    require './server/config.php';
    $sql = "
    SELECT products.id, products.name, products.logo, minPrice.price, totalManufacturer, productUrl, 
           minPrice.createdAt, manufacturers.name as manufacturerName, manufacturerId, manufacturers.logo as manufacturerLogo,
           categories.name as categoryName, categories.id as categoryId
    FROM products
    LEFT JOIN categories ON categories.id = products.categoryId
    LEFT JOIN (
        SELECT productId, COUNT(DISTINCT manufacturerId) AS totalManufacturer
        FROM prices
        GROUP BY prices.productId
    ) counts ON counts.productId = products.id
    LEFT JOIN (
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
    ) minPrice
    ON minPrice.productId = products.id
    LEFT JOIN manufacturers on minPrice.manufacturerId = manufacturers.id 
    WHERE products.id = :productId
    ";
    $stmt = $pdo->prepare($sql);

    //Bind value.
    $stmt->bindValue(':productId', $_GET['id']);

    //Execute.
    $stmt->execute();

    //Fetch row.
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "
    SELECT products.id, products.name, products.logo, products.createdBy, products.createdAt, minPrice.price,
           minPrice.createdAt, counts.count, minPrice.productUrl, categories.name as categoryName
    FROM products 
    LEFT JOIN categories ON products.categoryId = categories.id
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
    LEFT JOIN (
        SELECT productId, COUNT(DISTINCT manufacturerId) AS count FROM prices GROUP BY prices.productId
        ) counts 
    ON counts.productId = products.id
    WHERE products.categoryId = :categoryId AND products.id <> :productId
    LIMIT 5
    ";

    $stmt = $pdo->prepare($sql);

    //Bind value.
    $stmt->bindValue(':categoryId', $product['categoryId']);
    $stmt->bindValue(':productId', $_GET['id']);

    $sql = "
    SELECT products.id, products.name, products.logo, products.createdAt, lastValue.price, lastValue.createdAt, 
           lastValue.productUrl, manufacturerName, manufacturerLogo
    FROM products 
    LEFT JOIN (
        SELECT prices.createdAt, price, prices.productId, prices.productUrl, prices.manufacturerId as manufacturerId, 
               manufacturers.name as manufacturerName, manufacturers.logo as manufacturerLogo
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
    WHERE products.id = :productId and lastValue.manufacturerId <> :manufacturerId
    ORDER BY lastValue.price
    ";

    $stmtOther = $pdo->prepare($sql);

    //Bind value.
    $stmtOther->bindValue(':productId', $_GET['id']);
    $stmtOther->bindValue(':manufacturerId', $product['manufacturerId']);

    // Lịch sử
    $sql = "
        SELECT * FROM (
            SELECT productId, manufacturerId,DATE(prices.createdAt) as date, MAX(prices.createdAt) createdAt 
            FROM prices 
            GROUP BY DATE(prices.createdAt), productId, manufacturerId
        ) unquieDate
        JOIN prices ON unquieDate.productId = prices.productId and unquieDate.createdAt = prices.createdAt and unquieDate.manufacturerId = prices.manufacturerId
        JOIN manufacturers on prices.manufacturerId = manufacturers.id
        WHERE prices.productId = :productId
    ";

    $stmtHistory = $pdo->prepare($sql);

    //Bind value.
    $stmtHistory->bindValue(':productId', $_GET['id']);

    $prices = array();
    if ($stmtHistory->execute()) {
        while ($row = $stmtHistory->fetch(PDO::FETCH_ASSOC)) {
            $prices[] = $row;
        }
    }
}


echo '<title>' . ($product ? $product['name'] : 'Sản phẩm không tồn tại') . '</title>';
echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';
echo '<link rel="stylesheet" href="./assets/index.css">';
echo '<link rel="stylesheet" href="./assets/product.css">';
echo '<link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">';
echo '<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>';
echo '<script src="./assets/index.js"></script>';
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>';
echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';
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
                    <img class="logo" src="<?php echo $product['logo'] ?>" alt="">
                </div>
                <div class="col-7">
                    <h1 class="name"><?php echo $product['name'] ?></h1>
                    <span class="category"><?php echo $product['categoryName'] ?></span>
                    <br/>
                    <span class="price">Có: <?php echo $product['totalManufacturer'] ?> nơi bán</span>
                    <br/>
                    <span class="price">Giá tốt nhất: <?php echo number_format($product['price'], 0, ',', '.') . ' đ' ?></span>
                    <span class="history-txt"><a style="padding: 5px; cursor: pointer;" data-toggle="modal"
                                                 data-target="#history"><i class="fa fa-history"></i> Lịch sử</a></span>
                    <br/>
                    <?php if (isset($product['manufacturerLogo'])) echo '<a target="_blank" href="' . $product['productUrl'] . '" class="btn btn-primary"><img alt="" class="btn-manufacturer-logo" src="' . $product['manufacturerLogo'] . '">Đến nơi bán <i style="margin-left: 5px" class="fa fa-external-link-alt"></i></a>' ?>
                </div>
            </div>
            <?php
            if ($stmtOther->execute()) {
                if ($stmtOther->rowCount() > 0) {
                    echo '<div class="header-title">
                        <h3>Nhà cung cấp khác</h3>
                    </div>';
                }
                while ($row = $stmtOther->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="manufacturer">
                            <div class="d-flex justify-content-between align-items-center">    
                                <img class="logo" alt="" src="' . $row['manufacturerLogo'] . '">
                                <span style="flex: 1">' . $row['manufacturerName'] . '</span>
                                <span style="flex: 1">' . number_format($row['price'], 0, ',', '.') . ' đ' . '</span> 
                                <a style="height: 38px; margin-left: 50px" target="_blank" href="' . $row['productUrl'] . '" class="btn btn-primary"><img alt="" class="btn-manufacturer-logo" src="' . $row['manufacturerLogo'] . '">Đến nơi bán <i style="margin-left: 5px" class="fa fa-external-link-alt"></i></a>                       
                            </div>
                        </div>';
                }
            }
            ?>
        </div>
        <div class="col-4">
            <div class="header-title">
                <h3>Sản phẩm cùng danh mục</h3>
            </div>
            <?php
            if ($stmt->execute()) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="d-flex align-items-center justify-content-between same-product">    
                            <img class="logo" alt="" src="' . $row['logo'] . '">
                            <div>
                                <a href="product.php?id=' . $row['id'] . '">
                                    <span class="name">' . $row['name'] . '</span>
                                </a>
                                <br />
                                <span>' . number_format($row['price'], 0, ',', '.') . ' đ' . '</span> 
                            </div>                        
                        </div>';
                }
            }
            ?>
        </div>
    </div>
</div>
<div class="modal fade" id="history" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo $product['name'] ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="chart_div"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  let histories = '<?php echo json_encode($prices); ?>';
</script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawCurveTypes);

  function drawCurveTypes() {
    histories = JSON.parse(histories);
    const keys = [];
    const date = [];
    histories.map(his => {
      if (keys.findIndex(k => k.id === his.manufacturerId) === -1) {
        keys.push({id: his.manufacturerId, name: his.name})
      }
      if (!date.includes(his.date)) {
        date.push(his.date)
      }
    });
    console.log(keys);
    const value = [];
    for (let i = 0; i < 7; i++) {
      const d = [];
      const today = moment();
      today.subtract(i, "days");
      const day = today.format('YYYY-MM-DD');
      d.push(day);
      for (let i = 0; i < keys.length; i += 1) {
        d.push(0);
      }
      const valueInDate = histories.filter(d => d.date === day);
      valueInDate.map(price => {
        const dIndex = keys.findIndex(k => k.id === price.manufacturerId) + 1;
        d[dIndex] =price.price;
      });
      value.push(d);
    }

    // Mới -> cũ => cũ -> mới
    value.reverse();
    value.map((item, vIndex) => {
      item[0] = moment(item[0], 'YYYY-MM-DD').format('DD-MM');
      item.map((price, index) => {
        if (index > 0) {
          if (price === 0 && index > 1) {
            const lastPrice = value.map(v => v[index]);
            const lastPriceIndex = lastPrice.findIndex((price) => price > 0);
            console.log(price, lastPriceIndex, lastPrice[lastPriceIndex]);
            if (lastPriceIndex > 1 && lastPriceIndex <= vIndex) {
              item[index] = lastPrice[lastPriceIndex]
            }
          }
        }
      })
    });
    const data = google.visualization.arrayToDataTable([
      ['Ngày', ...keys.map(k => k.name)],
      ...value
    ]);
    console.log(data);

    var options = {
      hAxis: {
        title: 'Ngày'
      },
      width: 1050,
      height: 550,
      vAxis: {
        title: 'Giá'
      },
    };

    var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
    chart.draw(data, options);
  }
</script>
</body>

