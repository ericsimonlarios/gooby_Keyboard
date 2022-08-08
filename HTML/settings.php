<?php
require_once "../PHP/sessionChecker.php";
$duration = 86400;
if (isset($_SESSION['user'])) {
    if($_SESSION['customer_id'] == 15){
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
    <div class="profile-main-container">
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
                    <div class="profile-nav-content" onclick="redirect('profile')">Order History</div>
                    <div class="profile-nav-content" onclick="redirect('add-address')">Billing Info</div>
                    <div class="profile-nav-content profile-current-page">Settings</div>
                    <div class="profile-nav-content" onclick="redirect('../PHP/logout')">Logout</div>
                </div>
            </div>
            <div class="profile-content-container">
                <div class="profile-content-history-checker settings-content-notice">
                    <span>You can change your account details here</span>
                </div>

                <div class="settings-form-container">
                    <div class="settings-form-wrapper">
                        <form action="../PHP/settings-info-handler.php" method="POST">
                            <?php
                                if (isset($_GET['status'])) {
                                    $message = $_GET['msg'];
                                    if($message == "Account details successfully updated"){
                                        echo "<div class='successful-handler'><span>" . $message . "</span></div>";
                                    }else{
                                        echo "<div class='error-handler'><span>" . $message . "</span></div>";
                                    }
                                    
                                }
                            ?>
                           
                            <?php
                            $conn = new mysqli('localhost', 'root', '', 'gooby_keyboard');
                            if (mysqli_connect_error()) {
                                echo 'MySQL Error: ' . mysqli_connect_error();
                            } else {
                                $id = $_SESSION['customer_id'];
                                $stmt = 'SELECT * FROM customer where customer_id="' . $id . '"';
                                $result = $conn->query($stmt);
                                if (!$result) {
                                    die("Fatal Error: Query failed");
                                }
                                $rows = $result->num_rows;

                                for ($j = 0; $j < $rows; ++$j) {
                                    $row = $result->fetch_array(MYSQLI_ASSOC);

                                    $username = strval(htmlspecialchars($row['username']));
                                    $email = strval(htmlspecialchars($row['email']));

                                    echo <<<END
                                        <div class="billing-info-textbox">
                                            <label for="">Username<span>*</span></label>
                                            <input type="text" value="$username" name="user_settings" id="user_settings">
                                        </div>
                                        <div class="billing-info-textbox">
                                            <label for="">Email<span>*</span></label>
                                            <input type="email" value="$email" name="email_settings" id="email_settings">
                                        </div>
                                        END;
                                }
                            }
                            ?>
                            <span class="password-change-text billing-info-textbox">Password Change</span>

                            <div class="billing-info-textbox">
                                <label for="">Current Password (Leave blank to leave unchanged)</label>
                                <input type="password" name="pass_settings" id="pass_settings">
                            </div>
                            <div class="billing-info-textbox">
                                <label for="">New Password (Leave blank to leave unchanged)</label>
                                <input type="password" name="newpass_settings" id="newpass_settings">
                            </div>
                            <div class="billing-info-textbox">
                                <label for="">Confirm Password</label>
                                <input type="password" name="confirm_pass_settings" id="confirm_pass_settings">
                            </div>
                            <div class="billing-info-textbox billing-info-submit settings-info-submit margin-bottom-70px">
                                <input type="submit" id="submit_settings" value="Save Changes" name="submit_settings">
                            </div>
                        </form>
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

</html>