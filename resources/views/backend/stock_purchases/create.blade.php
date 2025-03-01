@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Store in Rack" button="Back to Purchase" back-button-route="admin.purchases.index" />

            <div class="card table-list-card">
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <form id="stockForm" action="{{ route('admin.stock-purchases.store', $purchase) }}" method="post">
                            @csrf

                            @foreach ($purchase->products as $product)
                                @php
                                    $purchasedQty = $purchase->purchaseDetails
                                        ->where('product_id', $product->id)
                                        ->value('quantity');
                                @endphp

                                <div class="card mb-3 product-card" data-product-id="{{ $product->id }}"
                                    data-purchased-qty="{{ $purchasedQty }}">
                                    <div class="card-header bg-light">
                                        <h5>{{ $product->name }} (Purchased: {{ $purchasedQty }})</h5>
                                        <div class="progress mt-2">
                                            <div class="progress-bar" role="progressbar" style="width: 0%;"
                                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                0%
                                            </div>
                                        </div>
                                        <div class="text-end mt-2">
                                            <span class="badge bg-primary assigned-qty">0</span> / <span
                                                class="badge bg-secondary">{{ $purchasedQty }}</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="storage-locations">
                                            <div class="row storage-row mb-3">
                                                <input type="hidden"
                                                    name="products[{{ $product->id }}][locations][0][product_id]"
                                                    value="{{ $product->id }}">

                                                <div class="col-md-4">
                                                    <label>Rack</label>
                                                    <select name="products[{{ $product->id }}][locations][0][rack_id]"
                                                        class="form-control rack-select" required>
                                                        <option value="">Choose Rack</option>
                                                        @foreach ($racks as $rack)
                                                            <option value="{{ $rack->id }}">{{ $rack->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label>Drawer</label>
                                                    <select name="products[{{ $product->id }}][locations][0][drawer_id]"
                                                        class="form-control drawer-select" required>
                                                        <option value="">Choose Drawer</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label>Quantity</label>
                                                    <input type="number"
                                                        name="products[{{ $product->id }}][locations][0][quantity]"
                                                        class="form-control quantity-input" min="1"
                                                        max="{{ $purchasedQty }}" required>
                                                </div>

                                                <div class="col-md-1 d-flex align-items-end">
                                                    <button class="btn btn-success add-row mt-4"><i
                                                            class="fas fa-plus-circle"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary mt-2" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Update progress bar and assigned quantity display
            function updateProgressBar(productCard) {
                let productId = productCard.data('product-id');
                let purchasedQty = parseInt(productCard.data('purchased-qty')) || 0;
                let assignedQty = getTotalAssignedQuantity(productCard);
                let percentage = purchasedQty > 0 ? Math.min(100, (assignedQty / purchasedQty) * 100) : 0;

                productCard.find('.progress-bar').css('width', percentage + '%');
                productCard.find('.progress-bar').attr('aria-valuenow', percentage);
                productCard.find('.progress-bar').text(percentage.toFixed(0) + '%');
                productCard.find('.assigned-qty').text(assignedQty);

                // Change progress bar color based on completion
                let progressBar = productCard.find('.progress-bar');
                if (percentage < 100) {
                    progressBar.removeClass('bg-success bg-danger').addClass('bg-primary');
                } else if (percentage === 100) {
                    progressBar.removeClass('bg-primary bg-danger').addClass('bg-success');
                } else {
                    progressBar.removeClass('bg-primary bg-success').addClass('bg-danger');
                }
            }

            function getTotalAssignedQuantity(productCard) {
                let totalAssigned = 0;
                productCard.find('.quantity-input').each(function() {
                    totalAssigned += parseInt($(this).val()) || 0;
                });
                return totalAssigned;
            }

            function validateAssignedQuantity(productCard) {
                let purchasedQty = parseInt(productCard.data('purchased-qty')) || 0;
                let totalAssigned = getTotalAssignedQuantity(productCard);

                updateProgressBar(productCard);
                updateMaxValues(productCard); // Add this line

                if (totalAssigned > purchasedQty) {
                    // Instead of just showing error, also reduce the value
                    let overage = totalAssigned - purchasedQty;
                    let lastChangedInput = productCard.find('.quantity-input.last-changed');

                    if (lastChangedInput.length) {
                        let currentVal = parseInt(lastChangedInput.val()) || 0;
                        let newVal = Math.max(1, currentVal - overage);
                        lastChangedInput.val(newVal);

                        // Recalculate after adjustment
                        totalAssigned = getTotalAssignedQuantity(productCard);
                        updateProgressBar(productCard);
                    }

                    toastr.error('Total assigned quantity cannot exceed the purchased quantity!');
                    return false;
                }
                return true;
            }


            // Track which input was last changed
            $(document).on('focus', '.quantity-input', function() {
                $('.quantity-input').removeClass('last-changed');
                $(this).addClass('last-changed');
            });

            // Add real-time validation as user types
            $(document).on('input', '.quantity-input', function() {
                let productCard = $(this).closest('.product-card');
                let purchasedQty = parseInt(productCard.data('purchased-qty'));
                let totalAssigned = getTotalAssignedQuantity(productCard);

                // If total exceeds purchased, adjust the current input
                if (totalAssigned > purchasedQty) {
                    let overage = totalAssigned - purchasedQty;
                    let currentVal = parseInt($(this).val()) || 0;
                    let newVal = Math.max(1, currentVal - overage);
                    $(this).val(newVal);

                    toastr.error('Value adjusted to prevent exceeding purchased quantity.');
                }

                validateAssignedQuantity(productCard);
            });

            // Add max attribute to quantity inputs dynamically
            function updateMaxValues(productCard) {
                let purchasedQty = parseInt(productCard.data('purchased-qty'));
                let totalAssigned = getTotalAssignedQuantity(productCard);
                let remaining = purchasedQty - totalAssigned;

                // Update each quantity input
                productCard.find('.quantity-input').each(function() {
                    let currentVal = parseInt($(this).val()) || 0;
                    // Set max attribute to current value + remaining
                    $(this).attr('max', currentVal + remaining);
                });
            }


            // Add new storage location row
            $(document).on('click', '.add-row', function(event) {
                event.preventDefault();

                let productCard = $(this).closest('.product-card');
                let productId = productCard.data('product-id');
                let purchasedQty = parseInt(productCard.data('purchased-qty')) || 0;
                let totalAssigned = getTotalAssignedQuantity(productCard);

                if (totalAssigned >= purchasedQty) {
                    toastr.error(
                        'Cannot add more rows! Assigned quantity already meets or exceeds the purchased quantity.'
                    );
                    return;
                }

                let storageLocations = productCard.find('.storage-locations');
                let newIndex = storageLocations.find('.storage-row').length;

                let newRow = `
                    <div class="row storage-row mb-3">
                        <input type="hidden" name="products[${productId}][locations][${newIndex}][product_id]" value="${productId}">
                        
                        <div class="col-md-4">
                            <label>Rack</label>
                            <select name="products[${productId}][locations][${newIndex}][rack_id]" class="form-control rack-select" required>
                                <option value="">Choose Rack</option>
                                @foreach ($racks as $rack)
                                    <option value="{{ $rack->id }}">{{ $rack->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label>Drawer</label>
                            <select name="products[${productId}][locations][${newIndex}][drawer_id]" class="form-control drawer-select" required>
                                <option value="">Choose Drawer</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label>Quantity</label>
                            <input type="number" name="products[${productId}][locations][${newIndex}][quantity]" class="form-control quantity-input" min="1" required>
                        </div>
                        
                        <div class="col-md-1 d-flex align-items-end">
                            <button class="btn btn-danger remove-row mt-4"><i class="fas fa-minus-circle"></i></button>
                        </div>
                    </div>
                `;

                storageLocations.append(newRow);
            });

            // Remove storage location row
            $(document).on('click', '.remove-row', function() {
                let row = $(this).closest('.storage-row');
                let productCard = $(this).closest('.product-card');

                row.remove();
                validateAssignedQuantity(productCard);
            });

            // Fetch drawers when rack is selected
            $(document).on('change', '.rack-select', function() {
                let rackId = $(this).val();
                let drawerSelect = $(this).closest('.storage-row').find('.drawer-select');

                if (rackId) {
                    $.ajax({
                        url: `/drawers/fetch/${rackId}`,
                        type: 'GET',
                        success: function(data) {
                            drawerSelect.empty().append(
                                '<option value="">Choose Drawer</option>');
                            data.data.forEach(drawer => {
                                drawerSelect.append(
                                    `<option value="${drawer.id}">${drawer.name}</option>`
                                );
                            });
                        }
                    });
                } else {
                    drawerSelect.empty().append('<option value="">Choose Drawer</option>');
                }
            });

            // Initialize progress bars
            $('.product-card').each(function() {
                updateProgressBar($(this));
            });

            // Form submission
            $('#stockForm').submit(function(e) {
                e.preventDefault();

                // Validate all product quantities before submission
                let isValid = true;
                $('.product-card').each(function() {
                    if (!validateAssignedQuantity($(this))) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    return false;
                }

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
                            response.redirectUrl ? window.location.href = response.redirectUrl : `{{ route('admin.purchases.index') }}`; ;
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                }).fail(function(xhr) {
                    let response = xhr.responseJSON;
                    if (response && response.errors) {
                        $.each(response.errors, function(key, value) {
                            toastr.error(value);
                        });
                    }
                });
            });
        });
    </script>
@endpush
