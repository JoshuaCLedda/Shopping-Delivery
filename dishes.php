<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', value: 1); // Ensure errors are displayed
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


// include_once 'product-action.php'; 

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    include "admin/Main.php";
    $index = new Index;

    $query = "SELECT f_name, l_name, username, email, phone, address FROM users WHERE u_id = '$user_id'";
    $result = mysqli_query($index->con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'User data not found.'];
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}


if (isset($_POST['submit'])) {
    // Collect form data
    $user_id = $_POST['user_id'];
    $d_id = $_POST['dishes_id'];
    $quantity = $_POST['quantity'];

    // Call the model function
    $result = $index->addToCart(
        $user_id,
        $quantity,
        $d_id
    );

    if ($result) {
        $_SESSION['message'] = ['type' => 'success', 'message' => 'Item added to cart successfully!'];
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'message' => 'Failed to add item to cart.'];
    }

    header("Location: dishes.php?res_id=" . $_GET['res_id']);
    exit();
}


?>


<?php include 'layouts/header.php'; ?>
<?php include 'layouts/navbar.php'; ?>


<div class="page-wrapper">
    <div class="top-links">
        <div class="container">
            <ul class="row links">

                <li class="col-xs-12 col-sm-4 link-item"><span>1</span><a href="restaurants.php">Choose Stall</a></li>
                <li class="col-xs-12 col-sm-4 link-item active"><span>2</span><a href="dishes.php?res_id=<?php echo $_GET['res_id']; ?>">Pick Your favorite food</a></li>
                <li class="col-xs-12 col-sm-4 link-item"><span>3</span><a href="#">Order and Pay</a></li>

            </ul>
        </div>
    </div>


    <section class="inner-page-hero bg-image" data-image-src="images/img/rest.png">


        <?php $ress = mysqli_query($index->con, "select * from restaurant where rs_id='$_GET[res_id]'");
        $rows = mysqli_fetch_array($ress);

        ?>

        <div class="profile">
            <div class="container">
                <div class="row align-items-center" style="min-height: 250px;">
                    <!-- Restaurant Logo Section -->
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-center">
                        <a class="restaurant-logo" href="dishes.php?res_id=<?= $restaurantId ?>">
                            <div style="
                            background-image: url('admin/Res_img/<?= htmlspecialchars($rows['image']) ?>');
                            background-size: cover;
                            background-position: center;
                            width: 100%;
                            height: 120px;
                            border-radius: 8px;">
                            </div>
                        </a>
                    </div>

                    <!-- Restaurant Info Section -->
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 profile-desc d-flex flex-column justify-content-center p-4" style="border-radius: 0 10px 10px 0;">
                        <div class="right-text">
                            <h6 class="mb-2">
                                <a href="#" style="color: black;"><?= htmlspecialchars($rows['title']) ?></a>
                            </h6>
                            <p style="color: black;">
    <?= htmlspecialchars($rows['address'] ?? 'No address available') ?>
