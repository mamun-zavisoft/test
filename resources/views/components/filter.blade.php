<form action="" method="get">
    <div class="table-top">
        {{-- custom filter --}}
        <div class="d-flex flex-wrap">
            {{ $slot }}
        </div>

        {{-- default filter --}}
        <div class="d-flex mt-3 mt-md-0">
            <div class="search-set ">
                <div class="search-input ms-3">
                    <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search"
                            class="feather-search"></i></a>
                    <input type="search" name="search" class="form-control form-control-sm" placeholder="Search" value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-secondary rounded-pill custom-submit-btn">filter</button>
            </div>
        </div>
    </div>
</form>
