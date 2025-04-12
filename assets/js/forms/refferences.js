$(document).ready(function () {
    $("#createRefferences").submit(function (e) {
        e.preventDefault();
        var isValid = true;

        // Fields and their validation messages
        var fields = {
            title: "Title is required.",
            author: "Author is required.",
            publication_date: "Publication Date is required.",
            type: "Please select a type.",
            url: "URL is required.",
            description: "Description is required."
        };

        // Clear previous error messages
        $("#createRefferences .text-red").text("");

        // Loop through each field and validate
        $.each(fields, function (name, errorMsg) {
            var field = $(`[name='${name}']`);
            if (field.val().trim() === "" || (name === "type" && field.val() === "Open this select menu")) {
                field.next("#validation").text(errorMsg);
                isValid = false;
            }
        });

        // Submit if valid
        if (isValid) {
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function (response) {
                    $("#message").html(response.message);  
                    $("#liveToast").removeClass("hide");    
                    $(".toast").toast("show");             

                    if (response.success) {
                        $("#createRefferences")[0].reset();
                        $('#cards').html(response.partialView);
                        toggleForm();
                    }
                },
                error: function () {
                    console.error("Error adding. Please try again.");
                }

            });
        }
    });
});