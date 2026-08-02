/* show popup when visiting website */
$(document).ready(function(){
    makeATripPopupShow()
})

// *******************************
// *******************************
/* popup back and next buttons */
function makeATripNextTab(tabNum){
    var val = -(tabNum * 100);
    if(button_direction == 'next' && popup_input_values == false){
        alert("{{__('dashboard.fill_required_fields')}}")
    }else{
        $('.popup_that_shows_on_startup #make_a_trip_popup .tabs').css("transition", "all 0.3s")
        $('.popup_that_shows_on_startup #make_a_trip_popup .tabs').css("opacity","0")
        $('.popup_that_shows_on_startup #make_a_trip_popup .loader_infinity').removeClass('hide')
        setTimeout(() => {
            $('.popup_that_shows_on_startup #make_a_trip_popup .tabs').css("transform", `translateX(${val}%)`)
        }, 300);
        setTimeout(() => {
            $('.popup_that_shows_on_startup #make_a_trip_popup .loader_infinity').addClass('hide')
            $('.popup_that_shows_on_startup #make_a_trip_popup .tabs').css("opacity","1")
        }, 600);
        // the following part is to control length of pages in popup
        if(tabNum == 4){
            $('popup_that_shows_on_startup #make_a_trip_popup .tab.registerPage').addClass('hide')
        }else if(tabNum == 5){
            $('popup_that_shows_on_startup #make_a_trip_popup .tab.registerPage').removeClass('hide')
        }
    }
}
// *******************************
// *******************************
/* show hide popup */
function makeATripPopupShow(){
    setTimeout(() => {
        $('.popup_that_shows_on_startup').removeClass('hide')
    }, 200);
    $('.popup_that_shows_on_startup').click(function(e){
        var $target = $(e.target);
        if(!$target.closest('#make_a_trip_popup').length){
            $('.popup_that_shows_on_startup').addClass('hide')
        }
        // alert('hi')
    })
}

// *******************************
// *******************************
/* only allow next when all required inputs are full */
var popup_input_values = false
$(".popup_that_shows_on_startup #make_a_trip_popup input[required], .popup_that_shows_on_startup #make_a_trip_popup select[required]").on("input", function() {
    // alert('changed')
    if ($(this).val()){
        $(this).siblings('input[required], select[required]').each(function(index){
            if ($(this).val()){
                // alert($(this).val())
                popup_input_values = true;
            }
            else{
                popup_input_values = false;
                // $(this).siblings('div.navigation_buttons').children('button.next').prop('disabled', true);
                // $(this).siblings('#login, #register').prop('disabled', true);
                // alert(popup_input_values); 
                return;
            }
        })
    }
    else{
        // $(this).siblings('div.navigation_buttons').children('button.next').prop('disabled', true);
        // $(this).siblings('#login, #register').prop('disabled', true);
        popup_input_values = false;
        // alert(popup_input_values); 
    }
    if(popup_input_values){
        popup_input_values = true;
        // $(this).siblings('div.navigation_buttons').children('button.next').prop('disabled', false);
        // $(this).siblings('#login, #register').prop('disabled', false);
    }
    // console.log(popup_input_values)
});

// *******************************
// *******************************
/* eliminating guest required bug */
$('.popup_that_shows_on_startup #make_a_trip_popup button#as_a_guest').click(function(){
    // alert('clicked')
    $('.popup_that_shows_on_startup #make_a_trip_popup input[required]').prop('required', false);
})

// *******************************
// *******************************