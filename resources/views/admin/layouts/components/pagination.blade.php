<div class="col-sm-12 col-md-7">
    <div class="al-table-info" id="customerList-table_info" role="status" aria-live="polite">
        Hiển thị từ {{ $paginator->firstItem() }} đến {{ $paginator->lastItem() }} trong tổng số {{ $paginator->total() }} mục
    </div>
</div>
<div class="col-sm-12 col-md-5">
    <div class="al-table-paginate paging_simple_numbers pagination-rounded" id="customerList-table_paginate">
        <ul class="pagination">
            @if ($paginator->onFirstPage())
            <li class="paginate_button page-item previous disabled">
                <a aria-disabled="true" class="page-link">
                    <i class="mdi mdi-chevron-left"></i>
                </a>
            </li>
            @else
            <li class="paginate_button page-item previous">
                <a href="{{ $paginator->previousPageUrl() }}" class="page-link">
                    <i class="mdi mdi-chevron-left"></i>
                </a>
            </li>
            @endif

            @foreach ($elements as $element)
            @if (is_string($element))
            <li class="paginate_button page-item disabled">
                <a class="page-link">{{ $element }}</a>
            </li>
            @endif

            @if (is_array($element))
            @foreach ($element as $page => $url)
            @if ($page == $paginator->currentPage())
            <li class="paginate_button page-item active">
                <a class="page-link">{{ $page }}</a>
            </li>
            @else
            <li class="paginate_button page-item">
                <a href="{{ $url }}" class="page-link">{{ $page }}</a>
            </li>
            @endif
            @endforeach
            @endif
            @endforeach

            @if ($paginator->hasMorePages())
            <li class="paginate_button page-item next">
                <a href="{{ $paginator->nextPageUrl() }}" class="page-link">
                    <i class="mdi mdi-chevron-right"></i>
                </a>
            </li>
            @else
            <li class="paginate_button page-item next disabled">
                <a class="page-link">
                    <i class="mdi mdi-chevron-right"></i>
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>