</p>

                            <span class="product-name" style="text-transform: uppercase; color: #333">
                                <?= htmlspecialchars($rows['o_hr']) ?> - <?= htmlspecialchars($rows['c_hr']) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="container m-t-30">

        <div class="row">


            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">

                <div class="widget widget-cart">
                    <div class="widget-heading">
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-<?php echo $_SESSION['message']['type']; ?> " role="alert" id="alert1">
                                <i class="fa-sharp fa-solid fa-circle-check"></i>
                                <?php echo $_SESSION['message']['message']; ?>
                            </div>
                            <?php unset($_SESSION['message']); // Unset the message after displaying it 
                            ?>
                        <?php endif; ?>
                        <h3 class="widget-title text-dark">

                            Your Cart
                        </h3>


                        <div class="clearfix"></div>
                    </div>
                    <div class="order-row bg-white">
                        <div class="widget-body">


                            <?php
                            $result = $index->getUserCart();
                            while ($row = mysqli_fetch_array($result)): ?>
                                <div class="title-row">
                                    <?php echo $row["dishName"]; ?>
                                    <!-- <a href="dishes.php?res_id=?php echo $_GET['res_id']; ?>&action=remove&id=<?php echo $row["d_id"]; ?>" -->
                                    <i class="fa fa-trash pull-right"></i></a>
                                </div>
                                <div class="form-group row no-gutter">
                                    <div class="col-xs-8">
                                        <input type="text" class="form-control b-r-0"
                                            value="<?php echo htmlspecialchars(isset($row['totalPrice']) && $row['totalPrice'] !== null ? '₱' . $row['totalPrice'] : 'No data'); ?>"
                                            readonly id="exampleSelect1">

                                    </div>
                                    <div class="col-xs-4">
                                        <input class="form-control" type="text" readonly value='<?php echo $row["quantity"]; ?>' id="example-number-input">
                                    </div>

                                </div>



                        </div>
                    </div>



                    <div class="widget-body">
                        <div class="price-wrap text-xs-center">
                            <p>TOTAL</p>
                            <h3 class="value"><strong>
                            </h3>
                            <p>Free Delivery!</p>

                            <a href="checkout.php?cartId=<?php echo $row['cartId']; ?>" class="btn btn-success btn-lg active">Checkout</a>
                        <?php endwhile; ?>

                        </div>
                    </div>




                </div>
            </div>

            <div class="col-md-8">


                <div class="menu-widget" id="2">
                    <div class="widget-heading">
                        <h3 class="widget-title text-dark">
                            MENU <a class="btn btn-link pull-right" data-toggle="collapse" href="#popular2" aria-expanded="true">
                                <i class="fa fa-angle-right pull-right"></i>
                                <i class="fa fa-angle-down pull-right"></i>
                            </a>
                        </h3>
                        <div class="clearfix"></div>
                    </div>
                    <div class="collapse in" id="popular2">
                        <?php
                        $stmt = $index->con->prepare("SELECT * FROM dishes 
                    WHERE dishes.status = 1 
                    AND rs_id = ?");
                        $stmt->bind_param("i", $_GET['res_id']);  // Assuming res_id is an integer
                        $stmt->execute();
                        $products = $stmt->get_result();

                        if (!empty($products)) {
                            foreach ($products as $product) {



                        ?>
                                <div class="food-item py-3 border-bottom">

                                    <form method="POST" action="">
                                        <div class="row align-items-center">

                                            <!-- Image -->
                                            <div class="col-12 col-sm-3 col-lg-2 mb-3 mb-sm-0">
                                                <div class="rest-logo">
                                                    <a class="restaurant-logo" href="#">
                                                        <img
                                                            src="admin/Res_img/dishes/<?= htmlspecialchars($product['img']) ?>"
                                                            alt="Food Image"
                                                            style="width: 100%; height: 100px; object-fit: cover; border-radius: 8px;" />
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- Dish info -->
                                            <div class="col-12 col-sm-6 col-lg-7">
                                                <div class="rest-descr">
                                                    <h6 class="mb-1">
                                                        <a href="#" style="color: #333;"><?= htmlspecialchars($product['title']) ?></a>
                                                    </h6>
                                                    <p class="small text-muted mb-0"><?= htmlspecialchars($product['slogan']) ?></p>
                                                </div>
                                            </div>

                                            <!-- Price + Quantity + Button -->
                                            <div class="col-12 col-sm-3 col-lg-3 text-sm-right">
                                                <div class="item-cart-info d-flex flex-column align-items-start align-items-sm-end">
                                                    <span class="price mb-2" style="font-weight: bold; font-size: 18px;">₱<?= htmlspecialchars($product['price']) ?></span>

                                                    <!-- Quantity -->
                                                    <input
                                                        class="form-control form-control-sm mb-2"
                                                        type="number"
                                                        name="quantity"
                                                        value="1"
                                                        min="1"
                                                        style="width: 170px;" />

                                                    <!-- Hidden fields -->
                                                    <input type="hidden" name="dishes_id" value="<?= htmlspecialchars($product['d_id']) ?>">
                                                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">

                                                    <!-- Submit -->
                                                    <input
                                                        type="submit"
                                                        name="submit"
                                                        class="btn btn-sm theme-btn"
                                                        value="Add To Cart" />
                                                </div>
                                            </div>

                                        </div>
                                    </form>

                                </div>



                        <?php
                            }
                        }

                        ?>



                    </div>

                </div>


            </div>

        </div>

    </div>

    <footer class="footer">
        <div class="container">

            <div class="row bottom-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3 payment-options color-gray">
                            <h5>Payment Options</h5>
                            <ul>
                                <li>
                                    <a href="#"> <img src="images/paypal.png" alt="Paypal"> </a>
                                </li>
                                <li>
                                    <a href="#"> <img src="images/mastercard.png" alt="Mastercard"> </a>
                                </li>
                                <li>
                                    <a href="#"> <img src="images/maestro.png" alt="Maestro"> </a>
                                </li>
                                <li>
                                    <a href="#"> <img src="images/stripe.png" alt="Stripe"> </a>
                                </li>
                                <li>
                                    <a href="#"> <img src="images/bitcoin.png" alt="Bitcoin"> </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-sm-4 address color-gray">
                            <h5>Address</h5>
                            <p>1086 Stockert Hollow Road, Seattle</p>
                            <h5>Phone: 75696969855</a></h5>
                        </div>
                        <div class="col-xs-12 col-sm-5 additional-info color-gray">
                            <h5>Addition informations</h5>
                            <p>Join thousands of other restaurants who benefit from having partnered with us.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </footer>

</div>

</div>


<div class="modal fade" id="order-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            <div class="modal-body cart-addon">
                <div class="food-item white">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <div class="item-img pull-left">
                                <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/70x70" alt="Food logo"></a>
                            </div>

                            <div class="rest-descr">
                                <h6><a href="#">Sandwich de Alegranza Grande Menü (28 - 30 cm.)</a></h6>
                            </div>

                        </div>

                        <div class="col-xs-6 col-sm-2 col-lg-2 text-xs-center"> <span class="price pull-left">₱ 2.99</span></div>
                        <div class="col-xs-6 col-sm-4 col-lg-4">
                            <div class="row no-gutter">
                                <div class="col-xs-7">
                                    <select class="form-control b-r-0" id="exampleSelect2">
                                        <option>Size SM</option>
                                        <option>Size LG</option>
                                        <option>Size XL</option>
                                    </select>
                                </div>
                                <div class="col-xs-5">
                                    <input class="form-control" type="number" value="0" id="quant-input-2">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="food-item">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <div class="item-img pull-left">
                                <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/70x70" alt="Food logo"></a>
                            </div>

                            <div class="rest-descr">
                                <h6><a href="#">Sandwich de Alegranza Grande Menü (28 - 30 cm.)</a></h6>
                            </div>

                        </div>

                        <div class="col-xs-6 col-sm-2 col-lg-2 text-xs-center"> <span class="price pull-left">₱ 2.49</span></div>
                        <div class="col-xs-6 col-sm-4 col-lg-4">
                            <div class="row no-gutter">
                                <div class="col-xs-7">
                                    <select class="form-control b-r-0" id="exampleSelect3">
                                        <option>Size SM</option>
                                        <option>Size LG</option>
                                        <option>Size XL</option>
                                    </select>
                                </div>
                                <div class="col-xs-5">
                                    <input class="form-control" type="number" value="0" id="quant-input-3">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="food-item">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <div class="item-img pull-left">
                                <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/70x70" alt="Food logo"></a>
                            </div>

                            <div class="rest-descr">
                                <h6><a href="#">Sandwich de Alegranza Grande Menü (28 - 30 cm.)</a></h6>
                            </div>

                        </div>

                        <div class="col-xs-6 col-sm-2 col-lg-2 text-xs-center"> <span class="price pull-left">₱ 1.99</span></div>
                        <div class="col-xs-6 col-sm-4 col-lg-4">
                            <div class="row no-gutter">
                                <div class="col-xs-7">
                                    <select class="form-control b-r-0" id="exampleSelect5">
                                        <option>Size SM</option>
                                        <option>Size LG</option>
                                        <option>Size XL</option>
                                    </select>
                                </div>
                                <div class="col-xs-5">
                                    <input class="form-control" type="number" value="0" id="quant-input-4">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="food-item">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <div class="item-img pull-left">
                                <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/70x70" alt="Food logo"></a>
                            </div>

                            <div class="rest-descr">
                                <h6><a href="#">Sandwich de Alegranza Grande Menü (28 - 30 cm.)</a></h6>
                            </div>

                        </div>

                        <div class="col-xs-6 col-sm-2 col-lg-2 text-xs-center"> <span class="price pull-left">₱ 3.15</span></div>
                        <div class="col-xs-6 col-sm-4 col-lg-4">
                            <div class="row no-gutter">
                                <div class="col-xs-7">
                                    <select class="form-control b-r-0" id="exampleSelect6">
                                        <option>Size SM</option>
                                        <option>Size LG</option>
                                        <option>Size XL</option>
                                    </select>
                                </div>
                                <div class="col-xs-5">
                                    <input class="form-control" type="number" value="0" id="quant-input-5">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn theme-btn">Add To Cart</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<?php include 'layouts/footer.php'; ?>