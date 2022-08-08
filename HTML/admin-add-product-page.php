<?php
require_once "../PHP/sessionChecker.php";
$duration = 86400;
if (isset($_SESSION['user'])) {
    $sessionChecker = new sessionCheck();
    $sessionChecker->sessionDuration($duration);
    $_SESSION['start'] = time();
} else {
    echo "<script>window.location.href='index.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gooby's Keyboard</title>
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
    <link rel="mask-icon" href="../safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <script src="https://cdn.tiny.cloud/1/4s91fwlzrik4h66tkj6zs1bo0dz7ttv2rn83jf5i3ak7ck0k/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
</head>

<body>
    <div class="main-admin-page  product-admin-page">
        <div class="admin-nav-container">
            <div class="admin-nav-container-wrapper">
                <div class="admin-nav-content" onclick="redirect('admin-page')">DASHBOARD</div>
                <div class="admin-nav-content" onclick="redirect('admin-order-page')">ORDERS</div>
                <div class="admin-nav-content admin-current-page"  onclick="redirect('admin-product-page')">PRODUCTS</div>
                <div class="admin-nav-content" onclick="redirect('admin-users')">USERS</div>
                <div class="admin-nav-content" onclick="redirect('../PHP/logout')">LOGOUT</div>
            </div>
        </div>
        <div class="admin-main-view-container">
            <div class="dashboard-header dashboard-table-header">
                <span>ADD NEW PRODUCT</span>
            </div>
            <div id="order-content-container" class="dashboard-content-container product-content-container">
                <form action="../PHP/order-process.php" method="POST" enctype="multipart/form-data" id="add-product-form" class="add-product-form">
                    <div class="add-product-input-box">
                        <label for="product_name">Product Name</label>
                        <input type="text" id="product_name" name="product_name" placeholder="ENTER PRODUCT NAME HERE">
                    </div>
                    <div class="add-product-input-box row">
                        <div class="add-product-input-box box-container">
                            <label for="product_cat">Category</label>
                            <select name="product_cat" id="product_cat">
                                <option value="" selected hidden disabled>CHOOSE A CATEGORY</option>
                                <option value="Keyboard">KEYBOARD</option>
                                <option value="Switches">SWITCHES</option>
                                <option value="Accessory">ACCESSORY</option>
                            </select>
                        </div>                 
                    </div>

                    <div class="add-product-input-box row">
                        <div class="add-product-input-box box-container price-container">
                                <label for="product_price">Price</label>
                                <input type="number" id="product_price" name="product_price" placeholder="ENTER PRODUCT PRICE HERE">
                        </div>

                        <div class="add-product-input-box ">
                            <label for="product_stock">Stock</label>
                            <div class="number-input add-product-input">   
                                <input type="button" value="-" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" class="minus"></input>
                                <input class="quantity" min="1" name="product_stock" value="1" type="number">
                                <input type="button" value="+" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="plus"></input>
                             </div>
                        </div>      
                    </div>

                    <div class="add-product-input-box insert-img-product">
                        <label for="product_img">Select Image file to upload</label>
                        <input accept="image/*" type="file" name="product_img" id="product_img" class="product_img">
                        <span>Preview :</span>
                        <img id="product_img_container" src="../RES/placeholder-image.png" alt="">
                    </div>
                    <div class="add-product-input-box">
                        <label for="product_desc">Product Description</label>
                        <textarea name="product_desc" id="product_desc" cols="30" rows="30" form="add-product-form"></textarea>
                    </div>
                    <input class="add-product-button add-product-submit" type="submit" value="SAVE" name="add-product-submit">
                </form>
            </div>

        </div>
    </div>
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'a11ychecker advcode casechange export formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
            toolbar: 'a11ycheck addcomment showcomments casechange checklist code export formatpainter pageembed permanentpen table',
            toolbar_mode: 'floating',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            
        });
    </script>
</body>
<script>
    var product_img = document.getElementById('product_img');
    product_img.onchange = function(event) {
        const [file] = product_img.files
        if (file) {
            document.getElementById('product_img_container').src = URL.createObjectURL(file);
        }
    }
</script>
<script src="JS/index.js"></script>
</html>