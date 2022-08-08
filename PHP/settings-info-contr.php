<?php

    require_once 'login-query.php';

    class settingInfoContr extends loginQuery{
        private $username;            
        private $email;                
        private $current_password;    
        private $new_password;           
        private $confirm_password; 

        public function __construct($username, $email, $current_password, $new_password, $confirm_password)
        {
            $this-> username           = $username;            
            $this-> email              = $email;                
            $this-> current_password   = $current_password;    
            $this-> new_password       = $new_password;           
            $this-> confirm_password   = $confirm_password;  
        }

        public function emptyInput(){
            if(empty($this-> username ) || empty($this-> email) || empty($this-> current_password ) 
            ||empty($this-> new_password) || empty($this-> confirm_password)){
                header('location: ../HTML/settings.php?status=error&msg=Should fill all input required fields'); 
                die();
            }
        }

        public function pwdMatch()
        {
            if ($this->new_password !== $this->confirm_password) {
                header('location: ../HTML/settings.php?status=error&msg=Password does not match');
                die();
            }
        }

        public function checkPasswordExist(){
            if($this -> checkPassword($this->current_password)){
                      
            }else{
                header('location: ../HTML/settings.php?status=error&msg=Current Password does not match'); 
                die();
            }
        }

        public function updateAccount(){
            if($this -> updateAccountDetails($this->username, $this->email, $this->new_password)){
                header('location: ../HTML/settings.php?status=error&msg=Account details successfully updated'); 
                die(); 
            }
        }
    }