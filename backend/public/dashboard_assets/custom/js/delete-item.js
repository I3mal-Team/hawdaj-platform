// Delete item.
var delete_route;
var item_id;
var reload = false;

$(document).on("click", ".delete-button", function () {
    delete_route = $(this).data("url");
    item_id = $(this).data("item-id");
    reload = $(this).data("reload");
});

$(document).on("click", ".restore-button", function () {
    restore_route = $(this).data("url");
    item_id = $(this).data("item-id");
    reload = $(this).data("reload");
});


$(document).on("click", "#delete-button", function () {
    $.ajax({
        url: delete_route,
        type: "POST",
        data: {
            _method: "delete",
            _token: $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            $("#delete_modal").modal("toggle");
            $(`#row-${item_id}`).remove();
            toastr.success(response.message);

            if (reload == true) {
                window.location.reload();
            }
        },
        error(data) {
            $("#delete_modal").modal("toggle");
            toastr.error(data.responseJSON.message);
        },
    });
});

$(document).on("click", "#restore-button", function () {
    
    $.ajax({
        url: restore_route,
        type: "GET",
        success: function (response) {
            $("#restore_modal").modal("toggle");
            $(`#row-${item_id}`).remove();
            toastr.success(response.message);

            if (reload == true) {
                window.location.reload();
            }
        },
        error(data) {
            $("#restore_modal").modal("toggle");
            toastr.error(data.responseJSON.message);
        },
    });
});