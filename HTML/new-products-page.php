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
        function redirectProduct(product_id) {

            window.location.href = "products.php?q=" + product_id;
        }
    </script>

</head>

<body onload="getItems()">
    <div class="products-main-container">
        <div class="products-content-container">
            <div class="products-category products-address buffer"><span>NEW PRODUCTS</span>
                <div class="products-address"><span><a href="index.php">HOME</a></span> &nbsp; / &nbsp;<span class="product">Keyboards</span></div>
            </div>
            <div class="products-category"><span>NEW PRODUCTS</span>
                <div class="products-address"><span><a href="index.php">HOME</a></span> &nbsp; / &nbsp;<span class="product">What's New?</span></div>
            </div>
            <select class="sort-shop" name="sort-shop" id="sort-shop" onchange="getItems()">
                <?php
                if (isset($_GET['sortItems'])) {
                    echo '<option value="' . $_GET['sortItems'] . '"selected hidden>' . $_GET['sortItems'] . '</option>';
                    echo <<<END
                                    <option value="Sort by Latest">Sort by Latest</option>
                                    <option value="Sort by Low to High Price">Sort by Low to High Price</option>
                                    <option value="Sort by High to Low Price">Sort by High to Low Price</option>
                                    END;
                } else {
                    echo <<<END
                                    <option value="Sort by Latest" selected>Sort by Latest</option>
                                    <option value="Sort by Low to High Price">Sort by Low to High Price</option>
                                    <option value="Sort by High to Low Price">Sort by High to Low Price</option>
                                    END;
                }
                ?>
            </select>
        </div>
        <div class="products-grid-container">
            <div id="products-grid-wrapper" class="products-grid-wrapper">

            </div>
        </div>
    </div>
</body>
<?php
$pages;
if (!isset($_GET['pages'])) {
    $pages = 1;
} else {
    $pages = $_GET['pages'];
}

?>
<script>
    var pages = '<?php echo $pages; ?>'

    function getItems() {
        var sortValue = document.getElementById('sort-shop').value;
        var value = 0
        var xml = new XMLHttpRequest();
        xml.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('products-grid-wrapper').innerHTML = this.response;
            }
        }
        xml.open("GET", "getProducts.php?getItems= " + value + "&pages=" + pages + "&sortItems=" + sortValue, true);
        xml.send();
    }
</script>
<script src="JS/index.js"></script>

</html>