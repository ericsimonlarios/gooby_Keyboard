<?php
    require_once "../PHP/sessionChecker.php";
    $duration = 86400;
    if(isset($_SESSION['user'])){   
        if($_SESSION['customer_id'] == 15){
            echo '<script>window.location.href="admin-page.php"</script>';
        }           
        $sessionChecker = new sessionCheck();
        $sessionChecker -> sessionDuration($duration);  
        $_SESSION['start']=time();         
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
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/index.css">
</head>
<body>
    <div class="categories-main-container products-main-container">
        <div class="products-content-container categories-title-container">
            <div class="categories-title"><span>CATEGORIES</span></div>
            <div class="categories-title-text"><span>Browse all products here at Gooby's Keyboard!</span></div>
        </div>
        <div class="categories-content-container">
            <div class="categories-kb">
                    <div class="category-name"><span>KEYBOARDS</span></div>
                    <div onclick="redirect('keyboard-tab')"><img src="../RES/kb-categories.jpg" alt=""></div>
            </div>
            <div class="categories-kb">
                    <div class="category-name"><span>SWITCHES</span></div>
                    <div onclick="redirect('switches-tab')"><img src="../RES/categories-sw.jpg" alt=""></div>
            </div>
            <div class="categories-kb">
                    <div class="category-name"><span>ACCESSORIES</span></div>
                    <div onclick="redirect('accessory-tab')"><img src="../RES/kb-categories.jpg" alt=""></div>
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
                <p>coyright &copy;2022 Gooby Keyboard
            </div>
        </footer>
    </div>
</body>
<script src="JS/index.js"></script>
</html>