$(document).ready(function() {

    // remove validation error message on input
    $('input[type="text"], textarea, select').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').hide();
    });

    // Handle Select2 change events
    $('.select2').on('change', function () {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').hide();
        $(this).parents(".form-group").find('.invalid-feedback').remove();
    });

    // URL validation
    $('input[type="url"]').on("input", function() {
        console.log("ddfb")
        errorMessage = $("<div>").addClass("invalid-feedback");
        $(this).parent().after(errorMessage);

        // var parentElement = $(this).parent();
        // var errorMessage = $("<div class='invalid-feedback'>").text("");
        // parentElement.after(errorMessage);

        errorMessage.text("");

        var url = $(this).val();
        if (url.length == 0) {
            errorMessage.text("");
        }
        var urlPattern = /^(http|https)?:\/\/[a-zA-Z0-9-\.]+\.[a-z]{2,4}/;
        if (url == "" || urlPattern.test(url)) {
            errorMessage.text("");
        } else {
            errorMessage.text("Invalid URL");
        }
    });

    // WhatsApp number validation
    $("#whatsappInput").on("input", function() {
        $("#whatsappValidationResult").text("");
        var whatsappInput = $(this).val();
        if (whatsappInput.length == 0) {
            $("#whatsappValidationResult").text("");
        }
        var whatsappPattern = /^\+\d{1,3}\d{6,14}(?:x\d+)?$/;
        if (whatsappInput == "" || whatsappPattern.test(whatsappInput)) {
            $("#whatsappValidationResult").text("");
        } else {
            $("#whatsappValidationResult").text("Invalid Whatsapp Number");
        }
    });

    // Remove the validation text when leaving the input fields
    $("#urlInput").on("blur", function() {
        $("#urlValidationResult").remove(); // Remove the URL validation text when input field loses focus
    });

    // Remove the validation text when leaving the input fields
    $("#whatsappInput").on("blur", function() {
        $("#whatsappValidationResult").remove(); // Remove the WhatsApp validation text when input field loses focus
    });
});
