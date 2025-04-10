<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Index
{
    public $con;

    // connection
    public function __construct()
    {
        $this->con = mysqli_connect('localhost', 'root', '', 'onlinefoodphp') or die(mysqli_error());
    }

    public function addRider(
        $username,
        $firstname,
        $lastname,
        $address,
        $email,
        $phone,
        $password_plain,
        $security_question,
        $security_answer
    ) {
        $username = mysqli_real_escape_string($this->con, $username);
        $firstname = mysqli_real_escape_string($this->con, $firstname);
        $lastname = mysqli_real_escape_string($this->con, $lastname);
        $address = mysqli_real_escape_string($this->con, $address);
        $email = mysqli_real_escape_string($this->con, $email);
        $phone = mysqli_real_escape_string($this->con, $phone);
        $security_question = mysqli_real_escape_string($this->con, $security_question);
        $security_answer = mysqli_real_escape_string($this->con, $security_answer);

        // Hash password securely
        $hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);

        // Check if username or email already exists
        $checkQuery = "SELECT * FROM riders WHERE username = '$username' OR email = '$email'";
        $checkResult = mysqli_query($this->con, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            return false; // Username or email already taken
        }

        // Insert new rider
        $sql = "INSERT INTO riders (username, f_name, l_name, address,  email, phone, password, security_question, answer)
        VALUES ('$username', '$firstname', '$lastname', '$address', '$email', '$phone', '$hashed_password', '$security_question', '$security_answer')";

        $result = mysqli_query($this->con, $sql);

        
        return $result;
       
    }

    public function addRestaurant(
        $res_name,
        $email,
        $phone,
        $url,
        $o_hr,
        $c_hr,
        $o_days,
        $c_name
    ) {
        // Escape variables
        $res_name = mysqli_real_escape_string($this->con, $res_name);
        $email    = mysqli_real_escape_string($this->con, $email);
        $phone    = mysqli_real_escape_string($this->con, $phone);
        $url      = mysqli_real_escape_string($this->con, $url);
        $o_hr     = mysqli_real_escape_string($this->con, $o_hr);
        $c_hr     = mysqli_real_escape_string($this->con, $c_hr);
        $o_days   = mysqli_real_escape_string($this->con, $o_days);
        $c_name   = mysqli_real_escape_string($this->con, $c_name);
    
    
        // Email uniqueness check
        $checkQuery = "SELECT * FROM restaurant WHERE email = '$email'";
        $checkResult = mysqli_query($this->con, $checkQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            return false; // Email already exists
        }
    
        // Insert restaurant data
        $sql = "INSERT INTO restaurant 
            (title, email, phone, url, o_hr, c_hr, o_days, c_id)
            VALUES 
            ('$res_name', '$email', '$phone', '$url', '$o_hr', '$c_hr', '$o_days', '$c_name')";
    
        return mysqli_query($this->con, $sql);
    }
    
    
    public function getRestCategory()
    {
        // Correct SQL query
        $sql = "SELECT * FROM res_category";
        
        // Execute the query
        $result = mysqli_query($this->con, $sql);
        
        // Check if the query was successful
        if (!$result) {
            // If the query failed, show the error message and stop
            die('Query failed: ' . mysqli_error($this->con));
        }
        
        // Return the result
        return $result;
    }

    public function getInProcessTransac()
    {
        // Correct SQL query
        $sql = "SELECT id AS transacID, u_id, total_price, stall_id, status, order_date 
        FROM transaction 
        WHERE status = 'in_process' ";
        
        $result = mysqli_query($this->con, $sql);
        
        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }
        
        return $result;
    }


    public function acceptOrderRider($rider_id, $transaction_id)
    {
        // Escape variables
        $rider_id = mysqli_real_escape_string($this->con, $rider_id);
        $transaction_id = mysqli_real_escape_string($this->con, $transaction_id);
        $status = "order_confirmation";
        // Update transaction with rider_id and status
        $sql = "UPDATE transaction 
                SET rider_id = '$rider_id',
                status = '$status'
                WHERE id = '$transaction_id'";
    
        return mysqli_query($this->con, $sql);
    }

    public function getRiderById($id) {
        $sql = "SELECT f_name, l_name FROM users WHERE u_id = '$id'";
    
        $result = mysqli_query($this->con, $sql);
    
        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }
    
        $row = mysqli_fetch_assoc($result); // This is the correct way with mysqli
        return $row; // ['f_name' => ..., 'l_name' => ...]
    }
    
    public function addRiderRating($rider_id, $rider_name,  $rating, $complaint)
    {
        // Escape inputs to prevent SQL injection
        $rider_id  = mysqli_real_escape_string($this->con, $rider_id);
        $rating    = mysqli_real_escape_string($this->con, $rating);
        $rider_name    = mysqli_real_escape_string($this->con, $rider_name);
        $complaint = mysqli_real_escape_string($this->con, $complaint);
    
        $sql = "INSERT INTO rating_rider (rider_id, rider_name, rating, complaint)
                VALUES ('$rider_id', '$rider_name', '$rating', '$complaint')";
    
        return mysqli_query($this->con, $sql);
    }

    public function getStallById($restaurant_id) {
        $sql = "SELECT rs_id, title FROM restaurant WHERE rs_id = '$restaurant_id'";
    
        $result = mysqli_query($this->con, $sql);
    
        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }
    
        $row = mysqli_fetch_assoc($result);
        return $row; 
    }


    public function addRestaurantRating($stall_name, $restaurant_id, $rating, $complaint, $user_id)
    {
        // Escape inputs to prevent SQL injection
        $stall_name     = mysqli_real_escape_string($this->con, $stall_name);
        $restaurant_id  = mysqli_real_escape_string($this->con, $restaurant_id);
        $rating         = mysqli_real_escape_string($this->con, $rating);
        $complaint      = mysqli_real_escape_string($this->con, $complaint);
        $user_id        = mysqli_real_escape_string($this->con, $user_id);
    
        // Insert into restaurant_rating table
        $sql = "INSERT INTO restaurant_ratings (user_id, restaurant_id, stall_name, rating, complaint) 
                VALUES ('$user_id', '$restaurant_id', '$stall_name', '$rating', '$complaint')";
    
        return mysqli_query($this->con, $sql);
    }
    
       

}