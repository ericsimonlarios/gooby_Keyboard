<?php
     session_start();   
    class sessionCheck {
        
        function sessionDuration($duration){    
            if(time() - $_SESSION['start'] > $duration){
                $this->sessionDestroy();
            }
        }

        function sessionDestroy(){
            // if (ini_get('session.use_cookies')) {
            //     $params = session_get_cookie_params();
            //     setcookie(session_name(), '', time() - 42000,
            //         $params["path"], $params["domain"],
            //         $params["secure"], $params["httponly"]
            //     );
            // }
            unset($_SESSION['user']);
            header('location: ../HTML/login.php?status=error&msg=Session time has timeout');
        }
    }
?>
