<?php
require_once "../PHP/dbcon.php";
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
    <script>
        function removeItem(removeCart, cart_id) {
            window.location.href = "getProducts.php?removeCart=" + removeCart + "&cart_id=" + cart_id;
        }

        function changeQuantity(changeMode, itemId, key) {
            let decrease = document.getElementById('cart-quantity');
            let increase = document.getElementById('cart-quantity');
            if (changeMode == 'minus') {
                if (parseInt(decrease.value) == 0) {
                    decrease.value = 0;
                } else {
                    decrease = parseInt(decrease.value) - 1;
                }
                window.location.href = "getProducts.php?changeQuantity=" + changeMode + "&currentQuantity=" + decrease + "&itemId=" + itemId + "&itemKey=" + key;
            } else {

                increase = parseInt(increase.value) + 1;
                window.location.href = "getProducts.php?changeQuantity=" + changeMode + "&currentQuantity=" + increase + "&itemId=" + itemId + "&itemKey=" + key;
            }
        }
    </script>
</head>

<body>
    <div id="products-window" class="products-main-container cart-page-main-container">
        <div class="cart-page-header">
            <a class="current-page-cart" href="cart.php">SHOPPING CART</a>
            <span> > </span>
            <a href="checkout.php">CHECKOUT DETAILS</a>
            <span> > </span>
            <p>ORDER COMPLETE</p>
        </div>
        <div class="cart-page-main-body">
            <?php
            if (isset($_SESSION['item_array'])) {
                if (empty($_SESSION['item_array'])) {
                    echo <<<END
                                <div class="cart-empty">
                                    <div>
                                        <div class="cart-empty-text">
                                            Your cart is currently empty
                                        </div>
                                        <div class="add-address-container" onclick="redirect('categories')">
                                            <span>Back to Shopping</span>
                                        </div>
                                    </div>      
                                </div>
                                END;
                } else {
                    echo '<div class="cart-page-main-wrapper">';
                    echo '<div class="cart-table-container">';
                    echo '<div class="cart-table-wrapper">';
                    $subtotal = 0;
                    foreach ($_SESSION['item_array'] as $key => $value) {

                        $product_name   = $value['product_name'];
                        $quantity       = $value['quantity'];
                        $product_price  = $value['product_price'];
                        $id             = $value['product_id'];
                        $product_img    = $value['product_img'];
                        $subtotal += ($product_price * $quantity);
                        echo <<<END
                                            <div class="cart-items-wrapper">
                                                    <div class="cart-added-img-container">
                                                        <img src="$product_img" alt="">
                                                    </div>
                                                    <div class="cart-details-holder">
                                                        <div class="cart-product-name">
                                                            <p>$product_name</p>
                                                        </div>
                                                        <div class="cart-price">
                                                            <p> &#8369; $product_price</p>
                                                        </div>
                                                        <div class="remove-cart-item" onclick="removeItem($key,$id)">
                                                            <span>Remove</span>
                                                        </div>
                                                    </div>
                                                    <div class="cart-quantity-container">
                                                    <form action="getProducts.php" method="POST">
                                                        <div class="number-input cart-number-input">
                                                            <input name="cart-id" type="hidden" value="$id">
                                                            <input name="minus" type="submit" value="-" class="minus"></input>
                                                            <div id="cart-quantity">
                                                            <input name="cart-quantity" class="quantity" min="0" name="quantity" value="$quantity" type="number" onchange="quantityChange(this.value, '$id')">
                                                            </div>
                                                            <input name="plus" type="submit" value="+"  class="plus"></input>
                                                        </div>
                                                    </div>
                                                    </form>
                                            </div>
                                        END;
                    }
                    echo '</div>';
                    echo '</div>';
                    $total = $subtotal + 50;
                    echo <<<END
                    <div class="cart-total-container">
                        <div class="cart-total-wrapper">
                            <div class="cart-total-header">
                                <span>PRODUCT TOTALS</span>
                            </div>
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
                            <div class="proceed-to-checkout" onclick="redirect('checkout')">
                                <span>PROCEED TO CHECKOUT</span>
                            </div>
                        </div>
                    </div>
                    END;
                    echo '</div>';
                }
            } else {
                echo <<<END
                            <div class="cart-empty">
                                <div>
                                    <div class="cart-empty-text">
                                        Your cart is currently empty
                                    </div>
                                    <div class="add-address-container" onclick="redirect('categories')">
                                        <span>Back to Shopping</span>
                                    </div>
                                </div>      
                            </div>
                            END;
            }
            ?>
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
    </div>
    </div>
    </div>

</body>
<script>
    function quantityChange(value, itemID){
        window.location.href = "getProducts.php?changeValue=" + value + "&itemID=" + itemID    
    }
</script>
</html>