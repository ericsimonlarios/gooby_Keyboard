<?php
require_once "../PHP/dbcon.php";
require_once "../PHP/sessionChecker.php";
$duration = 86400;
if (isset($_SESSION['user'])) {
    if($_SESSION['customer_id'] == 15){
        echo '<script>window.location.href="admin-page.php"</script>';
    }  
    $sessionChecker = new sessionCheck();
    $sessionChecker->sessionDuration($duration);
    $_SESSION['start'] = time();
}
?>
<?php
include 'nav.php';
if(empty($_REQUEST['q'])){
    echo '<script>window.location.href= "categories.php"</script>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/index.css">
    <script src="https://cdn.tiny.cloud/1/6dzqh904fpp3doa9zcl4w76h2wrqp05lujtwjv8ueluj6hjb/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<?php
            $stmt = 'SELECT * FROM product where product_id="' . $_GET['q'] . '"';
            $result = $conn->query($stmt);
            if (!$result) {
                die("Fatal Error: Query failed");
            }
            $rows = $result->num_rows;
            if($rows==0){
                echo '<script>alert("Product does not exist!")</script>';
                echo '<script>window.location.href= "categories.php"</script>';
            }
            $row = $result->fetch_array(MYSQLI_ASSOC);
            
            $product_id         = $_GET['q'];
            $product_name       = htmlspecialchars($row['product_name']);
            $price              = htmlspecialchars($row['price']);
            $product_id         = htmlspecialchars($row['product_id']);
            $product_category   = htmlspecialchars($row['category']);
            $product_img        = htmlspecialchars($row['product_img']);
            $product_desc       = htmlspecialchars($row['product_desc']);
            $stock              = htmlspecialchars($row['stock']);
            if($stock == 0){
                $stock = "Out of stock";
            }
            $conn->close();
           
?>
<body>
    <div id="products-window" class="products-main-container">
        <div class="products-content-container categories-title-container">     
            <div class="products-category"><span><?php echo $product_name; ?></span></div>
            <div class="products-address"><span><a href="index.php">HOME</a></span> &nbsp; / &nbsp;<span class="product"><?php echo $product_category; ?></span></div>
        </div>
        <div class="product-main-wrapper">
            <div class="details-container">
                <div class="details-wrapper">
                    <div class="products-items-img-container">
                        <img src="<?php echo $product_img; ?>" alt="">
                    </div>
                    <div class="add-to-cart-container">
                        <div class="add-to-cart-wrapper">
                            <div class="product-price">
                                <p>Price: &#8369; <?php echo $price; ?></p>
                                <p>In stock:  <?php echo $stock; ?></p>
                            </div>

                            <form class="add-to-cart-forms" action='getProducts.php' method="POST">
                                <div class="number-input">
                                    <input type="button" value="-" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" class="minus"></input>
                                    <input class="quantity" min="0" name="quantity" value="1" max="<?php echo $stock; ?>" type="number">
                                    <input type="button" value="+" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="plus"></input>
                                </div>
                                <input name="product_name" type="hidden" value="<?php echo $product_name; ?>">
                                <input name="product_price" type="hidden" value="<?php echo $price; ?>">
                                <input name="product_id" type="hidden" value="<?php echo $product_id; ?>">
                                <input name="product_img" type="hidden" value="<?php echo $product_img;?>">
                                <input name="url" type="hidden" value="<?php echo $product_id; ?>">
                                <?php
                                    if($stock == 0){
                                        echo <<<END
                                        <div class="add-to-cart-button">
                                            <input type="button" value="Add to Cart" name="add-to-cart-submit" id="add-to-cart-submit" onclick="productDisabled('disabled')>
                                        </div>
                                        END;
                                    }else{
                                        echo <<<END
                                        <div class="add-to-cart-button">
                                            <input type="submit" value="Add to Cart" name="add-to-cart-submit" id="add-to-cart-submit" disabled onclick="productDisabled('enable')">
                                        </div>
                                        END;
                                    }
                                ?>
                               
                            </form>
                        </div>
                    </div>
                </div>
                <div class="description-wrapper details-wrapper">
                    <div class="left-description-tab">
                        <p>DESCRIPTION</p>
                    </div>
                    <div id="description-container" class="description-container">
                  
                        <?php
                            echo htmlspecialchars_decode(stripslashes($product_desc));
                        ?>  
                    
                    </div>
                </div>
            </div>
        </div>
       
    </div>
    
</body>
<script src="JS/index.js"></script>
<script>
    function productDisabled(value){
        if(value == 'disabled'){
            alert('Product is out of stock')
        }else{
            alert('Product is successfully added')
        }
    }
</script>
</html>