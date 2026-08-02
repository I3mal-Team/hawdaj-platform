$(document).on("click","#activitiesModalActive",function(e){
    var requestId = $(this).data('id');
    e.preventDefault();
    //ajax
    var url = "dashboard/material-info";
    var getUrl = url+"/"+ requestId;
    $.ajax({
        url: getUrl,
        type: 'GET',
        data: requestId,
        //before success
        beforeSend: function() {
            $("#activitiesModal").html('')
        },
        success: function(data) {
            $("#activitiesModal").append(data)
            $("#activitiesModal").modal();
        },
    });
})
