<?php
require_once "../PHP/sessionChecker.php";
$duration = 86400;
if (isset($_SESSION['user'])) {
    if ($_SESSION['customer_id'] == 15) {
        echo '<script>window.location.href="admin-page.php"</script>';
    }
    $sessionChecker = new sessionCheck();
    $sessionChecker->sessionDuration($duration);
    $_SESSION['start'] = time();
}

?>
<?php
require 'nav.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>>GoobyTypes</title>
    <link rel="stylesheet" href="../CSS/index.css">
</head>

<body>
    <?php
         if (isset($_GET['status'])) {
            $status = $_GET['status'];
            $message = $_GET['msg'];
            echo "<script>alert('$message')</script>";
            echo "<script>window.location.href='admin-product-page.php'</script>";
        }
    ?>
    <div class="index-main-container contact-us-container">
       <div class="contacts-container">
            <div class="contacts-form">
                <div class="contact-us-header">Contact Us</div>
                <form action="getProducts.php" method="POST">
                    <div class="billing-info-textbox contact-us-textbox">
                        <input type="text" name="name" placeholder="Name">
                    </div> 
                    <div class="billing-info-textbox contact-us-textbox"><input type="email" name="email" placeholder="Email account"></div>
                    <div class="billing-info-textbox contact-us-textbox"><input type="text" name="subj" placeholder="Subject"></div>
                    <div class="billing-info-textbox contact-us-textbox"> <textarea name="msg" id="" cols="30" rows="10" placeholder="Message here"></textarea></div>
                    <div class="billing-info-submit billing-info-textbox contact-us-textbox"><input type="submit" name="contactSubmit" value="Submit"></div>
                </form>
            </div>
            <div class="contacts-half">
                <img src="../RES/contact-us-bg.png" alt="">
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
<script src="JS/index.js"></script>

</html>