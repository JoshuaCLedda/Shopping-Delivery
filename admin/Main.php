<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


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
        $security_answer,
        $orcr // this is $_FILES['orcr']
    ) {
        $username = mysqli_real_escape_string($this->con, $username);
        $firstname = mysqli_real_escape_string($this->con, $firstname);
        $lastname = mysqli_real_escape_string($this->con, $lastname);
        $address = mysqli_real_escape_string($this->con, $address);
        $email = mysqli_real_escape_string($this->con, $email);
        $phone = mysqli_real_escape_string($this->con, $phone);
        $security_question = mysqli_real_escape_string($this->con, $security_question);
        $security_answer = mysqli_real_escape_string($this->con, $security_answer);
        $role = 2;
        $hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);

        // Check if username or email already exists
        $checkQuery = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $checkResult = mysqli_query($this->con, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            return false; // Username or email already taken
        }

        // Handle ORCR PDF upload
        $orcr_name = $orcr['name'];
        $orcr_tmp = $orcr['tmp_name'];
        $orcr_ext = strtolower(pathinfo($orcr_name, PATHINFO_EXTENSION));

        // Validate PDF extension
        if ($orcr_ext !== 'pdf') {
            return false; // Invalid file format
        }

        $orcr_new_name = uniqid('orcr_', true) . '.pdf';
        $orcr_path = __DIR__ . '/uploads/' . $orcr_new_name;

        if (!move_uploaded_file($orcr_tmp, $orcr_path)) {
            return false; // File move failed
        }

        // Insert new rider
        $sql = "INSERT INTO users (username, f_name, l_name, email, phone,
        password, address, role, security_questions, answer, orcr)
        VALUES ('$username', '$firstname', '$lastname', '$email', '$phone', 
        '$hashed_password', '$address', '$role', '$security_question', '$security_answer', '$orcr_new_name')";

        return mysqli_query($this->con, $sql);
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
        $email = mysqli_real_escape_string($this->con, $email);
        $phone = mysqli_real_escape_string($this->con, $phone);
        $url = mysqli_real_escape_string($this->con, $url);
        $o_hr = mysqli_real_escape_string($this->con, $o_hr);
        $c_hr = mysqli_real_escape_string($this->con, $c_hr);
        $o_days = mysqli_real_escape_string($this->con, $o_days);
        $c_name = mysqli_real_escape_string($this->con, $c_name);


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
        // Corrected SQL query
        $sql = "SELECT id AS transacID, u_id, total_price, stall_id, status, order_date 
                FROM transaction 
                WHERE status = 'place_order'
                AND rider_id = 0";

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

    public function getRiderById($id)
    {
        $sql = "SELECT f_name, l_name FROM users WHERE u_id = '$id'";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        $row = mysqli_fetch_assoc($result); // This is the correct way with mysqli
        return $row; // ['f_name' => ..., 'l_name' => ...]
    }

    public function addRiderRating($rider_id, $rider_name, $rating, $complaint)
    {
        // Escape inputs to prevent SQL injection
        $rider_id = mysqli_real_escape_string($this->con, $rider_id);
        $rating = mysqli_real_escape_string($this->con, $rating);
        $rider_name = mysqli_real_escape_string($this->con, $rider_name);
        $complaint = mysqli_real_escape_string($this->con, $complaint);

        $sql = "INSERT INTO rating_rider (rider_id, rider_name, rating, complaint)
                VALUES ('$rider_id', '$rider_name', '$rating', '$complaint')";

        return mysqli_query($this->con, $sql);
    }

    public function getStallById($restaurant_id)
    {
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
        $stall_name = mysqli_real_escape_string($this->con, $stall_name);
        $restaurant_id = mysqli_real_escape_string($this->con, $restaurant_id);
        $rating = mysqli_real_escape_string($this->con, $rating);
        $complaint = mysqli_real_escape_string($this->con, $complaint);
        $user_id = mysqli_real_escape_string($this->con, $user_id);

        // Insert into restaurant_rating table
        $sql = "INSERT INTO restaurant_ratings (user_id, restaurant_id, stall_name, rating, complaint) 
                VALUES ('$user_id', '$restaurant_id', '$stall_name', '$rating', '$complaint')";

        return mysqli_query($this->con, $sql);
    }

    public function getStallRatings()
    {
        // Corrected SQL query
        $sql = "SELECT restaurant_ratings.id AS restoId,
        users.f_name, users.l_name, 
        restaurant.title AS restaurant, restaurant_ratings.rating
                FROM restaurant_ratings
                LEFT JOIN restaurant ON
                restaurant.rs_id = restaurant_ratings.restaurant_id
                LEFT JOIN users ON
                users.u_id = restaurant_ratings.user_id";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        return $result;
    }


    public function getRidersRatings()
    {
        // Corrected SQL query
        $sql = "SELECT rating_rider.id AS riderId,
        users.f_name, users.l_name, rating_rider.rating, rider_name 
                FROM rating_rider
                LEFT JOIN users ON
                users.u_id = rating_rider.user_id";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        return $result;
    }

    public function getOrders()
    {
        // Corrected SQL query
        $sql = "SELECT 
                    transaction.id AS transacId,
                    users.f_name, 
                    users.l_name, 
                    transaction.total_price,
                    transaction.status,
                    transaction.order_date,
                    restaurant.title
                FROM transaction
                LEFT JOIN users ON users.u_id = transaction.u_id
                LEFT JOIN restaurant ON restaurant.rs_id = transaction.rs_id
                ORDER BY transaction.order_date DESC";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        return $result;
    }


    public function viewOrderDetails($transacId)
    {
        $sql = "SELECT 
        transaction.id AS transacId,
        users.f_name, 
        users.l_name, 
        transaction.total_price,
        transaction.status,
        transaction.order_date,
        restaurant.title,
        transaction.titles AS dishesOrder,
        transaction.payment_method AS payMethod,
        transaction.total_quantity

        FROM transaction
        LEFT JOIN users ON users.u_id = transaction.u_id
        LEFT JOIN restaurant ON restaurant.rs_id = transaction.rs_id
        WHERE transaction.id = '$transacId'
        ORDER BY transaction.order_date DESC";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        return $result;
    }

    public function viewRiderDetails($id)
    {
        $sql = "SELECT * FROM users WHERE u_id = '$id'";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }


        return $result;
    }

    public function updateRiderApplication($id, $status)
    {
        $id = mysqli_real_escape_string($this->con, $id);
        $status = mysqli_real_escape_string($this->con, $status);

        $sql = "UPDATE users SET status = '$status' WHERE u_id = '$id'";

        return mysqli_query($this->con, $sql);
    }
}
