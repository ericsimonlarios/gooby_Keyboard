<?php

require_once "../PHP/dbcon.php";
session_start();

if (isset($_GET['getDatas'])) {
    $conn = connect();
    $getOrdersSQL = "SELECT * FROM orders";
    $ordersResult = $conn->query($getOrdersSQL);
    $order_rows = $ordersResult->num_rows;

    $getOrdersSQL = "SELECT * FROM orders where order_status ='Pending'";
    $pendingResult = $conn->query($getOrdersSQL);
    $pending_rows = $pendingResult->num_rows;

    $getOrdersSQL = "SELECT * FROM orders where order_status ='Shipped'";
    $shippedResult = $conn->query($getOrdersSQL);
    $shipped_rows = $shippedResult->num_rows;

    $getOrdersSQL = "SELECT * FROM orders where order_status ='Arrived'";
    $arrivedResult = $conn->query($getOrdersSQL);
    $arrived_rows = $arrivedResult->num_rows;

    $getAddressSQL = "SELECT * FROM billing_info";
    $addressResult = $conn->query($getAddressSQL);
    $address_rows = $addressResult->num_rows;

    $getProductsSQL = "SELECT * FROM product";
    $productResult = $conn->query($getProductsSQL);
    $product_rows = $productResult->num_rows;

    $getCustomerSQL = "SELECT * FROM customer";
    $customerResult = $conn->query($getCustomerSQL);
    $customer_rows = $customerResult->num_rows;

    $stock_items = 0;
    for ($i = 0; $product_rows > $i; ++$i) {
        $product_row = $productResult->fetch_array(MYSQLI_ASSOC);
        $stock = htmlspecialchars($product_row['stock']);
        if ($stock == 0) {
            $stock_items++;
        }
    }
    echo <<<END
    <div class="analysis-summary-container">
        <div class="analysis-title">
            <p>Products in the Shop</p>
        </div>
        <div class="analysis-number">
            <p>$product_rows Items</p>
        </div>
    </div>
    <div class="analysis-summary-container">
        <div class="analysis-title">
            <p>Orders processed</p> 
        </div>
        <div class="analysis-number">
            <p>$order_rows Orders</p>
        </div>
    </div>
    <div class="analysis-summary-container">
        <div class="analysis-title">
            <p>Customers registered </p>
        </div>
        <div class="analysis-number">
            <p>$customer_rows Customers</p>
        </div>
    </div>
    <div class="analysis-summary-container">
        <div class="analysis-title">
            <p>Products that are out of stock</p> 
        </div>
        <div class="analysis-number">
            <p>$stock_items Items</p>
        </div>
    </div>
    <div class="analysis-summary-container">
        <div class="analysis-title">
            <p>Pending Orders</p> 
        </div>
        <div class="analysis-number">
            <p>$pending_rows Orders</p>
        </div>
    </div>
     <div class="analysis-summary-container">
        <div class="analysis-title">
            <p>Shipped Orders</p> 
        </div>
        <div class="analysis-number">
            <p>$shipped_rows Orders</p>
        </div>
    </div>
    <div class="analysis-summary-container">
        <div class="analysis-title">
            <p>Arrived Orders</p> 
        </div>
        <div class="analysis-number">
            <p>$arrived_rows Orders</p>
        </div>
    </div>
    <div class="analysis-summary-container">
        <div class="analysis-title">
            <p>Address Registered</p> 
        </div>
        <div class="analysis-number">
            <p>$address_rows registered</p>
        </div>
    </div>
    END;
}

