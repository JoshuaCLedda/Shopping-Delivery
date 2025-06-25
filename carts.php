<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
include "admin/Main.php";
$index = new Index;

$query = "SELECT f_name, l_name, username, email, phone, address FROM users WHERE u_id = '$user_id'";
$result = mysqli_query($index->con, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    $_SESSION['message'] = ['type' => 'danger', 'message' => 'User data not found.'];
    header("Location: login.php");
    exit();
}

$user = mysqli_fetch_assoc($result);
?>

<?php include 'layouts/header.php'; ?>
<?php include 'layouts/navbar.php'; ?>
<?php include 'layouts/sweetalert.php'; ?>

<div class="container py-5">
  <div class="col-lg-10 mx-auto cart-wrapper">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="fw-bold"><i class="fa-solid fa-cart-shopping me-2"></i>My Cart</h3>
      <a href="#" id="deleteSelectedBtn" class="btn btn-danger btn-sm">
        <i class='bx bx-trash me-1'></i> Delete
      </a>
    </div>

    <form id="checkoutForm" action="checkout.php" method="POST">
      <?php
      date_default_timezone_set('Asia/Manila');
      $currentTime = date("H:i");
      $result = $index->getUserCart($user_id);

      if (mysqli_num_rows($result) > 0):
        $cartByRestaurant = [];
        while ($row = mysqli_fetch_assoc($result)) {
          $cartByRestaurant[$row['restaurantName']][] = $row;
        }

        foreach ($cartByRestaurant as $restaurantName => $items):
          $o_hr = $items[0]['o_hr'];
          $c_hr = $items[0]['c_hr'];
          $openTime = date("H:i", strtotime($o_hr));
          $closeTime = date("H:i", strtotime($c_hr));
          $isOpen = ($currentTime >= $openTime && $currentTime <= $closeTime);
      ?>
        <div class="mb-5">
          <div class="restaurant-header d-flex align-items-center gap-2 mb-2">
            <i class="fa-solid fa-store text-warning"></i>
            <span class="<?= !$isOpen ? 'text-danger' : '' ?> fw-semibold">
              <?= htmlspecialchars($restaurantName) ?>
              <?= !$isOpen ? '(Stall Closed)' : '' ?>
            </span>
          </div>

          <?php foreach ($items as $item): ?>
            <div class="card mb-3 shadow-sm border-0 <?= !$isOpen ? 'disabled-card' : ''; ?>">
              <div class="card-body d-flex align-items-center gap-3">

                <!-- Checkbox -->
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="selected_items[]" value="<?= $item['cartId']; ?>" <?= !$isOpen ? 'disabled' : ''; ?>>
                </div>

                <!-- Dish Image -->
                <div>
                  <img src="admin/Res_img/<?= htmlspecialchars($item['dishImage']) ?>" alt="Dish Image"
                    class="rounded border" style="width: 80px; height: 80px; object-fit: cover;">
                </div>







<div class="quantity-controls d-flex align-items-center gap-2"
     data-cart-id="<?= $item['cartId'] ?>"
     data-price="<?= (float)$item['price'] ?>">


  <!-- Minus Button -->
  <a href="#" class="btn btn-sm btn-outline-secondary px-2 py-1 btn-decrease">
    <i class="bx bx-minus"></i>
  </a>

  <!-- Quantity Display -->
  <input type="number" readonly class="form-control form-control-sm text-center quantity-display"
         value="<?= (int)$item['quantity'] ?>" min="1"
         style="width: 50px; background-color: #f8f9fa;">

  <!-- Plus Button -->
  <a href="#" class="btn btn-sm btn-outline-secondary px-2 py-1 btn-increase">
    <i class="bx bx-plus"></i>
  </a>

  <!-- Total Price Badge -->
  <span class="badge bg-success rounded-pill total-price ms-2"
        id="total-price-<?= $item['cartId'] ?>">
    ₱<?= number_format($item['totalPrice'], 2) ?>
  </span>

