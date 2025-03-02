@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Add Purchase" button="Back to Purchase" back-button-route="admin.purchases.index" />

            <!-- /add -->
            <form id="storeForm" action="{{ route('admin.purchases.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="page-wrapper-new p-0">
                    <div class="content shadow">
                        <div class="modal-body custom-modal-body">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="input-blocks add-product">
                                        <label>Supplier Name</label>
                                        <div class="row">
                                            <div class="col-lg-10 col-sm-10 col-10">
                                                <select class="select" name="supplier_id">
                                                    <option value="">Select</option>
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}"> {{ $supplier->name }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-2 col-sm-2 col-2 ps-0">
                                                <div class="add-icon tab">
                                                    <a href="javascript:void(0);"><i data-feather="plus-circle"
                                                            class="feather-plus-circles"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="input-blocks">
                                        <label>Purchase Date</label>

                                        <div class="input-groupicon calender-input">
                                            <i data-feather="calendar" class="info-img"></i>
                                            <input type="text" class="datetimepicker" placeholder="Choose"
                                                name="date">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="input-blocks">
                                        <label>Reference No</label>
                                        <input type="text" class="form-control" name="reference_no">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-blocks search-item">
                                        <label>Product Name</label>
                                        <input id="product_search" type="text"
                                            placeholder="Please type product code and select">
                                        <div id="myOptions" class="options-list text-center pt-2">
                                            {{-- searched product will appear here --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="modal-body-table">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Product Name</th>
                                                        <th>QTY</th>
                                                        <th>Purchase Price(৳) </th>
                                                        <th>Sale Price(৳) </th>
                                                        <th>Total Cost (৳) </th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="cart-list-items">
                                                    {{-- cart items will appear here --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="input-blocks">
                                            <label>Discount</label>
                                            <input type="text" value="0" name="discount_amount">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="input-blocks">
                                            <label>Shipping</label>
                                            <input type="text" value="0" name="shipping_charge">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="input-blocks">
                                            <label>Status</label>
                                            <select class="select" name="status">
                                                <option value="">Choose</option>
                                                <option value="received">Received</option>
                                                <option value="pending">Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_type" class="form-label">Payment Type</label>
                                        <select class="select" id="payment_type" name="payment_type" required>
                                            <option value="">Select Payment Type
                                            </option>
                                            <option value="partial_paid">Partial Paid
                                            </option>
                                            <option value="full_paid">Full Paid
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_type" class="form-label">Accounts</label>
                                        <select class="select" id="payment_account" name="account_id" required>
                                            <option value="">Select Account
                                            </option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">
                                                    {{ $account->name }}({{ number_format($account->balance) }} ৳)</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3 amount-field" style="display: none;">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="number" class="form-control" id="amount" name="amount"
                                            placeholder="Enter amount">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="payment_date" class="form-label">Payment Date</label>
                                        <input type="date" class="form-control" id="payment_date" name="payment_date"
                                            value="{{ date('Y-m-d') }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="note" class="form-label">Note</label>
                                        <input type="text" class="form-control" id="note" name="note"
                                            placeholder="Payment note (optional)">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 ms-auto">
                                        <div class="total-order w-100 max-widthauto m-auto mb-4">
                                            <ul>
                                                <li>
                                                    <h4>Grand Total</h4>
                                                    <h5 id="grand_total">0</h5>
                                                    <input type="hidden" name="grand_total">
                                                </li>
                                                <li>
                                                    <h4>Paid</h4>
                                                    <h5 id="paid_amount">0</h5>
                                                    <input type="hidden" name="paid_amount">
                                                </li>
                                                <li>
                                                    <h4>Due</h4>
                                                    <h5 id="due_amount">0</h5>
                                                    <input type="hidden" name="due_amount">
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <div class="input-blocks summer-description-box">
                                        <label>Notes</label>
                                        <textarea id="summernote" name="note"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="modal-footer-btn">
                                        <button type="button" class="btn btn-cancel me-2"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-submit" id="submit_btn">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
            <!-- /add -->

        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function handleSearch() {
            let search = $(this).val();
            $('#myOptions').html('<div class="spinner-border" role="status">' +
                '<span class="visually-hidden">Loading...</span>' +
                '</div>');

            $.ajax({
                url: "{{ route('admin.products.search') }}",
                type: 'GET',
                data: {
                    search: search,
                },
                success: function(res) {
                    $('#myOptions').html(res);
                    $('#product_search').empty();
                    $('#myOptions').show(); // Show the dropdown
                }
            });
        }

        $('#product_search').on('click keyup', handleSearch);

        // Hide dropdown when clicking outside
        $(document).on('click', function(event) {
            if (!$(event.target).closest('.search-item').length) {
                $('#myOptions').hide();
            }
        });

        let charge_amount = 0;
        let discount_amount = 0;
        let delivery_charge = 0;
        let total_amount = subtotal_amount = 0;

        $(document).on('click', '.product_cart', function() {
            let id = $(this).data('id');
            let img = $(this).data('img');
            let title = $(this).data('title');
            let purchase_price = parseFloat($(this).data('purchase-price'));
            let sale_price = parseFloat($(this).data('sale-price'));

            if ($(`#cart-list-items .item${id}`).length > 0) {
                let quantityInput = $(`#qty${id}`);
                let currentQuantity = parseInt(quantityInput.val());
                // quantityInput.val(currentQuantity + 1);
            } else {
                let tr_product = `
                <tr class="item item${id}">
                    <input type="hidden" name="product_id[]" value="${id}">
                    <td>
                        <div class="productimgname">
                            <a href="javascript:void(0);" class="product-img stock-img">
                                <img src="${img}" alt="${title}" onerror="this.onerror=null; this.src='{{ asset('build/img/no-image.svg') }}';">
                            </a>
                            <a href="javascript:void(0);">${title}</a>
                        </div>
                    </td>
                    <td>
                        <div class="product-quantity">
                            <span class="quantity-btn" >+<i data-feather="plus-circle" class="plus-circle"></i></span>
                            <input type="text" id="qty${id}" name="qty[]" data-price="${purchase_price}" class="quntity-input" value="1" data-id="${id}" >
                            <span class="quantity-btn" ><i data-feather="minus-circle" class="feather-search"></i></span>
                        </div>
                    </td>
                    <td>${purchase_price}</td>
                    <td>${sale_price}</td>
                    <td>${purchase_price}</td>
                    <td>
                        <a class="delete-set cart-item-action" data-id="${id}"><img src="{{ URL::asset('/build/img/icons/delete.svg') }}" alt="delete"></a>
                    </td>
                </tr>`;

                $('#cart-list-items').prepend(tr_product);
            }
            subtotal_amount += purchase_price;
            // remove searched text from input field
            $('#product_search').val('');
            $('#myOptions').hide();

            updateCartAction()
        })

        $('#storeForm').submit(function(e) {
            e.preventDefault();
            let SubmitBtn = $('#submit_btn');
            SubmitBtn.prop('disabled', true);
            let formData = new FormData(this);
            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,

            }).done(function(response) {
                if (response.type == 'success') {
                    toastr.success(response.message);
                    setTimeout(() => {
                        response.redirectUrl ? window.location.href = response.redirectUrl :
                            `{{ route('admin.purchases.index') }}`;;
                    }, 1000);
                } else {
                    SubmitBtn.prop('disabled', false);
                    toastr.error(response.message);
                }
            }).fail(function(xhr) {
                SubmitBtn.prop('disabled', false);
                $('#submit_btn').attr('disabled', false);
                let response = xhr.responseJSON;
                if (response && response.errors) {
                    $.each(response.errors, function(key, value) {
                        toastr.error(value);
                    });
                }
                if (response && response.message) {
                    toastr.error(response.message);
                }
            });
        });

        $(document).ready(function() {
            updateCartAction();
        });

        $(document).on('input', '.quntity-input', function() {
            let id = $(this).data('id');
            let quantity = parseInt($(this).val());
            let purchasePrice = parseFloat($(this).data('price'));

            if (isNaN(quantity) || quantity < 1) {
                quantity = 1;
                $(this).val(quantity);
            }

            let totalCost = quantity * purchasePrice;
            $(this).closest('tr').find('td').eq(4).text(totalCost.toFixed(2));

            updateCartAction();
        });

        $(document).on('click', '.cart-item-action', function() {
            let id = $(this).data('id');
            $(`.item${id}`).remove();
            updateCartAction();
        });

        $(document).on('input', 'input[name="discount_amount"], input[name="shipping_charge"]', function() {
            updateCartAction();
        });

        function updateCartAction() {
            let subtotal = 0;
            $('#cart-list-items tr').each(function() {
                let quantity = parseInt($(this).find('.quntity-input').val());
                let purchasePrice = parseFloat($(this).find('.quntity-input').data('price'));
                subtotal += quantity * purchasePrice;
            });

            let discountAmount = parseFloat($('input[name="discount_amount"]').val()) || 0;
            let shippingCharge = parseFloat($('input[name="shipping_charge"]').val()) || 0;

            let grandTotal = (subtotal - discountAmount) + shippingCharge;

            if (grandTotal < 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Total amount cannot be negative!',
                });
                grandTotal = 0;
            }

            $('#grand_total').text(grandTotal.toFixed(2));
            $('input[name="grand_total"]').val(grandTotal.toFixed(2));

            // Calculate paid amount based on payment type
            let paidAmount = 0;
            let paymentType = $('#payment_type').val();

            if (paymentType === 'full_paid') {
                paidAmount = grandTotal;
            } else if (paymentType === 'partial_paid') {
                paidAmount = parseFloat($('#amount').val()) || 0;
            }

            // Update paid amount display and input
            $('#paid_amount').text(paidAmount.toFixed(2));
            $('input[name="paid_amount"]').val(paidAmount.toFixed(2));

            // Calculate and update due amount
            let dueAmount = grandTotal - paidAmount;

            $('#due_amount').text(dueAmount.toFixed(2));
            $('input[name="due_amount"]').val(dueAmount.toFixed(2));
        }

        // Add event listeners for payment fields
        $('#payment_type').on('change', function() {
            if ($(this).val() === 'partial_paid') {
                $('.amount-field').show();
                $('#amount').attr('required', true);
            } else if ($(this).val() === 'full_paid') {
                $('.amount-field').hide();
                $('#amount').attr('required', false);
                // Set amount to grand total for calculation purposes
                $('#amount').val($('#grand_total').text());
            } else {
                // For empty selection
                $('.amount-field').hide();
                $('#amount').attr('required', false);
                $('#amount').val(0);
            }
            updateCartAction(); // Update totals when payment type changes
        });

        $('#amount').on('input', function() {
            updateCartAction();
        });

        // Make sure to call updateCartAction when payment account changes
        $('#payment_account').on('change', function() {
            updateCartAction();
        });
    </script>
@endpush
