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
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea name="note" class="form-control" rows="3"></textarea>
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
        });
    </script>
@endpush
