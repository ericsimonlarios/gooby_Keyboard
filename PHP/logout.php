<?php
    require_once "sessionChecker.php";
    $sessionDelete = new sessionCheck();
    $sessionDelete -> sessionDestroy();
    unset($_SESSION['user']);
    header('location: ../HTML/index.php');
?>