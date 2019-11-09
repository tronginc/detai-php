<?php
if (isset($_POST['addCategory'])) {
    //Retrieve the field values from our registration form.
    if (!empty($_FILES["categoryLogo"]["name"])){
        $name = $_POST['name'];
        $target_dir = "../assets/uploads/";
        $logo = 'category_' . round(microtime(true) * 1000) . '_' . basename($_FILES["categoryLogo"]["name"]);;
        $target_file = $target_dir . $logo;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["categoryLogo"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["categoryLogo"]["tmp_name"], $target_file))
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
                $sql = "INSERT INTO categories (name, logo, createdAt, createdBy) VALUES (:name, :logo, :createdAt, :createdBy)";
                $stmt = $pdo->prepare($sql);

                //Bind our variables.
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':logo', $logo);
                $stmt->bindValue(':createdAt', $createdAt);
                $stmt->bindValue(':createdBy', $createdBy);

                //Execute the statement and insert the new account.
                $result = $stmt->execute();

                //If the signup process is successful.
                if ($result) {
                    //What you do here is up to you!

                }
                else {
                    $error_message = 'Không thể thêm danh mục';
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
if (isset($_POST['updateCategory']) && isset( $_POST['id'] ) && is_numeric( $_POST['id'] ) && $_POST['id'] > 0) {
    //Retrieve the field values from our registration form.
    $name = $_POST['name'];
    if (!empty($_FILES["categoryLogo"]["name"])){
        $target_dir = "../assets/uploads/";
        $logo = 'category_' . round(microtime(true) * 1000) . '_' . basename($_FILES["categoryLogo"]["name"]);;
        $target_file = $target_dir . $logo;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["categoryLogo"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["categoryLogo"]["tmp_name"], $target_file))
            {
                $logo = '/assets/uploads/' . $logo;
                $sql = "UPDATE categories SET name = :name, logo = :logo WHERE id = :id";
                $stmt = $pdo->prepare($sql);

                //Bind our variables.
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':logo', $logo);
                $stmt->bindValue(':id', $_POST['id']);

                //Execute the statement and insert the new account.
                $result = $stmt->execute();

                //If the signup process is successful.
                if ($result) {
                    //What you do here is up to you!

                }
                else {
                    $error_message = 'Không thể sửa danh mục';
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
        $sql = "UPDATE categories SET name = :name WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        //Bind our variables.
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':id', $_POST['id']);

        //Execute the statement and insert the new account.
        $result = $stmt->execute();

        //If the signup process is successful.
        if ($result) {
            //What you do here is up to you!

        }
        else {
            $error_message = 'Không thể sửa danh mục';
        }
    }
}
if( isset($_POST['deleteCategory']) )
{
    if(isset( $_POST['id'] ) && is_numeric( $_POST['id'] ) && $_POST['id'] > 0 )
    {
        $id = $_POST['id'];
        $stmt = $pdo->prepare( "DELETE FROM categories WHERE id =:id" );
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if( ! $stmt->rowCount() ) $error_message = 'Không thể xóa danh mục';
    }
    else
    {
        $error_message = 'Không thể xóa danh mục';
    }
}
