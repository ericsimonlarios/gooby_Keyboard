<?php
session_start();
if (isset($_POST['add-to-cart-submit'])) {

    if (isset($_SESSION['item_array'])) {
        $item_array_id = array_column($_SESSION['item_array'], 'product_id');
        if (in_array($_POST['product_id'], $item_array_id)) {
            echo '<script>alert("There is already an existing product in the cart")</script>';
            echo '<script>window.location.href="products.php?q=' . $_POST['url'] . '"</script>';
        } else {
            $item_count = count($_SESSION['item_array']);
            $info_array = array(
                'product_name'   => $_POST['product_name'],
                'product_price'  => $_POST['product_price'],
                'quantity'       => $_POST['quantity'],
                'product_id'     => $_POST['product_id'],
                'lastVisited'    => $_POST['url'],
                'product_img'    => $_POST['product_img']
            );
            $_SESSION['item_array'][$item_count] = $info_array;
        }
    } else {
        $info_array = array(
            'product_name'   => $_POST['product_name'],
            'product_price'  => $_POST['product_price'],
            'quantity'       => $_POST['quantity'],
            'product_id'     => $_POST['product_id'],
            'lastVisited'    => $_POST['url'],
            'product_img'    => $_POST['product_img']
        );
        $_SESSION['item_array'][0] = $info_array;
    }

    echo '<script>window.location.href="products.php?q=' . $_POST['url'] . '"</script>';
    // session_destroy();
}