if (isset($_GET['getUpdate'])) {
    if (isset($_SESSION['user'])) {
        if (isset($_SESSION['customer_id'])) {
            $conn = connect();
            $changeStatus   = $_GET['getUpdate'];
            $id_order       = $_GET['order_id'];
            $sortValue      = $_GET['sortValue'];
            $insertSQL = "UPDATE orders SET order_status=? where order_id=?";
            if ($status_result = $conn->prepare($insertSQL)) {
                $status_result->bind_param('si', $changeStatus, $id_order);
            } else {
                $error = $conn->errno . ' ' . $conn->error;
                echo $error; // 1054 Unknown column 'foo' in 'field list'
            }
            if (!$status_result->execute()) {
                $status_result = null;
                $error = $conn->errno . ' ' . $conn->error;
                echo $error; // 1054 Unknown column 'foo' in 'field list'
                header('location: ../HTML/admin-order-page.php?error=stmtfailed');
                $conn->close();
                exit();
            }

            $items_per_page = 5;

            $pages = $_GET['pages'];
            $getSQL = "SELECT * FROM orders";
            switch ($sortValue) {
                case 'default':
                    $getSQL = "SELECT * FROM orders";
                    break;
                case 'Sort by Latest':
                    $getSQL = "SELECT * FROM orders ORDER BY date_ordered DESC";
                    break;
                case 'Sort by Pending Status':
                    $getSQL = "SELECT * FROM orders WHERE order_status='Pending'";
                    break;
                case 'Sort by Shipped Status':
                    $getSQL = "SELECT * FROM orders WHERE order_status='Shipped'";
                    break;
                case 'Sort by Arrived Status':
                    $getSQL = "SELECT * FROM orders WHERE order_status='Arrived'";
                    break;
                default:
                    $getSQL = "SELECT * FROM orders";
                    break;
            }
            $result = $conn->query($getSQL);
            $rows   = $result->num_rows;
            if (!$result) {
                $error = $conn->errno . ' ' . $conn->error;
                echo $error; // 1054 Unknown column 'foo' in 'field list'             
            }
            $getOrderSQL;
            $item_start_offset = ($pages - 1) * $items_per_page;
            switch ($sortValue) {
                case 'Default Sorting':
                    $getOrderSQL = "SELECT * FROM orders LIMIT $item_start_offset , $items_per_page";
                    break;
                case 'Sort by Latest':
                    $getOrderSQL = "SELECT * FROM orders ORDER BY date_ordered DESC LIMIT $item_start_offset , $items_per_page";
                    break;
                case 'Sort by Pending Status':
                    $getOrderSQL = "SELECT * FROM orders WHERE order_status='Pending' LIMIT $item_start_offset , $items_per_page";
                    break;
                case 'Sort by Shipped Status':
                    $getOrderSQL = "SELECT * FROM orders WHERE order_status='Shipped' LIMIT $item_start_offset , $items_per_page";
                    break;
                case 'Sort by Arrived Status':
                    $getOrderSQL = "SELECT * FROM orders WHERE order_status='Arrived' LIMIT $item_start_offset , $items_per_page";
                    break;
                default:
                    $getOrderSQL = "SELECT * FROM orders  LIMIT $item_start_offset , $items_per_page";
                    break;
            }

            $result_stmt = $conn->query($getOrderSQL);
            $items_returned = $result_stmt->num_rows;
            if (!$result) {
                $error = $conn->errno . ' ' . $conn->error;
                echo $error; // 1054 Unknown column 'foo' in 'field list'             
            }
            if ($rows == 0) {
                echo <<<END
                    <div class="profile-content-history-checker-container profile-nav-header admin-content-history-checker-container">
                        <div class="profile-content-history-checker admin-content-history-checker">            
                                <span>It seems there is no orders yet...</span>            
                        </div>
                    </div>
                    END;
                die();
            } else {
                $total_pages = ceil($rows / $items_per_page);
                echo <<<END
                    <div class="product-history-table-container">
                        <table class="product-history-table">
                            <thead>
                                <tr>
                                    <th style="width:5%;">ID</th>
                                    <th style="width:5%;">Customer</th>
                                    <th style="width:30%;">Product</th>
                                    <th style="width:7.5%;">Quantity</th>
                                    <th style="width:12.5%;">Price</th>
                                    <th style="width:12.5%;">Total</th>
                                    <th style="width:15%;">Date Ordered</th>
                                    <th style="width:20%;">Status</th>
                                </tr>
                            </thead>
                        <tbody id="order-table-body">
                    END;
                $prod_name;
                $prod_price;

                for ($i = 0; $items_returned > $i; ++$i) {
                    $row = $result_stmt->fetch_array(MYSQLI_ASSOC);
                    $order_id       = htmlspecialchars($row['order_id']);
                    $product_id     = htmlspecialchars($row['product_id']);
                    $customer       = htmlspecialchars($row['customer_id']);
                    $quantity       = htmlspecialchars($row['quantity']);
                    $date_purchased = htmlspecialchars($row['date_ordered']);
                    $order_status   = htmlspecialchars($row['order_status']);
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
                    $total = $prod_price * $quantity;
                    echo <<<END
                      
                            <tr class="row-cells" onclick="checkItem('$product_id')">
                                <td>$order_id</td>
                                <td>$customer</td>
                                <td>$prod_name</td>
                                <td>$quantity</td>
                                <td>&#8369;$prod_price</td>
                                <td>&#8369;$total</td>
                                <td>$date_purchased</td>
                                <td>
                                <select name="order_status" id="order_status"  onchange="updateStatus(this.value,'$order_id');">
                                    <option value="" selected hidden disabled>$order_status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Shipped">Shipped</option>
                                    <option value="Arrived">Arrived</option>
                                </select>
                                </td>
                            </tr>                                     
                        END;
                }
                echo <<<END
                         </tbody>
                            </table>
                        </div>
                        <div class="pagination">
                        END;
                $prev_page = $pages - 1;
                $next_page = $pages + 1;
                if ($pages > 1) {
                    echo <<<END
                            <a class="admin-pages" href="admin-order-page.php?pages=1&getOrder=$sortValue">First</a>
                            END;
                }
                if ($prev_page == 0) {
                    $prev_page = 1;
                    echo <<<END
                            <a style="background-color:white;"></a>
                            END;
                } else {
                    echo <<<END
                         <a class="admin-pages" href="admin-order-page.php?pages=$prev_page&getOrder=$sortValue">Prev</a>
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
                        for ($items = 0; $items < 1; $items++) {
                            for ($i = 0; $i < count($adder); $i++) {
                                $display[] = $max_page[$index + $adder[$i]];
                            }
                        }
                    } else if (count($max_page) == 2) {
                        for ($items = 0; $items < 2; $items++) {
                            $index = array_search($current_page, $max_page);
                            $adder = array(0, 1);
                            for ($i = 0; $i < count($adder); $i++) {
                                $display[] = $max_page[$index + $adder[$i]];
                            }
                            if ($current_page == $display[$items]) {
                                echo  '<a class="admin-pages admin-pages-current" href="admin-order-page.php?pages=' . $display[$items] . '&getOrder=' . $sortValue . '">' . $display[$items] . '</a>';
                            } else {
                                echo  '<a class="admin-pages" href="admin-order-page.php?pages=' . $display[$items] . '&getOrder=' . $sortValue . '">' . $display[$items] . '</a>';
                            }
                        }
                    } else {
                        for ($items = $current_page - 1; $items <= $shown_pages; $items++) {
                            if ($current_page == $max_page[$items]) {
                                echo  '<a class="admin-pages admin-pages-current" href="admin-order-page.php?pages=' . $max_page[$items] . '&getOrder=' . $sortValue . '">' . $max_page[$items] . '</a>';
                            } else {
                                echo  '<a class="admin-pages" href="admin-order-page.php?pages=' . $max_page[$items] . '&getOrder=' . $sortValue . '">' . $max_page[$items] . '</a>';
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
                            echo  '<a class="admin-pages admin-pages-current" href="admin-order-page.php?pages=' . $display[$items] . '&getOrder=' . $sortValue . '">' . $display[$items] . '</a>';
                        } else {
                            echo  '<a class="admin-pages" href="admin-order-page.php?pages=' . $display[$items] . '&getOrder=' . $sortValue . '">' . $display[$items] . '</a>';
                        }
                    }
                }
                if ($next_page > $total_pages) {
                    $next_page = $pages;
                } else {
                    echo <<<END
                         <a class="admin-pages" href="admin-order-page.php?pages=$next_page&getOrder=$sortValue">Next</a>
                         END;
                }

                if ($current_page == $total_pages) {
                } else {
                    echo <<<END
                            <a class="admin-pages" href="admin-order-page.php?pages=$total_pages&getOrder=$sortValue">Last</a>
                            END;
                }


                echo <<<END
                        </div>
                        END;
                
            }
        }
    }
}

