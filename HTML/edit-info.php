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
                        <div class="avatar-img"><img src="../RES/avatar.png" alt="avatar"></div>
                        <?php
                        if (isset($_SESSION['user'])) {
                            echo " <div><span> " . $_SESSION['user'] . "</span></div>";
                        } else {
                            echo '<a href="login.php">Login</a>';
                        }
                        ?>
                    </div>
                    <div class="profile-nav-content" onclick="redirect('profile')"><span>Order History</span></div>
                    <div class="profile-nav-content profile-current-page"><span>Billing Info</span></div>
                    <div class="profile-nav-content" onclick="redirect('settings')"><span>Settings</span></div>
                    <div class="profile-nav-content" onclick="redirect('../PHP/logout')"><span>Logout</span></div>
                </div>
            </div>
            <div class="profile-content-container billing-content-container">
                <div id="on-billing-info-container" class="on-billing-info-container">
                    
                    <div class="address-notice">
                        <span>The following info provided will be used on the checkout page </span>
                    </div>
                    <div class="add-address-container" onclick="redirect('edit-billing-info-form')">
                        <div>
                            <span>Edit Address</span>
                        </div>
                    </div>
                    <div class="address-content-container error-handler-container">
                        <?php
                        if (isset($_GET['status'])) {
                            $message = $_GET['msg'];
                            if ($message == "Billing info successfully updated") {
                                echo "<div class='successful-handler'><span>" . $message . "</span></div>";
                            } 
                        }
                        ?>
                    </div>
                    
                    <div class="address-notice address-title">
                        <span>Billing Info</span>
                    </div>
                    <div class="address-content-container">
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

                            for ($j = 0; $j < $rows; ++$j) {
                                $row = $result->fetch_array(MYSQLI_NUM);

                                for ($k = 1; $k < 11; ++$k) {
                                    echo "<div class='address-content'>";
                                    echo htmlspecialchars($row[$k]);
                                    echo "</div>";
                                }
                            }
                        }
                        ?>
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
</body>

</html>