<?php

    require_once "sign-up-contr.php";
    require_once "../HTML/sign-up.php";
    
    if(isset($_POST['sign-up-btn'])){
        $user = $_POST['user'];
        $pass = $_POST['pass'];
        $email = $_POST['email'];
        $confirm_pass = $_POST['confirm_pass'];

        $signup_Contr = new Signup_Contr($user, $pass, $confirm_pass, $email);

        $signup_Contr -> emptyInput();
        $signup_Contr -> invalidInput();
        $signup_Contr -> pwdMatch();
        $signup_Contr -> check();
        $signup_Contr -> signup();
    }
    
?>