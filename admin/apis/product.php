<?php
if (isset($_POST['addProduct'])) {
    if (!isset($_POST['name']) || !isset($_POST['categoryId'])){
        $error_message = 'Vui lòng điền đủ thông tin';
    }
    //Retrieve the field values from our registration form.
    else if (!empty($_FILES["productLogo"]["name"])){
        $name = $_POST['name'];
        $categoryId = $_POST['categoryId'];
        $target_dir = "../assets/uploads/";
        $logo = 'Product_' . round(microtime(true) * 1000) . '_' . basename($_FILES["productLogo"]["name"]);;
        $target_file = $target_dir . $logo;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["productLogo"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["productLogo"]["tmp_name"], $target_file))
            {
                $logo = '/assets/uploads/' . $logo;
                $tz = 'Asia/Ho_Chi_Minh';
                $timestamp = time();
                try {
                    $dt = new DateTime("now", new DateTimeZone($tz));
                } catch (Exception $e) {
                } //first argument "must" be a string
                $dt->setTimestamp($timestamp); //adjust
                $createdAt = $dt->format("Y-m-d H:i:s");
                $createdBy = $_SESSION['user_id'];
                $sql = "INSERT INTO products (name, logo, categoryId, createdAt, createdBy) VALUES (:name, :logo, :categoryId, :createdAt, :createdBy)";
                $stmt = $pdo->prepare($sql);

                //Bind our variables.
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':logo', $logo);
                $stmt->bindValue(':categoryId', $categoryId);
                $stmt->bindValue(':createdAt', $createdAt);
                $stmt->bindValue(':createdBy', $createdBy);

                //Execute the statement and insert the new account.
                $result = $stmt->execute();

                //If the signup process is successful.
                if ($result) {
                    //What you do here is up to you!

                }
                else {
                    $error_message = 'Không thể thêm sản phẩm';
                }
            }
            else
            {
                $error_message = "Có lỗi xảy ra khi upload logo.";
            }
        } else {
            $error_message = "Có lỗi xảy ra khi upload logo.";
        }
    }
    else {
        $error_message = "Vui lòng chọn logo.";
    }
}
if (isset($_POST['updateProduct']) && isset( $_POST['id'] ) && is_numeric( $_POST['id'] ) && $_POST['id'] > 0) {
    if (!isset($_POST['name']) || !isset($_POST['categoryId'])){
        $error_message = 'Vui lòng điền đủ thông tin';
    }
    else {
        $name = $_POST['name'];
        $categoryId = $_POST['categoryId'];
        if (!empty($_FILES["productLogo"]["name"])){
            $target_dir = "../assets/uploads/";
            $logo = 'product_' . round(microtime(true) * 1000) . '_' . basename($_FILES["productLogo"]["name"]);;
            $target_file = $target_dir . $logo;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $check = getimagesize($_FILES["productLogo"]["tmp_name"]);
            if($check !== false) {
                if (move_uploaded_file($_FILES["productLogo"]["tmp_name"], $target_file))
                {
                    $logo = '/assets/uploads/' . $logo;
                    $sql = "UPDATE products SET name = :name, logo = :logo, categoryId = :categoryId WHERE id = :id";
                    $stmt = $pdo->prepare($sql);

                    //Bind our variables.
                    $stmt->bindValue(':name', $name);
                    $stmt->bindValue(':logo', $logo);
                    $stmt->bindValue(':categoryId', $categoryId);
                    $stmt->bindValue(':id', $_POST['id']);

                    //Execute the statement and insert the new account.
                    $result = $stmt->execute();

                    //If the signup process is successful.
                    if ($result) {
                        //What you do here is up to you!

                    }
                    else {
                        $error_message = 'Không thể sửa sản phẩm';
                    }
                }
                else
                {
                    $error_message = "Có lỗi xảy ra khi upload logo.";
                }
            } else {
                $error_message = "Có lỗi xảy ra khi upload logo.";
            }
        }
        else {
            $sql = "UPDATE products SET name = :name, categoryId = :categoryId WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            //Bind our variables.
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':categoryId', $categoryId);
            $stmt->bindValue(':id', $_POST['id']);

            //Execute the statement and insert the new account.
            $result = $stmt->execute();

            //If the signup process is successful.
            if ($result) {
                //What you do here is up to you!

            }
            else {
                $error_message = 'Không thể sửa sản phẩm';
            }
        }
    }
}
if( isset($_POST['deleteProduct']) )
{
    try {
        if(isset( $_POST['id'] ) && is_numeric( $_POST['id'] ) && $_POST['id'] > 0 )
        {
            $id = $_POST['id'];
            $stmt = $pdo->prepare( "DELETE FROM products WHERE id =:id" );
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            if( ! $stmt->rowCount() ) $error_message = 'Không thể xóa sản phẩm';
        }
        else
        {
            $error_message = 'Không thể xóa sản phẩm.';
        }
    }
    catch (Exception $e){
        $error_message = 'Không thể xóa sản phẩm.';
    }
}

if( isset($_POST['updatePrice']) )
{
    try {
        if(isset( $_POST['id'] ) && is_numeric( $_POST['id'] ) && $_POST['id'] > 0 )
        {
            $id = $_POST['id'];
            $sql = "INSERT INTO prices (productId, price, manufacturerProductId, manufacturerShopId, productUrl, manufacturerId, createdBy, createdAt)
                    values (:productId, :price, :manufacturerProductId, :manufacturerShopId, :productUrl, :manufacturerId, :createdBy, :createdAt)
                    ";
            $stmt = $pdo->prepare( $sql);
            $stmt->bindParam(':productId', $_POST['id']);
            $stmt->bindParam(':price', $_POST['price']);
            $manufacturerProductId = isset($_POST['manufacturerProductId']) ? $_POST['manufacturerProductId'] : null;
            $stmt->bindParam(':manufacturerProductId', $manufacturerProductId);
            $manufacturerShopId = isset($_POST['manufacturerShopId']) ? $_POST['manufacturerShopId'] : null;
            $stmt->bindParam(':manufacturerShopId', $manufacturerShopId);
            $stmt->bindParam(':productUrl', $_POST['productUrl']);
            $stmt->bindParam(':manufacturerId', $_POST['manufacturerId']);
            $stmt->bindParam(':createdBy', $_SESSION['user_id']);
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
            if( ! $stmt->rowCount() ) $error_message = 'Không thể cập nhật giá';
        }
        else
        {
            $error_message = 'Không thể cập nhật giá.';
        }
    }
    catch (Exception $e){
        $error_message = 'Không thể cập nhật giá.';
    }
}
if( isset($_POST['searchProduct'])  && isset($_POST['search'])){
    header('Location: index.php?action=product&search='.$_POST['search']);
}
