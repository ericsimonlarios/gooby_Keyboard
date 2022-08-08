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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../CSS/index.css">
</head>

<body>
    <div id="products-window" class="products-main-container cart-page-main-container order-container commissions-main-container">      
        <div class="order-complete-container commissions-container">
            <div>             
                <span style="font-size:35px;">
                   Commissions are currently closed for now :(
                </span>
            </div>
            <div class="add-address-container" onclick="redirect('categories')">
                <span>Back to Shopping</span>
            </div>
        </div>
    </div>  
</body>
<script src="https://kit.fontawesome.com/5813fc1f75.js" crossorigin="anonymous"></script>

</html>