<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['message']['type']; ?> alert-dismissible fade show" role="alert" id="alert1">
        <i class="fa-sharp fa-solid fa-circle-check"></i>
        <?php echo $_SESSION['message']['message']; ?>
    </div>
    <?php unset($_SESSION['message']); // Unset the message after displaying it 
    ?>
<?php endif; ?>