@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Sales List" sub-title="Manage Your Sales" button="Add Sale"
                button-route="admin.sales.create" />

            <!-- /filter -->
                <div class="card table-list-card">
                <x-filter />

                    <!-- /Filter -->
                    <div class="table-responsive" id="dataTable">
                        <x-sales.table :sales="$sales" />
                    </div>
                        {{-- paid status modal --}}
                        <div class="modal fade" id="payment_modal">
                            <div class="modal-dialog modal-dialog-centered custom-modal-two">
                                <div class="modal-content" style="width: auto; padding-bottom: 50px;">
                                    <div class="page-wrapper-new p-0">
                                        <div class="content">
                                            <div class="modal-header border-0 custom-modal-header justify-content-between">
                                                <div class="page-title">
                                                    <h4>Purchase Payments</h4>
                                                </div>
                                                <button type="button" class="close" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body custom-modal-body new-employee-field"
                                                id="view_payments">
                                                {{-- dynamically show payments --}}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            <!-- /purchase list -->
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.payment_view').click(function(e) {
                let url = $(this).data('url');
                let spiner =
                    `<div class="d-flex justify-content-center">   <div class="spinner-border" role="status">     <span class="visually-hidden">Loading...</span>   </div> </div>`
                $('#view_payments').html(spiner);
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(res) {
                        $('#view_payments').html(res);
                    }
                })
            }) 
        });

        function printInvoice(button, id) {
            setTimeout(() => {
                let printContent = document.getElementById('print-invoice-template-' + id);
                if (!printContent) {
                    alert("Print template not found!");
                    return;
                }

                let originalContent = document.body.innerHTML;
                document.body.innerHTML = printContent.innerHTML;

                window.print();

                document.body.innerHTML = originalContent;
                location.reload();
            }, 300);
        }
    </script>
@endpush
