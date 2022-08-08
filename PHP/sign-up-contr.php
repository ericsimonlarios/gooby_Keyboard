<?php

require_once 'sign-up-query.php';

class Signup_Contr extends Signup
{
    private $user;
    private $pass;
    private $confirm_pass;
    private $email;

    public function __construct($user, $pass, $confirm_pass, $email)
    {
        $this->user = $user;
        $this->pass = $pass;
        $this->confirm_pass = $confirm_pass;
        $this->email = $email;
    }

    public function emptyInput()
    {
        if (
            empty($this->user) || empty($this->pass)
            || empty($this->confirm_pass) || empty($this->email)
        ) {
            header('location: ../HTML/sign-up.php?status=error&msg=Should fill all fields');
            die();
        }
    }

    public function invalidInput()
    {
        if (!preg_match("/^[a-zA-Z0-9]*$/", $this->user) || !preg_match("/^[a-zA-Z0-9]*$/", $this->pass)) {
            header('location: ../HTML/sign-up.php?status=error&msg=Only alphanumeric characters are allowed');
            die();
        }
    }

    public function pwdMatch()
    {
        if ($this->pass !== $this->confirm_pass) {
            header('location: ../HTML/sign-up.php?status=error&msg=Password does not match');
            die();
        }
    }

    public function check()
    {
        if ($this->checkUser($this->user, $this->email)) {
            header('location: ../HTML/sign-up.php?status=error&msg=Account already exist');
            die();
        }
    }

    public function signup()
    {
        $this->insertAcct($this->user, $this->pass, $this->email);
        header("location: ../HTML/login.php?status=success&msg=Registration Successful");
        die();
    }
}
