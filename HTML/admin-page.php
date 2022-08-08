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

<body onload="getDatas()">
    <div class="main-admin-page">
        <div class="admin-nav-container">
            <div class="admin-nav-container-wrapper">
                <div class="admin-nav-content admin-current-page">DASHBOARD</div>
                <div class="admin-nav-content" onclick="redirect('admin-order-page')">ORDERS</div>
                <div class="admin-nav-content" onclick="redirect('admin-product-page')">PRODUCTS</div>
                <div class="admin-nav-content" onclick="redirect('admin-users')">USERS</div>
                <div class="admin-nav-content" onclick="redirect('admin-address')">ADDRESS</div>
                <div class="admin-nav-content" onclick="redirect('../PHP/logout')">LOGOUT</div>
            </div>
        </div>
        <div class="admin-main-view-container">
            <div class="dashboard-header">
                <span>DASHBOARD</span>
            </div>
            <div class="dashboard-wrapper">
                <div id="dashboard-content-container" class="dashboard-content-container">
                
                </div>
            </div>
            
        </div>
    </div>
</body>
<script>
    function getDatas(){
        var xml = new XMLHttpRequest();
        xml.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200){
                document.getElementById('dashboard-content-container').innerHTML = this.responseText;
            }
        }
        xml.open("GET", "admin-actions.php?getDatas=get",true);
        xml.send();
    }   
</script>
<script src="JS/index.js"></script>
</html>