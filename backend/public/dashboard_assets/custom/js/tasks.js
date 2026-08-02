$(document).on("click","#tasksModalActive",function(e){
    var taskId = $(this).data('id');
    e.preventDefault();
    //ajax
    var url = "dashboard/task";
    var getUrl = url+"/"+ taskId;
    $.ajax({
        url: getUrl,
        type: 'GET',
        data: taskId,
        //before success
        beforeSend: function() {
            $("#taskModal").html('')
        },
        success: function(data) {
            $("#taskModal").append(data)
            $("#taskModal").modal();
        },
    });
})
$(document).on("click","#contractorsModalActive",function(e){
    var taskId = $(this).data('id');
    e.preventDefault();
    //ajax
    var url = "dashboard/contractor-task";
    var getUrl = url+"/"+ taskId;
    $.ajax({
        url: getUrl,
        type: 'GET',
        data: taskId,
        //before success
        beforeSend: function() {
            $("#contractorTaskModal").html('')
        },
        success: function(data) {
            $("#contractorTaskModal").append(data)
            $("#contractorTaskModal").modal();
        },
    });
})
$(document).on("click","#MaterialsModalActive",function(e){
    var MaterialId = $(this).data('id');
    e.preventDefault();
    //ajax
    var url = "dashboard/material-task";
    var getUrl = url+"/"+ MaterialId;
    $.ajax({
        url: getUrl,
        type: 'GET',
        data: MaterialId,
        //before success
        beforeSend: function() {
            $("#materialTaskModal").html('')
        },
        success: function(data) {
            $("#materialTaskModal").append(data)
            $("#materialTaskModal").modal();
        },
    });
})
$(document).on("click","#CarsModalActive",function(e){
    var CarId = $(this).data('id');
    e.preventDefault();
    //ajax
    var url = "dashboard/car-task";
    var getUrl = url+"/"+ CarId;
    $.ajax({
        url: getUrl,
        type: 'GET',
        data: CarId,
        //before success
        beforeSend: function() {
            $("#carTaskModal").html('')
        },
        success: function(data) {
            $("#carTaskModal").append(data)
            $("#carTaskModal").modal();
        },
    });
})
