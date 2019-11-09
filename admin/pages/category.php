<?php
$sql = "SELECT categories.id, categories.name, categories.logo, categories.createdBy, categories.createdAt, users.fullName, count FROM categories LEFT JOIN users ON categories.createdBy = users.id LEFT JOIN (SELECT products.categoryId, COUNT(*) AS count FROM products GROUP BY products.categoryId) counts ON categories.id = counts.categoryId";
$stmt = $pdo->prepare($sql);

?>
<main class="page-content">
    <div class="container-fluid">
        <h2>Danh mục sản phẩm
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addCategory">Thêm
                danh mục
            </button>
        </h2>
        <hr>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col" class="fit"><i class="fa fa-list"></i></th>
                <th scope="col" class="fit"><i class="fa fa-image"></i> Logo</th>
                <th scope="col"><i class="fa fa-book"></i> Tên danh mục</th>
                <th scope="col"><i class="fa fa-user"></i> Người tạo</th>
                <th scope="col" class="fit"><i class="fa fa-calendar"></i> Ngày tạo</th>
                <th scope="col" class="fit"><i class="fa fa-inbox"></i> Số sản phẩm</th>
                <th scope="col" class="fit"><i class="fa fa-cogs"></i> Hành động</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($stmt->execute()) {
                $index = 0;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $index += 1;
                    echo '<tr>
                            <th scope="row" class="fit">' . $index . '</th>
                            <td class="fit text-center"><img class="category-logo" src="' . $row['logo'] . '" alt="" /></td>
                            <td>' . $row['name'] . '</td>
                            <td>' . $row['fullName'] . '</td>
                            <td class="fit">' . date_format(date_create($row['createdAt'], timezone_open('Asia/Ho_Chi_Minh')), "H:i:s d/m/Y") . '</td>
                            <td class="text-right">' . $row['count'] . '</td>
                            <td class="fit">
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#updateCategory" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '"><i class="fa fa-pencil-alt"></i> Sửa</button>
                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteCategory" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '"><i class="fa fa-trash"></i> Xóa</button>
                            </td>
                         </tr>';
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Thêm danh mục</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/admin/index.php?action=category" enctype="multipart/form-data">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Tên danh mục</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Logo</label>
                            <div class="custom-file mb-3">
                                <input type="file" class="custom-file-input" id="categoryLogo" name="categoryLogo"
                                       lang="vi"
                                       title="Chọn tệp">
                                <label class="custom-file-label" for="categoryLogo">Chọn tệp (Tối
                                    đa: <?= ini_get('upload_max_filesize') ?>)</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" name="addCategory" id="addCategory" class="btn btn-primary">Thêm danh mục</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updateCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Sửa danh mục</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/admin/index.php?action=category" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input id="id" name="id" hidden aria-label="" value=""/>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Tên danh mục</label>
                            <input type="text" class="form-control" id="name" name="name" aria-label="">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Logo</label>
                            <div class="custom-file mb-3">
                                <input type="file" class="custom-file-input" id="categoryLogo" name="categoryLogo"
                                       lang="vi"
                                       title="Chọn tệp">
                                <label class="custom-file-label" for="categoryLogo">Chọn tệp (Tối
                                    đa: <?= ini_get('upload_max_filesize') ?>)</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" name="updateCategory" id="updateCategory" class="btn btn-warning">Sửa danh mục</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" tabindex="-1" role="dialog" id="deleteCategory">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xóa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="/admin/index.php?action=category">
                    <div class="modal-body">
                        <input id="id" name="id" hidden aria-label="" value="" />
                        <p>Bạn có chắn chắn muốn xóa không?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" id="deleteCategory" name="deleteCategory" class="btn btn-danger">Xóa</button>
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
