<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
        $orcr,
        $physic_exam
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

        // --- ORCR Upload ---
        $orcr_name = $orcr['name'];
        $orcr_tmp = $orcr['tmp_name'];
        $orcr_ext = strtolower(pathinfo($orcr_name, PATHINFO_EXTENSION));

        if ($orcr_ext !== 'pdf') {
            return false; // Invalid ORCR file format
        }

        $orcr_new_name = uniqid('orcr_', true) . '.pdf';
        $orcr_path = __DIR__ . '/uploads/' . $orcr_new_name;

        if (!move_uploaded_file($orcr_tmp, $orcr_path)) {
            return false; // ORCR file move failed
        }

        // --- Physical Exam Upload ---
        $physic_exam_name = $physic_exam['name'];
        $physic_exam_tmp = $physic_exam['tmp_name'];
        $physic_exam_ext = strtolower(pathinfo($physic_exam_name, PATHINFO_EXTENSION));

        if ($physic_exam_ext !== 'pdf') {
            return false; // Invalid physical exam file format
        }

        $physic_exam_new_name = uniqid('physic_', true) . '.pdf';
        $physic_exam_path = __DIR__ . '/uploads/' . $physic_exam_new_name;

        if (!move_uploaded_file($physic_exam_tmp, $physic_exam_path)) {
            return false; // Physical exam file move failed
        }

        // Insert new rider
        $sql = "INSERT INTO users (
            username, f_name, l_name, email, phone, password, address,
            role, security_questions, answer, orcr, physic_exam
        ) VALUES (
            '$username', '$firstname', '$lastname', '$email', '$phone',
            '$hashed_password', '$address', '$role', '$security_question',
            '$security_answer', '$orcr_new_name', '$physic_exam_new_name'
        )";

        $result = mysqli_query($this->con, $sql);

        if ($result) {
            // Log the activity (admin adds a new rider)
            $admin_id = $_SESSION["user_id"]; // Assuming the admin is logged in and the user_id is in the session
            $activity = 'Added new rider';
            $details = 'New rider added with username: ' . $username;

            // Insert log into activity_log table
            $logStmt = $this->con->prepare("INSERT INTO activity_log (user_id, activity, details) VALUES (?, ?, ?)");
            $logStmt->bind_param("iss", $admin_id, $activity, $details);
            $logStmt->execute();

            return true; // Rider added successfully
        } else {
            return false; // Rider not added
        }
    }



    public function addRestaurant(
        $res_name,
        $email,
        $phone,
        $url,
        $o_hr,
        $c_hr,
        $o_days,
        $image,
        $address,
        $c_id,
        $owner_id
    ) {
        // Escape variables
        $res_name = mysqli_real_escape_string($this->con, $res_name);
        $email = mysqli_real_escape_string($this->con, $email);
        $phone = mysqli_real_escape_string($this->con, $phone);
        $url = mysqli_real_escape_string($this->con, $url);
        $o_hr = mysqli_real_escape_string($this->con, $o_hr);
        $c_hr = mysqli_real_escape_string($this->con, $c_hr);
        $o_days = mysqli_real_escape_string($this->con, $o_days);
        $address = mysqli_real_escape_string($this->con, $address);
        $c_id = mysqli_real_escape_string($this->con, $c_id);
        $owner_id = (int) $owner_id;

        // Check for email uniqueness
        $checkQuery = "SELECT * FROM restaurant WHERE email = '$email'";
        $checkResult = mysqli_query($this->con, $checkQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            return false; // Email already exists
        }

        // Handle image upload
        $imageName = basename($image['name']);
        $imageTmp = $image['tmp_name'];
        $imageFolder = "Res_img/";

        if (!is_dir($imageFolder)) {
            mkdir($imageFolder, 0777, true);
        }

        $targetPath = $imageFolder . time() . "_" . $imageName;

        if (move_uploaded_file($imageTmp, $targetPath)) {
            $imagePathForDB = mysqli_real_escape_string($this->con, $targetPath);
        } else {
            return false; // Image upload failed
        }

        // Insert restaurant
        $sql = "INSERT INTO restaurant 
            (title, email, phone, url, o_hr, c_hr, o_days, address, c_id, image)
            VALUES 
            ('$res_name', '$email', '$phone', '$url', '$o_hr', '$c_hr', '$o_days', '$address', '$c_id', '$imagePathForDB')";

        $result = mysqli_query($this->con, $sql);

        if ($result) {
            $restaurant_id = mysqli_insert_id($this->con); // get the inserted restaurant's ID

            // Update user's restaurant_id
            $updateUser = "UPDATE users SET restaurant_id = '$restaurant_id' WHERE u_id = '$owner_id'";
            mysqli_query($this->con, $updateUser);

            // Log the activity
            $admin_id = $_SESSION["user_id"];
            $activity = 'Added new restaurant';
            $details = 'New restaurant "' . $res_name . '" added and assigned to owner ID ' . $owner_id;

            $logStmt = $this->con->prepare("INSERT INTO activity_log (user_id, activity, details) VALUES (?, ?, ?)");
            $logStmt->bind_param("iss", $admin_id, $activity, $details);
            $logStmt->execute();

            return true;
        } else {
            return false;
        }
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
        $sql = "SELECT 
                transaction.id AS transacID, 
                transaction.u_id, 
                transaction.total_price, 
                transaction.status, 
                transaction.order_date,
                CONCAT(users.f_name, ' ', users.l_name) AS customerName,
                users.phone AS customerPhone
            FROM transaction 
            LEFT JOIN users ON users.u_id = transaction.u_id
            WHERE transaction.status = 'in_process'
            ORDER BY transaction.updated_at DESC";

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

        // Update transaction with rider_id and status
        $sql = "UPDATE transaction 
                SET rider_id = '$rider_id',
                    status = 'order_received',
                    updated_at = NOW()
                WHERE id = '$transaction_id'";

        $result = mysqli_query($this->con, $sql);

        if ($result) {
            // Log the activity (rider accepts an order)
            $activity = 'Accepted order';
            $details = 'Rider with ID ' . $rider_id . ' accepted order with transaction ID ' . $transaction_id;

            // Assuming the rider is logged in and their ID is in the session
            $user_id = $_SESSION["user_id"]; // Rider's ID or admin's ID from the session

            // Insert log into activity_log table
            $logStmt = $this->con->prepare("INSERT INTO activity_log (user_id, activity, details) VALUES (?, ?, ?)");
            $logStmt->bind_param("iss", $user_id, $activity, $details);
            $logStmt->execute();

            return true; // Order accepted successfully and log created
        } else {
            return false; // Order not accepted
        }
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

    public function addRiderRating($rider_id, $rider_name, $rating, $complaint, $transaction_id)
    {
        // Escape inputs to prevent SQL injection
        $rider_id = mysqli_real_escape_string($this->con, $rider_id);
        $rating = mysqli_real_escape_string($this->con, $rating);
        $complaint = mysqli_real_escape_string($this->con, $complaint);
        $transaction_id = mysqli_real_escape_string($this->con, $transaction_id);

        // Insert rating into the rating_rider table
        $sql = "INSERT INTO rating_rider (rider_id, transaction_id, rider_name, rating, complaint)
                VALUES ('$rider_id', '$transaction_id', '$rider_name', '$rating', '$complaint')";

        $result = mysqli_query($this->con, $sql);

        if ($result) {
            // Log the activity (rating a rider)
            $activity = 'Rated rider';
            $details = 'Rated rider ' . $rider_name . ' (ID: ' . $rider_id . ') with a rating of ' . $rating . ' for transaction ID ' . $transaction_id;

            // Assuming the logged-in user is the one who rated (could be customer or admin)
            $user_id = $_SESSION["user_id"]; // User ID from session (customer or admin)

            // Insert log into activity_log table
            $logStmt = $this->con->prepare("INSERT INTO activity_log (user_id, activity, details) VALUES (?, ?, ?)");
            $logStmt->bind_param("iss", $user_id, $activity, $details);
            $logStmt->execute();

            return true; // Rating added and log created
        } else {
            return false; // Rating not added
        }
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


    public function addRestaurantRating($stall_name, $restaurant_id, $rating, $complaint, $user_id, $transaction_id)
    {
        // Escape inputs to prevent SQL injection
        $stall_name = mysqli_real_escape_string($this->con, $stall_name);
        $restaurant_id = mysqli_real_escape_string($this->con, $restaurant_id);
        $rating = mysqli_real_escape_string($this->con, $rating);
        $complaint = mysqli_real_escape_string($this->con, $complaint);
        $user_id = mysqli_real_escape_string($this->con, $user_id);
        $transaction_id = mysqli_real_escape_string($this->con, $transaction_id);

        // Insert into restaurant_rating table
        $sql = "INSERT INTO restaurant_ratings (user_id, transaction_id, restaurant_id, stall_name, rating, complaint) 
                VALUES ('$user_id', '$transaction_id',  '$restaurant_id', '$stall_name', '$rating', '$complaint')";

        $result = mysqli_query($this->con, $sql);

        if ($result) {
            // Log the activity (rating a restaurant)
            $activity = 'Rated restaurant';
            $details = 'Rated restaurant "' . $stall_name . '" (ID: ' . $restaurant_id . ') with a rating of ' . $rating . '. Complaint: ' . $complaint;

            // Insert log into activity_log table
            $logStmt = $this->con->prepare("INSERT INTO activity_log (user_id, activity, details) VALUES (?, ?, ?)");
            $logStmt->bind_param("iss", $user_id, $activity, $details);
            $logStmt->execute();

            return true; // Rating added and log created
        } else {
            return false; // Rating not added
        }
    }


    public function getStallRatings()
    {
        // Corrected SQL query
        $sql = "SELECT restaurant_ratings.id AS restoId, restaurant_ratings.complaint,
        users.f_name, users.l_name, restaurant_ratings.created_at,
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
        $sql = "SELECT rating_rider.id AS riderId, rating_rider.complaint,rating_rider.created_at,
                users.f_name, users.l_name, rating_rider.rating, rider_name 
                FROM rating_rider
                LEFT JOIN users ON
                users.u_id = rating_rider.rider_id";

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
            die('Query failed:   ' . mysqli_error($this->con));
        }

        return $result;
    }

    public function getInProcessOrders()
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
                WHERE transaction.status = 'in_process'
                ORDER BY transaction.order_date DESC";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed:   ' . mysqli_error($this->con));
        }

        return $result;
    }

    public function getCancelledOrders()
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
             WHERE transaction.status IN ('Order_Canceled', 'Order_Cancelled')
                ORDER BY transaction.order_date DESC";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed:   ' . mysqli_error($this->con));
        }

        return $result;
    }


    public function getPendingOrders()
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
                WHERE transaction.status = 'place_order'
                ORDER BY transaction.order_date DESC";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed:   ' . mysqli_error($this->con));
        }

        return $result;
    }
    public function viewOrderDetails($transacId)
    {
        $sql = "SELECT 
        transaction.id AS transacId,
        transaction.address AS orderAddress,
        users.f_name, 
        users.l_name, 
        transaction.total_price,
        transaction.status,
        transaction.order_date,
        restaurant.title as restaurant,
        transaction.payment_method AS payMethod,
        transaction.total_quantity,
        restaurant.rs_id AS restaurantId
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
        // Escape variables to prevent SQL injection
        $id = mysqli_real_escape_string($this->con, $id);
        $status = mysqli_real_escape_string($this->con, $status);

        // Update the status of the rider
        $sql = "UPDATE users SET status = '$status' WHERE u_id = '$id'";

        $result = mysqli_query($this->con, $sql);

        if ($result) {
            // Log the activity (updating rider status)
            $activity = 'Updated rider application status';
            $details = 'Updated rider application status for rider ID ' . $id . ' to "' . $status . '"';

            // Insert log into activity_log table
            $logStmt = $this->con->prepare("INSERT INTO activity_log (user_id, activity, details) VALUES (?, ?, ?)");
            $logStmt->bind_param("iss", $id, $activity, $details); // Log the admin user, activity, and details
            $logStmt->execute();

            return true; // Rider application status updated and log created
        } else {
            return false; // Update failed
        }
    }


    public function getRiderRatings($u_id)
    {
        $sql = "SELECT users.f_name, users.l_name,
        rating_rider.rider_name, rating_rider.rating, rating_rider.complaint,
        rating_rider.created_at
        FROM rating_rider
        LEFT JOIN users ON
        users.u_id = rating_rider.rider_id 
        WHERE rating_rider.rider_id = '$u_id'
        ORDER BY rating_rider.created_at DESC";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }


        return $result;
    }
    public function getRiderOverallRating($u_id)
    {
        $u_id = intval($u_id);

        $sql = "SELECT 
                    ROUND(AVG(rating), 1) AS avg_rating, 
                    COUNT(*) AS total 
                FROM rating_rider 
                WHERE rider_id = $u_id";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        $data = mysqli_fetch_assoc($result);
        return $data ?: ['avg_rating' => 0, 'total' => 0];
    }

    public function terminateRider($u_id)
    {
        // Escape the user ID to prevent SQL injection
        $u_id = mysqli_real_escape_string($this->con, $u_id);

        // Set the rider's status to 'banned' (terminate status)
        $sql = "UPDATE users SET status = 'banned' WHERE u_id = $u_id";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            error_log("Terminate Error: " . mysqli_error($this->con));
            return false;  // Return false if the query fails
        }

        // Log the activity if the update was successful
        $activity = 'Terminated rider account';
        $details = 'Terminated rider account with user ID ' . $u_id;

        // Insert log into activity_log table
        $logStmt = $this->con->prepare("INSERT INTO activity_log (user_id, activity, details) VALUES (?, ?, ?)");
        $logStmt->bind_param("iss", $u_id, $activity, $details); // Log the admin user, activity, and details
        $logStmt->execute();

        return true;  // Return true if the update was successful and log created
    }


    public function getRiderStatus($u_id)
    {
        $sql = "SELECT status FROM users WHERE u_id = '$u_id'";  // Removed extra comma after 'status'

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        $row = mysqli_fetch_assoc($result);
        return $row['status'];  // Return only the status value
    }

    public function getRecentTransactions()
    {
        // Get current week's Monday and Sunday
        $monday = date('Y-m-d 00:00:00', strtotime('monday this week'));
        $sunday = date('Y-m-d 23:59:59', strtotime('sunday this week'));

        $sql = "SELECT 
                    transaction.id AS transacId,
                    users.f_name, 
                    users.l_name, 
                    transaction.total_price,
                    transaction.status,
                    transaction.order_date,
                    restaurant.title AS dishesOrder
                FROM transaction
                LEFT JOIN users ON users.u_id = transaction.u_id
                LEFT JOIN restaurant ON restaurant.rs_id = transaction.rs_id
                WHERE transaction.order_date BETWEEN '$monday' AND '$sunday'
                ORDER BY transaction.order_date DESC
                LIMIT 5";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        return $result;
    }

    public function updateRestaurant(
        $rs_id,
        $res_name,
        $email,
        $phone,
        $url,
        $o_hr,
        $c_hr,
        $o_days,
        $c_name,
        $image,
        $address,
        $owner_id
    ) {
        // Escape variables
        $rs_id = mysqli_real_escape_string($this->con, $rs_id);
        $res_name = mysqli_real_escape_string($this->con, $res_name);
        $email = mysqli_real_escape_string($this->con, $email);
        $phone = mysqli_real_escape_string($this->con, $phone);
        $url = mysqli_real_escape_string($this->con, $url);
        $o_hr = mysqli_real_escape_string($this->con, $o_hr);
        $c_hr = mysqli_real_escape_string($this->con, $c_hr);
        $o_days = mysqli_real_escape_string($this->con, $o_days);
        $c_name = mysqli_real_escape_string($this->con, $c_name);
        $address = mysqli_real_escape_string($this->con, $address);
        $owner_id = mysqli_real_escape_string($this->con, $owner_id);
    
        // Prepare base update query
        $sql = "UPDATE restaurant SET 
                    title = '$res_name',
                    email = '$email',
                    phone = '$phone',
                    url = '$url',
                    o_hr = '$o_hr',
                    c_hr = '$c_hr',
                    o_days = '$o_days',
                    address = '$address',
                    c_id = '$c_name'";
    
        // Handle image upload
        if (!empty($image['name'])) {
            $imageName = basename($image['name']);
            $imageTmp = $image['tmp_name'];
            $imageFolder = "Res_img/";
    
            if (!is_dir($imageFolder)) {
                mkdir($imageFolder, 0777, true);
            }
    
            $targetPath = $imageFolder . time() . "_" . $imageName;
    
            if (move_uploaded_file($imageTmp, $targetPath)) {
                $imagePathForDB = mysqli_real_escape_string($this->con, $targetPath);
                $sql .= ", image = '$imagePathForDB'";
            } else {
                return false; // Image upload failed
            }
        }
    
        // Finish update query
        $sql .= " WHERE rs_id = '$rs_id'";
    
        // Execute update
        $result = mysqli_query($this->con, $sql);
    
        if ($result) {
            // ✅ Step 1: Unassign previous user from this restaurant
            mysqli_query($this->con, "UPDATE users SET restaurant_id = NULL WHERE restaurant_id = '$rs_id'");
    
            // ✅ Step 2: Assign new owner to this restaurant
            mysqli_query($this->con, "UPDATE users SET restaurant_id = '$rs_id' WHERE u_id = '$owner_id'");
    
            // Log activity
            $activity = 'Updated restaurant details';
            $details = "Updated restaurant with ID: $rs_id, Name: $res_name";
    
            $logStmt = $this->con->prepare("INSERT INTO activity_log (user_id, activity, details) VALUES (?, ?, ?)");
            $user_id = $_SESSION['user_id'];
            $logStmt->bind_param("iss", $user_id, $activity, $details);
            $logStmt->execute();
    
            return true;
        } else {
            return false;
        }
    }
    


    public function getAllMenu()
    {
        // Corrected SQL query
        $sql = "SELECT dishes.d_id as dishedId, dishes.title AS dish_name, 
                dishes.price, dishes.available_quantity, dishes.img AS image, 
                restaurant.title AS stall_name, dishes.status
                FROM dishes
                LEFT JOIN restaurant ON
                restaurant.rs_id = dishes.rs_id";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        return $result;
    }

    public function updateMenu(
        $dishes_Id,
        $title,
        $status,
        $slogan,
        $price,
        $available_quantity,
        $dish_category_id,
        $rs_id,
        $image
    ) {
        // Escape variables properly
        $title = mysqli_real_escape_string($this->con, $title);
        $slogan = mysqli_real_escape_string($this->con, $slogan);
        $price = mysqli_real_escape_string($this->con, $price);
        $available_quantity = mysqli_real_escape_string($this->con, $available_quantity);
        $dish_category_id = mysqli_real_escape_string($this->con, $dish_category_id);
        $rs_id = mysqli_real_escape_string($this->con, $rs_id);

        // Start building the SQL
        $sql = "UPDATE dishes SET 
        title = '$title',
        slogan = '$slogan',
        price = '$price',
        status = '$status',
        available_quantity = '$available_quantity',
        dish_category_id = '$dish_category_id',
        rs_id = '$rs_id'";

        // Handle image upload if a new image is uploaded
        if (!empty($image) && !empty($image['name'])) {
            $imageName = basename($image['name']);
            $imageTmp = $image['tmp_name'];
            $imageFolder = "Res_img/";

            if (!is_dir($imageFolder)) {
                mkdir($imageFolder, 0777, true);
            }

            $newImageName = time() . "_" . $imageName; // Unique file name
            $targetPath = $imageFolder . $newImageName;

            if (move_uploaded_file($imageTmp, $targetPath)) {
                $imagePathForDB = mysqli_real_escape_string($this->con, $newImageName);
                $sql .= ", img = '$imagePathForDB'"; // Append img only if upload succeeded
            } else {
                return false; // Image upload failed
            }
        }

        // Add the WHERE clause at the end
        $sql .= " WHERE dishes.d_id = '$dishes_Id'";

        // Execute the update query
        $result = mysqli_query($this->con, $sql);

        if ($result) {
            // Log the activity
            $activity = 'Updated menu item';
            $details = "Updated dish with ID: $dishes_Id, Title: $title";

            // Insert log into activity_log table
            $logStmt = $this->con->prepare("INSERT INTO activity_log (user_id, activity, details) VALUES (?, ?, ?)");
            // Assuming the user performing the action has a session variable `user_id`
            $user_id = $_SESSION['user_id'];  // This can be set based on your session handling
            $logStmt->bind_param("iss", $user_id, $activity, $details); // Log the user, activity, and details
            $logStmt->execute();

            return true;
        } else {
            return false;  // Return false if the update failed
        }
    }

    public function updateStallMenu(
        $dishes_Id,
        $title,
        $status,
        $slogan,
        $price,
        $available_quantity,
        $dish_category_id,
        $image
    ) {
        // Escape variables properly
        $title = mysqli_real_escape_string($this->con, $title);
        $slogan = mysqli_real_escape_string($this->con, $slogan);
        $price = mysqli_real_escape_string($this->con, $price);
        $available_quantity = mysqli_real_escape_string($this->con, $available_quantity);
        $dish_category_id = mysqli_real_escape_string($this->con, $dish_category_id);

        // Start building the SQL
        $sql = "UPDATE dishes SET 
        title = '$title',
        slogan = '$slogan',
        price = '$price',
        status = '$status',
        available_quantity = '$available_quantity',
        dish_category_id = '$dish_category_id'";

        // Handle image upload if a new image is uploaded
        if (!empty($image) && !empty($image['name'])) {
            $imageName = basename($image['name']);
            $imageTmp = $image['tmp_name'];
            $imageFolder = "Res_img/";

            if (!is_dir($imageFolder)) {
                mkdir($imageFolder, 0777, true);
            }

            $newImageName = time() . "_" . $imageName; // Unique file name
            $targetPath = $imageFolder . $newImageName;

            if (move_uploaded_file($imageTmp, $targetPath)) {
                $imagePathForDB = mysqli_real_escape_string($this->con, $newImageName);
                $sql .= ", img = '$imagePathForDB'"; // Append img only if upload succeeded
            } else {
                return false; // Image upload failed
            }
        }

        // Add the WHERE clause at the end
        $sql .= " WHERE dishes.d_id = '$dishes_Id'";

        // Execute the update query
        $result = mysqli_query($this->con, $sql);

        if ($result) {
            // Log the activity
            $activity = 'Updated menu item';
            $details = "Updated dish with ID: $dishes_Id, Title: $title";

            // Insert log into activity_log table
            $logStmt = $this->con->prepare("INSERT INTO activity_log (user_id, activity, details) VALUES (?, ?, ?)");
            // Assuming the user performing the action has a session variable `user_id`
            $user_id = $_SESSION['user_id'];  // This can be set based on your session handling
            $logStmt->bind_param("iss", $user_id, $activity, $details); // Log the user, activity, and details
            $logStmt->execute();

            return true;
        } else {
            return false;  // Return false if the update failed
        }
    }

    // category

    public function viewCategoryDetails($c_id)
    {
        $sql = "SELECT * FROM res_category WHERE c_id = '$c_id'";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        return $result;
    }
    public function updateCategory($c_id, $c_name, $status)
    {
        // Escape variables properly
        $c_id = mysqli_real_escape_string($this->con, $c_id);
        $c_name = mysqli_real_escape_string($this->con, $c_name);
        $status = mysqli_real_escape_string($this->con, $status);

        // Prepare SQL query for updating the category
        $sql = "UPDATE res_category SET c_name = '$c_name', status = '$status' WHERE c_id = '$c_id'";

        // Execute the update query
        $result = mysqli_query($this->con, $sql);

        if ($result) {
            // Log the activity
            $activity = 'Updated category';
            $details = "Updated category with ID: $c_id, Name: $c_name, Status: $status";

            // Insert log into activity_log table
            $logStmt = $this->con->prepare("INSERT INTO activity_log (user_id, activity, details) VALUES (?, ?, ?)");
            // Assuming the user performing the action has a session variable `user_id`
            $user_id = $_SESSION['user_id'];  // This can be set based on your session handling
            $logStmt->bind_param("iss", $user_id, $activity, $details); // Log the user, activity, and details
            $logStmt->execute();

            return true;
        } else {
            return false;  // Return false if the update failed
        }
    }

    // Updated Profile
    public function updateProfile($user_id, $f_name, $l_name, $username, $email, $address, $password = null)
    {
        $user_id = intval($user_id); // to be safe

        $f_name = mysqli_real_escape_string($this->con, $f_name);
        $l_name = mysqli_real_escape_string($this->con, $l_name);
        $username = mysqli_real_escape_string($this->con, $username);
        $email = mysqli_real_escape_string($this->con, $email);
        $address = mysqli_real_escape_string($this->con, $address);

        // If password is provided, hash it
        if ($password) {
            $password = mysqli_real_escape_string($this->con, $password);
            $query = "UPDATE users 
                      SET f_name='$f_name', l_name='$l_name', username='$username', email='$email', address='$address', password='$password' 
                      WHERE u_id='$user_id'";
        } else {
            $query = "UPDATE users 
                      SET f_name='$f_name', l_name='$l_name', username='$username', email='$email', address='$address' 
                      WHERE u_id='$user_id'";
        }

        // Execute the update query
        $result = mysqli_query($this->con, $query);

        if ($result) {
            // Log the activity
            $activity = 'Updated user profile';
            $details = "User profile updated for user ID: $user_id, Name: $f_name $l_name, Username: $username, Email: $email";

            // Insert log into activity_log table
            $logStmt = $this->con->prepare("INSERT INTO activity_log (user_id, activity, details) VALUES (?, ?, ?)");
            // Assuming the user performing the action has a session variable `user_id`
            $logged_in_user_id = $_SESSION['user_id'];  // Get the user who is logged in
            $logStmt->bind_param("iss", $logged_in_user_id, $activity, $details); // Log the user, activity, and details
            $logStmt->execute();

            return true; // Return true if the update was successful
        } else {
            return false; // Return false if the update failed
        }
    }


    public function updateUser($u_id, $f_name, $l_name, $username, $phone, $email, $address, $role, $password = null)
    {
        $user_id = intval($u_id); // to be safe

        $f_name = mysqli_real_escape_string($this->con, $f_name);
        $l_name = mysqli_real_escape_string($this->con, $l_name);
        $username = mysqli_real_escape_string($this->con, $username);
        $email = mysqli_real_escape_string($this->con, $email);
        $address = mysqli_real_escape_string($this->con, $address);
        $phone = mysqli_real_escape_string($this->con, $phone);
        $role = mysqli_real_escape_string($this->con, $role);

        // Determine the query based on whether the password is provided
        if ($password) {
            $password = mysqli_real_escape_string($this->con, $password);
            $query = "UPDATE users 
                      SET f_name='$f_name', l_name='$l_name', username='$username', email='$email', 
                      phone='$phone', role='$role',
                      address='$address', password='$password' 
                      WHERE u_id='$user_id'";
        } else {
            $query = "UPDATE users 
                      SET f_name='$f_name', l_name='$l_name', username='$username', email='$email',
                      phone='$phone', role='$role',
                      address='$address' 
                      WHERE u_id='$user_id'";
        }

        // Execute the update query
        $result = mysqli_query($this->con, $query);

        if ($result) {
            // Log the activity
            $activity = 'Updated user information';
            $details = "User information updated for user ID: $user_id, Name: $f_name $l_name, Username: $username, Email: $email";

            // Insert log into activity_log table
            $logStmt = $this->con->prepare("INSERT INTO activity_log (user_id, activity, details) VALUES (?, ?, ?)");
            // Assuming the user performing the action has a session variable `user_id`
            $logged_in_user_id = $_SESSION['user_id'];  // Get the user who is logged in
            $logStmt->bind_param("iss", $logged_in_user_id, $activity, $details); // Log the user, activity, and details
            $logStmt->execute();

            return true; // Return true if the update was successful
        } else {
            return false; // Return false if the update failed
        }
    }



    public function ridersReceivedOrder($user_id)
    {
        // Corrected SQL query
        $sql = "SELECT 
                transaction.id AS transacID, 
                transaction.u_id, 
                transaction.total_price, 
                transaction.status, 
                transaction.order_date,
                CONCAT(users.f_name, ' ', users.l_name) AS customerName,
                users.phone AS customerPhone
            FROM transaction 
            LEFT JOIN users ON users.u_id = transaction.u_id
            WHERE transaction.status = 'order_received'
            AND transaction.rider_id = '$user_id'
            ORDER BY transaction.updated_at DESC";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        return $result;
    }


    public function ridersDeliveredOrder($user_id)
    {
        // Corrected SQL query
        $sql = "SELECT 
                transaction.id AS transacID, 
                transaction.u_id, 
                transaction.total_price, 
                transaction.status, 
                transaction.order_date,
                CONCAT(users.f_name, ' ', users.l_name) AS customerName,
                users.phone AS customerPhone
            FROM transaction 
            LEFT JOIN users ON users.u_id = transaction.u_id
            WHERE transaction.status = 'order_delivered'
            AND transaction.rider_id = '$user_id'
            ORDER BY transaction.updated_at DESC";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        return $result;
    }

    public function getDeliveredOrderRating($transacId)
    {
        $sql = "SELECT users.f_name, users.l_name,
        rating_rider.rider_name, rating_rider.rating, rating_rider.complaint,
        rating_rider.created_at
        FROM rating_rider
        LEFT JOIN users ON
        users.u_id = rating_rider.user_id 
        WHERE rating_rider.transaction_id = '$transacId'
        ORDER BY rating_rider.created_at DESC";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }


        return $result;
    }


    public function getDeliveredRating($transacId)
    {
        $transacId = intval($transacId);

        $sql = "SELECT 
                    ROUND(AVG(rating), 1) AS avg_rating, 
                    COUNT(*) AS total 
                FROM rating_rider 
                WHERE transaction_id = $transacId";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        $data = mysqli_fetch_assoc($result);
        return $data ?: ['avg_rating' => 0, 'total' => 0];
    }

    public function addToCart($user_id, $quantity, $dishes_id)
    {
        // Escape variables 
        $user_id = mysqli_real_escape_string($this->con, $user_id);
        $d_id = mysqli_real_escape_string($this->con, $dishes_id);
        $quantity = mysqli_real_escape_string($this->con, $quantity);

        // Check if the dish is already in the user's cart
        $checkSql = "SELECT * FROM carts WHERE user_id = '$user_id' AND dishes_id = '$d_id'";
        $checkResult = mysqli_query($this->con, $checkSql);

        if (mysqli_num_rows($checkResult) > 0) {
            // If the dish is already in the cart, update the quantity
            $updateSql = "UPDATE carts SET quantity = quantity + '$quantity' WHERE user_id = '$user_id' AND dishes_id = '$d_id'";
            return mysqli_query($this->con, $updateSql);
        } else {
            // If the dish is not in the cart, insert it
            $insertSql = "INSERT INTO carts (user_id, dishes_id, quantity) VALUES ('$user_id', '$d_id', '$quantity')";
            return mysqli_query($this->con, $insertSql);
        }
    }

    public function getUserCart($user_id)
    {
        $user_id = mysqli_real_escape_string($this->con, $user_id);

        $sql = "SELECT 
                    carts.id AS cartId,
                    carts.quantity,
                    carts.quantity * dishes.price AS totalPrice,
                    dishes.title AS dishName,
                    dishes.rs_id,
                    restaurant.title AS restaurantName,
                    restaurant.o_hr,  -- Operating hour (open)
                    restaurant.c_hr,   -- Closing hour
                    dishes.img AS dishImage
                FROM carts
                LEFT JOIN dishes ON dishes.d_id = carts.dishes_id
                LEFT JOIN restaurant ON restaurant.rs_id = dishes.rs_id
                WHERE carts.user_id = '$user_id'
                ORDER BY restaurant.title";

        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        return $result;
    }



    public function viewCheckOutDetails($cartId)
    {
        $sql = "SELECT 
            dishes.title AS dishesName, 
            (dishes.price * carts.quantity) AS totalPrice,
            restaurant.title AS restauName,
            dishes.rs_id, 
            users.address AS userAddress
        FROM carts
        LEFT JOIN users ON users.u_id = carts.user_id
        LEFT JOIN dishes ON dishes.d_id = carts.dishes_id
        LEFT JOIN restaurant ON restaurant.rs_id = dishes.rs_id
        WHERE carts.id = '$cartId'";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        return $result;
    }

    public function checkoutOrder(
        $user_id,
        $gcash_proof,
        $mod,
        $delivery_type,
        $total_price,
        $userAddress,
        $address
    ) {
        $user_id = mysqli_real_escape_string($this->con, $user_id);
        $gcash_proof = mysqli_real_escape_string($this->con, $gcash_proof);
        $mod = mysqli_real_escape_string($this->con, $mod);
        $delivery_type = mysqli_real_escape_string($this->con, $delivery_type);
        $total_price = mysqli_real_escape_string($this->con, $total_price);
        $userAddress = mysqli_real_escape_string($this->con, $userAddress);
        $address = mysqli_real_escape_string($this->con, $address);

        $order_date = date("Y-m-d H:i:s");
        $payment_status = ($mod == "GCash" && $gcash_proof) ? "Pending" : "Paid";

        $sql = "INSERT INTO transaction (
                    u_id, total_price, address, order_date, status, payment_status, payment_method, gcash_proof
                ) 
                VALUES (
                    '$user_id', '$total_price', '$address', '$order_date', 'place_order',
                    '$payment_status', '$mod', '$gcash_proof'
                )";

        if (mysqli_query($this->con, $sql)) {
            $transaction_id = mysqli_insert_id($this->con);

            // Log the activity after successful checkout
            $activity = "Checked out order";
            $details = "User ID $user_id placed an order (Transaction ID: $transaction_id) with total ₱$total_price";

            $logStmt = $this->con->prepare("INSERT INTO activity_log (user_id, activity, details) VALUES (?, ?, ?)");
            $logStmt->bind_param("iss", $user_id, $activity, $details);
            $logStmt->execute();

            return $transaction_id;
        } else {
            return false;
        }
    }

    public function viewOrderItems($transacId)
    {
        $transacId = mysqli_real_escape_string($this->con, $transacId); // security: prevent SQL injection
        $sql = "SELECT 
                    order_items.dishes_id, 
                    order_items.quantity AS orderQuantity,
                    order_items.total_price AS orderTotalPrice,
                    dishes.title AS dishName
                FROM order_items
                LEFT JOIN dishes ON dishes.d_id = order_items.dishes_id
                WHERE order_items.transaction_id = '$transacId'";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        return $result;
    }

    public function deleteCartItem($cartId)
    {

        $cartId = mysqli_real_escape_string($this->con, $cartId);
        $result = mysqli_query($this->con, "DELETE FROM carts WHERE id = '$cartId'");

        if ($result && isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $activity = "Deleted Cart Item";
            $details = "Cart item with ID $cartId was deleted.";

            $logStmt = $this->con->prepare("INSERT INTO activity_log (user_id, activity, details) VALUES (?, ?, ?)");
            $logStmt->bind_param("iss", $user_id, $activity, $details);
            $logStmt->execute();
        }

        return $result;
    }



    public function getActiveUsers()
    {
        // Correct SQL query
        $sql = "SELECT * FROM users";

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

    public function getActiveNoStallUsers()
    {
        // Correct SQL query
        $sql = "SELECT * FROM users WHERE restaurant_id IS NULL
        AND role = 3"; //stall owner

        // Execute the query
        $result = mysqli_query($this->con, $sql);

        // Check if the query was successful
        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        return $result;
    }

    public function getActiveStallUsers()
    {
        // Correct SQL query
        $sql = "SELECT * FROM users WHERE role = 3";

        // Execute the query
        $result = mysqli_query($this->con, $sql);

        // Check if the query was successful
        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        return $result;
    }



    public function getActiveRestaurant()
    {
        $sql = "SELECT * FROM restaurant";


        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        return $result;
    }
    public function getActivityLog()
    {
        $sql = "SELECT 
                    activity_log.id, 
                    activity_log.user_id, 
                    activity_log.activity, 
                    activity_log.details, 
                    activity_log.created_at, 
                    CONCAT(users.f_name, ' ', users.l_name) AS user_name
                FROM activity_log
                LEFT JOIN users ON users.u_id = activity_log.user_id
                ORDER BY activity_log.created_at DESC";

        $result = mysqli_query($this->con, $sql);
        if (!$result) {
            die('Query failed: ' . mysqli_error($this->con));
        }

        return $result;
    }

    public function getDeliveredOrders()
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
                WHERE transaction.status = 'order_delivered'
                ORDER BY transaction.order_date DESC";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            die('Query failed:   ' . mysqli_error($this->con));
        }

        return $result;
    }
}
