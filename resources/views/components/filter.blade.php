<form action="{{ request()->url() }}" method="get" id="filter-form">
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
                    <input type="search" name="search" class="form-control form-control-sm" placeholder="Search" value="{{ request('search') }}" autocomplete="off">
                </div>
                <button type="submit" id="reset-filter" class="btn btn-secondary rounded-pill custom-submit-btn">Reset</button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    $(document).ready(function() {
        let form = $('#filter-form');
        let formAction = form.attr('action');

        $('input[name="search"]').on('input', function() {
            sendAjaxRequest();
        });

        $(document).on('change', '.filter-input', function() {
            sendAjaxRequest();
        });

        $('#reset-filter').on('click', function(e) {
            e.preventDefault();
            $('input[name="search"]').val('');
              $('.filter-input').val('');
            $('.filter-input').trigger('change');
            sendAjaxRequest();
        });

        function sendAjaxRequest(url = formAction) {
            $.ajax({
                url: url,
                type: 'GET',
                data: form.serialize(),
                success: function(res) {
                    let response = $(res);
                    $('#dataTable').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed.", error);
                }
            });
        }

        $(document).on('click', '#pagination a', function(e) {
            e.preventDefault();
            let pageUrl = $(this).attr('href');
            sendAjaxRequest(pageUrl);
        });
    });
</script>
@endpush

