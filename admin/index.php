<?php
//if(!isset($_COOKIE["token"])) {
//    header("Location: login.php");
//    die();
//}

session_start();
$user = false;
if(!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])){
    //User not logged in. Redirect them back to the login.php page.
    header('Location: login.php');
    exit;
}
else {
    require '../server/config.php';
    //Retrieve the user account information for the given username.
    $sql = "SELECT id, username, fullName FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    //Bind value.
    $stmt->bindValue(':id', $_SESSION['user_id']);

    //Execute.
    $stmt->execute();

    //Fetch row.
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    //If $row is FALSE.
    if($user === false){
        //Could not find a user with that username!
        //PS: You might want to handle this error in a more user-friendly manner!
        header('Location: login.php');
        exit;
    }
    $error_message = '';
    require './apis/category.php';
    require './apis/manufacturer.php';
    require './apis/product.php';

}
if(!isset($_GET['action'])) {
    header('Location: index.php?action=category');
    exit;
}
echo '<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>';
echo '<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>';
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>';
echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">';
?>
<script src="./js/admin.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Responsive sidebar template with sliding effect and dropdown menu based on bootstrap 3">
    <title>Quản trị so sánh giá</title>
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link href="./css/admin.css" rel="stylesheet">


</head>

<body>
<div class="page-wrapper chiller-theme toggled">
    <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
        <i class="fas fa-bars"></i>
    </a>
    <nav id="sidebar" class="sidebar-wrapper">
        <div class="sidebar-content">
            <div class="sidebar-brand">
                <a href="#">TRANG QUẢN TRỊ</a>
                <div id="close-sidebar">
                    <i class="fas fa-times"></i>
                </div>
            </div>
            <div class="sidebar-header">
                <div class="user-pic">
                    <img class="img-responsive img-rounded" src="https://raw.githubusercontent.com/azouaoui-med/pro-sidebar-template/gh-pages/src/img/user.jpg"
                         alt="User picture">
                </div>
                <div class="user-info">
          <span class="user-name">
            <strong><?php echo $user['fullName'] ?></strong>
          </span>
                    <span class="user-role">Quản trị viên</span>
                    <span class="user-status">
            <i class="fa fa-circle"></i>
            <span>Đang hoạt động</span>
          </span>
                </div>
            </div>
            <!-- sidebar-header  -->
            <div class="sidebar-search">
                <div>
                    <div class="input-group">
                        <input type="text" class="form-control search-menu" placeholder="Tìm kiếm">
                        <div class="input-group-append">
              <span class="input-group-text">
                <i class="fa fa-search" aria-hidden="true"></i>
              </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- sidebar-search  -->
            <div class="sidebar-menu">
                <ul>
                    <li class="header-menu">
                        <span>Sản phẩm</span>
                    </li>
                    <li class="<?php if(isset($_GET['action']) && $_GET['action'] == 'category') echo "has-active" ?>">
                        <a href="?action=category">
                            <i class="fa fa-book"></i>
                            <span>Danh mục</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($_GET['action']) && $_GET['action'] == 'manufacturer') echo "has-active" ?>">
                        <a href="?action=manufacturer">
                            <i class="fa fa-calendar"></i>
                            <span>Nhà cung cấp</span>
                        </a>
                    </li>
                    <li class="<?php if(isset($_GET['action']) && $_GET['action'] == 'product') echo "has-active" ?>">
                        <a href="?action=product">
                            <i class="fa fa-folder"></i>
                            <span>Sản phẩm</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- sidebar-menu  -->
        </div>
    </nav>
    <!-- sidebar-wrapper  -->
    <?php
    if(isset($_GET['action'])) {
        if ($_GET['action'] == 'category'){
            include "./pages/category.php";
        }
        if ($_GET['action'] == 'manufacturer'){
            include "./pages/manufacturer.php";
        }
        if ($_GET['action'] == 'product'){
            include "./pages/product.php";
        }
    }
    ?>
    <!-- page-content" -->
</div>
<!-- page-wrapper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<div id="snackbar"><?php echo $error_message?></div>
</body>
<script>
  function showErrorMessage() {
    // Get the snackbar DIV
    const x = document.getElementById("snackbar");
    // Add the "show" class to DIV
    x.className = "show";
    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
  }
</script>
<?php
if ($error_message != ''){
    echo '<script type="text/javascript">',
    'showErrorMessage();',
    '</script>'
    ;
}
?>
</html>
