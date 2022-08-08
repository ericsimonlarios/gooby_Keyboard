<?php
require_once 'dbcon.php';
class loginQuery extends dbh
{
    protected function checkUser($user,$pass){

        $conn = $this -> connect();
        $user = $this->mysql_entities_fix_string($user);
        $pass = $this->mysql_entities_fix_string($pass);
        $getHash = $conn -> prepare('SELECT pass FROM customer WHERE username = ?');
        $getHash -> bind_param('s', $user);
        $getHash -> execute();
        $getHash -> bind_result($hash);
        $getHash -> store_result();
        
        while ($getHash -> fetch()){
        
        }

        $stmt = $conn->prepare('SELECT customer_id FROM customer WHERE username = ? AND pass = ?');
        $stmt -> bind_param('ss', $user, $hash);
        $stmt -> execute();    
        $stmt -> bind_result($id);

        if($stmt -> store_result()){
            echo "We made it here";
            while($stmt -> fetch()){
            }
        }
        if(password_verify($pass, $hash))
        {
            session_start();
            $_SESSION['start'] = time();
            $_SESSION['user'] = $user;
            $_SESSION['customer_id'] = $id;
            $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
            $conn -> close(); 
            if($id == 15){
                return "admin";
            }else{
                return "not admin";
            }
              
            // if($stmt -> num_rows() === 1){
            //     $conn -> close();  
            //     return true;
            // }
            // else{
            //     $conn -> close();  
            //     return false;
            // }
    
            // if(!$stmt -> execute()){
            //     $stmt=null;
            //     header('location: sign_up.php?error=stmtfailed');
            //     exit();
            // }
        }
        else{
            $conn -> close(); 
            return false;
        }    
        
    }

    protected function checkPassword($currentPass){
        $conn = $this->connect();
        $currentPass = $this -> mysql_entities_fix_string($currentPass);
        session_start();
        $id = $_SESSION['customer_id'];
        $stmt = $conn->prepare('SELECT pass FROM customer where customer_id=?');
        $stmt -> bind_param('i',$id);
         if(!$stmt -> execute()){
                $stmt=null;
                header('location: ../HTML/settings.php?error=stmtfailed');
                exit();
            }
        $stmt -> bind_result($hashed_pass);
        $stmt -> store_result();
        while ($stmt -> fetch()){
        
        }
        if(password_verify($currentPass,$hashed_pass)){
            $conn -> close();
            return true;
        }else{
            $conn -> close();
            return false;
        }

    }

    protected function updateAccountDetails($username, $email, $new_password){
        $conn = $this -> connect();
        $username       = $this -> mysql_entities_fix_string($username);
        $email          = $this -> mysql_entities_fix_string($email);
        $new_password   = $this -> mysql_entities_fix_string($new_password);
        $new_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $id = $_SESSION['customer_id'];
        $stmt = $conn -> prepare("UPDATE customer SET username=?, email=?, pass=? where customer_id=?");
        $stmt -> bind_param("sssi", $username, $email, $new_password, $id);
        if(!$stmt -> execute()){
            $stmt=null;
            header('location: ../HTML/settings.php?error=stmtfailed');
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