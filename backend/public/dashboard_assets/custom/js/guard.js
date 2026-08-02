// // open action modal
$(document).on("click","#takeAction",function () {
    console.log('oh')
    $("#actionModal").modal()
})

function setActionValue(value) {
    $("#formAction").append("<input type='hidden' name='status_action' class='form-control' value='"+value+"'/>")
    $( "#target" ).submit();
}

// matrials
$(document).on("click","#guardtakeAction",function () {
    $("#matrialactionModal").modal()
})

// cars
$(document).on("click","#guardtakeCarAction",function () {
    $("#carActionModal").modal()
})


// cars
$(document).on("click","#contractTakeAction",function () {
    $("#contractActionModal").modal()
})
