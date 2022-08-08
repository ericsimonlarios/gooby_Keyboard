<?php
require_once "order-process-query.php";
class orderProcessContr extends orderQuery
{
    private $product_array;
    private $customer_id;
    private $payment_mode;
    private $quantity;
    private $date;
    private $order_status;
    private $total;
    public function __construct($product_array, $customer_id, $payment_mode, $quantity, $date, $order_status, $total)
    {
        $this->product_array = $product_array;
        $this->customer_id   = $customer_id;
        $this->payment_mode  = $payment_mode;
        $this->quantity      = $quantity;
        $this->date          = $date;
        $this->order_status  = $order_status;
        $this->total         = $total;
    }

    public function insertOrder()
    {
        if ($this->addOrder($this->product_array, $this->customer_id, $this->payment_mode, $this->quantity, $this->date, $this->order_status, $this->total)) {
            header('location: ../HTML/order-complete.php');
            die();
        }
    }
}

class addProductsContr extends orderQuery
{
    private $product_name;
    private $category;
    private $price;
    private $product_stock;
    private $product_desc;
    private $product_img;
    private $url;
    private $date;
    public function __construct($product_name, $category, $price,$product_stock, $product_desc, $product_img, $date, $url)
    {
        $this->product_name     = $product_name;
        $this->category         = $category;
        $this->price            = $price;
        $this->product_stock    = $product_stock;
        $this->product_desc     = $product_desc;
        $this->product_img      = $product_img;
        $this->date             = $date;
        $this->url              = $url;
    }

    public function emptyInput(){
        if(empty($this->product_name) || empty($this->category) || empty($this->price) || empty($this->product_stock)
        ||empty($this->product_desc) ||empty($this->product_img)){
            header('location: ../HTML/' . $this->url . '.php?status=error&msg=Should fill all input required fields'); 
            die();
        }
    }
  
    public function invalidInput(){
        if ( !preg_match("/^[a-zA-Z0-9\s\/-]*$/", $this->product_name) || !preg_match("/^[a-zA-Z0-9]*$/", $this->category) || !preg_match("/^[a-zA-Z0-9]*$/", $this->price) || !preg_match("/^[a-zA-Z0-9]*$/", $this->product_stock))       
        {
            header('location: ../HTML/' . $this->url . '.php?status=error&msg=Only alphanumeric characters are allowed');
            die();
        }
    }

    public function productExist(){
        if($this->productCheck($this->product_name)){
            header('location: ../HTML/' . $this->url . '.php?status=error&msg=Product already exist in our Database');
            die();
        }
    }

    public function addProduct(){
        if($this->insertProduct($this->product_name,$this->product_img,$this->category,$this->price,$this->product_stock,$this->product_desc,$this->date)){
            header('location: ../HTML/admin-product-page.php?status=success&msg=Product: ' . $this->product_name . ' has been successfully added');
            die();
        }
    }

}

class editProductContr extends orderQuery{
    private $prod_id;
    private $product_name;
    private $category;
    private $price;
    private $product_stock;
    private $product_desc;
    private $product_img;
    private $date;
    private $url;

    public function __construct($prod_id,$product_name, $category, $price,$product_stock, $product_desc, $product_img, $date, $url)
    {
        $this->prod_id          = $prod_id;
        $this->product_name     = $product_name;
        $this->category         = $category;
        $this->price            = $price;
        $this->product_stock    = $product_stock;
        $this->product_desc     = $product_desc;
        $this->product_img      = $product_img;
        $this->date             = $date;
        $this->url              = $url;
    }

    public function emptyInput(){
        if(empty($this->product_name) || empty($this->category) || empty($this->price) || empty($this->product_stock)
        ||empty($this->product_desc) ||empty($this->product_img)){
            header('location: ../HTML/' . $this->url . '.php?prod_id='.$this->prod_id.'&status=error&msg=Should fill all input required fields'); 
            die();
        }
    }
  
    public function invalidInput(){
        if ( !preg_match("/^[a-zA-Z0-9\s\/-]*$/", $this->product_name) || !preg_match("/^[a-zA-Z0-9]*$/", $this->category) || !preg_match("/^[a-zA-Z0-9]*$/", $this->price) || !preg_match("/^[a-zA-Z0-9]*$/", $this->product_stock))       
        {
            header('location: ../HTML/' . $this->url . '.php?prod_id='.$this->prod_id.'&status=error&msg=Only alphanumeric characters are allowed');
            die();
        }
    }

    public function productExist(){
        if($this->productCheck($this->product_name)){
            header('location: ../HTML/' . $this->url . '.php?prod_id='.$this->prod_id.'&status=error&msg=Product already exist in our Database');
            die();
        }
    }


    public function editProduct(){
        if($this->updateProduct($this->prod_id, $this->product_name,$this->product_img,$this->category,$this->price,$this->product_stock,$this->product_desc,$this->date)){
            header('location: ../HTML/admin-product-page.php?status=success&msg=Product: ' . $this->product_name . ' has been successfully edited');
            die();
        }
    }
}
