<?php if (isset($_SESSION['message'])): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: '<?= $_SESSION['message']['type'] ?>',
            title: '<?= $_SESSION['message']['type'] === "success" ? "Success" : "Error" ?>',
            text: '<?= $_SESSION['message']['message'] ?>',
            confirmButtonColor: '#3085d6'
        });
    });
</script>
<?php unset($_SESSION['message']); ?>
<?php endif; ?>