</div>








                <!-- Delete Button -->
                <div>
                  <a href="delete_cart_item.php?cartId=<?= $item['cartId']; ?>"
                    class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Remove this item from your cart?');">
                    <i class='bx bx-trash'></i>
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <?php endforeach; ?>

        <div class="text-center my-4">
          <button type="submit" class="btn btn-success btn-lg px-4 shadow">
            <i class='bx bxs-cart-alt me-2'></i> Checkout
          </button>
        </div>
      <?php else: ?>
        <div class="text-center py-5 bg-light border rounded shadow-sm">
          <img src="assets/images/icons/emptycart.svg" alt="Empty Cart" class="mb-4" style="width: 200px;">
          <h5 class="text-muted">Your cart is empty 🛒</h5>
        </div>
      <?php endif; ?>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
  $('.quantity-controls').each(function () {
    const container = $(this);
    const cartId = container.data('cart-id');
const price = parseFloat(container.data('price')) || 0;

    const input = container.find('.quantity-display');

    container.find('.btn-increase, .btn-decrease').on('click', function (e) {
      e.preventDefault();
      const action = $(this).hasClass('btn-increase') ? 'increase' : 'decrease';

      $.ajax({
        url: 'update_cart_quantity.php',
        method: 'POST',
        data: {
          cart_id: cartId,
          action: action
        },
      success: function (data) {
  if (data.success) {
    input.val(data.new_quantity);
    const unitPrice = parseFloat(data.price); // <- now from backend
    const newTotal = (unitPrice * data.new_quantity).toFixed(2);
    $(`#total-price-${cartId}`).text(`₱${newTotal}`);
  } else {
    alert(data.message || 'Quantity update failed.');
  }
},

        error: function () {
          alert('Something went wrong with the server.');
        }
      });
    });
  });
});

</script>

<script>
  // Prevent checkout if no item is selected
  $('#checkoutForm').on('submit', function (e) {
    if ($('input[name="selected_items[]"]:checked').length === 0) {
      e.preventDefault();
      alert("Please select at least one item to checkout.");
    }
  });

  // Handle delete selected items
  document.getElementById('deleteSelectedBtn').addEventListener('click', function (e) {
    e.preventDefault();
    const form = document.getElementById('checkoutForm');
    const checked = form.querySelectorAll('input[name="selected_items[]"]:checked');
    if (checked.length === 0) {
      alert('Please select at least one item to delete.');
      return;
    }
    if (confirm('Are you sure you want to delete the selected items?')) {
      form.action = 'delete_selected_cart_items.php';
      form.submit();
    }
  });

  // Prevent quantity form bubbling to checkout form
  document.querySelectorAll('.quantity-form').forEach(form => {
    form.addEventListener('submit', function (e) {
      e.stopPropagation();
    });
  });
</script>



<style>
    .disabled-card {
        opacity: 0.5;
        /* Reduce opacity to show that it's disabled */
        background-color: #f8f9fa;
        /* Light grey background for disabled items */
        color: #6c757d;
        /* Greyed out text */
    }

    .disabled-card .form-check-input:disabled {
        background-color: #e0e0e0;
        /* Disabled checkbox color */
        border-color: #ccc;
        /* Border color for the disabled checkbox */
    }

    .disabled-card .form-check-label {
        color: #6c757d;
        /* Grey out the label for disabled items */
    }

    .disabled-card .btn {
        background-color: #f8f9fa !important;
        border: 1px solid #ccc;
        color: #6c757d;
    }

    .disabled-card .btn:hover {
        background-color: #f8f9fa !important;
    }
    .disabled-card {
  opacity: 0.6;
  pointer-events: none;
}
.restaurant-header {
  font-size: 1rem;
  font-weight: 600;
}
.cart-wrapper .badge {
  font-size: 0.85rem;
  padding: 0.4em 0.7em;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>