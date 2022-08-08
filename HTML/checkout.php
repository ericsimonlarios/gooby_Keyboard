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
    <script type="text/javascript">
        function disableBack() { window.history.forward(); }
        setTimeout("disableBack()", 0);
        window.onunload = function () { null };
    </script>
</head>

<body>
    <div id="products-window" class="products-main-container cart-page-main-container">
        <div class="cart-page-header">
            <a href="cart.php">SHOPPING CART</a>
            <span> > </span>
            <a href="checkout.php" class="current-page-cart">CHECKOUT DETAILS</a>
            <span> > </span>
            <p>ORDER COMPLETE</p>
        </div>
        <div class="checkout-page-main-body">
            <form class="checkout-form" action="../PHP/order-process.php" method="POST">
                <div class="checkout-address">
                    <?php
                    if (isset($_SESSION['user'])) {
                        if (empty($_SESSION['user'])) {
                            echo <<<END
                                <div class="cart-empty">
                                    <div>
                                        <div class="cart-empty-text">
                                            Please login to proceed to purchase
                                        </div>
                                        <div class="add-address-container" onclick="redirect('login')">
                                            <span>Login</span>
                                        </div>
                                    </div>      
                                </div>
                                END;
                        } else {
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
                                    $row = $result->fetch_array(MYSQLI_ASSOC);

                                    $first_name = strval(htmlspecialchars($row['first_name']));
                                    $last_name = strval(htmlspecialchars($row['last_name']));
                                    $mobile_number = strval(htmlspecialchars($row['mobile_number']));
                                    $region = strval(htmlspecialchars($row['region']));
                                    $postcode_zip = strval(htmlspecialchars($row['postcode_zip']));
                                    $province = strval(htmlspecialchars($row['province']));
                                    $town_city = strval(htmlspecialchars($row['town_city']));
                                    $brgy = strval(htmlspecialchars($row['brgy']));
                                    $street_address = strval(htmlspecialchars($row['street_address']));
                                }
                                if ($rows == 1) {
                                    
                                    echo <<<END
                                        <div class="profile-content-container billing-content-container checkout-address-container">
                                            <div id="billing-info-content-container" class="billing-info-content-container">
                                                <div class="billing-text-title checkout-text-title"> <span>Billing Info</span> </div>
                                                <div class="billing-info-wrapper">   
                                    END;
                                    if(isset($_GET['status'])){
                                        $status = $_GET['status'];
                                        $message = $_GET['msg'];
                                        echo  "<div class='error-handler'><span>$message</span></div>";
                                    }
                                    echo <<<END
                                        <div class="billing-info-textbox name-info-textbox">
                                        <div>
                                            <label for="">First name <span>*</span></label>
                                            <input type="text" value="$first_name" id="first_name" placeholder="First Last" name="first_name" list="autocompleteOff" autocomplete="off">
                                        </div>
                                        <div>
                                            <label for="">Last name<span>*</span></label>
                                            <input type="text" value="$last_name" id="last_name" placeholder="Last Name" name="last_name" list="autocompleteOff" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="billing-info-textbox">
                                        <label for="">Mobile number<span>*</span></label>
                                        <input type="number" value="$mobile_number" id="mob_num" placeholder="Enter your mobile number" name="mob_num" maxlength="11" autocomplete="off">
                                    </div>
                                    <div class="billing-info-textbox">
                                        <label for="">Country<span>*</span></label>
                                        <input type="text" value="Philippines" id="country" name="country">
                                    </div>
                                    <div id="regionSelect">
                                    <div class="billing-info-textbox">
                                        <label for="">Region<span>*</span></label>
                                        <select id="region" name="region" onchange="getRegions(this.value, 'region', '')">
                                        <option value="$region" selected hidden>$region</option>
                                    END;                                    
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
                                                           
                                    echo <<<END
                                                    </select>
                                                    </div>
                                                    <div class="billing-info-textbox">
                                                        <label for="">Province<span>*</span></label>
                                                        <select id="province" name="province">
                                                            <option value="$province" selected>$province</option>
                                                        </select>
                                                    </div>
                                                
                                                    <div class="billing-info-textbox">
                                                        <label for="">Town/City<span>*</span></label>
                                                        <select id="city" name="city">
                                                            <option value="$town_city" selected>$town_city</option>
                                                        </select>
                                                    </div>
                                                    </div>
                                                    <div class="billing-info-textbox">
                                                    <label for="">Post Code<span>*</span></label>
                                                    <input type="number" value="$postcode_zip" id="post_code" placeholder="Enter your Post Code" maxlength="4" name="post_code">
                                                </div>
                                                    <div class="billing-info-textbox">
                                                        <label for="">Baranggay<span>*</span></label>
                                                        <input type="text" value="$brgy" id="brgy" placeholder="Enter your Baranggay" autocomplete="off" name="brgy">
                                                    </div>
                                                    <div class="billing-info-textbox">
                                                        <label for="">Street Address<span>*</span></label>
                                                        <input type="text" value="$street_address" id="street_address" placeholder="Enter your Street Address" name="street_address">
                                                    </div>
                                                    <input type="hidden" name="customer_id" value="$id">
                                                    <input type="hidden" name="url" value="checkout">
                                                    <input type="hidden" name="addressExist" value="yes">
                                            </div>
                                        </div>
                                    </div>
                                    END;
                                } else {
                                    echo <<<END
                                        <div class="profile-content-container billing-content-container checkout-address-container">
                                            <div id="billing-info-content-container" class="billing-info-content-container">
                                                <div class="billing-text-title checkout-text-title"> <span>Billing Info</span> </div>
                                                <div class="billing-info-wrapper"> 
                                    END;
                                    if(isset($_GET['status'])){
                                        $status = $_GET['status'];
                                        $message = $_GET['msg'];
                                        echo  "<div class='error-handler'><span>$message</span></div>";
                                    }
                                    echo <<<END
                                        <div class="billing-info-textbox name-info-textbox">
                                        <div>
                                            <label for="">First name <span>*</span></label>
                                            <input type="text" value="" id="first_name" placeholder="First Last" name="first_name" list="autocompleteOff" autocomplete="off">
                                        </div>
                                        <div>
                                            <label for="">Last name<span>*</span></label>
                                            <input type="text" value="" id="last_name" placeholder="Last Name" name="last_name" list="autocompleteOff" autocomplete="off">
                                        </div>
                                        </div>
                                        <div class="billing-info-textbox">
                                            <label for="">Mobile number<span>*</span></label>
                                            <input type="number" value="" id="mob_num" placeholder="Enter your mobile number" name="mob_num" maxlength="11" autocomplete="off">
                                        </div>
                                        <div class="billing-info-textbox">
                                            <label for="">Country<span>*</span></label>
                                            <input type="text" value="Philippines" id="country" name="country">
                                        </div>
                                        <div id="regionSelect">
                                        <div class="billing-info-textbox">
                                        <label for="">Region<span>*</span></label>
                                        <select id="region" name="region" onchange="getRegions(this.value, 'region', '')">
                                        <option value="" selected>Choose a Region</option>
                                    END;
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
                                    echo <<<END
                                                    </select>
                                                    </div>
                                                    <div class="billing-info-textbox">
                                                        <label for="">Province<span>*</span></label>
                                                        <select id="province" name="province">
                                                            <option value="" selected> Choose a Province</option>
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
                                                        <input type="number" value="" id="post_code" placeholder="Enter your Post Code" maxlength="4" name="post_code">
                                                    </div>
                                                
                                                    <div class="billing-info-textbox">
                                                        <label for="">Baranggay<span>*</span></label>
                                                        <input type="text" value="" id="brgy" placeholder="Enter your Baranggay" autocomplete="off" name="brgy">
                                                    </div>
                                                    <div class="billing-info-textbox">
                                                        <label for="">Street Address<span>*</span></label>
                                                        <input type="text" value="" id="street_address" placeholder="Enter your Street Address" name="street_address">
                                                    </div>
                                                    <input type="hidden" name="customer_id" value="$id">
                                                    <input type="hidden" name="url" value="checkout">
                                                    <input type="hidden" name="addressExist" value="no">
                                            </div>
                                        </div>
                                    </div>
                                    END;                                                                                   
                                }
                            }
                        }
                    } else {
                        echo <<<END
                        <div class="cart-empty">
                            <div>
                                <div class="cart-empty-text">
                                    Please login to proceed to purchase
                                </div>
                                <div class="add-address-container" onclick="redirect('login')">
                                    <span>Login</span>
                                </div>
                            </div>      
                        </div>
                        END;
                    }
                    ?>
                </div>
                <?php
                if (isset($_SESSION['item_array'])) {
                    if (isset($_SESSION['user'])) {
                        $subtotal = 0;
                        echo <<<END
                        <div class="checkout-details">  
                        <div class="cart-total-container checkout-total-container">   
                        <div class="checkout-total-wrapper">
                        <div class="cart-total-header checkout-total-header">
                            <span>YOUR ORDER</span>
                        </div>
                        <div class="cart-total-header">
                            <span>PRODUCT TOTALS</span>
                        </div>
                        END;
                       
                        foreach ($_SESSION['item_array'] as $key => $value) {

                            $product_name   = $value['product_name'];
                            $quantity       = $value['quantity'];
                            $product_price  = $value['product_price'];
                            $product_id     = $value['product_id'];
                          
                            $subtotal += ($product_price * $quantity);
                            echo <<<END
                            <div class="cart-details-holder checkout-details-holder">
                                <div class="cart-product-name checkout-product-name">
                                    <p>$product_name x $quantity</p>
                                    <input type="hidden" name="quantity[$key]" value="$quantity">
                                </div>
                                <div class="cart-price checkout-product-price">
                                    <p> &#8369; $product_price </p>
                                </div>
                                <input type="hidden" name="product_id[$key]" value="$product_id">
                            </div>       
                            END;
                        }
                        $total = $subtotal + 50;     
                    }
                }

                if (isset($_SESSION['user'])) {
                    echo <<<END
                    <div class="subtotal-text">
                        <span>SUBTOTAL</span>
                        <span class="right-side"> &#8369; $subtotal</span>
                    </div>
                    <div class="subtotal-text">
                        <span>SHIPPING FEE</span>
                        <span class="right-side"> &#8369; 50</span>
                    </div>
                    <div class="subtotal-text">
                        <span>TOTAL</span>
                        <span class="right-side"> &#8369; $total</span>
                    </div>
                    <div class="pay-via-visa-container">
                        <div class="pay-via-visa">
                            <label for="visa">Payment Mode:</label>
                            <div>
                            <input type="radio" id="visa" name="payment_mode" value="cod" onclick="showDetails('show');" checked>
                            Cash on Delivery
                            </div>                          
                        </div>
                    </div>
                    <input type="hidden" name="total" value="$total">
                    <input type="submit" class="cart-page-button check-out-submit margin20 checkout-place-order" name="order-checkout-submit" value="Place Order">
                    </div>
                    </div>
                    </div>
                    END;
                }
                ?>
            </form>
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
<!-- <div id="pay-via-visa-details" class="pay-via-visa-details">
                            <div class="billing-info-textbox">
                                <label for="">Card number<span>*</span></label>
                                <input type="number" id="card_num" name="card_num" autocomplete="off">
                            </div>
                            <div class="billing-info-textbox">
                                <label for="">Expiry Date<span>*</span></label>
                                <input type="month" id="exp_date" placeholder="MM / YY" name="exp_date" autocomplete="off">
                            </div>
                            <div class="billing-info-textbox">
                                <label for="">Card Code (CVC)<span>*</span></label>
                                <input type="password" id="card_code" placeholder="CVC" name="card_code" autocomplete="off">
                            </div>
                        </div>

                        <div class="pay-via-gcash pay-via-visa">
                            <label for="visa"></label>
                            <input type="radio" id="visa" value="gcash" name="payment_mode" onclick="showDetails('unshow');">
                            Gcash via Paymongo
                            <img src="../RES/GCash_Logo.jpg" alt="">
                        </div> -->
</html>