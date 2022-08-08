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
} else {
    echo "<script>window.location.href='index.php';</script>";
}
?>
<?php
require_once "nav.php";
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

<body onload="getOrders()">
    <div class="profile-main-container">
        <div class="account-title-container profile-title-container">
            <span class="page-title">MY ACCOUNT</span>
        </div>
        <div class="profile-main-wrapper">
            <div class="profile-nav-wrapper">
                <div class="profile-nav-container">
                    <div class="profile-nav-header">
                        <div class="avatar-img"><img src="../RES/avatar.png" alt="avatar"></div>
                        <?php
                        if (isset($_SESSION['user'])) {
                            echo " <div><span> " . $_SESSION['user'] . "</span></div>";
                        } else {
                            echo '<a href="login.php">Login</a>';
                        }
                        ?>

                    </div>
                    <div class="profile-nav-content profile-current-page">Order History</div>
                    <div class="profile-nav-content" onclick="redirect('add-address')">Billing Info</div>
                    <div class="profile-nav-content" onclick="redirect('settings')">Settings</div>
                    <div class="profile-nav-content" onclick="redirect('../PHP/logout')">Logout</div>
                </div>
            </div>
            <div id="profile-content-container" class="profile-content-container">
                <div class="profile-content-purchase-container  profile-nav-header">
                    <div class="profile-content-purchase-container  profile-nav-header">
                        <div class="purchase-header">
                            <span>Order History</span>
                        </div>
                        <?php
                              if (isset($_GET['status'])) {
                                $status = $_GET['status'];
                                $message = $_GET['msg'];
                                echo <<<END
                                <div class="error-container">
                                 <div class='error-handler'><span>$message</span></div>
                                </div>
                                END;
                            }
                        ?>
                        <div id="purchase-history-table-container" class="purchase-history-table-container">
                           
                                        <!-- display ordered products here using AJAX -->                                  
                           
                        </div>
                    </div>
                </div>
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
<?php
    $pages;
    if(!isset($_GET['pages'])){
        $pages = 1;
    }else{
        $pages = $_GET['pages'];
    }
    
?>
<script>
    var pages = '<?php echo $pages; ?>'
    function getOrders() {        
        var xml = new XMLHttpRequest();
        xml.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('purchase-history-table-container').innerHTML = this.responseText;
            }
        };
        xml.open("GET", "getProducts.php?getOrders=" + pages, true);
        xml.send();
    }

    function checkItem($id) {
        window.location.href = "products.php?q=" + $id;
    }
</script>
<script src="JS/index.js"></script>

</html>