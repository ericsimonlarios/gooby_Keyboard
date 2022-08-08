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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gooby's Keyboard</title>
    <link rel="stylesheet" href="../CSS/index.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
    <link rel="mask-icon" href="../safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>

<body onload="getOrder()">
    <div class="main-admin-page">
        <div class="admin-nav-container">
            <div class="admin-nav-container-wrapper">
                <div class="admin-nav-content" onclick="redirect('admin-page')">DASHBOARD</div>
                <div class="admin-nav-content" onclick="redirect('admin-order-page')">ORDERS</div>
                <div class="admin-nav-content admin-current-page">PRODUCTS</div>
                <div class="admin-nav-content" onclick="redirect('admin-users')">USERS</div>
                <div class="admin-nav-content" onclick="redirect('admin-address')">ADDRESS</div>
                <div class="admin-nav-content" onclick="redirect('../PHP/logout')">LOGOUT</div>
            </div>
        </div>
        <div class="admin-main-view-container">
            <div id="order-content-container" class="dashboard-content-container product-content-container">
                <div class="profile-content-purchase-container  profile-nav-header product-content-purchase-container">
                    <div class="dashboard-table-header">
                        <span>Products</span>
                    </div>
                    
                    <div class="sort-container">
                        <div class="sort-wrapper">
                            <select name="sort_products" id="sort_products" onchange="getOrder()">
                            <?php
                             if (isset($_GET['status'])) {
                                $status = $_GET['status'];
                                $message = $_GET['msg'];
                                echo "<script>alert('$message')</script>";
                                echo "<script>window.location.href='admin-product-page.php'</script>";
                            }
                                if(isset($_GET['getProducts'])){
                                    echo '<option value="'. $_GET['getProducts'] . '"selected hidden>'. $_GET['getProducts'] . '</option>';
                                    echo <<<END
                                    <option value="Default Sorting">Default Sorting</option>
                                    <option value="Sort by Latest">Sort by Latest</option>
                                    <option value="Sort by Low to High Price">Sort by Low to High Price</option>
                                    <option value="Sort by High to Low Price">Sort by High to Low Price</option>
                                    END;
                                }else{
                                    echo <<<END
                                    <option value="Default Sorting" selected>Default Sorting</option>
                                    <option value="Sort by Latest">Sort by Latest</option>
                                    <option value="Sort by Low to High Price">Sort by Low to High Price</option>
                                    <option value="Sort by High to Low Price">Sort by High to Low Price</option>
                                    END;
                                }
                            ?>             
                               
                            </select>
                        </div>
                    </div>
                    <div id="products-container" class="purchase-history-table-container admin-history-table-container">
                        

                                
                    </div>
                </div>
            </div>
            <div class="add-product-button" onclick="redirect('admin-add-product-page')">
                <span>ADD PRODUCT</span>
            </div>

        </div>
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
    function getOrder() {
        var sortValue = document.getElementById('sort_products').value;
        var xml = new XMLHttpRequest();
        xml.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('products-container').innerHTML = this.responseText;
            }
        }
        xml.open("GET", "admin-actions.php?getProducts=" + sortValue + "&pages=" + pages, true);
        xml.send();
    }
</script>
<script src="JS/index.js"></script>

</html>