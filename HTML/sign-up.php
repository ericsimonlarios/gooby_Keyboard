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
    <div class="login-page sign-up-page">
        <div class="login-nav-container nav-container">
            <div class="login-logo-container" onclick="redirect('index')">
                <img src="https://cdn.discordapp.com/attachments/813649116700999680/928963302996987904/giphy.gif" alt="">
            </div>
        </div>
        <div class="login-box sign-up-box">
           
            <?php
            if (isset($_GET['status'])) {
                $message = $_GET['msg'];
                echo "<div class='error-announcer'> $message </div>";
            }
            ?>
            <div class="sign-in-text">Sign up</div>
            <div class="login-form-container sign-up-form-container">
                
                <form class="login-form-content sign-up-form-content" action="../PHP/sign-up-handler.php" method="post">

                    <div>
                        <label for="user">Username</label>
                        <input type="text" name="user" id="user" maxlength="16" required>
                    </div>
                    <div>
                        <label for="user">Email</label>
                        <input type="text" name="email" id="email" required>
                    </div>
                        <div>
                            <label for="">Password</label>
                            <input type="password" name="pass" id="pass" maxlength="18" required>
                        </div>
                        <div>
                            <label for="">Confirm Password</label>
                            <input type="password" name="confirm_pass" id="confirm_pass" maxlength="18" required>
                        </div>
                             
                    <div>
                        <input class="login-submit-button" type="submit" name="sign-up-btn" id="sign-up-btn" value="Sign up">
                    </div>

                    <div class="sign-up-redirect-container">
                        Already have an account? <a href="login.php">Sign in now</a>
                    </div>
                </form>
            </div>
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

</html>