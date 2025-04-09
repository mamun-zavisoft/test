@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Create Parts Sale" button="Back to Sales" back-button-route="admin.sales.index" />
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Parts Sale</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.sales.store') }}" id="partsSaleForm" method="POST">
                        @csrf

                        <div id="partsSection" class="mb-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5>Parts Details</h5>
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
                                    <label>Discount (৳)</label>
                                    <input name="discount" type="number" class="form-control" id="discount" value="0"
                                        min="0" onwheel="this.blur()">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Account<span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="account_id" id="payment_account" required>
                                        <option value="">Select Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">
                                                {{ $account->name }}({{ number_format($account->balance) }}৳)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea name="note" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <label>Phone</label>
                                <input name="phone" class="form-control">
                            </div>
                        </div>

                        <div class="mb-4 bg-light card">
                            <div class="card-body">
                                <div class="row">
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
                                    <div class="col-md-4">
                                        <div class="form-group text-end">
                                            <label>Grand Total:</label>
                                            <h4 id="grandTotal">0.00</h4>
                                            <input type="hidden" name="grand_total" id="grandTotalInput" value="0">
                                            <input type="hidden" name="total_amount" id="totalAmountInput" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Part row template -->
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
                            {{ $product->name }}(Total Stock:{{ $totalStock }})</option>
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
                <input type="number" name="parts[__index__][quantity]" class="form-control part-quantity" min="1"
                    value="0" step="1" onmousewheel="this.blur()" disabled>
                <small class="text-muted stock-info">Available: 0</small>
            </div>
            <div class="col-md-2">
                <label>Price</label>
                <div class="input-group">
                    <span class="input-group-text">৳</span>
                    <input type="text" class="form-control part-price" name="parts[__index__][price]" readonly>
                    <input type="hidden" name="parts[__index__][unit_sale_price]" class="unit-sale-price">
                </div>
                <small class="text-muted part-unit-price">Unit Price: 0</small>
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
            $('.select2').select2();
            let partsTotal = 0;
            let discount = 0;
            let grandTotal = 0;
            let partRowIndex = 0;

            // Add initial part row if needed
            addPartRow();

            $('#addPart').click(function() {
                addPartRow();
            });

            $(document).on('click', '.remove-part', function() {
                $(this).closest('.part-row').remove();
                calculatePartsTotals();
            });

            $(document).on('change', '.part-select', function() {
                let row = $(this).closest('.part-row');
                let productId = $(this).val();

                row.find('.rack-select').html('<option value="">Select Rack</option>').prop('disabled',
                    true);
                row.find('.drawer-select').html('<option value="">Select Drawer</option>').prop('disabled',
                    true);
                row.find('.part-quantity').val(0).prop('disabled', true);
                row.find('.stock-info').text('Available: 0');
                row.find('.part-unit-price').text('Unit Price: 0');
                row.find('.part-price').val('0.00');
                row.find('.stock-purchase-id').val('');

                if (productId) {
                    row.find('.rack-select').prop('disabled', false);
                    loadRacksForProduct(productId, row);
                }
                calculatePartsTotals();
            });

            $(document).on('change', '.rack-select', function() {
                let row = $(this).closest('.part-row');
                let rackId = $(this).val();
                let productId = row.find('.part-select').val();

                row.find('.drawer-select').html('<option value="">Select Drawer</option>').prop('disabled',
                    true);
                row.find('.part-quantity').val(0).prop('disabled', true);
                row.find('.stock-info').text('Available: 0');
                row.find('.part-unit-price').text('Unit Price: 0');
                row.find('.part-price').val('0.00');
                row.find('.stock-purchase-id').val('');

                if (rackId && productId) {
                    row.find('.drawer-select').prop('disabled', false);
                    loadDrawersForRack(productId, rackId, row);
                }
                calculatePartsTotals();
            });

            $(document).on('change', '.drawer-select', function() {
                let row = $(this).closest('.part-row');
                let drawerId = $(this).val();
                let productId = row.find('.part-select').val();
                let rackId = row.find('.rack-select').val();

                row.find('.part-quantity').val(0).prop('disabled', true);
                row.find('.stock-info').text('Available: 0');
                row.find('.part-unit-price').text('Unit Price: 0');
                row.find('.part-price').val('0.00');
                row.find('.stock-purchase-id').val('');

                if (drawerId && rackId && productId) {
                    getStockInfo(productId, rackId, drawerId, row);
                }
                calculatePartsTotals();
            });

            $(document).on('input', '.part-quantity', function() {
                let row = $(this).closest('.part-row');
                let quantity = parseInt($(this).val()) || 0;
                let maxStock = parseInt(row.find('.part-quantity').attr('max')) || 0;

                if (quantity > maxStock) {
                    $(this).val(maxStock);
                    toastr.error(`Maximum available quantity is ${maxStock}`);
                    quantity = maxStock;
                }

                let unitPrice = parseFloat(row.find('.part-price').data('unit-price')) || 0;
                row.find('.part-price').val((unitPrice * quantity).toFixed(2));
                calculatePartsTotals();
            });

            function addPartRow() {
                let template = $('#partRowTemplate').html();
                template = template.replace(/__index__/g, partRowIndex++);
                $('#partsContainer').append(template);
                $('#partsContainer .part-row:last-child .part-select').select2();
                $('#partsContainer .part-row:last-child .rack-select').select2();
                $('#partsContainer .part-row:last-child .drawer-select').select2();
            }

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

            function loadDrawersForRack(productId, rackId, row) {
                let url = "{{ route('admin.stock.get-drawers-for-rack', [':productId', ':rackId']) }}".replace(
                    ':productId', productId).replace(':rackId', rackId);
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

            function getStockInfo(productId, rackId, drawerId, row) {
                let url = "{{ route('admin.stock.get-stock-info', [':productId', ':rackId', ':drawerId']) }}"
                    .replace(':productId', productId).replace(':rackId', rackId).replace(':drawerId', drawerId);
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.stock) {
                            let availableQty = response.stock.available_qty || 0;
                            let salePrice = response.stock.sale_price || 0;

                            row.find('.part-quantity').prop('disabled', false).attr('max', availableQty)
                                .val(1);
                            row.find('.stock-info').text(`Available: ${availableQty}`);
                            row.find('.part-unit-price').text(`Unit Price: ${salePrice}`);
                            row.find('.part-price').val(parseFloat(salePrice).toFixed(2)).data(
                                'unit-price', parseFloat(salePrice));

                            if (response.stock.sale_price) {
                                row.find('.unit-sale-price').val(response.stock.sale_price);
                            }

                            calculatePartsTotals();
                        } else {
                            row.find('.part-quantity').prop('disabled', true);
                            row.find('.stock-info').text('No stock available');
                            row.find('.part-unit-price').text('Unit Price: 0');
                            row.find('.part-price').val('0.00');
                        }
                    },
                    error: function() {
                        toastr.error('Failed to get stock information');
                    }
                });
            }

            $('#partsSaleForm').submit(function(e) {
                e.preventDefault();

                if ($('.part-row').length === 0) {
                    toastr.error('Please add at least one part');
                    return false;
                }

                let valid = true;
                let uniqueCombinations = new Set();

                $('.part-row').each(function() {
                    let productId = $(this).find('.part-select').val();
                    let rackId = $(this).find('.rack-select').val();
                    let drawerId = $(this).find('.drawer-select').val();
                    let quantity = parseInt($(this).find('.part-quantity').val()) || 0;

                    if (productId === '' || rackId === '' || drawerId === '' || quantity <= 0) {
                        valid = false;
                        toastr.error('Please complete all part details');
                        return false;
                    }

                    let combination = `${productId}-${rackId}-${drawerId}`;
                    if (uniqueCombinations.has(combination)) {
                        toastr.error('Duplicate product-rack-drawer combination detected.');
                        valid = false;
                        return false;
                    }

                    uniqueCombinations.add(combination);
                });

                if (!valid) {
                    return false;
                }

                if ($('#payment_account').val() === '') {
                    toastr.error('Please select a payment account');
                    return false;
                }

                let formData = $(this).serialize();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        toastr.success(response.message);
                        setTimeout(function() {
                            window.location.href = response.redirectUrl ||
                                "{{ route('admin.sales.index') }}";
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

            function calculatePartsTotals() {
                partsTotal = 0;
                $('.part-row').each(function() {
                    let price = parseFloat($(this).find('.part-price').val()) || 0;
                    partsTotal += price;
                });

                $('#partsTotal').text(partsTotal.toFixed(2));
                updateTotals();
                validateDiscount();
            }

            function validateDiscount() {
                let totalBeforeDiscount = partsTotal;
                let currentDiscount = parseFloat($('#discount').val()) || 0;

                if (currentDiscount > totalBeforeDiscount) {
                    $('#discount').val(totalBeforeDiscount);
                    discount = totalBeforeDiscount;
                    toastr.warning('Discount has been adjusted to match the total amount');
                }
            }

            function updateTotals() {
                let totalBeforeDiscount = partsTotal;
                let discountAmount = Math.min(discount, totalBeforeDiscount);
                grandTotal = totalBeforeDiscount - discountAmount;

                $('#discountAmount').text(discountAmount.toFixed(2));
                $('#grandTotal').text(grandTotal.toFixed(2));
                $('#grandTotalInput').val(grandTotal);
                $('#totalAmountInput').val(totalBeforeDiscount);
            }

            $('#discount').on('input', function() {
                let inputDiscount = parseFloat($(this).val()) || 0;
                let totalBeforeDiscount = partsTotal;

                if (inputDiscount > totalBeforeDiscount) {
                    inputDiscount = totalBeforeDiscount;
                    $(this).val(totalBeforeDiscount);
                    toastr.warning('Discount cannot be more than the total amount');
                }

                discount = inputDiscount;
                updateTotals();
            });

            // Add a part using the select dropdown
            $('#partSelect').change(function() {
                let productId = $(this).val();
                if (productId) {
                    // Reset the select after selection
                    setTimeout(() => {
                        $(this).val('').trigger('change.select2');
                    }, 100);

                    // Check if this product is already in the list
                    let isDuplicate = false;
                    $('.part-select').each(function() {
                        if ($(this).val() === productId) {
                            isDuplicate = true;
                            toastr.info(
                                'This part is already added. Please modify its quantity if needed.'
                                );
                            return false;
                        }
                    });

                    if (!isDuplicate) {
                        addPartRow();
                        let newRow = $('#partsContainer .part-row:last-child');
                        newRow.find('.part-select').val(productId).trigger('change');
                    }
                }
            });
        });
    </script>
@endpush