if (isset($_GET['removeItem'])) {
    $item_remove = $_GET['removeItem'];
    $product_id = $_GET['product_id'];
    if (isset($_SESSION['item_array'])) {
        foreach ($_SESSION['item_array'] as $key => $value) {
            if ($product_id == $value['product_id']) {
                unset($_SESSION['item_array'][$key]);
                continue;
            }

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
                                                <div class="remove-product-container" onclick="removeCartProduct($item_remove,$id)">
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
        if (empty($_SESSION['item_array'])) {
            echo "<div class='if-no-products'><span>No products in the cart</span></div>";
            $_SESSION['empty_array'] = "empty";
        } else {
            echo <<<END
        <div id="check-out-container" class="check-out-container">             
            <form action="#" method="POST">
                <div class="cart-page-button margin20">
                    <div>
                        <span>View Cart</span>
                    </div>
                </div>
                <div class="check-out-button ">
                    <input class="cart-page-button check-out-submit margin20" type="submit" name="check-out-submit" value="Checkout">
                </div>
                <input type="hidden" value="#" name="check-out-product-id">
                <input type="hidden" value="#" name="check-out-product-name">
                <input type="hidden" value="#" name="check-out-product-price">
                <input type="hidden" value="#" name="check-out-product-quantity">
            </form>   
        </div>     
        END;
        }
    } else {
        echo "<div class='if-no-products'><span>No products in the cart</span></div>";
    }
}

if (isset($_GET['removeCart'])) {
    $itemRemove = $_GET['removeCart'];
    $cart_product_id = $_GET['cart_id'];
    if (isset($_SESSION['item_array'])) {
        foreach ($_SESSION['item_array'] as $key => $value) {
            if ($cart_product_id == $value['product_id']) {
                unset($_SESSION['item_array'][$key]);
                continue;
            }
        }
        header('location: cart.php');
        die();
    }
}

if (isset($_POST['minus']) || isset($_POST['plus'])) {
    $cart_id = $_POST['cart-id'];
    $changeQuantity;
    if (isset($_POST['minus'])) {
        $changeQuantity =  $_POST['cart-quantity'] - 1;
        $cart_id = $_POST['cart-id'];
        $itemKey;
        if (isset($_SESSION['item_array'])) {
            foreach ($_SESSION['item_array'] as $key => $value) {
                if ($cart_id == $value['product_id']) {
                    $itemKey = $key;
                    if ($changeQuantity == 0) {
                        unset($_SESSION['item_array'][$key]);
                        header('location: cart.php');
                        die();
                    }
                }
            }
            $_SESSION['item_array'][$itemKey]['quantity'] = $changeQuantity;
            header('location: cart.php');
            die();
        }
    }
    if (isset($_POST['plus'])) {
        $changeQuantity =  $_POST['cart-quantity'] + 1;
        $cart_id = $_POST['cart-id'];
        $itemKey;
        if (isset($_SESSION['item_array'])) {
            foreach ($_SESSION['item_array'] as $key => $value) {
                if ($cart_id == $value['product_id']) {

                    $itemKey = $key;
                }
            }
            $_SESSION['item_array'][$itemKey]['quantity'] = $changeQuantity;
            header('location: cart.php');
            die();
        }
    }
}

if(isset($_GET['changeValue'])){
    $newvalue = $_GET['changeValue'];
    $itemID   = $_GET['itemID'];
    $itemKey;
    foreach ($_SESSION['item_array'] as $key => $value) {
        if($itemID == $value['product_id']){
           $itemKey = $key;   
        }
    }
    if(empty($newvalue)){
        $_SESSION['item_array'][$itemKey]['quantity'] = 1;
    }else{
        $_SESSION['item_array'][$itemKey]['quantity'] = $newvalue;
    }   
    header('location: cart.php');
    die();
}
if (isset($_GET['getOrders'])) {
    if (isset($_SESSION['user'])) {
        if (isset($_SESSION['customer_id'])) {
            $conn = connect();
                $items_per_page = 5;

                $pages = $_GET['getOrders'];

                $current_id = $_SESSION['customer_id'];
                $getSQL = "SELECT * FROM orders where customer_id='" . $current_id . "'";
                $result = $conn->query($getSQL);
                $rows   = $result->num_rows;
                if (!$result) {
                    $error = $conn->errno . ' ' . $conn->error;
                    echo $error; // 1054 Unknown column 'foo' in 'field list'             
                }

                $item_start_offset = ($pages - 1) * $items_per_page;
                $getSQL = "SELECT * FROM orders where customer_id='" . $current_id . "'" . "LIMIT " . $item_start_offset . "," . $items_per_page;
                $result = $conn->query($getSQL);
                $items_returned = $result->num_rows;
                if (!$result) {
                    $error = $conn->errno . ' ' . $conn->error;
                    echo $error; // 1054 Unknown column 'foo' in 'field list'             
                }
                if ($rows == 0) {
                    echo <<<END
                   
                        <div class="profile-content-history-checker">
                            <div class="browse-button" onclick='redirect("categories")'>
                                <span>Browse Products</span>            
                            </div>
                            <span class="margin-left-10px">No purchases has been made yet</span>
                        </div>
                 
                    END;
                    die();
                } else {
                    $prod_name;
                    $prod_price;
                    $total_pages = ceil($rows / $items_per_page);
                    echo <<<END
                    <div class="purchase-history-table-wrapper">
                    <table class="purchase-history-table">
                        <thead>
                            <tr>
                                <th style="width:10%;">Order ID</th>
                                <th style="width:25%;">Product</th>
                                <th style="width:10%;">Quantity</th>
                                <th style="width:10%;">Price</th>
                                <th style="width:10%;">Total</th>
                                <th style="width:15%;">Date Ordered</th>
                                <th style="width:15%;">Status</th>
                                <th style="width:25%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="displayOrder">
                    END;

                    for ($i = 0; $items_returned > $i; ++$i) {
                        $row = $result->fetch_array(MYSQLI_ASSOC);
                        $order_id       = htmlspecialchars($row['order_id']);
                        $product_id     = htmlspecialchars($row['product_id']);
                        $quantity       = htmlspecialchars($row['quantity']);
                        $date_purchased = htmlspecialchars($row['date_ordered']);
                        $order_status   = htmlspecialchars($row['order_status']);
                        $total          = htmlspecialchars($row['total']);
                        $getProductSQL = "SELECT * FROM product where product_id='" . $product_id . "'";
                        $stmt = $conn->query($getProductSQL);
                        if (!$stmt) {
                            $error = $conn->errno . ' ' . $conn->error;
                            echo $error; // 1054 Unknown column 'foo' in 'field list'
                        }
                        $prod_rows = $stmt->num_rows;
                        for ($j = 0; $prod_rows > $j; ++$j) {
                            $prod_row  = $stmt->fetch_array(MYSQLI_ASSOC);
                            $prod_name = htmlspecialchars($prod_row['product_name']);
                            $prod_price = htmlspecialchars($prod_row['price']);
                        }
                        echo <<<END
                      
                            <tr class="row-cells" onclick="checkItem('$product_id')">
                                <td>$order_id</td>
                                <td>$prod_name</td>
                                <td>$quantity</td>
                                <td>&#8369;$prod_price</td>
                                <td>&#8369;$total</td>
                                <td>$date_purchased</td>
                                <td>$order_status</td>
                                <td><a class="product_actions order_actions" href="getProducts.php?removeProduct=$order_id">Cancel</a></td>                          
                            </tr>                                     
                        END;
                    }

                    echo <<<END
                        </tbody>
                        </table>
                    </div>
                    <div class="pagination">
                    END;
                    $href = 'profile.php';
                    pagination($pages, $total_pages, $href);
                   
                }
            
        }
    }
}
if (isset($_GET['removeProduct'])) {
    $remove_id  =   $_GET['removeProduct'];
   
    if (isset($_SESSION['customer_id'])) {
        $conn   =   connect();
        $query  =   "SELECT * FROM orders WHERE order_id=" . '"' . $remove_id . '"';
        $result =   $conn->query($query);
        if (!$result) {
            $error  = $conn->errno . ' ' . $conn->error;
            echo $error;
        }
        $rows   =   $result->num_rows;
        $order_status;
        for ($i = 0; $rows > $i; ++$i) {
            $row            =   $result->fetch_array(MYSQLI_ASSOC);
            $order_id       =   htmlspecialchars($row['order_id']);
            $order_status   =   htmlspecialchars($row['order_status']);         
        }
        if($order_status == "Pending"){
            $deleteQuery    =   "DELETE FROM orders WHERE order_id=" . '"' . $remove_id . '"';
            $result     =   $conn->query($deleteQuery);
            if (!$result) {
                $error  = $conn->errno . ' ' . $conn->error;
                echo $error;
            }
            echo "<script>alert(Order ID:  ' + $remove_id + ' has been successfully deleted')</script>";
            echo "<script>window.location.href='profile.php?status=error&msg=Order ID:  ' + $remove_id + ' has been successfully deleted'</script>";
            die();
        }else{
            echo "<script>alert('Order ID:  ' + $remove_id + ' cancellation is denied, the current status is already ' + $order_status)</script>";
            echo "<script>window.location.href='profile.php?status=error&msg=Order ID:  ' + $remove_id + ' cancellation is denied, current status is already $order_status'</script>";
            die();
        }
       
    }
}
if (isset($_GET['search_query'])) {
    $search_query = $_GET['search_query'];
    if (empty($_GET['search_query'])) {
    } else {
        $conn = new mysqli('localhost', 'root', '', 'gooby_keyboard');
        if (mysqli_connect_error()) {
            echo 'MySQL Error: ' . mysqli_connect_error();
        } else {
            $getSQL = "SELECT * FROM product where product_name LIKE '%$search_query%' OR category LIKE '%$search_query%'";
            $result = $conn->query($getSQL);
            $rows   = $result->num_rows;
            if (!$result) {
                $error = $conn->errno . ' ' . $conn->error;
                echo $error; // 1054 Unknown column 'foo' in 'field list'             
            }
            if ($rows == 0) {
                echo '<div id="search-responses-container" class="search-responses-container no-products"><p>No products found</p></div>';
            } else {

                echo '<div id="search-responses-container" class="search-responses-container">';

                for ($i = 0; $rows > $i; ++$i) {
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    $product_id = htmlspecialchars($row['product_id']);
                    $prod_name  = htmlspecialchars($row['product_name']);
                    $category   = htmlspecialchars($row['category']);
                    $prod_price = htmlspecialchars($row['price']);
                    $product_img = htmlspecialchars($row['product_img']);

                    echo <<<END
            <div class="search-item-container" onclick="checkItem('$product_id')">
                <div class="search-item-img-container">
                    <img src="$product_img" alt="">
                </div>
                <div class="search-item-name">
                    <p>$prod_name</p>
                    <p>$category</p>
                </div>
                <div class="search-item-price">
                    <p>&#8369;$prod_price</p>
                </div>
            </div>   
            END;
                }
                echo '</div>';
            }
        }
    }
}

if (isset($_GET['getItems'])) {

    $conn = connect();
    $cat  = $_GET['getItems'];
    $items_per_page = 8;
    $pages = $_GET['pages'];
    $sortItems = $_GET['sortItems'];
    switch ($cat) {
        case 1: {
                switch ($sortItems) {
                    case 'Default Sorting': {
                            $sql = "SELECT * FROM product where category='Keyboard'";
                            $redirect = "keyboard-tab.php";
                            break;
                        }
                    case 'Sort by Latest': {
                            $sql = "SELECT * FROM product where category='Keyboard' ORDER BY date_added DESC";
                            $redirect = "keyboard-tab.php";
                            break;
                        }
                    case 'Sort by Low to High Price': {
                            $sql = "SELECT * FROM product where category='Keyboard' ORDER BY price ASC";
                            $redirect = "keyboard-tab.php";
                            break;
                        }
                    case 'Sort by High to Low Price': {
                            $sql = "SELECT * FROM product where category='Keyboard' ORDER BY price DESC";
                            $redirect = "keyboard-tab.php";
                            break;
                        }
                }
                break;
            }
        case 2: {
                switch ($sortItems) {
                    case 'Default Sorting':
                        $sql = "SELECT * FROM product where category='Switches'";
                        $redirect = "switches-tab.php";
                        break;
                    case 'Sort by Latest':
                        $sql = "SELECT * FROM product where category='Switches' ORDER BY date_added DESC";
                        $redirect = "switches-tab.php";
                        break;
                    case 'Sort by Low to High Price':
                        $sql = "SELECT * FROM product where category='Switches' ORDER BY price ASC";
                        $redirect = "switches-tab.php";
                        break;
                    case 'Sort by High to Low Price':
                        $sql = "SELECT * FROM product where category='Switches' ORDER BY price DESC";
                        $redirect = "switches-tab.php";
                        break;
                }
                break;
            }
        case 3: {
                switch ($sortItems) {
                    case 'Default Sorting':
                        $sql = "SELECT * FROM product where category='Accessory'";
                        $redirect = "accessory-tab.php";
                        break;
                    case 'Sort by Latest':
                        $sql = "SELECT * FROM product where category='Accessory' ORDER BY date_added DESC";
                        $redirect = "accessory-tab.php";
                        break;
                    case 'Sort by Low to High Price':
                        $sql = "SELECT * FROM product where category='Accessory' ORDER BY price ASC";
                        $redirect = "accessory-tab.php";
                        break;
                    case 'Sort by High to Low Price':
                        $sql = "SELECT * FROM product where category='Accessory' ORDER BY price DESC";
                        $redirect = "accessory-tab.php";
                        break;
                }
                break;
            }
        default: {
            switch ($sortItems) {
                case 'Sort by Latest':
                    $sql = "SELECT * FROM product ORDER BY date_added DESC";
                    $redirect = "new-products-page.php";
                    break;
                case 'Sort by Low to High Price':
                    $sql = "SELECT * FROM product ORDER BY price ASC";
                    $redirect = "new-products-page.php";
                    break;
                case 'Sort by High to Low Price':
                    $sql = "SELECT * FROM product ORDER BY price DESC";
                    $redirect = "new-products-page.php";
                    break;              
            }
            break;
        }
    }

    $checkRows = $conn->query($sql);
    $prod_rows = $checkRows->num_rows;
    if (!$checkRows) {
        $error = $conn->errno . ' ' . $conn->error;
        echo $error; // 1054 Unknown column 'foo' in 'field list'             
    }
    $item_start_offset = ($pages - 1) * $items_per_page;
    switch ($cat) {
        case 1: {
                switch ($sortItems) {
                    case 'Default Sorting':
                        $stmt = "SELECT * FROM product where category='Keyboard' LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by Latest':
                        $stmt = "SELECT * FROM product where category='Keyboard' ORDER BY date_added DESC LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by Low to High Price':
                        $stmt = "SELECT * FROM product where category='Keyboard' ORDER BY price ASC  LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by High to Low Price':
                        $stmt = "SELECT * FROM product where category='Keyboard' ORDER BY price DESC LIMIT $item_start_offset , $items_per_page";
                        break;
                }
                break;
            }
        case 2: {
                switch ($sortItems) {
                    case 'Default Sorting':
                        $stmt = "SELECT * FROM product where category='Switches' LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by Latest':
                        $stmt = "SELECT * FROM product where category='Switches' ORDER BY date_added DESC  LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by Low to High Price':
                        $stmt = "SELECT * FROM product where category='Switches' ORDER BY price ASC  LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by High to Low Price':
                        $stmt = "SELECT * FROM product where category='Switches' ORDER BY price DESC LIMIT $item_start_offset , $items_per_page";
                        break;
                }
                break;
            }
        case 3: {
                switch ($sortItems) {
                    case 'Default Sorting':
                        $stmt = "SELECT * FROM product where category='Accessory' LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by Latest':
                        $stmt = "SELECT * FROM product where category='Accessory' ORDER BY date_added DESC LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by Low to High Price':
                        $stmt = "SELECT * FROM product where category='Accessory' ORDER BY price ASC  LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by High to Low Price':
                        $stmt = "SELECT * FROM product where category='Accessory' ORDER BY price DESC LIMIT $item_start_offset , $items_per_page";
                        break;
                }
                break;
            }
        default:
            switch ($sortItems) {              
                case 'Sort by Latest':
                    $stmt = "SELECT * FROM product  ORDER BY date_added DESC LIMIT $item_start_offset , $items_per_page";
                    break;
                case 'Sort by Low to High Price':
                    $stmt = "SELECT * FROM product  ORDER BY price ASC  LIMIT $item_start_offset , $items_per_page";
                    break;
                case 'Sort by High to Low Price':
                    $stmt = "SELECT * FROM product  ORDER BY price DESC LIMIT $item_start_offset , $items_per_page";
                    break;
            }
            break;
    }

    $result = $conn->query($stmt);
    if (!$result) {
        $error = $conn->errno . " " . $conn->error;
        echo $error;
    }
    $total_pages = ceil($prod_rows / $items_per_page);
    $rows = $result->num_rows;
    echo ' <div id="products-grid" class="products-grid">';
    for ($j = 0; $j < $rows; ++$j) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $product_name   = htmlspecialchars($row['product_name']);
        $price          = htmlspecialchars($row['price']);
        $product_id     = htmlspecialchars($row['product_id']);
        $product_img    = htmlspecialchars($row['product_img']);
        $category       = htmlspecialchars($row['category']);
        echo <<<END
                <div class="products-item" onclick="redirectProduct($product_id);">
                    <div class="products-item-img-container">
                        <div class="product-img-container">
                            <img src="$product_img" alt="">
                        </div>
                    </div>                  
                        <div class="products-item-details-container">
                        <div class="product-title-container">
                        <p class="product-name-content" id="product-name-content">$product_name</p>
                        </div>
                        <div class="product-price-container">
                            <span id="product-price-content"> &#8369; $price</span>
                        </div>
                        <div class="product-price-container">
                            <span id="product-price-content">$category</span>
                        </div>
                        </div>
                </div>                             
            END;
    }

    echo <<<END
    </div>
     <div class="pagination-container">
     <div class="pagination">
    END;
    $prev_page = $pages - 1;
    $next_page = $pages + 1;
    if ($pages > 1) {
        echo <<<END
        <a class="pages" href="$redirect?pages=1&sortItems=$sortItems">First</a>
        END;
    }
    if ($prev_page == 0) {
        $prev_page = 1;
        echo <<<END
        <a style="background-color:white;"></a>
        END;
    } else {
        echo <<<END
     <a class="pages" href="$redirect?pages=$prev_page&sortItems=$sortItems">Prev</a>
     END;
    }
    $max_page = array();
    $current_page = $pages;
    for ($pages = 1; $pages <= $total_pages; $pages++) {
        array_push($max_page, $pages);
    }
    $shown_pages = 2;
    if ($current_page == 1) {
        if (count($max_page) == 1) {
            $index = array_search($current_page, $max_page);
            $adder = array(0);
        } else if (count($max_page) == 2) {
            for ($items = 0; $items < 2; $items++) {
                $index = array_search($current_page, $max_page);
                $adder = array(0, 1);
                for ($i = 0; $i < count($adder); $i++) {
                    $display[] = $max_page[$index + $adder[$i]];
                }
                if ($current_page == $display[$items]) {
                    echo  <<<END
                    <a class="pages page-current" href="$redirect?pages=$display[$items]&sortItems=$sortItems">$display[$items]</a>
                    END;
                } else {
                    echo  <<<END
                    <a class="pages" href="$redirect?pages=$display[$items]&sortItems=$sortItems">$display[$items]</a>
                    END;
                }
            }
        } else {
            for ($items = $current_page - 1; $items <= $shown_pages; $items++) {
                if ($current_page == $max_page[$items]) {
                    echo <<<END
                    <a class="pages page-current" href="$redirect?pages=$max_page[$items]&sortItems=$sortItems">$max_page[$items]</a>
                    END;
                } else {
                    echo <<<END
                    <a class="pages" href="$redirect?pages=$max_page[$items]&sortItems=$sortItems">$max_page[$items]</a>
                    END;
                }
            }
        }
    } else {
        $index = array_search($current_page, $max_page);
        if (count($max_page) == 2) {
            $adder = array(-1, 0);
            $shown_pages = 1;
        } else if (array_key_last($max_page) == $index) {
            $adder = array(-2, -1, 0);
        } else {
            $adder = array(-1, 0, 1);
        }
        for ($items = 0; $items <= $shown_pages; $items++) {
            for ($i = 0; $i < count($adder); $i++) {
                $display[] = $max_page[$index + $adder[$i]];
            }
            if ($current_page == $display[$items]) {
                echo  <<<END
                <a class="pages page-current" href="$redirect?pages=$display[$items]&sortItems=$sortItems">$display[$items]</a>
                END;
            } else {
                echo  <<<END
                <a class="pages" href="$redirect?pages=$display[$items]&sortItems=$sortItems">$display[$items]</a>
                END;
            }
        }
    }
    if ($next_page > $total_pages) {
        $next_page = $pages;
    } else {
        echo <<<END
     <a class="pages" href="$redirect?pages=$next_page&sortItems=$sortItems">Next</a>
     END;
    }
    if ($current_page == $total_pages) {
    } else {
        echo <<<END
        <a class="pages" href="$redirect?pages=$total_pages&sortItems=$sortItems">Last</a>
        END;
    }
    echo <<<END
    
    </div>
    </div>
    END;
}

