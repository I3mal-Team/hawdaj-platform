@if ($paginator->hasPages())
    <style>
        .page-item.active .page-link {
            background-color: #2c085 !important;
            border-color: #2c085 !important;
        }
    </style>
    <div class="card-tools">
        <ul class="pagination pagination-sm mt-3 ml-3 justify-content-center">

            @if ($paginator->onFirstPage())
                <li class="disabled page-link"><span>«</span></li>
            @else
                <li class="page-item"><a style="background-color: white" class="page-link" href="{{ $paginator->previousPageUrl() }}"
                        rel="prev">«</a></li>
            @endif



            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="disabled page-item"><span class="page-link">{{ $element }}</span></li>
                @endif



                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active my-active " ><span
                                    class="page-link" style="background-color: #84548E">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link"
                                    href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach



            @if ($paginator->hasMorePages())
                <li class="page-item"><a style="background-color: white" class="page-link" href="{{ $paginator->nextPageUrl() }}"
                        rel="next">»</a></li>
            @else
                <li class="disabled page-link"><span>»</span></li>
            @endif
        </ul>
    </div>
@endif
