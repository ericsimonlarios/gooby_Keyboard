<?php

require_once 'dbcon.php';

class billingInfoQuery extends dbh{

    protected function insertAddress($first_name, $last_name, $mob_num, 
    $country, $region, $province, $post_code, $city, $brgy, $street_address){

        session_start();
        $id             = $_SESSION['customer_id'];
        $first_name     = $this -> mysql_entities_fix_string($first_name);
        $last_name      = $this -> mysql_entities_fix_string($last_name);
        $mob_num        = $this -> mysql_entities_fix_string($mob_num);

        $country        = $this -> mysql_entities_fix_string($country);
        $region         = $this -> mysql_entities_fix_string($region);
        $province       = $this -> mysql_entities_fix_string($province);
        $post_code      = $this -> mysql_entities_fix_string($post_code);
        $city           = $this -> mysql_entities_fix_string($city);
        $brgy           = $this -> mysql_entities_fix_string($brgy);
        $street_address = $this -> mysql_entities_fix_string($street_address);

        $conn = $this -> connect();
        $stmt = $conn -> prepare("INSERT INTO billing_info (customer_id, first_name, last_name, country, street_address, town_city, region, province, postcode_zip, mobile_number, brgy) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $stmt -> bind_param('issssssssss', $id, $first_name, $last_name, $country, $street_address,$city, $region, $province, $post_code, $mob_num, $brgy);
  
        if(!$stmt -> execute()){
            $stmt=null;
            $error = $conn->errno . ' ' . $conn->error;
            echo $error;
            header('location: ../HTML/billing-info.php?error=stmtfailed');
            $conn -> close(); 
            exit();
        }
       
        $conn -> close();
        return true;
    }

    protected function updateAddress($first_name, $last_name, $mob_num, 
    $country, $region, $province, $post_code, $city, $brgy, $street_address){
        session_start();
        $id = $_SESSION['customer_id'];
        $first_name     = $this -> mysql_entities_fix_string($first_name);
        $last_name      = $this -> mysql_entities_fix_string($last_name);
        $mob_num        = $this -> mysql_entities_fix_string($mob_num);
        $country        = $this -> mysql_entities_fix_string($country);
        $region         = $this -> mysql_entities_fix_string($region);
        $province       = $this -> mysql_entities_fix_string($province);
        $post_code      = $this -> mysql_entities_fix_string($post_code);
        $city           = $this -> mysql_entities_fix_string($city);
        $brgy           = $this -> mysql_entities_fix_string($brgy);
        $street_address = $this -> mysql_entities_fix_string($street_address);

        $conn = $this -> connect();
        $stmt = $conn -> prepare("UPDATE billing_info SET first_name=?, last_name=?, country=?, street_address=?, town_city=?, region=?, province=?, postcode_zip=?, mobile_number=?, brgy=? where customer_id=?");
        $stmt -> bind_param('ssssssssssi', $first_name, $last_name, $country, $street_address,$city, $region, $province, $post_code, $mob_num, $brgy, $id);
  
        if(!$stmt -> execute()){
            $stmt=null;
            $error = $conn->errno . ' ' . $conn->error;
            echo $error;
            // header('location: ../HTML/billing-info.php?error=stmtfailed');
            // $conn -> close(); 
            exit();
        }
        
        $conn -> close();
        return true;

    }

    function mysql_entities_fix_string($string)
    {
        $string = strip_tags($string); // strips any html tags
        return htmlentities($string);
    }
}