<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['message']['type']; ?> alert-dismissible fade show" role="alert" id="alert1">
        <i class="fa-sharp fa-solid fa-circle-check"></i>
        <?php echo $_SESSION['message']['message']; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php unset($_SESSION['message']); // Unset the message after displaying it 
    ?>
<?php endif; ?>