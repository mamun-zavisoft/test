@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Create Service" button="Back to Services" back-button-route="admin.services.index" />

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Vehicle Service Information</h4>
                </div>
                <div class="card-body">
                    <form id="serviceForm" action="{{ route('admin.services.store') }}" method="POST">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Service Type <span class="text-danger">*</span></label>
                                    <select name="service_type" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <option value="self">Self</option>
                                        <option value="external">External</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Vehicle <span class="text-danger">*</span></label>
                                    <select name="vehicle_id" class="form-control select2" required>
                                        <option value="">Select Vehicle</option>
                                        @foreach ($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}">{{ $vehicle->license_plate }} -
                                                {{ $vehicle->owner_type == '1' ? 'Self' : 'External' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Select Services <span class="text-danger">*</span></label>
                                    <select name="service_chart_ids[]" class="form-control select2" multiple required
                                        id="serviceChartSelect">
                                        @foreach ($serviceCharts as $chart)
                                            <option value="{{ $chart->id }}" data-price="{{ $chart->price }}">
                                                {{ $chart->name }} ({{ number_format($chart->price) }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="partsCheckbox"
                                        name="any_parts_purchase" value="1">
                                    <label class="form-check-label" for="partsCheckbox">
                                        Include Parts in Service
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="partsSection" class="d-none mb-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5>Parts Required</h5>
                                    <button type="button" class="btn btn-sm btn-primary float-end" id="addPart">
                                        <i class="fas fa-plus"></i> Add Part
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div id="partsContainer">
                                        <!-- Parts will be added here dynamically -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea name="note" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 ms-auto">
                                <div class="total-order w-100 max-widthauto m-auto mb-4">
                                    <ul>
                                        <li>
                                            <h4>Paid</h4>
                                            <h5 id="paid_amount">0</h5>
                                             <input type="hidden" name="paid_amount"> {{-- main paid amount data sent by amount field --}}
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

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Type</label>
                                    <select name="payment_type_id" class="form-control">
                                        <option value="">Select Payment Type</option>
                                        <option value="">Cash</option>
                                        {{-- @foreach ($paymentTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach --}}
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Discount</label>
                                    <input type="number" name="discount" id="discount" class="form-control" value="0"
                                        min="0">
                                </div>
                            </div>

                            <!-- Payment Section - Initially Hidden -->
                            <div id="paymentSection" class="col-md-12 mt-3 d-none">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h5>Payment Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="payment_type" class="form-label">Payment Type</label>
                                                <select class="form-control select2" id="payment_type" name="payment_type">
                                                    <option value="">Select Payment Type</option>
                                                    <option value="full_due" selected>Full Due</option>
                                                    <option value="partial_paid">Partial Paid</option>
                                                    <option value="full_paid">Full Paid</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="payment_account" class="form-label">Accounts</label>
                                                <select class="form-control select2" id="payment_account"
                                                    name="account_id">
                                                    <option value="">Select Account</option>
                                                    @foreach ($accounts as $account)
                                                        <option value="{{ $account->id }}">
                                                            {{ $account->name }}({{ number_format($account->balance) }} ৳)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3 amount-field" style="display: none;">
                                                <label for="amount" class="form-label">Amount</label>
                                                <input type="number" class="form-control" id="payment_amount"
                                                    name="amount" placeholder="Enter amount">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="payment_date" class="form-label">Payment Date</label>
                                                <input type="date" class="form-control" id="payment_date"
                                                    name="payment_date" value="{{ date('Y-m-d') }}">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="payment_note" class="form-label">Note</label>
                                                <input type="text" class="form-control" id="payment_note"
                                                    name="payment_note" placeholder="Payment note (optional)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Service Charges:</label>
                                            <h5 id="serviceCharges">0.00</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Parts Total:</label>
                                            <h5 id="partsTotal">0.00</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Discount:</label>
                                            <h5 id="discountAmount">0.00</h5>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12 text-end">
                                        <h4>Grand Total: <span id="grandTotal">0.00</span></h4>
                                        <input type="hidden" name="grand_total" id="grandTotalInput" value="0">
                                        <input type="hidden" name="total_amount" id="totalAmountInput" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Save Service</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Part row template  -->
    <template id="partRowTemplate">
        <div class="row part-row mb-3">
            <div class="col-md-3">
                <label>Part</label>
                <select name="parts[__index__][product_id]" class="form-control part-select" required>
                    <option value="">Select Part</option>
                    @foreach ($products as $product)
                        @php
                            $totalStock = $product->getTotalAvailableQuantity();
                        @endphp
                        <option value="{{ $product->id }}" data-total-stock="{{ $totalStock }}">
                            {{ $product->name }} (Total Stock: {{ $totalStock }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Rack</label>
                <select name="parts[__index__][rack_id]" class="form-control rack-select" disabled>
                    <option value="">Select Rack</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>Drawer</label>
                <select name="parts[__index__][drawer_id]" class="form-control drawer-select" disabled>
                    <option value="">Select Drawer</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>Quantity</label>
                <input type="number" name="parts[__index__][quantity]" class="form-control part-quantity"
                    min="1" value="1" disabled>
                <small class="text-muted stock-info">Available: 0</small>
            </div>
            <div class="col-md-2">
                <label>Price</label>
                <div class="input-group">
                    <span class="input-group-text">৳</span>
                    <input type="text" class="form-control part-price" name="parts[__index__][price]" readonly>
                    <input type="hidden" name="parts[__index__][unit_sale_price]" class="unit-sale-price">
                </div>
            </div>
            <div class="col-md-1">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-danger remove-part form-control">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize select2
            $('.select2').select2();

            // Variables for calculations
            let serviceTotal = 0;
            let partsTotal = 0;
            let discount = 0;
            let grandTotal = 0;
            let partRowIndex = 0;

            // Toggle parts section
            $('#partsCheckbox').change(function() {
                if ($(this).is(':checked')) {
                    $('#partsSection').removeClass('d-none');
                    // Add first row if none exists
                    if ($('.part-row').length === 0) {
                        addPartRow();
                    }
                } else {
                    $('#partsSection').addClass('d-none');
                    // Clear parts for calculation
                    partsTotal = 0;
                    updateTotals();
                }
            });

            // Add part row
            $('#addPart').click(function() {
                addPartRow();
            });

            // Remove part row
            $(document).on('click', '.remove-part', function() {
                $(this).closest('.part-row').remove();
                calculatePartsTotals();
            });

            // Calculate service price when selection changes
            $('#serviceChartSelect').change(function() {
                calculateServiceTotal();
            });

            // Product selection changed
            $(document).on('change', '.part-select', function() {
                let row = $(this).closest('.part-row');
                let productId = $(this).val();

                // Reset dependent fields
                row.find('.rack-select').html('<option value="">Select Rack</option>').prop('disabled',
                    true);
                row.find('.drawer-select').html('<option value="">Select Drawer</option>').prop('disabled',
                    true);
                row.find('.part-quantity').val(1).prop('disabled', true);
                row.find('.stock-info').text('Available: 0');
                row.find('.part-price').val('0.00');
                row.find('.stock-purchase-id').val('');

                if (productId) {
                    // Enable rack dropdown and load racks for this product
                    row.find('.rack-select').prop('disabled', false);
                    loadRacksForProduct(productId, row);
                }

                calculatePartsTotals();
            });

            // Rack selection changed
            $(document).on('change', '.rack-select', function() {
                let row = $(this).closest('.part-row');
                let rackId = $(this).val();
                let productId = row.find('.part-select').val();

                // Reset dependent fields
                row.find('.drawer-select').html('<option value="">Select Drawer</option>').prop('disabled',
                    true);
                row.find('.part-quantity').val(1).prop('disabled', true);
                row.find('.stock-info').text('Available: 0');
                row.find('.part-price').val('0.00');
                row.find('.stock-purchase-id').val('');

                if (rackId && productId) {
                    // Enable drawer dropdown and load drawers for this rack and product
                    row.find('.drawer-select').prop('disabled', false);
                    loadDrawersForRack(productId, rackId, row);
                }

                calculatePartsTotals();
            });

            // Drawer selection changed
            $(document).on('change', '.drawer-select', function() {
                let row = $(this).closest('.part-row');
                let drawerId = $(this).val();
                let productId = row.find('.part-select').val();
                let rackId = row.find('.rack-select').val();

                // Reset quantity and price fields
                row.find('.part-quantity').val(1).prop('disabled', true);
                row.find('.stock-info').text('Available: 0');
                row.find('.part-price').val('0.00');
                row.find('.stock-purchase-id').val('');

                if (drawerId && rackId && productId) {
                    // Get stock information for this drawer
                    getStockInfo(productId, rackId, drawerId, row);
                }

                calculatePartsTotals();
            });

            // Recalculate on quantity change
            $(document).on('input', '.part-quantity', function() {
                let row = $(this).closest('.part-row');
                let quantity = parseInt($(this).val()) || 0;
                let maxStock = parseInt(row.find('.part-quantity').attr('max')) || 0;

                // Validate against stock
                if (quantity > maxStock) {
                    $(this).val(maxStock);
                    toastr.error(`Maximum available quantity is ${maxStock}`);
                    quantity = maxStock;
                }

                // Update row price
                let unitPrice = parseFloat(row.find('.part-price').data('unit-price')) || 0;
                row.find('.part-price').val((unitPrice * quantity).toFixed(2));

                calculatePartsTotals();
            });

            // Function to add a new part row
            function addPartRow() {
                let template = $('#partRowTemplate').html();
                template = template.replace(/__index__/g, partRowIndex++);

                $('#partsContainer').append(template);

                // Initialize select2 on new selects
                $('#partsContainer .part-row:last-child .part-select').select2();
                $('#partsContainer .part-row:last-child .rack-select').select2();
                $('#partsContainer .part-row:last-child .drawer-select').select2();
            }

            // Load racks that contain the selected product
            function loadRacksForProduct(productId, row) {
                let url = "{{ route('admin.stock.get-racks-for-product', '') }}/" + productId;
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        let rackSelect = row.find('.rack-select');
                        rackSelect.html('<option value="">Select Rack</option>');

                        if (response.racks && response.racks.length > 0) {
                            $.each(response.racks, function(index, rack) {
                                rackSelect.append(
                                    `<option value="${rack.id}">${rack.name} (${rack.product_count})</option>`
                                );
                            });
                        }
                    },
                    error: function() {
                        toastr.error('Failed to load racks');
                    }
                });
            }

            // Load drawers for the selected rack that contain the product
            function loadDrawersForRack(productId, rackId, row) {
                let url = "{{ route('admin.stock.get-drawers-for-rack', [':productId', ':rackId']) }}"
                    .replace(':productId', productId)
                    .replace(':rackId', rackId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        let drawerSelect = row.find('.drawer-select');
                        drawerSelect.html('<option value="">Select Drawer</option>');

                        if (response.drawers && response.drawers.length > 0) {
                            $.each(response.drawers, function(index, drawer) {
                                drawerSelect.append(
                                    `<option value="${drawer.id}">${drawer.name} (${drawer.product_count})</option>`
                                );
                            });
                        }
                    },
                    error: function() {
                        toastr.error('Failed to load drawers');
                    }
                });
            }

            // Get stock information for a specific product in a drawer
            function getStockInfo(productId, rackId, drawerId, row) {
                let url = "{{ route('admin.stock.get-stock-info', [':productId', ':rackId', ':drawerId']) }}"
                    .replace(':productId', productId)
                    .replace(':rackId', rackId)
                    .replace(':drawerId', drawerId);
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.stock) {
                            let availableQty = response.stock.available_qty || 0;
                            let salePrice = response.stock.sale_price || 0;
                            console.log(salePrice);


                            // Update UI with stock info
                            row.find('.part-quantity')
                                .prop('disabled', false)
                                .attr('max', availableQty)
                                .val(1);

                            row.find('.stock-info').text(`Available: ${availableQty}`);
                            row.find('.part-price')
                                .val(parseFloat(salePrice).toFixed(2))
                                .data('unit-price', parseFloat(salePrice));
                            // Set the sale price if provided in the API response
                            if (response.stock.sale_price) {
                                row.find('.unit-sale-price').val(response.stock.sale_price);
                            }

                            // Recalculate totals
                            calculatePartsTotals();
                        } else {
                            row.find('.part-quantity').prop('disabled', true);
                            row.find('.stock-info').text('No stock available');
                            row.find('.part-price').val('0.00');
                        }
                    },
                    error: function() {
                        toastr.error('Failed to get stock information');
                    }
                });
            }

            // Form validation before submit
            $('#serviceForm').submit(function(e) {
                e.preventDefault();

                // Validate service selection
                if ($('#serviceChartSelect').val() === null || $('#serviceChartSelect').val().length ===
                    0) {
                    toastr.error('Please select at least one service');
                    return false;
                }

                // Validate parts if parts checkbox is checked
                if ($('#partsCheckbox').is(':checked')) {
                    let valid = true;
                    let uniqueCombinations = new Set();

                    if ($('.part-row').length === 0) {
                        toastr.error('Please add at least one part');
                        return false;
                    }

                    $('.part-row').each(function() {
                        let productId = $(this).find('.part-select').val();
                        let rackId = $(this).find('.rack-select').val();
                        let drawerId = $(this).find('.drawer-select').val();
                        let quantity = parseInt($(this).find('.part-quantity').val()) || 0;

                        if (productId === '' || rackId === '' || drawerId === '' || quantity <= 0) {
                            valid = false;
                            return false; // Break the loop
                        }

                        // Create a unique identifier for this combination
                        let combination = `${productId}-${rackId}-${drawerId}`;

                        // Check if this exact combination has already been added
                        if (uniqueCombinations.has(combination)) {
                            toastr.error(
                                'Duplicate product-rack-drawer combination detected. Please use different locations.'
                            );
                            valid = false;
                            return false; // Break the loop
                        }

                        uniqueCombinations.add(combination);
                    });

                    if (!valid) {
                        return false;
                    }
                }

                // Submit form via AJAX
                let formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.href = response.redirectUrl ||
                                "{{ route('admin.services.index') }}";
                        }, 1000);
                    },
                    error: function(xhr) {
                        let response = xhr.responseJSON;
                        if (response && response.errors) {
                            $.each(response.errors, function(key, value) {
                                toastr.error(value);
                            });
                        } else if (response && response.message) {
                            toastr.error(response.message);
                        } else {
                            toastr.error('An error occurred. Please try again.');
                        }
                    }
                });
            });

            // Other existing calculation functions
            function calculateServiceTotal() {
                serviceTotal = 0;
                $('#serviceChartSelect option:selected').each(function() {
                    serviceTotal += parseFloat($(this).data('price')) || 0;
                });

                $('#serviceCharges').text(serviceTotal.toFixed(2));
                updateTotals();

                // Revalidate discount after service total changes
                validateDiscount();
            }

            function calculatePartsTotals() {
                partsTotal = 0;

                $('.part-row').each(function() {
                    let price = parseFloat($(this).find('.part-price').val()) || 0;
                    let quantity = parseInt($(this).find('.part-quantity').val()) || 0;

                    if (!isNaN(price) && !isNaN(quantity)) {
                        partsTotal += price //* quantity;
                    }
                });

                $('#partsTotal').text(partsTotal.toFixed(2));
                updateTotals();

                // Revalidate discount after parts total changes
                validateDiscount();
            }

            function validateDiscount() {
                let totalBeforeDiscount = serviceTotal + partsTotal;
                let currentDiscount = parseFloat($('#discount').val()) || 0;

                if (currentDiscount > totalBeforeDiscount) {
                    $('#discount').val(totalBeforeDiscount);
                    discount = totalBeforeDiscount;
                    toastr.warning('Discount has been adjusted to match the total amount');
                }
            }

            function updateTotals() {
                let totalBeforeDiscount = serviceTotal + partsTotal;
                let discountAmount = Math.min(discount, totalBeforeDiscount);
                grandTotal = totalBeforeDiscount - discountAmount;

                $('#discountAmount').text(discountAmount.toFixed(2));
                $('#grandTotal').text(grandTotal.toFixed(2));

                // Update hidden inputs for form submission
                $('#grandTotalInput').val(grandTotal);
                $('#totalAmountInput').val(totalBeforeDiscount);
            }

            // Initialize discount input event
            $('#discount').on('input', function() {
                let inputDiscount = parseFloat($(this).val()) || 0;
                let totalBeforeDiscount = serviceTotal + partsTotal;

                // Ensure discount isn't more than total amount
                if (inputDiscount > totalBeforeDiscount) {
                    inputDiscount = totalBeforeDiscount;
                    $(this).val(totalBeforeDiscount);
                    toastr.warning('Discount cannot be more than the total amount');
                }

                discount = inputDiscount;
                updateTotals();
            });

            // Handle service type selection (show/hide payment section)
            $('select[name="service_type"]').change(function() {
                if ($(this).val() === 'external') {
                    $('#paymentSection').removeClass('d-none');
                    // Initialize select2 on payment dropdowns
                    $('#payment_type, #payment_account').select2();
                    // Update paid/due calculation
                    updatePaidDueAmounts();
                } else {
                    $('#paymentSection').addClass('d-none');
                    // Reset payment fields when hiding
                    $('#payment_type').val('full_due');
                    $('#payment_account').val('');
                    $('#payment_amount').val('');
                    $('.amount-field').hide();
                    // Reset paid/due amounts to zero when service type is self
                    $('#paid_amount').text('0.00');
                    $('#due_amount').text(grandTotal.toFixed(2));
                    $('input[name="paid_amount"]').val(0);
                    $('input[name="due_amount"]').val(grandTotal);
                }
            });

            // Handle payment type selection
            $('#payment_type').change(function() {
                if ($(this).val() === 'partial_paid') {
                    $('.amount-field').show();
                    // Set maximum amount to grand total
                    $('#payment_amount').attr('max', grandTotal);
                    // Set a default value (e.g., half of grand total)
                    if (!$('#payment_amount').val()) {
                        $('#payment_amount').val(Math.round(grandTotal / 2));
                    }
                    // Update paid/due calculation
                    updatePaidDueAmounts();
                } else if ($(this).val() === 'full_paid') {
                    $('.amount-field').hide();
                    // Set payment amount to grand total for calculation purposes
                    $('#payment_amount').val(grandTotal);
                    // Update paid/due calculation
                    updatePaidDueAmounts();
                } else {
                    // For full_due
                    $('.amount-field').hide();
                    $('#payment_amount').val(0);
                    // Update paid/due calculation
                    updatePaidDueAmounts();
                }
            });

            // Function to update paid and due amounts

            function updatePaidDueAmounts() {
                let paidAmount = 0;
                let dueAmount = grandTotal;

                // Check if external service is selected
                if ($('select[name="service_type"]').val() === 'external') {
                    let paymentType = $('#payment_type').val();

                    if (paymentType === 'full_paid') {
                        paidAmount = grandTotal;
                        dueAmount = 0;
                    } else if (paymentType === 'partial_paid') {
                        paidAmount = parseFloat($('#payment_amount').val()) || 0;
                        dueAmount = grandTotal - paidAmount;

                        // Ensure paid amount doesn't exceed grand total
                        if (paidAmount > grandTotal) {
                            paidAmount = grandTotal;
                            dueAmount = 0;
                            $('#payment_amount').val(grandTotal);
                        }
                    } else {
                        // full_due or empty selection
                        paidAmount = 0;
                        dueAmount = grandTotal;
                    }
                } else {
                    // For 'self' service type
                    paidAmount = 0;
                    dueAmount = grandTotal;
                }

                // Update UI
                $('#paid_amount').text(paidAmount.toFixed(2));
                $('#due_amount').text(dueAmount.toFixed(2));

                // Update hidden input fields
                $('input[name="paid_amount"]').val(paidAmount);
                $('input[name="due_amount"]').val(dueAmount);
            }


            // Update payment amount max when grand total changes
            function updatePaymentFields() {
                // Get current payment type
                let paymentType = $('#payment_type').val();

                // Set maximum payment amount to new grand total
                if (paymentType === 'partial_paid') {
                    let currentAmount = parseFloat($('#payment_amount').val()) || 0;
                    $('#payment_amount').attr('max', grandTotal);

                    // If current amount exceeds new grand total, adjust it
                    if (currentAmount > grandTotal) {
                        $('#payment_amount').val(grandTotal);
                        toastr.warning('Payment amount has been adjusted to match the grand total');
                    }
                } else if (paymentType === 'full_paid') {
                    $('#payment_amount').val(grandTotal);
                }

                // Always update paid/due calculation when grand total changes
                updatePaidDueAmounts();
            }

            // Add payment field update to the updateTotals function
            const originalUpdateTotals = updateTotals;
            updateTotals = function() {
                // Call the original function first
                originalUpdateTotals();

                // Then update payment fields
                updatePaymentFields();
            };

            // Handle payment amount changes
            $('#payment_amount').on('input', function() {
                let inputAmount = parseFloat($(this).val()) || 0;

                // Ensure payment amount isn't more than grand total
                if (inputAmount > grandTotal) {
                    inputAmount = grandTotal;
                    $(this).val(grandTotal);
                    toastr.warning('Payment amount cannot be more than the grand total');
                }

                // Update paid/due amounts whenever payment amount changes
                updatePaidDueAmounts();
            });

            // Validate payment fields before form submission
            const originalFormSubmit = $('#serviceForm').submit;
            $('#serviceForm').submit(function(e) {
                // Check payment validation only if external type is selected
                if ($('select[name="service_type"]').val() === 'external') {
                    // Validate payment type is selected
                    if ($('#payment_type').val() === '') {
                        toastr.error('Please select a payment type');
                        return false;
                    }

                    // Validate account is selected for partial_paid and full_paid
                    if (($('#payment_type').val() === 'partial_paid' || $('#payment_type').val() ===
                            'full_paid') &&
                        $('#payment_account').val() === '') {
                        toastr.error('Please select an account for the payment');
                        return false;
                    }

                    // Validate amount for partial payment
                    if ($('#payment_type').val() === 'partial_paid') {
                        let amount = parseFloat($('#payment_amount').val()) || 0;
                        if (amount <= 0) {
                            toastr.error('Please enter a valid payment amount');
                            return false;
                        }
                        if (amount > grandTotal) {
                            $('#payment_amount').val(grandTotal);
                            toastr.warning('Payment amount adjusted to match grand total');
                            updatePaidDueAmounts(); // Update paid/due after adjustment
                        }
                    }
                }

                // Continue with original validation
                return true;
            });
            // Initialize paid/due amounts on page load
            updatePaidDueAmounts();
        });
    </script>
@endpush