if (isset($_GET['getOrder'])) {
    $sortValue  =   $_GET['getOrder'];
    if (isset($_SESSION['user'])) {
        if (isset($_SESSION['customer_id'])) {
            $conn = connect();
            if (mysqli_connect_error()) {
                echo 'MySQL Error: ' . mysqli_connect_error();
            } else {
                $current_id = $_SESSION['customer_id'];
                $items_per_page = 5;

                $pages = $_GET['pages'];
                $getSQL = "SELECT * FROM orders";
                switch ($sortValue) {
                    case 'default':
                        $getSQL = "SELECT * FROM orders";
                        break;
                    case 'Sort by Latest':
                        $getSQL = "SELECT * FROM orders ORDER BY date_ordered DESC";
                        break;
                    case 'Sort by Pending Status':
                        $getSQL = "SELECT * FROM orders WHERE order_status='Pending'";
                        break;
                    case 'Sort by Shipped Status':
                        $getSQL = "SELECT * FROM orders WHERE order_status='Shipped'";
                        break;
                    case 'Sort by Arrived Status':
                        $getSQL = "SELECT * FROM orders WHERE order_status='Arrived'";
                        break;
                    default:
                        $getSQL = "SELECT * FROM orders";
                        break;
                }
                $result = $conn->query($getSQL);
                $rows   = $result->num_rows;
                if (!$result) {
                    $error = $conn->errno . ' ' . $conn->error;
                    echo $error; // 1054 Unknown column 'foo' in 'field list'             
                }
                $getOrderSQL;
                $item_start_offset = ($pages - 1) * $items_per_page;
                switch ($sortValue) {
                    case 'Default Sorting':
                        $getOrderSQL = "SELECT * FROM orders LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by Latest':
                        $getOrderSQL = "SELECT * FROM orders ORDER BY date_ordered DESC LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by Pending Status':
                        $getOrderSQL = "SELECT * FROM orders WHERE order_status='Pending' LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by Shipped Status':
                        $getOrderSQL = "SELECT * FROM orders WHERE order_status='Shipped' LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by Arrived Status':
                        $getOrderSQL = "SELECT * FROM orders WHERE order_status='Arrived' LIMIT $item_start_offset , $items_per_page";
                        break;
                    default:
                        $getOrderSQL = "SELECT * FROM orders  LIMIT $item_start_offset , $items_per_page";
                        break;
                }

                $result_stmt = $conn->query($getOrderSQL);
                $items_returned = $result_stmt->num_rows;
                if (!$result) {
                    $error = $conn->errno . ' ' . $conn->error;
                    echo $error; // 1054 Unknown column 'foo' in 'field list'             
                }
                if ($rows == 0) {
                    echo <<<END
                    <div class="profile-content-history-checker-container profile-nav-header admin-content-history-checker-container">
                        <div class="profile-content-history-checker admin-content-history-checker">            
                                <span>It seems there is no orders yet...</span>            
                        </div>
                    </div>
                    END;
                    die();
                } else {
                    $total_pages = ceil($rows / $items_per_page);
                    echo <<<END
                    <div class="product-history-table-container">
                        <table class="product-history-table">
                            <thead>
                                <tr>
                                    <th style="width:5%;">ID</th>
                                    <th style="width:10%;">Customer</th>
                                    <th style="width:25%;">Product</th>
                                    <th style="width:7.5%;">Quantity</th>
                                    <th style="width:12.5%;">Price</th>
                                    <th style="width:12.5%;">Total</th>
                                    <th style="width:15%;">Date Ordered</th>
                                    <th style="width:20%;">Status</th>
                                </tr>
                            </thead>
                        <tbody id="order-table-body">
                    END;
                    $prod_name;
                    $prod_price;

                    for ($i = 0; $items_returned > $i; ++$i) {
                        $row = $result_stmt->fetch_array(MYSQLI_ASSOC);
                        $order_id       = htmlspecialchars($row['order_id']);
                        $product_id     = htmlspecialchars($row['product_id']);
                        $customer       = htmlspecialchars($row['customer_id']);
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
                        $getCustomerSQL = "SELECT * FROM customer where customer_id='" . $customer . "'";
                        $customer_stmt = $conn->query($getCustomerSQL);
                        if (!$customer_stmt) {
                            $error = $conn->errno . ' ' . $conn->error;
                            echo $error; // 1054 Unknown column 'foo' in 'field list'
                        }
                        $cust_rows = $customer_stmt->num_rows;
                        for ($j = 0; $cust_rows > $j; ++$j) {
                            $cust_row  = $customer_stmt->fetch_array(MYSQLI_ASSOC);
                            $cust_name = htmlspecialchars($cust_row['username']);
                           
                        }
                        echo <<<END
                      
                            <tr class="row-cells" onclick="checkItem('$product_id')">
                                <td>$order_id</td>
                                <td>$cust_name</td>
                                <td>$prod_name</td>
                                <td>$quantity</td>
                                <td>&#8369;$prod_price</td>
                                <td>&#8369;$total</td>
                                <td>$date_purchased</td>
                                <td>
                                <select name="order_status" id="order_status"  onchange="updateStatus(this.value,'$order_id');">
                                    <option value="" selected hidden disabled>$order_status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Shipped">Shipped</option>
                                    <option value="Arrived">Arrived</option>
                                </select>
                                </td>
                            </tr>                                     
                        END;
                    }
                    echo <<<END
                         </tbody>
                            </table>
                        </div>
                        <div class="pagination">
                        END;
                    $prev_page = $pages - 1;
                    $next_page = $pages + 1;
                    if ($pages > 1) {
                        echo <<<END
                            <a class="admin-pages" href="admin-order-page.php?pages=1&getOrder=$sortValue">First</a>
                            END;
                    }
                    if ($prev_page == 0) {
                        $prev_page = 1;
                        echo <<<END
                            <a style="background-color:white;"></a>
                            END;
                    } else {
                        echo <<<END
                         <a class="admin-pages" href="admin-order-page.php?pages=$prev_page&getOrder=$sortValue">Prev</a>
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
                            for ($items = 0; $items < 1; $items++) {
                                for ($i = 0; $i < count($adder); $i++) {
                                    $display[] = $max_page[$index + $adder[$i]];
                                }
                            }
                        } else if (count($max_page) == 2) {
                            for ($items = 0; $items < 2; $items++) {
                                $index = array_search($current_page, $max_page);
                                $adder = array(0, 1);
                                for ($i = 0; $i < count($adder); $i++) {
                                    $display[] = $max_page[$index + $adder[$i]];
                                }
                                if ($current_page == $display[$items]) {
                                    echo  '<a class="admin-pages admin-pages-current" href="admin-order-page.php?pages=' . $display[$items] . '&getOrder=' . $sortValue . '">' . $display[$items] . '</a>';
                                } else {
                                    echo  '<a class="admin-pages" href="admin-order-page.php?pages=' . $display[$items] . '&getOrder=' . $sortValue . '">' . $display[$items] . '</a>';
                                }
                            }
                        } else {
                            for ($items = $current_page - 1; $items <= $shown_pages; $items++) {
                                if ($current_page == $max_page[$items]) {
                                    echo  '<a class="admin-pages admin-pages-current" href="admin-order-page.php?pages=' . $max_page[$items] . '&getOrder=' . $sortValue . '">' . $max_page[$items] . '</a>';
                                } else {
                                    echo  '<a class="admin-pages" href="admin-order-page.php?pages=' . $max_page[$items] . '&getOrder=' . $sortValue . '">' . $max_page[$items] . '</a>';
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
                                echo  '<a class="admin-pages admin-pages-current" href="admin-order-page.php?pages=' . $display[$items] . '&getOrder=' . $sortValue . '">' . $display[$items] . '</a>';
                            } else {
                                echo  '<a class="admin-pages" href="admin-order-page.php?pages=' . $display[$items] . '&getOrder=' . $sortValue . '">' . $display[$items] . '</a>';
                            }
                        }
                    }
                    if ($next_page > $total_pages) {
                        $next_page = $pages;
                    } else {
                        echo <<<END
                         <a class="admin-pages" href="admin-order-page.php?pages=$next_page&getOrder=$sortValue">Next</a>
                         END;
                    }

                    if ($current_page == $total_pages) {
                    } else {
                        echo <<<END
                            <a class="admin-pages" href="admin-order-page.php?pages=$total_pages&getOrder=$sortValue">Last</a>
                            END;
                    }


                    echo <<<END
                        </div>
                        END;
                }
            }
        }
    }
}

