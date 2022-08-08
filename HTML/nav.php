<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gooby's Keyboard</title>
    <link rel="stylesheet" href="..CSS\index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="..CSS\index.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
    <link rel="mask-icon" href="../safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <script>
        function removeCartProduct(removeItem, product_id) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('cart-body').innerHTML = this.responseText;
                }
            }
            xmlhttp.open("GET", "getProducts.php?removeItem=" + removeItem + "&product_id=" + product_id, true);
            xmlhttp.send();
        }
    </script>
</head>
<body>
    <div class=main-container>
        <div class=nav-container>
            <div class="nav-flex-left">
                <div id="menu-button" class="menu-button" onclick="menuOpen('menu-container')">
                    <div>
                        <span class="line-icon"></span>
                        <span class="line-icon"></span>
                        <span class="line-icon"></span>
                    </div>
                    <div class="menu-text">Menu</div>
                </div>
                <div class="shop-button" onclick="shopRedirect()">
                    <div>
                        <div>Shop</div>
                    </div>
                </div>
                <div class="search-bar">
                    <div>
                        <i class="fa fa-search"></i> Search
                        <div class="search-box-container">
                            <div class="search-box">
                                <form class="search" action="#">
                                    <input id="search-input-box" type="text" placeholder="Looking for something?" name="search" onkeyup="checkResponse();">
                                    <button type="submit"><i class="fa fa-search"></i></button>
                                </form>
                                
                            </div>
                            <div id="search-responses" class="search-responses">
                                
                            </div>
                        </div>
                    </div>  
                </div>
                
            </div>
            <div class="logo-container">
                <div class="logo-container" onclick="redirect('index')">
                    <img src="../RES/gooby_keyboard_logo.gif" alt="gooby_Keyboard_logo">
                </div>
            </div>
            <div class="nav-flex-right">
                <?php
                if ($_SERVER['REQUEST_URI'] == "/gooby_Keyboard/HTML/cart.php") {
                    echo <<<END
                        <div class="cart-icon-container nav-margin" onclick="redirect('cart')">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                    END;
                } else if ($_SERVER['REQUEST_URI'] == "/gooby_Keyboard/HTML/checkout.php") {
                    echo <<<END
                         <div class="cart-icon-container nav-margin" onclick="redirect('cart')">
                             <i class="fa fa-shopping-cart"></i>
                         </div>
                     END;
                } else {
                    echo <<<END
                        <div class="cart-icon-container nav-margin" onclick="menuOpen('cart-content')">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                    END;
                }
                ?>

                <div class="nav-divider nav-margin"></div>
                <div class="login-container nav-margin">
                    <?php
                    if (isset($_SESSION['user'])) {
                        echo "<div class='user-name'><span style='color:white;'>" . "Hi, " . $_SESSION['user'] . "</span>" . "<div id='down-arrow' onclick='showArrow();' class='down-arrow'></div></div>";
                    } else {
                        echo '<a href="login.php">Login</a>';
                    }
                    ?>
                    <div class="user-settings-anchor">
                    <div id="user-settings-container" style='display: none;' class="user-settings-container">
                        <div>
                            <div class="user-settings-content" onclick="redirect('profile')">Order History</div>
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

                                if ($rows == 1) {
                                    echo "<div class='user-settings-content' id='edit-info' onclick='redirect(this.id)'>Billing Info</div>";
                                } else {

                                    echo "<div class='user-settings-content'id='add-address' onclick='redirect(this.id)'>Billing Info</div>";
                                }
                            }
                            ?>
                            <div class="user-settings-content" onclick="redirect('settings')">Settings</div>
                            <div class="user-settings-content" onclick="redirect('../PHP/logout')">Logout</div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="cart-container" class="cart-container" onclick="menuClose('cart-content')">

        </div>
        <div id="cart-content" class="cart-content">
            <div class="cart-heading">
                <div class="cart-title">CART</div>
                <div class="cart-close-button" onclick="menuClose('cart-content')">
                    <i class="fa fa-close"></i>
                </div>
            </div>
            <div id="cart-body" class="cart-body">
                <?php
                if (isset($_SESSION['item_array'])) {
                    $_SESSION['empty_array'] = "not_empty";
                    if (empty($_SESSION['item_array'])) {
                        echo "<div class='if-no-products'><span>No products in the cart</span></div>";
                    } else {
                        foreach ($_SESSION['item_array'] as $key => $value) {

                            $product_name   = $value['product_name'];
                            $quantity       = $value['quantity'];
                            $product_price  = $value['product_price'];
                            $id             = $value['product_id'];
                            $product_img    = $value['product_img'];
                            echo <<<END
                              
                                                    <div id="product-added-container" class="product-added-container">
                                                        <div class="product-added-wrapper">
                                                            <div class="product-added-cover">
                                                                <div class="product-added-img-container">
                                                                    <img src="$product_img" alt="">
                                                                </div>
                                                                <div class="product-added-name">
                                                                    <p>$product_name </p>       
                                                                </div> 
                                                                <div class="remove-product-container" onclick="removeCartProduct($key,$id)">
                                                                    <div class="remove-product">
                                                                        <i class="fa fa-close"></i>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            <div class="product-added-price">
                                                                <p>$quantity x $product_price</p> 
                                                            </div>
                                                        </div>  
                                                    </div>
                                                
                                    END;
                        }
                    }
                } else {
                    echo "<div class='if-no-products'><span>No products in the cart</span></div>";
                }
                ?>
                <div id="check-out-container" class="check-out-container">

                    <?php
                    if (isset($_SESSION['item_array'])) {
                        if (!empty($_SESSION['item_array'])) {
                            echo <<<END
                                   
                                        <div class="cart-page-button margin20" onclick="redirect('cart')">
                                            <div><span>View Cart<span></div>
                                        </div>                                     
                                    <form action="checkout.php" method="POST">
                                        <input type="hidden" value="$id" name="check-out-product-id">
                                        <input type="hidden" value="$product_name" name="check-out-product-name">
                                        <input type="hidden" value="$product_price" name="check-out-product-price">
                                        <input type="hidden" value="$quantity" name="check-out-product-quantity">
                                        <input type="hidden" value="$product_img" name="check-out-product-quantity">
                                        <div class="check-out-button ">
                                            <input class="cart-page-button check-out-submit margin20" type="submit" name="check-out-submit" value="Checkout">
                                        </div>                                    
                                    </form>
                                
                            END;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div id="menu-container" class="menu-container">
        <div id="menu-content" class="menu-content">
         
            <div id="menu-new-items" class="menu-items" onclick='redirect("new-products-page")'>What's New?</div>
            <div id="menu-commissions" class="menu-items" onclick='redirect("commission")'>Commissions</div>
            <div id="categories" class="menu-items" onclick='redirect(this.id)'>Categories</div>
            <?php
            if (!isset($_SESSION['user'])) {
                echo "<div id='sign-up' class='menu-items' onclick='redirect(this.id)'>Register</div>";
            }
            ?>
            <div id="menu-about" class="menu-items" onclick='redirect("about-us")'>About us</div>
            <div id="menu-about" class="menu-items" onclick='redirect("contact-us")'>Contact us</div>
        </div>
        <div id="menu-content-leftover" class="menu-content-leftover" onclick="menuClose('menu-container')"></div>
    </div>

</body>
<script src="JS/index.js"></script>
<script src="https://kit.fontawesome.com/5813fc1f75.js" crossorigin="anonymous"></script>
<script> 
    function checkResponse(){
        var search_query = document.getElementById('search-input-box').value;
        var xml = new XMLHttpRequest();
        xml.onreadystatechange = function (){
            if(this.readyState == 4 && this.status == 200){
                document.getElementById('search-responses').innerHTML = this.responseText;
            }
        }
        xml.open("GET", "getProducts.php?search_query=" + search_query, true);
        xml.send();
    }    
    function checkItem($id){
            window.location.href = "products.php?q=" + $id;
        } 
</script>

</html>