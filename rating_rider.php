<form action="submit_rider_rating.php" method="POST" onsubmit="return validateForm();">
    <label for="rider">Select Rider:</label>
    <select name="rider_id" required>
        <option value="">-- Select Rider --</option>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <option value="<?php echo $row['u_id']; ?>">
                <?php echo htmlspecialchars($row['f_name'] . ' ' . $row['l_name']); ?>
            </option>
        <?php } ?>
    </select>

    <label>Rate Rider:</label>
    <span class="rating">
        <i class="fa fa-star star" data-value="1"></i>
        <i class="fa fa-star star" data-value="2"></i>
        <i class="fa fa-star star" data-value="3"></i>
        <i class="fa fa-star star" data-value="4"></i>
        <i class="fa fa-star star" data-value="5"></i>
    </span>
    <input type="hidden" id="rider_rating" name="rider_rating" value="">

    <h5>Complaint (Optional)</h5>
    <textarea name="complaint" class="form-control" placeholder="Describe your complaint (if any)..."></textarea>

    <button type="submit" class="btn btn-success">Submit</button>
</form>
<script>
    document.querySelectorAll(".star").forEach(star => {
        star.addEventListener("click", function () {
            let rating = this.getAttribute("data-value");
            document.getElementById("rider_rating").value = rating;

            // Highlight selected stars
            document.querySelectorAll(".star").forEach(s => {
                s.style.color = (s.getAttribute("data-value") <= rating) ? "gold" : "gray";
            });
        });
    });

    function validateForm() {
        let rating = document.getElementById("rider_rating").value;
        if (!rating || rating < 1 || rating > 5) {
            alert("Please select a rating between 1 and 5 before submitting.");
            return false; // Prevent form submission
        }
        return true;
    }
</script>
