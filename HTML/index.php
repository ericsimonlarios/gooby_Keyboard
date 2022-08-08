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
    <div class="index-main-container">
        <!-- START -->
        <div class="slide-show-container">
            <div class="img-slide-show-container">
                <img class="img-slide-show" id="img-slide-show" src="../RES/awesome-bg-kb.jpg" alt="">
            </div>
            <div class="slide-show-previous-button" onclick="moveImg(-1)">
                <div class="previous-button-1 right"></div>
            </div>
            <div class="slide-show-next-button" onclick="moveImg(1)">
                <div class="previous-button-1 left"></div>
            </div>
            <div class="flickity-dots-container">
                <ul class="flickity-dots-ul">
                    <li class="flickity-dots current-img" onclick="changeImg(1)"></li>
                    <li class="flickity-dots" onclick="changeImg(2)"></li>
                    <li class="flickity-dots" onclick="changeImg(3)"></li>
                </ul>
            </div>
        </div>
        <div class="slide-show-container">
            <img class="img-slide-show" src="../RES/awesome-bg-kb.jpg" alt="">
        </div>
        <div class="flickity-dots-container">
                <ul class="flickity-dots-ul">
                    <li> Hello</li>
                    <li> Hello</li>
                    <li> Hello</li>
                </ul>
        </div>
        <!-- END -->
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