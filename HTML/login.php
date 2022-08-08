<?php
session_start();
if (isset($_SESSION['user'])) {
    if ($_SESSION['customer_id'] == 15) {
        echo '<script>window.location.href="admin-page.php"</script>';
    }else{
        echo '<script>window.location.href="index.php"</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="..\CSS\index.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
    <link rel="mask-icon" href="../safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <title>Gooby's Keyboard</title>
</head>
<body>
    <div class="login-page">
        <div class="login-nav-container nav-container">
            <div class="login-logo-container" onclick="redirect('index')">
                <img src="https://cdn.discordapp.com/attachments/813649116700999680/928963302996987904/giphy.gif" alt="">
            </div>  
        </div>
        <div class="login-box"> 
            <div class="sign-in-text">Sign in</div>
            <?php
                if(isset($_GET['status'])){
                    if($_GET['status'] == 'success'){
                        $message=$_GET['msg'];
                    echo "<div class='successful-announcer'> $message </div>";
                 
                    }else{
                        $message=$_GET['msg'];
                        echo "<div class='error-announcer'> $message </div>";
        
                    }                  
                }
            ?>
            <div class="login-form-container">     
                <form class="login-form-content" action="../PHP/login-handler.php" method="post">
                    <div>
                        <label for="user">Username or Email</label>
                        <input type="text" name="user" id="user" maxlength="16" required>
                    </div>
                    <div>
                        <label for="">Password</label>
                        <input type="password" name="pass" id="pass" required>
                    </div>
                    <div>
                        <input class="login-submit-button" type="submit" name="login-btn" maxlength="18" value="Sign in">
                    </div>

                    <div class="sign-up-redirect-container">
                        No account yet? <a href="sign-up.php">Create an account</a>
                    </div>
                </form>
            </div>
           
        </div>

    </div>
</body>
<script src="JS/index.js"></script>
</html>