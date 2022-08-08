<?php
require_once "../PHP/sessionChecker.php";
$duration = 86400;
if (isset($_SESSION['user'])) {
    $sessionChecker = new sessionCheck();
    $sessionChecker->sessionDuration($duration);
    $_SESSION['start'] = time();
}else{
    echo "<script>window.location.href='index.php';</script>";
}
?>
<?php
require_once 'nav.php';
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
    <div class="profile-main-container billing-main-container">
        <div class="account-title-container">
            <span class="page-title">MY ACCOUNT</span>
        </div>
        <div class="profile-main-wrapper billing-main-wrapper">
            <div class="profile-nav-wrapper">
                <div class="profile-nav-container">
                    <div class="profile-nav-header">
                        <div class="avatar-img"><img src="https://secure.gravatar.com/avatar/4658a77f714623239561837a42a8dde7?s=70&d=mm&r=g" alt="avatar"></div>
                        <?php
                        if (isset($_SESSION['user'])) {
                            echo " <div><span> " . $_SESSION['user'] . "</span></div>";
                        } else {
                            echo '<a href="login.php">Login</a>';
                        }
                        ?>
                    </div>
                    <div class="profile-nav-content" onclick="redirect('profile')"><span>Purchase History</span></div>
                    <div class="profile-nav-content profile-current-page"><span>Billing Info</span></div>
                    <div class="profile-nav-content" onclick="redirect('settings')"><span>Settings</span></div>
                    <div class="profile-nav-content" onclick="redirect('../PHP/logout')"><span>Logout</span></div>
                </div>
            </div>
            <?php
                $conn = new mysqli('localhost', 'root', '', 'gooby_keyboard');
                if (mysqli_connect_error()) {
                    echo 'MySQL Error: ' . mysqli_connect_error();
                } else {
                    $id = $_SESSION['customer_id'];
                    $stmt = 'SELECT * FROM billing_info where customer_id="' . $id . '"';
                    $result = $conn->query($stmt);
                    if (!$result) {
                        die("Fatal Error: Query failed");
                    }
                    $rows = $result->num_rows;

                    if($rows == 1){
                        echo "<script>window.location.href='edit-info.php';</script>";
                    }else{
                        echo <<<END
                            <div class="profile-content-container billing-content-container">
                                <div id="no-billing-info-container" class="no-billing-info-container">
                                    <div class="address-notice">
                                        <span>The following info provided will be used on the checkout page </span>
                                    </div>
                                    <div class="add-address-container" onclick="redirect('billing-info')">
                                        <div>
                                            <span>Add Address</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        END;
                    }
                }
            ?>
            
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
</body>

</html>