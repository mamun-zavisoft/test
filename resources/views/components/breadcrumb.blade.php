<div class="page-header">
    <div class="add-item d-flex">
        <div class="page-title">
            <h4>{{ $title ?? '' }}</h4>
            <h6>{{ $subTitle ?? '' }}</h6>
        </div>
    </div>
    @isset($actionButtons)
        @if ($actionButtons)
            <ul class="table-top-head">
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img
                            src="{{ URL::asset('/build/img/icons/pdf.svg') }}" alt="img"></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img
                            src="{{ URL::asset('/build/img/icons/excel.svg') }}" alt="img"></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><i data-feather="printer"
                            class="feather-rotate-ccw"></i></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i data-feather="rotate-ccw"
                            class="feather-rotate-ccw"></i></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                            data-feather="chevron-up" class="feather-chevron-up"></i></a>
                </li>
            </ul>
        @endif
    @endisset

    @if (isset($button) && isset($buttonRoute))
        @if (isset($permission) && !empty($permission))
            @permission($permission)
                <div class="page-btn">
                    <a href="{{ route($buttonRoute) }}" class="btn btn-added"><i data-feather="plus-circle"
                            class="me-2"></i>
                        {{ $button }}</a>
                </div>
            @endpermission
        @else
            <div class="page-btn">
                <a href="{{ route($buttonRoute) }}" class="btn btn-added"><i data-feather="plus-circle"
                        class="me-2"></i>
                    {{ $button }}</a>
            </div>
        @endif
    @endif

    @if (isset($button) && isset($backButtonRoute))
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{ route($backButtonRoute) }}" class="btn btn-secondary"><i data-feather="arrow-left"
                            class="me-2"></i>
                        {{ $button }}</a>
                </div>
            </li>
        </ul>
    @endif
</div>
