@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Services List" button="Add Service" button-route="admin.services.create" />

            <!-- /filter -->
                <div class="card table-list-card">
                <x-filter>
                    <div class="col-lg-4 col-sm-3 col-12 ms-2" style="width: 200px;">
                        <div class="mb-3 add-product">
                            <div class="add-newplus">
                                <label class="form-label">Service Type</label>
                            </div>
                            <select class="select filter-input" name="serviceType">
                                <option value="">Choose</option>
                                <option value="self" @selected(request()->serviceType == 'self')>Self</option>
                                <option value="external" @selected(request()->serviceType == 'external')>External</option>
                            </select>
                        </div>
                    </div>
                </x-filter>
                    <!-- /Filter -->

                    <div class="table-responsive" id="dataTable">
                        <x-services.table :services="$services" />
                    </div>
                        {{-- paid status modal --}}
                        <div class="modal fade" id="payment_modal">
                            <div class="modal-dialog modal-dialog-centered custom-modal-two">
                                <div class="modal-content" style="width: auto; padding-bottom: 50px;">
                                    <div class="page-wrapper-new p-0">
                                        <div class="content">
                                            <div class="modal-header border-0 custom-modal-header justify-content-between">
                                                <div class="page-title">
                                                    <h4>Service Payments</h4>
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
            <!-- /service list -->
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