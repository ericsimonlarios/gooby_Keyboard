<?php
    require_once "login-contr.php";
    session_start();
    if(isset($_POST["login-btn"])){
        $username = $_POST["user"];
        $pass = $_POST["pass"];
        $login_contr = new loginContr($username, $pass);  
        $login_contr -> emptyInput();
        $login_contr -> invalidInput();
        $login_contr -> login();
    }
    
?>