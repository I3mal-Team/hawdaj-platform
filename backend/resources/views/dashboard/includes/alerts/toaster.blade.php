@push('js')
    <script>
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "30000",
            "hideDuration": "100000",
            "timeOut": "400000",
            "extendedTimeOut": "10000",
            "showEasing": "swing",
            "hideEasing": "swing",
            "showMethod": "slideDown",
            "hideMethod": "slideUp"
        };

        toastr.{{$alert_status}}("{{$message}}");
    </script>
@endpush


