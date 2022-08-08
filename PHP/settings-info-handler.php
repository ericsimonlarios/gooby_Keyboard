<?php
    require_once 'settings-info-contr.php';

    if(isset($_POST['submit_settings'])){

        $username           = $_POST['user_settings'];
        $email              = $_POST['email_settings'];
        $current_password   = $_POST['pass_settings'];
        $new_password       = $_POST['newpass_settings'];
        $confirm_password   = $_POST['confirm_pass_settings'];

        $settingsObj = new settingInfoContr($username, $email, $current_password, $new_password, $confirm_password);
        // $settingsObj -> emptyInput();
        // $settingsObj -> pwdMatch();
        $settingsObj -> checkPasswordExist();
        $settingsObj -> updateAccount();

    }