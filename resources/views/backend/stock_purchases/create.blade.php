@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Store in Rack" button="Back to Purchase" back-button-route="admin.purchases.index" />
            <div class="card table-list-card">
                <div class="card-body p-3">
                    <form action="#" method="POST">
                        @csrf

                        <div id="productRows">
                            <!-- Initial Row -->
                            <div class="row product-row">
                                <div class="col-lg-3">
                                    <label>Select Product</label>
                                    <select class="form-control select product-select" name="products[0][product_id]"
                                        required>
                                        <option value="">Choose Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label>Select Rack</label>
                                    <select class="form-control select rack-select" name="products[0][rack_id]" required>
                                        <option value="">Choose Rack</option>
                                        @foreach ($racks as $rack)
                                            <option value="{{ $rack->id }}">{{ $rack->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label>Select Drawer</label>
                                    <select class="form-control select drawer-select" name="products[0][drawer_id]"
                                        required>
                                        <option value="">Choose Drawer</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label>Quantity</label>
                                    <input type="number" class="form-control quantity-input" name="products[0][quantity]"
                                        placeholder="Enter Quantity" required>
                                </div>
                                <div class="col-lg-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <button type="button" class="btn btn-primary" id="addRow">Add Another Product</button>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            const racks = @json($racks);

            // Add New Row
            let rowIndex = 1;
            $('#addRow').on('click', function() {
                const newRow = `
                <div class="row product-row">
                    <div class="col-lg-3">
                        <label>Select Product</label>
                        <select class="form-control select product-select" name="products[${rowIndex}][product_id]" required>
                            <option value="">Choose Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label>Select Rack</label>
                        <select class="form-control select rack-select" name="products[${rowIndex}][rack_id]" required>
                            <option value="">Choose Rack</option>
                            @foreach ($racks as $rack)
                                <option value="{{ $rack->id }}">{{ $rack->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label>Select Drawer</label>
                        <select class="form-control select drawer-select" name="products[${rowIndex}][drawer_id]" required>
                            <option value="">Choose Drawer</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label>Quantity</label>
                        <input type="number" class="form-control quantity-input" name="products[${rowIndex}][quantity]" placeholder="Enter Quantity" required>
                    </div>
                    <div class="col-lg-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-row">Remove</button>
                    </div>
                </div>
            `;
                $('#productRows').append(newRow);
                rowIndex++;
            });

            // Remove Row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('.product-row').remove();
            });

            // Populate Drawers Dynamically
            $(document).on('change', '.rack-select', function() {
                const rackId = $(this).val();
                const rack = racks.find(r => r.id == rackId);
                const drawerSelect = $(this).closest('.product-row').find('.drawer-select');

                // Clear previous drawers
                drawerSelect.empty().append('<option value="">Choose Drawer</option>');

                if (rack && rack.drawers) {
                    rack.drawers.forEach(drawer => {
                        drawerSelect.append(`<option value="${drawer.id}">${drawer.name}</option>`);
                    });
                }
            });
        });
    </script>
@endpush
