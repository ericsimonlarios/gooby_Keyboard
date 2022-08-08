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
require 'nav.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/index.css">
</head>

<body>
    <div class="about-us">
        <div class="about-us-container">
            <div class="about-us-wrapper">
                <div class="about-us-title">
                    <h1>ABOUT US</h1>
                </div>
                <div>
                    <h2 style="text-align:center;">Website Developers</h2>
                </div>
                <div class="developers">
                    <div class="developer-wrapper">
                        <div class="icon-zagreus">
                            <div class="icon-container">
                                <img src="../RES/zagreus.png" alt="">
                            </div>
                        </div>
                        <div class="nickname">
                            <span>Zagreus</span>
                        </div>
                        <div class="fname">
                            <span>(Eric Simon Larios)</span>
                        </div>
                        <div class="role">
                            <span>Roles :</span>
                            <span>Full Stack Developer</span>
                        </div>

                    </div>
                    <div class="developer-wrapper">
                    <div class="icon-zagreus">
                        <div class="icon-gooby">
                            <img src="../RES/Gooby2.png" alt="">
                        </div>
                        </div>
                        <div class="nickname">
                            <span>Gooby</span>
                        </div>
                        <div class="fname">
                            <span>(Joshua Austria)</span>
                        </div>
                        <div class="role">
                            <span>Roles :</span>
                            <span>Front End Developer</span>
                            <span>Inventory Manager</span>
                        </div>
                    </div>
                </div>
                <div class="developers-notes margin-top20">
                    <p>We are third year college students at Polytechnic University of the Philippines (Sta. Mesa) and are currently under the BSCOE program.
                        We have permissions from the client for this project to be used as our final project in Web Development. 
                    </p>
                </div>
                <div>
                    <h2 style="text-align:center;">The Website</h2>
                </div>
                <div class="website-notes margin-top20">
                    <p>The Gooby Keyboard's Website is a E-commerce websystem, designed using HTML, CSS, Javascript, while its backend is developed using PHP.
                        The Gooby Keyboard aims to provide keyboard stuffs to Keyboard Enthusiast around the country.
                    </p>
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
    </div>
</body>
<script src="JS/index.js"></script>

</html>