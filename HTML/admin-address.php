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

<body onload="getUser()">
    <div class="main-admin-page">
        <div class="admin-nav-container">
            <div class="admin-nav-container-wrapper">
                <div class="admin-nav-content" onclick="redirect('admin-page')">DASHBOARD</div>
                <div class="admin-nav-content" onclick="redirect('admin-order-page')">ORDERS</div>
                <div class="admin-nav-content" onclick="redirect('admin-product-page')">PRODUCTS</div>
                <div class="admin-nav-content">USERS</div>
                <div class="admin-nav-content  admin-current-page" onclick="redirect('admin-address')">ADDRESS</div>
                <div class="admin-nav-content" onclick="redirect('../PHP/logout')">LOGOUT</div>
            </div>
        </div>
        <div class="admin-main-view-container">
            <div id="order-content-container" class="dashboard-content-container product-content-container">
                <div class="profile-content-purchase-container  profile-nav-header product-content-purchase-container">
                    <div class="dashboard-table-header">
                        <span>ADDRESS</span>
                    </div>
                    <div class="sort-container">
                        <div class="sort-wrapper">
                        <select name="sort_products" id="sort_products" onchange="getUser()">
                            <?php
                                if(isset($_GET['getUser'])){
                                    echo '<option value="'. $_GET['getUser'] . '"selected hidden>'. $_GET['getUser'] . '</option>';
                                    echo <<<END
                                    <option value="Default Sorting">Default Sorting</option>
                                    <option value="Sort by First Name">Sort by First Name</option>
                                    <option value="Sort by Last Name">Sort by Last Name</option>
                                    <option value="Sort by Region">Sort by Region</option>
                                    <option value="Sort by Province">Sort by Province</option>
                                    <option value="Sort by City">Sort by City</option>
                                 

                                    END;
                                }else{
                                    echo <<<END
                                    <option value="Default Sorting">Default Sorting</option>
                                    <option value="Sort by First Name">Sort by First Name</option>
                                    <option value="Sort by Last Name">Sort by Last Name</option>
                                    <option value="Sort by Region">Sort by Region</option>
                                    <option value="Sort by Province">Sort by Province</option>
                                    <option value="Sort by City">Sort by City</option>
                                    END;
                                }
                            ?>             
                        </select>  
                        </div>   
                    </div>      
                    <div id="order-table" class="purchase-history-table-container admin-history-table-container">
                        
                               
                    </div>
                </div>
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
    function getUser() {
        var sortValue = document.getElementById('sort_products').value;
        var xml = new XMLHttpRequest();
        xml.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('order-table').innerHTML = this.responseText;
            }
        }
        xml.open("GET", "admin-actions.php?getAddress=" + sortValue + "&pages=" + pages, true);
        xml.send();
    }

</script>
<script src="JS/index.js"></script>

</html>