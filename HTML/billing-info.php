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
require 'nav.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/index.css">
    <title>Document</title>
</head>

<body class="billing-info-body">
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
                    <div class="profile-nav-content profile-current-page" onclick="redirect('add-address')"><span>Billing Info</span></div>
                    <div class="profile-nav-content" onclick="redirect('settings')"><span>Settings</span></div>
                    <div class="profile-nav-content" onclick="redirect('../PHP/logout')"><span>Logout</span></div>
                </div>
            </div>
            <div class="profile-content-container billing-content-container">
                <div id="billing-info-content-container" class="billing-info-content-container">
                    <div class="billing-text-title"> <span>Billing Info</span> </div>
                    <div class="billing-info-wrapper">
                        <form class="billing-info-form" action="../PHP/billing-info-handler.php" method="POST">
                            <?php
                            if (isset($_GET['status'])) {
                                $message = $_GET['msg'];
                                if ($message == "Account details successfully updated") {
                                    echo "<div class='successful-handler'><span>" . $message . "</span></div>";
                                } else {
                                    echo "<div class='error-handler'><span>" . $message . "</span></div>";
                                }
                            }
                            ?>
                            <div class="billing-info-textbox name-info-textbox">
                                <div>
                                    <label for="">First name <span>*</span></label>
                                    <input type="text" id="first_name" placeholder="First Name" name="first_name" list="autocompleteOff" autocomplete="off">
                                </div>
                                <div>
                                    <label for="">Last name<span>*</span></label>
                                    <input type="text" id="last_name" placeholder="Last Name" name="last_name" list="autocompleteOff" autocomplete="off">
                                </div>
                            </div>
                            <div class="billing-info-textbox">
                                <label for="">Mobile number<span>*</span></label>
                                <input type="number" id="mob_num" placeholder="Enter your mobile number" maxlength='11' name="mob_num" autocomplete="off">
                            </div>
                            <div class="billing-info-textbox">
                                <label for="">Country<span>*</span></label>
                                <input type="text" value="Philippines" id="country" name="country">
                            </div>
                            <div id="regionSelect">
                                <div class="billing-info-textbox">
                                    <label for="">Region<span>*</span></label>
                                    <select id="region" name="region" onchange="getRegions(this.value, 'region', '')">
                                        <option value="" selected hidden> Choose a Region</option>
                                        <?php
                                        $conn = new mysqli('localhost', 'root', '', 'gooby_keyboard');
                                        if (mysqli_connect_error()) {
                                            echo 'MySQL Error: ' . mysqli_connect_error();
                                        }
                                        $regionSQL = "SELECT * FROM refregion";
                                        $regionResult   = $conn->query($regionSQL);
                                        if (!$regionResult) {
                                            $error = $conn->errno . " " . $conn->error;
                                            echo $error;
                                        }
                                        $regionRows = $regionResult->num_rows;
                                        for ($i = 0; $i < $regionRows; ++$i) {
                                            $returnedRows   = $regionResult->fetch_array(MYSQLI_ASSOC);
                                            $regionDesc     = htmlspecialchars($returnedRows['regDesc']);
                                            $regionCode     = htmlspecialchars($returnedRows['regCode']);
                                            echo <<<END
                                                 <option value="$regionCode">$regionDesc</option>
                                                END;
                                        }
                                        ?>
                                    </select>
                                </div>                            
                                    <div class="billing-info-textbox">
                                        <label for="">Province<span>*</span></label>
                                        <select id="province" name="province" onchange="getRegions(this.value, 'province', '')">
                                            <option value="" hidden> Choose a Province</option>
                                        </select>
                                    </div>
                                    <div class="billing-info-textbox">
                                        <label for="">Town/City<span>*</span></label>
                                        <select id="city" name="city">
                                            <option value="" selected> Choose a City</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="billing-info-textbox">
                                    <label for="">Post Code<span>*</span></label>
                                    <input type="number" id="post_code" placeholder="Enter your Post Code" maxlength='4' name="post_code">
                                </div>
                                <div class="billing-info-textbox">
                                    <label for="">Baranggay<span>*</span></label>
                                    <input type="text" id="brgy" placeholder="Enter your Baranggay" autocomplete="off" name="brgy">
                                </div>
                                <div class="billing-info-textbox">
                                    <label for="">Street Address<span>*</span></label>
                                    <input type="text" id="street_address" placeholder="Enter your Street Address" name="street_address">
                                </div>
                                <input type="hidden" name="hiddenValue" value="billing-info">
                                <div class="billing-info-textbox billing-info-submit margin-bottom-70px">
                                    <input type="submit" id="submit_billing" value="Save" name="submit_billing">
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
<script src="JS/index.js"></script>
<script>
    function getRegions(selectValue, id, regCode, provCode) {

        var xml = new XMLHttpRequest();
        xml.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('regionSelect').innerHTML = this.responseText;
            }
        }
        xml.open("GET", "getProducts.php?selectValue= " + selectValue + '&selectID=' + id + '&regCode= ' + regCode + '&provCode=' + provCode, true)
        xml.send();
    }
</script>

</html>