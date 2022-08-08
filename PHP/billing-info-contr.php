<?php

require_once 'billing-info-query.php';

class billingInfoContr extends billingInfoQuery {

    private $first_name;     
    private $last_name;     
    private $mob_num;        
    private $country;     
    private $region;     
    private $province;    
    private $post_code;     
    private $city;     
    private $brgy;           
    private $street_address; 
    private $href;

    
    public function __construct($first_name, $last_name, $mob_num, $country, $region, $province, $post_code, $city, $brgy, $street_address, $href)
    {
        $this -> first_name     = $first_name;
        $this -> last_name      = $last_name;
        $this -> mob_num        = $mob_num;
        $this -> country        = $country;
        $this -> region         = $region;
        $this -> province       = $province;
        $this -> post_code      = $post_code;
        $this -> city           = $city;
        $this -> brgy           = $brgy;
        $this -> street_address = $street_address;
        $this -> href = $href;
    }

    public function emptyInput(){
        if(empty($this -> first_name) || empty($this -> last_name) || empty($this -> mob_num) || 
        empty($this -> country) || empty($this -> region) || empty($this -> post_code) ||
        empty($this ->city) || empty($this ->brgy) || empty($this ->street_address) || empty( $this -> province)){
            header('location: ../HTML/' .$this->href . '.php?status=error&msg=Should fill all fields');
            die();
        }
    }

    public function invalidInput(){
        if (!preg_match("/^[a-zA-Z0-9]*$/", $this->first_name) || !preg_match("/^[a-zA-Z0-9]*$/", $this->last_name) || 
        !preg_match("/^[a-zA-Z0-9]*$/", $this->mob_num) || !preg_match("/^[a-zA-Z0-9]*$/", $this->country) ||
        !preg_match("/^[a-zA-Z0-9]*$/", $this->region) || !preg_match("/^[a-zA-Z0-9]*$/", $this->post_code)||
        !preg_match("/^[a-zA-Z0-9]*$/", $this->city) || !preg_match("/^[a-zA-Z0-9]*$/", $this->brgy)||
        !preg_match("/^[a-zA-Z0-9]*$/", $this->street_address) || !preg_match("/^[a-zA-Z0-9]*$/", $this->province)) {
            header('location: ../HTML/' .$this->href . '.php?status=error&msg=Only alphanumeric characters are allowed');
            die();
        }
    }

    public function saveAddress(){
        if($this -> insertAddress( $this->first_name, $this -> last_name, $this -> mob_num, 
        $this -> country, $this -> region, $this -> province, $this -> post_code, $this -> city, 
        $this -> brgy, $this -> street_address)){
            header('location: ../HTML/edit-info.php?status=error&msg=Billing info successfully added');
            die();
        }
    }

    public function addAddress(){
        if($this -> insertAddress( $this->first_name, $this -> last_name, $this -> mob_num, 
        $this -> country, $this -> region, $this -> province, $this -> post_code, $this -> city, 
        $this -> brgy, $this -> street_address)){
        }
    }
    public function changeAddress(){
        if($this -> updateAddress( $this->first_name, $this -> last_name, $this -> mob_num, 
        $this -> country, $this -> region, $this -> province, $this -> post_code, $this -> city, 
        $this -> brgy, $this -> street_address)){
            header('location: ../HTML/edit-info.php?status=error&msg=Billing info successfully updated');
            die();
        }
    }

    public function checkOutAddress(){
        if($this -> updateAddress( $this->first_name, $this -> last_name, $this -> mob_num, 
        $this -> country, $this -> region, $this -> province, $this -> post_code, $this -> city, 
        $this -> brgy, $this -> street_address)){
        }
    }

}