<?php
    require_once "login-query.php";
   
    class loginContr extends loginQuery{
        
        private $user;
        private $pass;
    

        public function __construct($user, $pass)
        {
            $this -> user = $user;
            $this -> pass = $pass;
        
        }

        public function emptyInput()
    {
        if (empty($this->user) || empty($this->pass)) {
            header('location: ../HTML/login.php?status=error&msg=Should fill all input required fields'); 
            die();
        }
        
    }

    public function invalidInput()
    {
        if (!preg_match("/^[a-zA-Z0-9]*$/", $this->user) || !preg_match("/^[a-zA-Z0-9]*$/", $this->pass)) {
            header('location: ../HTML/login.php?status=error&msg=Only alphanumeric characters are allowed');
            die();
        }
    }

    public function login()
    {   
        $checkUser = $this->checkUser($this->user, $this->pass);
        
        if ($checkUser == "admin") {
            // die ("<p><a href='index.php'>Click here to continue</a></p>");
           
            header("location: ../HTML/admin-page.php");
            die();
          
            
        } else if($checkUser == "not admin"){
            header("location: ../HTML/index.php");
                die();
        }
        else{
            header('location: ../HTML/login.php?status=error&msg=Credentials does not exist');
            die();
        }
    }
}
    
?>
