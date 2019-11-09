<?php
$order = 'latest';
if (isset($_GET['orderBy'])){
    $order = $_GET['orderBy'];
}
$sql = "
    SELECT products.id, products.name, products.logo, products.createdBy, products.createdAt, users.fullName, categories.name AS categoryName, categories.id AS categoryId , lastValue.price, lastValue.createdAt, counts.count
    FROM products 
    LEFT JOIN users ON products.createdBy = users.id 
    LEFT JOIN categories ON products.categoryId = categories.id
    LEFT JOIN (
        SELECT prices.createdAt, price, prices.productId 
        FROM (SELECT productId, MAX(createdAt) createdAt
             FROM prices
             GROUP BY productId
             ) latest
        JOIN prices
        ON latest.productId = prices.productId AND prices.createdAt = latest.createdAt
    ) lastValue
    ON lastValue.productId = products.id
    LEFT JOIN (
        SELECT productId, COUNT(DISTINCT ProductId) AS count FROM prices GROUP BY prices.productId
        ) counts 
    ON counts.productId = products.id
    ORDER BY products.createdAt " . ($order == 'latest' ? 'DESC' : 'ASC') . "
";
$stmt = $pdo->prepare($sql);

$sqlCategory = "SELECT * from categories";
$stmtCategory = $pdo->prepare($sqlCategory);
?>
<main class="page-content">
    <div class="container-fluid">
        <div class="flex-row d-flex">
            <h2>Sản phẩm
            </h2>
            <button style="height: 30px; margin-top: 8px; margin-left: 10px" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addProduct">Thêm
                sản phẩm
            </button>
            <select id="orderBy" name="orderBy" style="height: 30px; margin-top: 8px; margin-left: 10px">
                <option value="latest" <?php if ($order == "latest") echo "selected"?>>Mới nhất</option>
                <option value="oldest" <?php if ($order == "oldest") echo "selected"?>>Cũ nhất</option>
            </select>
        </div>
        <hr>
        <div class="row">
            <?php
            if ($stmt->execute()) {
                $index = 0;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $index += 1;
                    echo '<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 mb-4 product-relative">
                            <button class="product-edit" data-toggle="modal" data-target="#updateProduct" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-categoryId="' . $row['categoryId'] . '"><i class="fa fa-pencil-alt"></i></button>
                            <div class="card rounded-0 p-0 shadow-sm">
                                <img src="' . $row['logo'] . '" class="card-img-top rounded-0 product-logo" alt="Angular pro sidebar">                         
                                <div class="card-body">
                                    <h6 class="card-title">' . $row['name'] . '</h6>
                                    <p class="card-title">' . $row['categoryName'] . '</p>
                                    <p class="card-title">' . ($row['price'] ? number_format($row['price'], 0, ',', '.') . ' đ' : 'Chưa cập nhật') . '</p>
                                    <p class="card-title">' . ($row['count'] ? $row['count'] : 'Chưa có') . ' nơi bán</p>
                                    <p class="card-title">Cập nhật lần cuối: ' . ($row['createdAt'] ? date_format(date_create($row['createdAt'], timezone_open('Asia/Ho_Chi_Minh')), "H:i:s d/m/Y") : 'Chưa cập nhật') . '</p>
                                    <a href="https://github.com/azouaoui-med/angular-pro-sidebar" target="_blank" class="btn btn-block btn-primary btn-sm"><i class="fa fa-chart-bar"></i> Xem chi tiết</a>
                                    <a href="https://azouaoui-med.github.io/angular-pro-sidebar/demo/" target="_blank" class="btn btn-block btn-success btn-sm"><i class="fa fa-sync-alt"></i> Cập nhật giá</a>
                                </div>
                            </div>
                        </div>';
                }
            }
            ?>
        </div>
    </div>
    <div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Thêm sản phẩm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/admin/index.php?action=product" enctype="multipart/form-data">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Danh mục</label>
                            <label for="categoryId"></label><select name="categoryId" class="custom-select" id="categoryId">
                                <?php
                                if ($stmtCategory->execute()) {
                                    $index = 0;
                                    while ($row = $stmtCategory->fetch(PDO::FETCH_ASSOC)) {
                                        $index += 1;
                                        echo '<option value="'. $row['id'] .'">'. $row['name'] .'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Logo</label>
                            <div class="custom-file mb-3">
                                <input type="file" class="custom-file-input" id="productLogo" name="productLogo"
                                       lang="vi"
                                       title="Chọn tệp">
                                <label class="custom-file-label" for="productLogo">Chọn tệp (Tối
                                    đa: <?= ini_get('upload_max_filesize') ?>)</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" name="addProduct" id="addProduct" class="btn btn-primary">Thêm sản phẩm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updateProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Sửa sản phẩm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/admin/index.php?action=product" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input id="id" name="id" hidden aria-label="" value=""/>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="name" name="name" aria-label="">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Danh mục</label>
                            <label for="categoryId"></label><select name="categoryId" class="custom-select" id="categoryId">
                                <?php
                                if ($stmtCategory->execute()) {
                                    $index = 0;
                                    while ($row = $stmtCategory->fetch(PDO::FETCH_ASSOC)) {
                                        $index += 1;
                                        echo '<option value="'. $row['id'] .'">'. $row['name'] .'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Logo</label>
                            <div class="custom-file mb-3">
                                <input type="file" class="custom-file-input" id="productLogo" name="productLogo"
                                       lang="vi"
                                       title="Chọn tệp">
                                <label class="custom-file-label" for="productLogo">Chọn tệp (Tối
                                    đa: <?= ini_get('upload_max_filesize') ?>)</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" name="updateProduct" id="updateProduct" class="btn btn-warning">Sửa sản phẩm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="deleteProduct">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xóa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/admin/index.php?action=Product">
                    <div class="modal-body">
                        <input id="id" name="id" hidden aria-label="" value="" />
                        <p>Bạn có chắn chắn muốn xóa không?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" id="deleteProduct" name="deleteProduct" class="btn btn-danger">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script>
  // Add the following code if you want the name of the file appear on select
  $(".custom-file-input").on("change", function () {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
  });
</script>