if (isset($_GET['selectValue'])) {
    $conn       = connect();
    $dataReg    = $_GET['selectValue'];
    $selectID   = $_GET['selectID'];
    $regCode    = $_GET['regCode'];
    $provCodex  = $_GET['provCode'];
    echo <<<END
    <div class="billing-info-textbox">
    <label for="">Region<span>*</span></label>
    <select id="region" name="region" onchange="getRegions(this.value, 'region', '')">
        <option value=""  selected hidden> Choose a Region</option>
        
    END;
    $findSQL;
    if($selectID == "region"){
        $findSQL = "SELECT * FROM refregion where regCode= $dataReg";
    }else if ($selectID == "province"){
        $findSQL = "SELECT * FROM refregion where regCode= $regCode";
    }  
    $findResult   = $conn->query($findSQL);
    if (!$findResult) {
        $error = $conn->errno . " " . $conn->error;
        echo $error;
    }
    $find = $findResult->fetch_array(MYSQLI_ASSOC);
    $regName = htmlspecialchars($find['regDesc']);

    $regionSQL = "SELECT * FROM refregion";
    $regionResult   = $conn->query($regionSQL);
    if (!$regionResult) {
        $error = $conn->errno . " " . $conn->error;
        echo $error;
    }
    $regionRows = $regionResult->num_rows;
    if($selectID == 'region'){
        echo <<<END
        <option value="$regName" selected>$regName</option>
        END;
    }else if ($selectID == 'province'){
        echo <<<END
        <option value="$regName" selected>$regName</option>
        END;
    }  
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
    END;

    // <div id="regionSelect" class="billing-info-textbox">
    // <div class="billing-info-textbox">
    // <label for="">Province<span>*</span></label>
    // <select id="province" name="province" onclick="getRegions(this.id)">
    //     <option value=""  selected hidden> Choose a Province</option>                                                            
    // </select>
    // </div>                          
    // <div class="billing-info-textbox">
    //     <label for="">Town/City<span>*</span></label>
    //     <select id="city" name="city">
    //         <option value="" selected> Choose a City</option>
    //     </select>
    // </div>

    if(!empty($dataReg)){
        if($selectID == "region"){
            echo <<<END
            <div class="billing-info-textbox">
                <label for="">Province<span>*</span></label>
                <select id="province" name="province" onchange="getRegions(this.value, 'province', '$dataReg', '')">
                <option value=""  selected> Choose a Province</option>  
            END;
        }else if ($selectID == "province"){
            echo <<<END
            <div class="billing-info-textbox">
                <label for="">Province<span>*</span></label>
                <select id="province" name="province" onchange="getRegions(this.value, 'province', '$regCode', '$provCodex')">
                <option value=""  selected hidden> Choose a Province</option>  
            END;
        }  
       
        $provinceSQL;
        if($selectID == "region"){
            $provinceSQL = "SELECT * FROM refprovince where regCode=$dataReg";
        }else if ($selectID == "province"){
            $provinceSQL = "SELECT * FROM refprovince where regCode=$regCode";
        }        
        $provinceResult   = $conn->query($provinceSQL);
        if (!$provinceResult) {
            $error = $conn->errno . " " . $conn->error;
            echo $error;
        }
        $provCode;
        $provinceRows = $provinceResult->num_rows;
        for ($i = 0; $i < $provinceRows; ++$i) {
            $returnedRows   = $provinceResult->fetch_array(MYSQLI_ASSOC);
            $provinceDesc     = htmlspecialchars($returnedRows['provDesc']);
            $provinceCode     = htmlspecialchars($returnedRows['provCode']);
            if($provinceCode == $regCode || $provinceCode == $dataReg){
                $provName =  $provinceDesc;
                $provCode = $provinceCode;
            }
            echo <<<END
                <option value="$provinceCode">$provinceDesc</option>
                END;
        }
        echo <<<END
        <option value="$provName" selected hidden>$provName</option>
        END;
        echo <<<END
        </select>
        </div>
        END;

        if(!empty($dataReg)){
            if($selectID == "province"){
                echo <<<END
                <div class="billing-info-textbox">
                <label for="">Town/City<span>*</span></label>
                <select id="city" name="city" onchange="getRegions(this.value, 'city', '$dataReg', '')>
                <option value="" selected> Choose a City</option>
               
                END;
            }else if ($selectID == "city"){
                echo <<<END
                <div class="billing-info-textbox">
                    <label for="">Town/City<span>*</span></label>
                    <select id="city" name="city" onchange="getRegions(this.value, 'city', '$regCode', '$provCode')">
                    <option value=""  selected>Choose a City</option>  
                END;
            }else if ($selectID == "region"){
                echo <<<END
                <div class="billing-info-textbox">
                    <label for="">Town/City<span>*</span></label>
                    <select id="city" name="city" onchange="getRegions(this.value, 'city', '$dataReg', '')">
                    <option value=""  selected>Choose a City</option>  
                END;
            }
            $townSQL;
            if($selectID == "province"){
                $townSQL = "SELECT * FROM refcitymun where provCode=$dataReg";
            }else if ($selectID == "city"){
                $townSQL = "SELECT * FROM refcitymun where provCode=$provCode";
            }
            else if ($selectID == "region"){
                $townSQL = "SELECT * FROM refcitymun where provCode='0'";
            }
            $cityResult   = $conn->query($townSQL);
            if (!$cityResult) {
                $error = $conn->errno . " " . $conn->error;
                echo $error;
            }
            $cityRows = $cityResult->num_rows;
            $townCode;
            $cityName;
            for ($i = 0; $i < $cityRows; ++$i) {
                $returnedRows   = $cityResult->fetch_array(MYSQLI_ASSOC);
                $cityDesc     = htmlspecialchars($returnedRows['citymunDesc']);
                $cityCode     = htmlspecialchars($returnedRows['citymunCode']);
                if($cityCode == $provCode || $cityCode == $dataReg){
                    $cityName =  $cityDesc;
                    $townCode = $cityCode;
                }
                echo <<<END
                    <option value="$cityDesc">$cityDesc</option>
                END;
            }
            echo <<<END
            <option value="$cityName" selected hidden>$cityName</option>
            END;
            echo <<<END
            </select>
            </div>
            END;
        }
    }       
    
}

