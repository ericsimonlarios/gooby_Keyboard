<?php

    require_once 'billing-info-contr.php';

    if(isset($_POST['submit_billing'])){
        $first_name     = $_POST['first_name'];
        $last_name      = $_POST['last_name'];
        $mob_num        = $_POST['mob_num'];
        $country        = $_POST['country'];
        $region         = $_POST['region'];
        $province       = $_POST['province'];
        $post_code      = $_POST['post_code'];
        $city           = $_POST['city'];
        $brgy           = $_POST['brgy'];
        $street_address = $_POST['street_address'];
        $href           = $_POST['hiddenValue'];

        $billingInfo = new billingInfoContr($first_name, $last_name, $mob_num, $country, $region, $province, $post_code, $city,  $brgy, $street_address, $href );

        
        $billingInfo -> emptyInput();
        // $billingInfo -> invalidInput();
        $billingInfo -> changeAddress();
    }