if (isset($_GET['getProducts'])) {
    echo "<script>alert('We in here?')</script>";
    $sortValue  =   $_GET['getProducts'];
    if (isset($_SESSION['user'])) {
        if (isset($_SESSION['customer_id'])) {
            $conn = connect();
            if (mysqli_connect_error()) {
                echo 'MySQL Error: ' . mysqli_connect_error();
            } else {
                $items_per_page = 5;
                $pages = $_GET['pages'];
                $getSQL;
                switch ($sortValue) {
                    case 'Default Sorting':
                        $getSQL = "SELECT * FROM product";
                        break;
                    case 'Sort by Latest':
                        $getSQL = "SELECT * FROM product ORDER BY date_added DESC";
                        break;
                    case 'Sort by Low to High Price':
                        $getSQL = "SELECT * FROM product ORDER BY price ASC";
                        break;
                    case 'Sort by High to Low Price':
                        $getSQL = "SELECT * FROM product ORDER BY price DESC";
                        break;
                    default:
                        $getSQL = "SELECT * FROM product";
                        break;
                }
                $result = $conn->query($getSQL);
                $rows   = $result->num_rows;
                if (!$result) {
                    $error = $conn->errno . ' ' . $conn->error;
                    echo $error; // 1054 Unknown column 'foo' in 'field list'             
                }
                if ($rows == 0) {
                    echo <<<END
                    <div class="profile-content-history-checker-container profile-nav-header admin-content-history-checker-container">
                        <div class="profile-content-history-checker admin-content-history-checker">            
                                <span>It seems there is no Products yet...</span>            
                        </div>
                    </div>
                    END;
                    die();
                }else{
                    $getProductSQL;
                    $item_start_offset = ($pages - 1) * $items_per_page;
                    switch ($sortValue) {
                        case 'Default Sorting':
                            $getProductSQL = "SELECT * FROM product LIMIT $item_start_offset , $items_per_page";
                            break;
                        case 'Sort by Latest':
                            $getProductSQL = "SELECT * FROM product ORDER BY date_added DESC LIMIT $item_start_offset , $items_per_page";
                            break;
                        case 'Sort by Low to High Price':
                            $getProductSQL = "SELECT * FROM product ORDER BY price ASC LIMIT $item_start_offset , $items_per_page";
                            break;
                        case 'Sort by High to Low Price':
                            $getProductSQL = "SELECT * FROM product ORDER BY price DESC LIMIT $item_start_offset , $items_per_page";
                            break;
                        default:
                            $getProductSQL = "SELECT * FROM product LIMIT $item_start_offset , $items_per_page";
                            break;
                    }
    
                    $stmt = $conn->query($getProductSQL);
    
                    if (!$stmt) {
                        $error = $conn->errno . ' ' . $conn->error;
                        echo $error; // 1054 Unknown column 'foo' in 'field list'
                    }
                    echo <<< END
                    <div class="product-history-table-container">
                    <table class="product-history-table">
                        <thead>
                            <tr>
                                <th style="width:7.5%;">ID</th>
                                <th style="width:25%;">Product</th>
                                <th style="width:12.5%;">Category</th>
                                <th style="width:12.5%;">Price</th>
                                <th style="width:7.5%;">Stock</th>
                                <th style="width:15%;">Date Added</th>
                                <th style="width:20%;">Action</th>
                            </tr>
                        </thead>                       
                        <tbody id="products-table-body" class="products-table-body">
                    END;
                    $total_pages = ceil($rows / $items_per_page);
                    $prod_rows = $stmt->num_rows;
                    for ($j = 0; $prod_rows > $j; ++$j) {
                        $prod_row  = $stmt->fetch_array(MYSQLI_ASSOC);
                        $prod_name      = htmlspecialchars($prod_row['product_name']);
                        $prod_price     = htmlspecialchars($prod_row['price']);
                        $prod_id        = htmlspecialchars($prod_row['product_id']);
                        $prod_stock     = htmlspecialchars($prod_row['stock']);
                        $prod_category  = htmlspecialchars($prod_row['category']);
                        $prod_img       = htmlspecialchars($prod_row['product_img']);
                        $date_added     = htmlspecialchars($prod_row['date_added']);
                        echo <<<END
                         
                                <tr>
                                    <td>$prod_id</td>
                                    <td>$prod_name</td>
                                    <td>$prod_category</td>
                                    <td>&#8369;$prod_price</td>
                                    <td>$prod_stock</td>  
                                    <td>$date_added</td>  
                                    <td><a class="product_actions" href="admin-actions.php?removeProduct=$prod_id&productIMG=$prod_img">REMOVE</a> | <a class="product_actions" href="admin-edit-product-page.php?prod_id=$prod_id">EDIT</a> </td>                          
                                </tr>                                     
                        END;
                    }
                    echo <<<END
                        </tbody>                      
                        </table>
                    </div>
                    <div class="pagination">
                    END;
                    $prev_page = $pages - 1;
                    $next_page = $pages + 1;
                    if ($pages > 1) {
                        echo <<<END
                        <a class="admin-pages" href="admin-product-page.php?pages=1&getProducts=$sortValue">First</a>
                        END;
                    }
                    if ($prev_page == 0) {
                        $prev_page = 1;
                        echo <<<END
                        <a style="background-color:white;"></a>
                        END;
                    } else {
                        echo <<<END
                     <a class="admin-pages" href="admin-product-page.php?pages=$prev_page&getProducts=$sortValue">Prev</a>
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
                                    echo  '<a class="admin-pages admin-pages-current" href="admin-product-page.php?pages=' . $display[$items] . '&getProducts=' . $sortValue . '">' . $display[$items] . '</a>';
                                } else {
                                    echo  '<a class="admin-pages" href="admin-product-page.php?pages=' . $display[$items] . '&getProducts=' . $sortValue . '">' . $display[$items] . '</a>';
                                }
                            }
                        } else {
                            for ($items = $current_page - 1; $items <= $shown_pages; $items++) {
                                if ($current_page == $max_page[$items]) {
                                    echo  '<a class="admin-pages admin-pages-current" href="admin-product-page.php?pages=' . $max_page[$items] . '&getProducts=' . $sortValue . '">' . $max_page[$items] . '</a>';
                                } else {
                                    echo  '<a class="admin-pages" href="admin-product-page.php?pages=' . $max_page[$items] . '&getProducts=' . $sortValue . '">' . $max_page[$items] . '</a>';
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
                                echo  '<a class="admin-pages admin-pages-current" href="admin-product-page.php?pages=' . $display[$items] . '&getProducts=' . $sortValue . '">' . $display[$items] . '</a>';
                            } else {
                                echo  '<a class="admin-pages" href="admin-product-page.php?pages=' . $display[$items] . '&getProducts=' . $sortValue . '">' . $display[$items] . '</a>';
                            }
                        }
                    }
                    if ($next_page > $total_pages) {
                        $next_page = $pages;
                    } else {
                        echo <<<END
                     <a class="admin-pages" href="admin-product-page.php?pages=$next_page&getProducts=$sortValue">Next</a>
                     END;
                    }
    
                    if ($current_page == $total_pages) {
                    } else {
                        echo <<<END
                        <a class="admin-pages" href="admin-product-page.php?pages=$total_pages&getProducts=$sortValue">Last</a>
                        END;
                    }
                    echo <<<END
                    </div>
                    END;
                }
               
            }
        }
    }
}

