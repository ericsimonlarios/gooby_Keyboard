<?php
require_once "billing-info-contr.php";
require_once "order-process-contr.php";

if (isset($_POST['order-checkout-submit'])) {
    $product_array  = $_POST['product_id'];
    print_r($product_array);
    $customer_id    = $_POST['customer_id'];
    $payment_mode   = $_POST['payment_mode'];
    $quantity       = $_POST['quantity'];
    $total          = $_POST['total'];
    date_default_timezone_set('Asia/Manila');
    $date           = date('Y/m/d');
    $order_status   = "Pending";
    // billing info
    $first_name     = $_POST['first_name'];
    $last_name      = $_POST['last_name'];
    $mob_num        = $_POST['mob_num'];
    $country        = $_POST['country'];
    $region         = $_POST['region'];
    $province       = $_POST['province'];
    $post_code      = $_POST['post_code'];
    $city           = $_POST['city'];
    $brgy           = $_POST['brgy'];
    $street_address = $_POST['street_address'];
    $href           = $_POST['url'];

    $addressExist   = $_POST['addressExist'];

    $billingInfo = new billingInfoContr($first_name, $last_name, $mob_num, $country, $region, $province, $post_code, $city,  $brgy, $street_address, $href);
    // $billingInfo->emptyInput();
    // $billingInfo -> invalidInput();
    if($addressExist == 'yes'){
        $billingInfo->checkOutAddress();
    }
    else{
        $billingInfo->addAddress();
    }

    $orderProcess = new orderProcessContr($product_array, $customer_id, $payment_mode, $quantity, $date, $order_status, $total);
    $orderProcess->insertOrder();
}

if (isset($_POST['add-product-submit'])) {

    $product_name   =   $_POST['product_name'];
    $category       =   $_POST['product_cat'];
    $price          =   $_POST['product_price'];
    $product_stock  =   $_POST['product_stock'];
    $product_desc   =   $_POST['product_desc'];
    date_default_timezone_set('Asia/Manila');
    $date           =   date('Y/m/d');
    $url            =   "admin-add-product-page";
    $targetDir      =   "../PRODUCT-IMG/";
    $product_img    =   basename($_FILES['product_img']['name']);
    $targetFilePath =   $targetDir;
    $filetype       =   pathinfo($targetFilePath . $product_img, PATHINFO_EXTENSION);
    $changeImgName  =   $targetFilePath . $product_name . "." . $filetype;
    $allowedTypes   =   array('jpg','jpeg','png', 'gif', 'tif');
    if (in_array($filetype, $allowedTypes)) {
        move_uploaded_file($_FILES['product_img']['tmp_name'], $changeImgName);
    } else {
        header('location: ../HTML/admin-add-product-page.php?status=error&msg=Only JPG, PNG, GIF, AND TIF extensions are allowed');
        die();
    }

    $addProducts    =   new addProductsContr($product_name, $category, $price, $product_stock, $product_desc, $changeImgName, $date, $url);

    $addProducts->emptyInput();
    $addProducts->invalidInput();
    $addProducts->productExist();
    $addProducts->addProduct();
}

if (isset($_POST['update-product-submit'])) {

    $product_name   =   $_POST['product_name'];
    $category       =   $_POST['product_cat'];
    $price          =   $_POST['product_price'];
    $product_stock  =   $_POST['product_stock'];
    $product_desc   =   $_POST['product_desc'];
    date_default_timezone_set('Asia/Manila');
    $date           =   date('Y/m/d');
    $prod_id        =   $_POST['prod_id'];
    echo $prod_id;
    $url            =   "admin-edit-product-page";
    $changeImgName;
    if ($_FILES['product_img']['size'] == 0) {
        $targetDir      =   "../PRODUCT-IMG/";
        $changeImgName = $_POST['current_product_img'];
        $changeName  = substr(strrchr($changeImgName, '.'), 1);
        $renameImg   = $targetDir . $product_name . "." . $changeName;
        $allowedTypes   =   array('jpg','jpeg', 'png', 'gif', 'tif');
        if (in_array($changeName, $allowedTypes)) {
            rename($changeImgName, $renameImg);
        } else {
            header('location: ../HTML/admin-edit-product-page.php?status=error&msg=Only JPG, PNG, GIF, AND TIF extensions are allowed');
            die();
        }
        $changeImgName = $renameImg;
    } else {
        $conn = new mysqli('localhost', 'root', '', 'gooby_keyboard');
        if (mysqli_connect_error()) {
            echo 'MySQL Error: ' . mysqli_connect_error();
        }
        $query  =   "SELECT * FROM product WHERE product_id=" . '"' . $prod_id . '"';
        $result =   $conn->query($query);
        if(!$result){
            $error = $conn->errno . " " . $conn->error;
            echo $error;
        }
        $row    =   $result->fetch_array(MYSQLI_ASSOC);
        $currentProdName    =   htmlspecialchars($row['product_name']);
        if($currentProdName === $product_name){
            $targetDir      =   "../PRODUCT-IMG/";
            $product_img    =   basename($_FILES['product_img']['name']);
            $targetFilePath =   $targetDir;
            $filetype       =   pathinfo($targetFilePath . $product_img, PATHINFO_EXTENSION);
            $changeImgName  =   $targetFilePath . $product_name . "." . $filetype;
            $allowedTypes   =   array('jpg','jpeg', 'png', 'gif', 'tif');
            if (in_array($filetype, $allowedTypes)) {
                move_uploaded_file($_FILES['product_img']['tmp_name'], $changeImgName);
            } else {
                header('location: ../HTML/admin-edit-product-page.php?status=error&msg=Only JPG, PNG, GIF, AND TIF extensions are allowed');
                die();
            }
        }else{
            $targetDir      =   "../PRODUCT-IMG/";
            $product_img    =   basename($_FILES['product_img']['name']);
            $targetFilePath =   $targetDir;
            $filetype       =   pathinfo($targetFilePath . $product_img, PATHINFO_EXTENSION);
            $changeImgName  =   $targetFilePath . $product_name . "." . $filetype;
            $allowedTypes   =   array('jpg','jpeg', 'png', 'gif', 'tif');
            if (in_array($filetype, $allowedTypes)) {
                move_uploaded_file($_FILES['product_img']['tmp_name'], $changeImgName);
            } else {
                header('location: ../HTML/admin-edit-product-page.php?status=error&msg=Only JPG, PNG, GIF, AND TIF extensions are allowed');
                die();
            }
            unlink($_POST['current_product_img']);
        }      
    }

    $editProducts    =   new editProductContr($prod_id, $product_name, $category, $price, $product_stock, $product_desc, $changeImgName, $date, $url);
    $editProducts->emptyInput();
    $editProducts->invalidInput();
    $editProducts->editProduct();
}
