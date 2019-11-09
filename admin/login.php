<?php
include("../server/config.php");
session_start();
$error_message = '';
if(isset($_SESSION['user_id']) && isset($_SESSION['logged_in'])){
    //User not logged in. Redirect them back to the login.php page.
    header('Location: index.php');
    exit;
}
if(isset($_POST['login'])){
    $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;

    //Retrieve the user account information for the given username.
    $sql = "SELECT id, username, password FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);

    //Bind value.
    $stmt->bindValue(':username', $username);

    //Execute.
    $stmt->execute();

    //Fetch row.
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    //If $row is FALSE.
    if($user === false){
        //Could not find a user with that username!
        //PS: You might want to handle this error in a more user-friendly manner!
        $error_message = 'Tài khoản hoặc mật khẩu không chính xác';
    } else{
        //User account found. Check to see if the given password matches the
        //password hash that we stored in our users table.
        //If $validPassword is TRUE, the login has been successful.
        if($user['password'] == md5($passwordAttempt)){

            //Provide the user with a login session.
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['logged_in'] = time();

            //Redirect to our protected page, which we called home.php
            header('Location: index.php');
            exit;

        } else{
            //$validPassword was FALSE. Passwords do not match.
            $error_message = 'Tài khoản hoặc mật khẩu không chính xác';
            echo '<script type="text/javascript">',
            'showErrorMessage();',
            '</script>'
            ;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Đăng nhập hệ thống</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(images/bg-01.jpg);">
					<span class="login100-form-title-1">
						ĐĂNG NHẬP VÀO HỆ THỐNG
					</span>
				</div>

				<form class="login100-form validate-form" action="login.php" method="post">
					<div class="wrap-input100 validate-input m-b-26" data-validate="Tài khoản bắt buộc phải nhập">
						<span class="label-input100">Tài khoản</span>
						<input class="input100" autocomplete="false" type="text" name="username" id="username" placeholder="Nhập tài khoản">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate = "Mật khẩu bắt buộc phải nhập">
						<span class="label-input100">Mật nhẩu</span>
						<input class="input100" type="password" name="password" id="password" placeholder="Nhập mật khẩu">
						<span class="focus-input100"></span>
					</div>

					<div class="flex-sb-m w-full p-b-30">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Nhớ đăng nhập
							</label>
						</div>

						<div>
							<a href="#" class="txt1">
								Quên mật khẩu?
							</a>
						</div>
					</div>

					<div class="container-login100-form-btn">
						<button type="submit" name="login" class="login100-form-btn">
							ĐĂNG NHẬP
						</button>
					</div>
				</form>
			</div>
		</div>
        <div id="snackbar"><?php echo $error_message?></div>
	</div>

<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>
    <script type="text/javascript">
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
</body>
</html>