if (isset($_GET['removeProduct'])) {
    $remove_id  =   $_GET['removeProduct'];
    $remove_img =   $_GET['productIMG'];

    if ($_SESSION['customer_id'] == 15) {
        $conn   =   connect();
        $query  =   "SELECT * FROM orders WHERE product_id=" . '"' . $remove_id . '"';
        $result =   $conn->query($query);
        if (!$result) {
            $error  = $conn->errno . ' ' . $conn->error;
            echo $error;
        }
        $rows   =   $result->num_rows;
        $orderMade  =   0;

        for ($i = 0; $rows > $i; ++$i) {
            $row    =   $result->fetch_array(MYSQLI_ASSOC);
            $product_id =   htmlspecialchars($row['product_id']);
            if ($product_id == $remove_id) {
                $orderMade++;
            }
            echo $product_id;
        }
        if ($orderMade != 0) {
            echo "<script>alert('Cannot remove product. There is an ongoing order of this product')</script>";
            echo "<script>window.location.href='admin-product-page.php?status=error&msg=Cannot remove product. There is an ongoing order of this product'</script>";
            die();
        }

        if (!empty($remove_img)) {
            if (!unlink($remove_img)) {
                echo "<script>alert('Action is stopped due to an error')</script>";
                echo "<script>window.location.href='admin-product-page.php?status=error&msg=Action is stopped due to an error'</script>";
                die();
            }
        }
        $conn = connect();
        $deleteQuery    =   "DELETE FROM product WHERE product_id=" . '"' . $remove_id . '"';
        $result     =   $conn->query($deleteQuery);
        if (!$result) {
            $error  = $conn->errno . ' ' . $conn->error;
            echo $error;
        }
        echo "<script>alert(Product ID:  ' + $remove_id + ' has been successfully deleted')</script>";
        echo "<script>window.location.href='admin-product-page.php?status=error&msg=Product ID:  ' + $remove_id + ' has been successfully deleted'</script>";
        die();
    }
}

