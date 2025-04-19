<?php
error_reporting(E_ALL);
ini_set('display_errors', value: 1); // Ensure errors are displayed
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
        $image,
        $address,
        $c_id
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

        // Email uniqueness check
        $checkQuery = "SELECT * FROM restaurant WHERE email = '$email'";
        $checkResult = mysqli_query($this->con, $checkQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            return false; // Email already exists
        }

        // Handle image upload
        $imageName = basename($image['name']);
        $imageTmp = $image['tmp_name'];
        $imageFolder = "Res_img/";

        // Create folder if it doesn't exist
        if (!is_dir($imageFolder)) {
            mkdir($imageFolder, 0777, true);
        }

        $targetPath = $imageFolder . time() . "_" . $imageName;

        if (move_uploaded_file($imageTmp, $targetPath)) {
            $imagePathForDB = mysqli_real_escape_string($this->con, $targetPath);
        } else {
            return false; // Image upload failed
        }

        // Insert restaurant data with image
        $sql = "INSERT INTO restaurant 
            (title, email, phone, url, o_hr, c_hr, o_days, address, c_id, image)
            VALUES 
            ('$res_name', '$email', '$phone', '$url', '$o_hr', '$c_hr', '$o_days', '$address', '$c_id', '$imagePathForDB')";

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
        $sql = "SELECT 
                transaction.id AS transacID, 
                transaction.u_id, 
                transaction.total_price, 
                transaction.stall_id, 
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
        $status = "order_confirmation";
        // Update transaction with rider_id and status
        $sql = "UPDATE transaction 
                SET rider_id = '$rider_id',
                status = 'order_received'
                updated_at = NOW()
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
        // Set the rider's status to 'banned' (terminate status)
        $sql = "UPDATE users SET status = 'banned' WHERE u_id = $u_id";

        $result = mysqli_query($this->con, $sql);

        if (!$result) {
            error_log("Terminate Error: " . mysqli_error($this->con));
            return false;  // Return false if the query fails
        }

        return true;  // Return true if the update was successful
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
                    transaction.titles AS dishesOrder,
                    restaurant.title
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
        $address
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

        // Prepare base query
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

        // If image is uploaded, handle it
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

        // Finish query
        $sql .= " WHERE rs_id = '$rs_id'";

        return mysqli_query($this->con, $sql);
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
        $slogan,
        $price,
        $available_quantity,
        $dish_category_id,
        $rs_id,
        $image = null
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
        $sql .= " WHERE disheiSId = '$dishes_Id'";

        // Execute and return
        return mysqli_query($this->con, $sql);
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
        $c_id = mysqli_real_escape_string($this->con, $c_id);
        $c_name = mysqli_real_escape_string($this->con, $c_name);
        $status = mysqli_real_escape_string($this->con, $status);

        $sql = "UPDATE res_category SET c_name = '$c_name', status = '$status' WHERE c_id = '$c_id'";

        return mysqli_query($this->con, $sql);
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

        return mysqli_query($this->con, $query);
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

        return mysqli_query($this->con, $query);
    }


    public function ridersReceivedOrder($user_id)
    {
        // Corrected SQL query
        $sql = "SELECT 
                transaction.id AS transacID, 
                transaction.u_id, 
                transaction.total_price, 
                transaction.stall_id, 
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
                transaction.stall_id, 
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
        WHERE rating_rider.orders_id = '$transacId'
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
                WHERE orders_id = $transacId";

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
    
    
    public function getUserCart()
    {
        // Corrected SQL query with total price calculation
        $sql = "SELECT 
                    dishes.title as dishName, 
                    dishes.d_id AS dishesId, 
                    carts.id AS cartId,
                    dishes.img, 
                    dishes.price,
                    carts.quantity,
                    (dishes.price * carts.quantity) AS totalPrice
                FROM carts
                LEFT JOIN dishes ON dishes.d_id = carts.dishes_id";
    
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



    public function checkoutOrder
    (
    $user_id,
    $quantity,
    $d_id,
    $gcash_proof,
    $mod,
    $delivery_type,
    $total_price,
    $dishesName,
    $restauName,
    $userAddress)
    {
        // Escape inputs to prevent SQL injection
        $stall_name = mysqli_real_escape_string($this->con, $stall_name);
        $restaurant_id = mysqli_real_escape_string($this->con, $restaurant_id);
        $rating = mysqli_real_escape_string($this->con, $rating);
        $complaint = mysqli_real_escape_string($this->con, $complaint);
        $user_id = mysqli_real_escape_string($this->con, $user_id);

        // Insert into restaurant_rating table
        $sql = "INSERT INTO transations (user_id, restaurant_id, stall_name, rating, complaint) 
                VALUES ('$user_id', '$restaurant_id', '$stall_name', '$rating', '$complaint')";

        return mysqli_query($this->con, $sql);
    }

    
    
}
