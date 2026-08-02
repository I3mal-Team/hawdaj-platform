$(document).ready(function () {
    $('.select2').select2({});

    $('.nice-select').select2({
        minimumResultsForSearch: -1
    });

    var d = Date(Date.now());
    a = d.toString()
    document.getElementsByClassName("date").min = a;

    $('.add_loading').on('click', function (e) {

        $(this)
            .addClass('spinner spinner-white spinner-right')
            .text(lang[LANG].please_wait)
            .prop('disabled', true);

        $(".form").submit();
    });
});

function logout() {
    $("#LogoutForm").submit();
}

$('.file').on('change', function () { //on file input change
    if (window.File && window.FileReader && window.FileList && window.Blob) {
        $(this).parent().parent().siblings('.image').find('.thumb-output').html('');
        var data = $(this)[0].files;
        var self = $(this);
        $.each(data, function (index, file) {
            if (/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)) {
                var fRead = new FileReader();
                fRead.onload = (function (file) {
                    return function (e) {
                        var img = $('<img/>').addClass('thumb').attr('src', e.target.result);
                        // console.log(  $(this).parent().parent());
                        self.parent().parent().siblings('.image').children('.thumb-output').append(img);
                    };
                })(file);
                fRead.readAsDataURL(file);
            }
        });
    } else {
        alert("Your browser doesn't support File API!");
    }
});

$('.add_loading').on('click', function (e) {
    if ($(this).hasClass('disabled')) {
        return;
    }
    let form = $(this).closest('form');

    $(this)
        .addClass('spinner spinner-white spinner-right')
        .text(langs[LANG].please_wait)
        .prop('disabled', true);

    form.submit();
});

$(document).ready(function() {

    // remove validation error message on input
    $('input[type="text"], input[type="file"], textarea, select').on('input change', function() {
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

        var errorMessage = $(this).next(".error-message");
        if (!errorMessage.length) {
            errorMessage = $("<div>").addClass("invalid-feedback").addClass("error-message");
            $(this).after(errorMessage);
        }
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
    $(".validate-phone").on("input", function() {
        var phoneErrorMessage = $(this).parent().next(".error-message");
        if (!phoneErrorMessage.length) {
            phoneErrorMessage = $("<div>").addClass("invalid-feedback").addClass("error-message");
            $(this).parent().after(phoneErrorMessage);
        }
        phoneErrorMessage.text("");

        var whatsappInput = $(this).val();
        if (whatsappInput.length == 0) {
            phoneErrorMessage.text("");
        }
        var whatsappPattern = /^(\+\d{1,3})?\d{6,14}(?:x\d+)?$/;
        if (whatsappInput == "" || whatsappPattern.test(whatsappInput)) {
            phoneErrorMessage.text("");
        } else {
            phoneErrorMessage.text("Invalid Whatsapp Number");
        }
    });
});