if (isset($_GET['getUser'])) {
    $sortValue  =   $_GET['getUser'];
    if (isset($_SESSION['user'])) {
        if (isset($_SESSION['customer_id'])) {
            $conn = connect();
            if (mysqli_connect_error()) {
                echo 'MySQL Error: ' . mysqli_connect_error();
            } else {

                $items_per_page = 5;
                $pages = $_GET['pages'];
                $getOrderSQL;
                switch ($sortValue) {
                    case 'Default Sorting':
                        $getOrderSQL = "SELECT * FROM customer";
                        break;
                    case 'Sort by ID':
                        $getOrderSQL = "SELECT * FROM customer ORDER BY customer_id DESC";
                        break;
                    case 'Sort by Name[A-Z]':
                        $getOrderSQL = "SELECT * FROM customer ORDER BY username ASC ";
                        break;
                    case 'Sort by Name[Z-A]':
                        $getOrderSQL = "SELECT * FROM customer ORDER BY username DESC";
                        break;
                    default:
                        $getOrderSQL = "SELECT * FROM customer";
                        break;
                }
                $stmt = $conn->query($getOrderSQL);
                $rows   = $stmt->num_rows;
                $item_start_offset = ($pages - 1) * $items_per_page;
                $getUserSQL;
                switch ($sortValue) {
                    case 'Default Sorting':
                        $getUserSQL = "SELECT * FROM customer LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by ID':
                        $getUserSQL = "SELECT * FROM customer ORDER BY customer_id DESC LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by Name[A-Z]':
                        $getUserSQL = "SELECT * FROM customer ORDER BY username ASC LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by Name[Z-A]':
                        $getUserSQL = "SELECT * FROM customer ORDER BY username DESC LIMIT $item_start_offset , $items_per_page";
                        break;
                    default:
                        $getUserSQL = "SELECT * FROM customer LIMIT $item_start_offset , $items_per_page";
                        break;
                }
                $result = $conn->query($getUserSQL);
                $user_rows   = $result->num_rows;
                if (!$result) {
                    $error = $conn->errno . ' ' . $conn->error;
                    echo $error; // 1054 Unknown column 'foo' in 'field list'             
                }
                if ($rows == 0) {
                    echo <<<END
                    <div class="profile-content-history-checker-container profile-nav-header admin-content-history-checker-container">
                        <div class="profile-content-history-checker admin-content-history-checker">            
                                <span>It seems there is no orders yet...</span>            
                        </div>
                    </div>
                    END;
                    die();
                } else {
                    echo <<<END
                    <div class="product-history-table-container">
                        <table class="product-history-table">
                            <thead>
                                <tr>
                                    <th style="width:10%;">ID</th>
                                    <th style="width:25%;">Username</th>
                                    <th style="width:10%;">Email</th>
                                 
                                </tr>
                            </thead>
                        <tbody id="order-table-body">
                    END;
                    $prod_name;
                    $prod_price;
                    $total_pages = ceil($rows / $items_per_page);
                    for ($i = 0; $user_rows > $i; ++$i) {
                        $row = $result->fetch_array(MYSQLI_ASSOC);
                        $customer_id       = htmlspecialchars($row['customer_id']);
                        $username     = htmlspecialchars($row['username']);
                        $email       = htmlspecialchars($row['email']);

                        echo <<<END
                      
                            <tr class="row-cells">
                                <td>$customer_id</td>
                                <td>$username</td>
                                <td>$email</td>
                                
                            </tr>                                     
                        END;
                    }
                    echo <<<END
                         </tbody>
                            </table>
                        </div>
                        <div class="pagination">
                        END;
                    $prev_page = $pages - 1;
                    $next_page = $pages + 1;
                    if ($pages > 1) {
                        echo <<<END
                            <a class="admin-pages" href="admin-users.php?pages=1&getUser=$sortValue">First</a>
                            END;
                    }
                    if ($prev_page == 0) {
                        $prev_page = 1;
                        echo <<<END
                            <a style="background-color:white;"></a>
                            END;
                    } else {
                        echo <<<END
                         <a class="admin-pages" href="admin-users.php?pages=$prev_page&getUser=$sortValue">Prev</a>
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
                            for ($items = 0; $items < 1; $items++) {
                                for ($i = 0; $i < count($adder); $i++) {
                                    $display[] = $max_page[$index + $adder[$i]];
                                }
                            }
                        } else if (count($max_page) == 2) {
                            for ($items = 0; $items < 2; $items++) {
                                $index = array_search($current_page, $max_page);
                                $adder = array(0, 1);
                                for ($i = 0; $i < count($adder); $i++) {
                                    $display[] = $max_page[$index + $adder[$i]];
                                }
                                if ($current_page == $display[$items]) {
                                    echo  '<a class="admin-pages admin-pages-current" href="admin-users.php?pages=' . $display[$items] . '&getUser=' . $sortValue . '">' . $display[$items] . '</a>';
                                } else {
                                    echo  '<a class="admin-pages" href="admin-users.php?pages=' . $display[$items] . '&getUser=' . $sortValue . '">' . $display[$items] . '</a>';
                                }
                            }
                        } else {
                            for ($items = $current_page - 1; $items <= $shown_pages; $items++) {
                                if ($current_page == $max_page[$items]) {
                                    echo  '<a class="admin-pages admin-pages-current" href="admin-users.php?pages=' . $max_page[$items] . '&getUser=' . $sortValue . '">' . $max_page[$items] . '</a>';
                                } else {
                                    echo  '<a class="admin-pages" href="admin-users.php?pages=' . $max_page[$items] . '&getUser=' . $sortValue . '">' . $max_page[$items] . '</a>';
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
                                echo  '<a class="admin-pages admin-pages-current" href="admin-users.php?pages=' . $display[$items] . '&getUser=' . $sortValue . '">' . $display[$items] . '</a>';
                            } else {
                                echo  '<a class="admin-pages" href="admin-users.php?pages=' . $display[$items] . '&getUser=' . $sortValue . '">' . $display[$items] . '</a>';
                            }
                        }
                    }
                    if ($next_page > $total_pages) {
                        $next_page = $pages;
                    } else {
                        echo <<<END
                         <a class="admin-pages" href="admin-users.php?pages=$next_page&getUser=$sortValue">Next</a>
                         END;
                    }

                    if ($current_page == $total_pages) {
                    } else {
                        echo <<<END
                            <a class="admin-pages" href="admin-users.php?pages=$total_pages&getUser=$sortValue">Last</a>
                            END;
                    }
                    echo <<<END
                        </div>
                        END;
                }
            }
        }
    }
}