if(isset($_POST['contactSubmit'])){
    $name  = $_POST['name'];
    $subj  = $_POST['subj'];
    $email = 'FROM: ' . $_POST['email'];
    $receiver = "goobykeyboard@gmail.com";
    $msg   = $_POST['msg'];
    if(mail($receiver, $subj, $msg, $email)){
        
        echo '<script>window.location.href="contact-us.php?msg=Message has been sent successfully"</script>';
    }else{
        $errorMessage = error_get_last()['message'];
        // echo '<script>window.location.href="contact-us.php?msg=Message failed"</script>';
    }
    
}
function connect()
{
    $conn = new mysqli('localhost', 'root', '', 'gooby_keyboard');
    if (mysqli_connect_error()) {
        echo 'MySQL Error: ' . mysqli_connect_error();
    }
    return $conn;
}

function pagination($pages,$total_pages, $href){
    $prev_page = $pages - 1;
    $next_page = $pages + 1;
                    if ($pages > 1) {
                        echo <<<END
                        <a class="pages" href="$href?pages=1">First</a>
                        END;
                    }
                    if ($prev_page == 0) {
                        $prev_page = 1;
                        echo <<<END
                        <a style="background-color:white;"></a>
                        END;
                    } else {
                        echo <<<END
                     <a class="pages" href="$href?pages=$prev_page">Prev</a>
                     END;
                    }
                    $max_page = array();
                    $current_page = $pages;
                    for ($pages = 1; $pages <= $total_pages; $pages++) {
                        array_push($max_page, $pages);
                    }
                    $shown_pages = 2;
                    if ($current_page == 1) {
                        if (count($max_page) == 1) {
                            $index = array_search($current_page, $max_page);
                            $adder = array(0);
                            for ($items = 0; $items <= 1; $items++) {
                               
                            }
                        } else if (count($max_page) == 2) {
                            for ($items = 0; $items < 2; $items++) {
                                $index = array_search($current_page, $max_page);
                                $adder = array(0, 1);
                                for ($i = 0; $i < count($adder); $i++) {
                                    $display[] = $max_page[$index + $adder[$i]];
                                }
                                if ($current_page == $display[$items]) {
                                   echo <<< END
                                   <a class="pages page-current" href="$href?pages=$display[$items]">$display[$items]</a>
                                   END;
                                } else {
                                    echo <<< END
                                    <a class="pages page-current" href="$href?pages=$display[$items]">$display[$items]</a>
                                    END;
                                   
                                }
                            }
                        } else {
                            for ($items = $current_page - 1; $items <= $shown_pages; $items++) {
                                if ($current_page == $max_page[$items]) {
                                    echo <<< END
                                    <a class="pages page-current" href="$href?pages=$max_page[$items]">$max_page[$items]</a>
                                    END;
                                } else {
                                    echo <<< END
                                    <a class="pages page-current" href="$href?pages=$max_page[$items]">$max_page[$items]</a>
                                    END;
                                }
                            }
                        }
                    } else {
                        $index = array_search($current_page, $max_page);
                        if (count($max_page) == 2) {
                            $adder = array(-1, 0);
                            $shown_pages = 1;
                        } else if (array_key_last($max_page) == $index) {
                            $adder = array(-2, -1, 0);
                        } else {
                            $adder = array(-1, 0, 1);
                        }
                        for ($items = 0; $items <= $shown_pages; $items++) {
                            for ($i = 0; $i < count($adder); $i++) {
                                $display[] = $max_page[$index + $adder[$i]];
                            }
                            if ($current_page == $display[$items]) {
                                echo <<< END
                                <a class="pages page-current" href="$href?pages=$display[$items]">$display[$items]</a>
                                END;
                            } else {
                                echo <<< END
                                <a class="pages page-current" href="$href?pages=$display[$items]">$display[$items]</a>
                                END;
                            }
                        }
                    }
                    if ($next_page > $total_pages) {
                        $next_page = $pages;
                    } else {
                        echo <<<END
                     <a class="pages" href="$href?pages=$next_page">Next</a>
                     END;
                    }
                    if ($current_page == $total_pages) {
                    } else {
                        echo <<<END
                        <a class="pages" href="$href?pages=$total_pages">Last</a>
                        END;
                    }
                    echo <<<END
                    </div>
                    END;
}
