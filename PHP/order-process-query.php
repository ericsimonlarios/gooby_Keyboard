<?php
require_once "dbcon.php";
class orderQuery extends dbh
{
    protected function addOrder(      
        $product_array,
        $customer_id,
        $payment_mode,
        $quantity,
        $date,
        $order_status,
        $total
    ) {
        $conn = $this->connect();
        $selectsql = "SELECT customer_id, product_id FROM orders ORDER BY order_id";
        $result = $conn->query($selectsql);
        if (!$result) {
            die("Fatal Error: Query failed");
        }
        $rows = $result->num_rows;
        $cus_id  = array();
        $prod_id = array();
        for ($j = 0; $j < $rows; ++$j) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $cus_id[$j]   = htmlspecialchars($row['customer_id']);
            $prod_id[$j]  = htmlspecialchars($row['product_id']);
            
        }
        foreach ($product_array as $key => $value) {
            $product_id = intval($product_array[$key]);
            $product_quantity = intval($quantity[$key]);
            // if (in_array($customer_id, $cus_id) && in_array($product_id, $prod_id)) {
            //     echo "we entered here as well" . "<br>";
            //     $updatesql = "UPDATE orders SET quantity=?, date_ordered=?, payment_mode=?, order_status=? where customer_id = ? AND product_id=?";
            //     if ($stmt = $conn->prepare($updatesql)) {
            //         $stmt->bind_param("ssssii", $product_quantity, $date, $payment_mode, $order_status, $customer_id, $product_id);
            //     } else {
            //         $error = $conn->errno . ' ' . $conn->error;
            //         echo $error; // 1054 Unknown column 'foo' in 'field list'
            //     }
            //     if (!$stmt->execute()) {
            //         $stmt=null;
            //         $error = $conn->errno . ' ' . $conn->error;
            //         echo $error; // 1054 Unknown column 'foo' in 'field list'
            //         header('location: ../HTML/billing-info.php?error=stmtfailed');
            //         // $conn -> close(); 
            //         // exit();
            //     }
            //     unset($_SESSION['item_array'][$key]);
            //     continue;
            // } else {
                $selectSQL = "SELECT * FROM product";
                $selectResult = $conn -> query($selectSQL);
                if (!$selectResult) {
                    die("Fatal Error: Query failed");
                }
                $selectRows = $selectResult -> num_rows;
                $product_stock=0;
    
                for($i=0; $selectRows > $i; ++$i){
                    $selectRow = $selectResult -> fetch_array(MYSQLI_ASSOC);
                    $product_stock  = htmlspecialchars($selectRow['stock']);
                    $productID      = htmlspecialchars($selectRow['product_id']);
                    if($productID == $product_id){
                        $stock = $product_stock - $product_quantity;
                    }
                }
                $updateSQL = "UPDATE product SET stock=? where product_id =?";
                if($updateResult = $conn->prepare($updateSQL)){
                    $updateResult -> bind_param('si', $stock, $product_id);
                }else{
                    $error = $conn->errno . ' ' . $conn->error;
                    echo $error; // 1054 Unknown column 'foo' in 'field list'
                }
                if(!$updateResult->execute()){
                    $updateResult=null;
                    $error = $conn->errno . ' ' . $conn->error;
                    echo $error; // 1054 Unknown column 'foo' in 'field list'
                    $conn -> close(); 
                    header('location: ../HTML/checkout.php?prod_id=' . $product_id . 'error=stmtfailed'); 
                    exit();
                }
                $sql = "INSERT INTO orders (customer_id, product_id, quantity, date_ordered, payment_mode, order_status, total) VALUES (?,?,?,?,?,?,?)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("iisssss", $customer_id, $product_id, $product_quantity, $date, $payment_mode, $order_status, $total);
                } else {
                    $error = $conn->errno . ' ' . $conn->error;
                    echo $error; // 1054 Unknown column 'foo' in 'field list'
                }
                if (!$stmt->execute()) {
                    $stmt=null;
                    $error = $conn->errno . ' ' . $conn->error;
                    echo $error; // 1054 Unknown column 'foo' in 'field list'
                    header('location: ../HTML/billing-info.php?error=stmtfailed');
                    $conn -> close(); 
                    exit();
                }
                unset($_SESSION['item_array'][$key]);        
            // }
        }
        $conn -> close();
        return true;
    }

    protected function productCheck($current_name){
        $conn           =   $this->connect();
        $current_name   =   $this->mysql_entities_fix_string($current_name);
        $query          =   "SELECT product_name FROM product";
        $result         =   $conn->query($query);
        $rows           =   $result->num_rows;
        for($i=0; $rows > $i; ++$i){
            $row    =   $result->fetch_array(MYSQLI_ASSOC);
            $product_name   =   htmlspecialchars($row['product_name']);
            if($current_name == $product_name){
                $conn->close();
                return true;
            }
        }     
        $conn->close();
        return false;
    }

    protected function insertProduct($product_name,$product_img,$category,$price,$product_stock,$product_desc,$date){
        $conn   =   $this->connect();

        $product_name   =  $this->mysql_entities_fix_string($product_name);
        $product_img    =  $this->mysql_entities_fix_string($product_img);
        $category       =  $this->mysql_entities_fix_string($category);
        $price          =  $this->mysql_entities_fix_string($price);
        $product_stock  =  $this->mysql_entities_fix_string($product_stock);
        
        $insertQuery    =   "INSERT INTO product (product_name,product_img,category,price,stock,product_desc,date_added) VALUES(?,?,?,?,?,?,?)";
        if($stmt = $conn->prepare($insertQuery)){
            $stmt->bind_param("sssssss", $product_name,$product_img,$category,$price,$product_stock,$product_desc,$date);
        }else{
            $error = $conn->errno . ' ' . $conn->error;
            echo $error; // 1054 Unknown column 'foo' in 'field list'
        }
        if(!$stmt->execute()){
            $stmt=null;
            $error = $conn->errno . ' ' . $conn->error;
            echo $error; // 1054 Unknown column 'foo' in 'field list'
            $conn -> close(); 
            header('location: ../HTML/admin-add-product-page.php?error=stmtfailed'); 
            exit();
        }
        $conn->close();
        return true;
    }

    protected function updateProduct($product_id, $product_name,$product_img,$category,$price,$product_stock,$product_desc,$date){
        $conn   =   $this->connect();

        $product_name   =  $this->mysql_entities_fix_string($product_name);
        $product_img    =  $this->mysql_entities_fix_string($product_img);
        $category       =  $this->mysql_entities_fix_string($category);
        $price          =  $this->mysql_entities_fix_string($price);
        $product_stock  =  $this->mysql_entities_fix_string($product_stock);
        
        $insertQuery    =   "UPDATE product SET product_name=?,product_img=?,category=?,price=?,stock=?,product_desc=?,date_added=? WHERE product_id=?";
        if($stmt = $conn->prepare($insertQuery)){
            $stmt->bind_param("sssssssi", $product_name,$product_img,$category,$price,$product_stock,$product_desc,$date,$product_id);
        }else{
            $error = $conn->errno . ' ' . $conn->error;
            echo $error; // 1054 Unknown column 'foo' in 'field list'
        }
        if(!$stmt->execute()){
            $stmt=null;
            $error = $conn->errno . ' ' . $conn->error;
            echo $error; // 1054 Unknown column 'foo' in 'field list'
            $conn -> close(); 
            header('location: ../HTML/admin-edit-product-page.php?prod_id=' . $product_id . 'error=stmtfailed'); 
            exit();
        }
        $conn->close();
        return true;
    }

    function mysql_entities_fix_string($string)
    {
        $string = strip_tags($string); // strips any html tags
        return htmlentities($string);
    }
}