if (isset($_GET['getAddress'])) {
    $sortValue  =   $_GET['getAddress'];
    if (isset($_SESSION['user'])) {
        if (isset($_SESSION['customer_id'])) {
            $conn = connect();
            if (mysqli_connect_error()) {
                echo 'MySQL Error: ' . mysqli_connect_error();
            } else {

                $items_per_page = 5;
                $pages = $_GET['pages'];
                $getOrderSQL;
                switch ($sortValue) {
                    case 'Default Sorting':
                        $getOrderSQL = "SELECT * FROM billing_info";
                        break;
                    case 'Sort by First Name':
                        $getOrderSQL = "SELECT * FROM billing_info ORDER BY first_name DESC";
                        break;
                    case 'Sort by Last Name':
                        $getOrderSQL = "SELECT * FROM billing_info ORDER BY last_name DESC";
                        break;
                    case 'Sort by Region':
                        $getOrderSQL = "SELECT * FROM billing_info ORDER BY region DESC";
                        break;
                    case 'Sort by Province':
                        $getOrderSQL = "SELECT * FROM billing_info ORDER BY province DESC";
                        break;
                    case 'Sort by City':
                        $getOrderSQL = "SELECT * FROM billing_info ORDER BY town_city DESC";
                        break;
                    default:
                        $getOrderSQL = "SELECT * FROM billing_info";
                        break;
                }
                $stmt = $conn->query($getOrderSQL);
                $rows   = $stmt->num_rows;
                $item_start_offset = ($pages - 1) * $items_per_page;
                $getUserSQL;
                switch ($sortValue) {
                    case 'Default Sorting':
                        $getUserSQL = "SELECT * FROM billing_info LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by First Name':
                        $getUserSQL = "SELECT * FROM billing_info ORDER BY first_name DESC LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by Last Name':
                        $getUserSQL = "SELECT * FROM billing_info ORDER BY last_name DESC LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by Region':
                        $getUserSQL = "SELECT * FROM billing_info ORDER BY region DESC LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by Region':
                        $getUserSQL = "SELECT * FROM billing_info ORDER BY province DESC LIMIT $item_start_offset , $items_per_page";
                        break;
                    case 'Sort by Region':
                        $getUserSQL = "SELECT * FROM billing_info ORDER BY town_city DESC LIMIT $item_start_offset , $items_per_page";
                        break;
                    default:
                        $getUserSQL = "SELECT * FROM billing_info LIMIT $item_start_offset , $items_per_page";
                        break;
                }
                $result = $conn->query($getUserSQL);
                $user_rows   = $result->num_rows;
                if (!$result) {
                    $error = $conn->errno . ' ' . $conn->error;
                    echo $error; // 1054 Unknown column 'foo' in 'field list'             
                }
                if ($rows == 0) {
                    echo <<<END
                    <div class="profile-content-history-checker-container profile-nav-header admin-content-history-checker-container">
                        <div class="profile-content-history-checker admin-content-history-checker">            
                                <span>It seems there is no orders yet...</span>            
                        </div>
                    </div>
                    END;
                    die();
                } else {
                    echo <<<END
                    <div class="product-history-table-container">
                        <table class="product-history-table address-table">
                            <thead>
                                <tr>
                                    <th >User</th>
                                    <th >First Name</th>
                                    <th >Last Name</th>
                                    <th >Mobile</th>
                                    <th >Country</th>
                                    <th >Region</th>                                 
                                    <th >Province</th>
                                    <th >City</th>
                                    <th >ZIP</th>
                                    <th >Brgy</th>
                                    <th >Unit #</th>                              
                                </tr>
                            </thead>
                        <tbody id="order-table-body">
                    END;
                    $prod_name;
                    $prod_price;
                    $total_pages = ceil($rows / $items_per_page);
                   
                    for ($i = 0; $user_rows > $i; ++$i) {

                        $row = $result->fetch_array(MYSQLI_ASSOC);
                        $customer_id       = htmlspecialchars($row['customer_id']);
                        $first_name = strval(htmlspecialchars($row['first_name']));
                        $last_name = strval(htmlspecialchars($row['last_name']));
                        $mobile_number = strval(htmlspecialchars($row['mobile_number']));
                        $country = strval(htmlspecialchars($row['country']));
                        $region = strval(htmlspecialchars($row['region']));
                        $postcode_zip = strval(htmlspecialchars($row['postcode_zip']));
                        $province = strval(htmlspecialchars($row['province']));
                        $town_city = strval(htmlspecialchars_decode($row['town_city']));
                        $brgy = strval(htmlspecialchars($row['brgy']));
                        $street_address = strval(htmlspecialchars($row['street_address']));

                        $getCustomerSQL = "SELECT * FROM customer where customer_id='$customer_id'";
                        $cust_result = $conn->query($getCustomerSQL);
                        $cust_row = $cust_result->fetch_array(MYSQLI_ASSOC);
                        $cust_name = htmlspecialchars($cust_row['username']);

                        echo <<<END
                      
                            <tr class="row-cells">
                                <td>$cust_name</td>
                                <td>$first_name</td>
                                <td>$last_name</td>
                                <td>$mobile_number</td>
                                <td>$country</td>
                                <td>$region</td>
                                <td>$province</td>
                                <td>$town_city</td>
                                <td>$postcode_zip</td>
                                <td>$brgy</td>
                                <td>$street_address</td>   
                            </tr>                                     
                        END;
                    }
                    echo <<<END
                         </tbody>
                            </table>
                        </div>
                        <div class="pagination">
                        END;
                    $prev_page = $pages - 1;
                    $next_page = $pages + 1;
                    if ($pages > 1) {
                        echo <<<END
                            <a class="admin-pages" href="admin-address.php?pages=1&getUser=$sortValue">First</a>
                            END;
                    }
                    if ($prev_page == 0) {
                        $prev_page = 1;
                        echo <<<END
                            <a style="background-color:white;"></a>
                            END;
                    } else {
                        echo <<<END
                         <a class="admin-pages" href="admin-address.php?pages=$prev_page&getUser=$sortValue">Prev</a>
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
                            for ($items = 0; $items < 1; $items++) {
                                for ($i = 0; $i < count($adder); $i++) {
                                    $display[] = $max_page[$index + $adder[$i]];
                                }
                            }
                        } else if (count($max_page) == 2) {
                            for ($items = 0; $items < 2; $items++) {
                                $index = array_search($current_page, $max_page);
                                $adder = array(0, 1);
                                for ($i = 0; $i < count($adder); $i++) {
                                    $display[] = $max_page[$index + $adder[$i]];
                                }
                                if ($current_page == $display[$items]) {
                                    echo  '<a class="admin-pages admin-pages-current" href="admin-address.php?pages=' . $display[$items] . '&getUser=' . $sortValue . '">' . $display[$items] . '</a>';
                                } else {
                                    echo  '<a class="admin-pages" href="admin-address.php?pages=' . $display[$items] . '&getUser=' . $sortValue . '">' . $display[$items] . '</a>';
                                }
                            }
                        } else {
                            for ($items = $current_page - 1; $items <= $shown_pages; $items++) {
                                if ($current_page == $max_page[$items]) {
                                    echo  '<a class="admin-pages admin-pages-current" href="admin-address.php?pages=' . $max_page[$items] . '&getUser=' . $sortValue . '">' . $max_page[$items] . '</a>';
                                } else {
                                    echo  '<a class="admin-pages" href="admin-address.php?pages=' . $max_page[$items] . '&getUser=' . $sortValue . '">' . $max_page[$items] . '</a>';
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
                                echo  '<a class="admin-pages admin-pages-current" href="admin-address.php?pages=' . $display[$items] . '&getUser=' . $sortValue . '">' . $display[$items] . '</a>';
                            } else {
                                echo  '<a class="admin-pages" href="admin-address.php?pages=' . $display[$items] . '&getUser=' . $sortValue . '">' . $display[$items] . '</a>';
                            }
                        }
                    }
                    if ($next_page > $total_pages) {
                        $next_page = $pages;
                    } else {
                        echo <<<END
                         <a class="admin-pages" href="admin-address.php?pages=$next_page&getUser=$sortValue">Next</a>
                         END;
                    }

                    if ($current_page == $total_pages) {
                    } else {
                        echo <<<END
                            <a class="admin-pages" href="admin-address.php?pages=$total_pages&getUser=$sortValue">Last</a>
                            END;
                    }
                    echo <<<END
                        </div>
                        END;
                }
            }
        }
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
