<style>
    /* make a trip popup start */

    .popup_login {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        right: 0;
        z-index: 999;
        background-color: #000d;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .popup_login #make_a_trip_popup {
        z-index: 999999;
        display: flex;
        flex-direction: column;
        width: 500px;
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
        transition: all 0.5s;
        visibility: visible;
        opacity: 1;
        overflow: hidden;
    }

    .popup_login.hide {
        /* transition: all 0.5s;
        visibility: hidden;
        opacity: 0;
        z-index: -999; */
        display: none;
    }

    .popup_login #make_a_trip_popup h5 {
        margin-bottom: 20px;
        font-weight: bold;
        /* width: 60%; */
    }

    .popup_login #make_a_trip_popup .tabs {
        flex-wrap: nowrap;
    }

    .popup_login #make_a_trip_popup .main_tab,
    .popup_login #make_a_trip_popup .tab {
        display: flex;
        flex-direction: row;
        justify-content: space-evenly;
    }

    .popup_login #make_a_trip_popup .main_tab > .left-side > p.description {
        width: 75%;
        margin: 20px auto;
    }

    .popup_login #make_a_trip_popup .main_tab > .left-side > button.make_a_trip_button {
        border-radius: 50px;
        padding: 10px 20px;
        font-weight: bold;
        letter-spacing: 1px;
        margin: 5px 0 0;
        width: max-content;
        align-self: center;
        color: #84548E;
        border-color: #84548E;
    }

    .popup_login .tabs .left-side {
        padding-bottom: 25px;
        width: 60%;
        display: flex;
        flex-direction: column;
        margin: auto;
    }

    .popup_login .tabs .right-side {
        width: 40%;
        text-align: start;
    }

    .popup_login .tabs .right-side img {
        width: 85%;
        object-fit: cover;
        height: 350px;
        min-height: 90%;
        border-radius: 50px;
    }

    .popup_login #make_a_trip_popup .main_tab > .left-side > button.make_a_trip_button:hover:not([disabled]),
    .popup_login #make_a_trip_popup .tab button.make_a_trip_button:hover:not([disabled]),
    .popup_login .tab > .left-side > button:hover:not([disabled]) {
        background-color: #84548E;
        color: #fff;
    }

    .popup_login #make_a_trip_popup .tab button.make_a_trip_button {
        border: solid 2px #000;
        font-weight: bold;
        width: 100px;
        border-radius: 50px;
        font-weight: bold;
    }

    .popup_login .tab {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .popup_login .tab.registerPage.hide {
        display: none !important;
    }

    .popup_login .tab > .left-side > *:not(h6):not(.trip_categories):not(a):not(.navigation_buttons):not(label):not(.timetable_container),
    #make_a_trip_popup .tab .trip_categories > span {
        width: 70% !important;
        margin: 0 auto 20px;
        height: 40px !important;
        border-radius: 10px;
        padding: 5px 10px !important;
        border: 1px solid #84548E !important;
        cursor: pointer;
        background-color: hsl(231, 20%, 85%);
    }

    .popup_login .tab > .left-side > #as_a_guest {
        border: none !important;
        background-color: unset;
        color: #84548E;
    }

    .popup_login .tab > .left-side > #as_a_guest:hover {
        color: #8d839b;
        text-decoration: underline;
    }

    .popup_login .select2-container {
        z-index: 999999;
    }

    .popup_login .tab > .left-side > .select2.select2-container.select2-container--default.select2-container--below {
        height: fit-content !important;
    }

    /* .popup_login .tab > .left-side > input#date{
        background-color: #84548E;
        color: #fff;
    } */

    input::-webkit-calendar-picker-indicator {
        display: none;
    }

    ::-webkit-calendar-picker-indicator {
        color: #fff;
        background-color: #fff;
        border-radius: 5px
    }

    ::-webkit-inner-spin-button {
        cursor: pointer;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        width: 30px;
    }

    .popup_login .tab > .left-side > label::after {
        content: '*';
        color: red;
        font-size: 20px;
        margin: 0 10px;
    }

    .popup_login .tab > .left-side > label.not_required::after {
        content: '' !important;
    }

    .popup_login .tab > .left-side > label {
        margin: 5px auto 0;
        width: 70%;
        text-align: start;
    }

    .popup_login .tab > .left-side > select#categories,
    .popup_login .tab > .left-side > select#key_words {
        max-height: 75px;
    }

    .popup_login .tab > .left-side > button#login,
    .popup_login .tab > .left-side > button#register {
        border-radius: 50px;
        font-weight: bold;
        margin-top: 10px;
        background-color: #84548E;
        color: #fff;
    }

    .popup_login .tab > .left-side > button#login:hover,
    .popup_login .tab > .left-side > button#register:hover {
        background-color: #8d839b;
        color: #84548E;
    }

    .popup_login .tab > .left-side > .navigation_buttons {
        width: 70%;
        margin: 10px auto 0;
        height: 40px;
        display: flex;
        justify-content: space-evenly;
    }

    #make_a_trip_popup .tab .trip_categories {
        padding: 0 10px;
    }

    #make_a_trip_popup .tab .trip_categories > span {
        width: fit-content;
        display: inline-block;
        margin: 10px;
    }

    #make_a_trip_popup .tab .trip_categories > span.active {
        border-color: #84548E;
        background-color: #84548E;
        color: #fff;
        transition: all 0.2s;
    }

    .popup_login .tab > .left-side > input {
        cursor: auto !important;
    }

    .popup_login .tab > .left-side > input#date {
        cursor: pointer !important;
    }

    /* select2 lib start */
    .select2-container--default .select2-selection--single,
    .select2-selection {
        background-color: #fff !important;
        border: none !important;
    }

    .select2-search--inline {
        display: inline;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field,
    .select2-dropdown.select2-dropdown--above {
        border: 1px solid #84548E;
        border-radius: 10px;
    }

    .select2-container--open .select2-dropdown--below,
    .select2-dropdown.select2-dropdown--above {
        z-index: 9999999;
        background-color: #d1d3e0;
        border-radius: 10px;
        border: solid 1px #84548E;
    }

    .select2-container--default[dir="rtl"] .select2-selection--single .select2-selection__arrow {
        left: 15px;
        right: auto;
        top: 0px;
    }

    .popup_login .tab > .left-side > .select2 {
        border: none !important;
        padding: 0 !important;
        height: fit-content !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        text-align: start;
        background-color: hsl(231, 20%, 85%);
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        padding-right: 5px;
        background-color: #84548E;
        color: #fff;
        border: none;
        margin-top: 2px;
    }

    .select2-results__option {
        cursor: pointer;
    }

    .select2-results__option:hover {
        background-color: #e5e5e5;
    }

    .select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
    }

    .select2-container--default[dir="rtl"] .select2-selection--multiple .select2-selection__choice__remove:hover {
        background: transparent;
    }

    /* select2 lib end */
    /* loader start */
    .popup_login #make_a_trip_popup .loader_infinity {
        position: absolute;
        left: 42%;
        top: 39%;
        width: 150px;
        transition: all 0.2s;
    }

    .popup_login #make_a_trip_popup .loader_infinity.hide {
        display: none;
    }

    /* loader end */
    /* timetable start */
    @import url('https://fonts.googleapis.com/css?family=Ubuntu:500&display=swap');

    .timetable_container {
        position: absolute;
        z-index: 9999999;
        left: 200px;
    }

    .timetable_container.hide {
        display: none;
    }

    .timetable_container .calendar {
        box-shadow: 0 0 10px #878787;
        padding: 1em;
        border-radius: 10px;
        display: grid;
        place-items: center;
        grid-template-columns: repeat(7, 1fr);
        grid-auto-rows: max-content;
        grid-auto-flow: row;
        color: black;
        background-color: hsl(231, 20%, 85%);
    }

    .timetable_container .cell {
        padding: 0.4em 0.8em;
        text-align: center;

        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .timetable_container .cell:not(.cell--unselectable) {
        cursor: pointer;
    }

    .timetable_container .cell:hover,
    .cell:focus {
        color: hsl(231, 20%, 85%);
        background-color: #84548E;
        border-radius: 10px;
    }

    .timetable_container .cell:empty {
        width: 0;
        padding: 0;
    }

    .timetable_container .cell--unselectable {
        color: hsl(231, 20%, 50%);
    }

    .timetable_container .cell--unselectable:hover,
    .cell--unselectable:focus {
        color: hsl(231, 20%, 50%);
        background-color: transparent;
    }

    .timetable_container .date-text {
        padding: 1em 0.8em;
        grid-column: 1 / 5;
        justify-self: start;
        display: flex;
        align-items: center;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .timetable_container .button {
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* OTHER */

    .timetable_container {
        height: 200px;
        width: fit-content;
        font-family: 'Ubuntu', sans-serif;
    }

    /* timetable end */

    @media only screen and (max-width: 830px) {
        .popup_login #make_a_trip_popup {
            width: 90%;
            margin-top: 150px;
            margin-left: 5%;
        }
    }

    /* make a trip popup end */
</style>

<div class="popup_login hide">
    <div id="make_a_trip_popup" class="container" style="z-index: 999999;">
        <div id="home_login_and_register_form">
            <form class="login_page" action="#">
                @csrf
                <h5 class="text-center font-weight-bold">{{ __('dashboard.login') }}</h5>
                <!-- <h6>{{ __('dashboard.login') }}</h6> -->
                <div class="text-start mb-4">
                    <label>{{ __('dashboard.email') }}</label>
                    <input type="email" name="email" class="form-control" required>
                    <p id="u_email" style="display: none;color: red">{{ __("The data for this field is incorrect") }}</p>
                </div>
                <div class="text-start mb-4">
                    <label>{{ __('dashboard.password') }}</label>
                    <input type="password" name="password" class="form-control" required>
                    <p id="u_password" style="display: none;color: red">{{ __("Please enter the data for this field") }}</p>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{url('password/reset')}}"
                       class="text-primary font-size-h6 font-weight-bolder text-hover-primary"
                       id="kt_login_forgot">{{__("Forgot Password ?")}}</a>
                </div>
                <button type="button" id="login">{{ __('dashboard.login') }}</button>
                <a class="btn btn-outline-primary" href="{{url("google/redirect")}}" role="button"
                   style="text-transform:none">
                    <img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in"
                         src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png"/>
                    {{__("Login With Google")}}
                </a>
                <a class="btn btn-outline-primary" href="{{url("twitter/redirect")}}" role="button"
                   style="text-transform:none">
                    <img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in"
                         src="https://upload.wikimedia.org/wikipedia/commons/6/6f/Logo_of_Twitter.svg"/>
                    {{__("Login With Twitter")}}
                </a>
                <a href="#" onclick="toggleLoginAndRegisterPages()">{{ __('dashboard.register') }}</a>
            </form>

            <form class="register_page hide">
                @csrf
                <h5 class="text-center font-weight-bold">{{ __('dashboard.register') }}</h5>

                <div class="text-start mb-2">
                    <label>{{ __('dashboard.first_name') }}</label>
                    <input type="text" name="first_name" placeholder="" class="form-control" required>
                    <p id="u_first_name" style="display: none;color: red">{{ __("The data for this field is incorrect") }}</p>
                </div>


                <div class="text-start mb-2">
                    <label>{{ __('dashboard.last_name') }}</label>
                    <input type="text" name="last_name" placeholder="" class="form-control" required>
                    <p id="u_last_name" style="display: none;color: red">{{ __("The data for this field is incorrect") }}</p>
                </div>

                <div class="text-start mb-2">
                    <label>{{ __('dashboard.email') }}</label>
                    <input type="email" name="register_email" placeholder="" class="form-control" required>
                    <p id="u_register_email" style="display: none;color: red">{{ __("The data for this field is incorrect") }}</p>
                </div>

                <div class="text-start mb-2">
                    <label>{{ __('dashboard.password') }}</label>
                    <input type="password" name="register_password" placeholder="" class="form-control" required>
                    <p id="u_password" style="display: none;color: red">{{ __("The data for this field is incorrect") }}</p>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{url('password/reset')}}"
                       class="text-primary font-size-h6 font-weight-bolder text-hover-primary"
                       id="kt_login_forgot">{{__("Forgot Password ?")}}</a>
                </div>
                <button type="button" id="register">{{ __('dashboard.register') }}</button>
                <a href="#" onclick="toggleLoginAndRegisterPages()">{{ __('dashboard.login') }}</a>
                <a class="btn btn-outline-primary" href="{{url("google/redirect")}}" role="button"
                   style="text-transform:none">
                    <img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in"
                         src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png"/>
                    {{__("Register With Google")}}
                </a>
                <a class="btn btn-outline-primary" href="{{url("twitter/redirect")}}" role="button"
                   style="text-transform:none">
                    <img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in"
                         src="https://upload.wikimedia.org/wikipedia/commons/6/6f/Logo_of_Twitter.svg"/>
                    {{__("Register With Twitter")}}
                </a>
            </form>
            <img class="loader_infinity hide" src="{{ asset('front_assets/imgs/popup_images/Infinity_loader.gif') }}"
                 alt="fabulous trip image">
        </div>
    </div>
</div>
