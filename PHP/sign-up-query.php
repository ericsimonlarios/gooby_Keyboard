<?php

require_once 'dbcon.php';

class Signup extends dbh
{
    protected function insertAcct($uid, $pass, $email)
    {
        $uid  = $this->mysql_entities_fix_string($uid);
        $pass = $this->mysql_entities_fix_string($pass);
        $email= $this->mysql_entities_fix_string($email);
        $pass = password_hash($pass, PASSWORD_DEFAULT);
        $conn = $this -> connect();
        $stmt = $conn->prepare('INSERT INTO customer (username, pass, email) VALUES(?,?,?)');
        $stmt -> bind_param('sss', $uid, $pass, $email);
 
        $insertId = $conn -> insert_id;
        
        if(!$stmt -> execute()){
            $stmt=null;
            header('location: sign-up.php?error=stmtfailed');
            $conn -> close(); 
            exit();
        }

        echo "ID: " . $insertId;
    
        $conn->close();
    }
            
    protected function checkUser($uid, $email)
    {
        $uid  = $this->mysql_entities_fix_string($uid);
        $email= $this->mysql_entities_fix_string($email);
        $conn = $this -> connect();
        $stmt = $conn->prepare('SELECT customer_id FROM customer WHERE username = ? OR email = ?');
        $stmt -> bind_param('ss', $uid, $email);
        $stmt -> execute();    
        $stmt -> bind_result($id);
     
        if($stmt -> store_result()){
            while($stmt -> fetch()){
            }
        }
        if($stmt -> num_rows() > 0){
            $conn -> close();  
            return true;
        }
        else{
            $conn -> close();  
            return false;
        }

        if(!$stmt -> execute()){
            $stmt=null;
            header('location: sign-up.php?error=stmtfailed');
            $conn -> close(); 
            exit();
        }
    }

    function mysql_entities_fix_string($string)
    {
        $string = strip_tags($string); // strips any html tags
        return htmlentities($string);
    }
}