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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
</head>

<body>
    <div id="products-window" class="products-main-container cart-page-main-container order-container">
        <div class="cart-page-header">
            <a href="cart.php">SHOPPING CART</a>
            <span> > </span>
            <a href="checkout.php">CHECKOUT DETAILS</a>
            <span> > </span>
            <p class="current-page-cart">ORDER COMPLETE</p>
        </div>
        <div class="order-complete-container">
            <div>
                <span style="color:green;font-size:35px;">
                    <i stlye="margin-top:40px;" class="fas fa-check-circle"></i>
                </span>
                <span style="font-size:35px;">
                    Order has been added successfully!
                </span>
            </div>
            <div class="add-address-container" onclick="redirect('categories')">
                <span>Back to Shopping</span>
            </div>
        </div>
        <footer>
            <div class="footer-content">
                <h3>Gooby Keyboard </h3>
                <p>Thank you for shopping at Gooby Keyboard PH.

                    Upon checking out, the estimated time of arrival will be shown. Disclaimer, not all estimation are accurate as it may differ to the shipping company that will be assigned.

                    We at Gooby Keyboard assures to give the best and fast effort that are within our capabilities. We also assure the security and the condition of your item as every item that we offer shows our passion in this hobby.</p>
            </div>
            <div class="footer-bottom">
                <p>Copyright &copy;2022 Gooby Keyboard
            </div>
        </footer>
    </div>  
</body>
<script src="https://kit.fontawesome.com/5813fc1f75.js" crossorigin="anonymous"></script>

</html>