@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb-modal title="Drawer List" sub-title="Manage drawer" button="Add drawer" modal-id="add-drawer" />

            <!-- filter -->
            <div class="card table-list-card">
                    <x-filter />

                    <!-- /Filter -->

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="no-sort">SL</th>
                                    <th>Rack</th>
                                    <th>Drawer</th>
                                    <th>Available Stored Quantity</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                @forelse($drawers as $drawer)
                                    <tr>
                                        <td>{{ $loop->iteration + $drawers->firstItem() - 1 }}</td>
                                        <td>{{ $drawer->rack?->name }}</td>
                                        <td>{{ $drawer->name }}</td>
                                        <td>{{ $drawer->available_products_count }}</td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 edit-icon p-2" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#drawer-products-{{ $drawer->id }}">
                                                    <i data-feather="eye" class="feather-eye"></i>
                                                </a>
                                                <a class="me-2 p-2" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit-drawer-{{ $drawer->id }}">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.drawers.destroy', $drawer->id) }}"
                                                    method="post" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="confirm-text2 p-2" href="javascript:void(0);">
                                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                                    </a>
                                                </form>
                                            </div>

                                        </td>

                                    </tr>

                                    <!-- Edit drawer -->
                                    <div class="modal fade" id="edit-drawer-{{ $drawer->id }}">
                                        <div class="modal-dialog modal-dialog-centered custom-modal-two">
                                            <div class="modal-content">
                                                <div class="page-wrapper-new p-0">
                                                    <div class="content">
                                                        <div
                                                            class="modal-header border-0 custom-modal-header justify-content-between">
                                                            <div class="page-title">
                                                                <h4>Edit Drawer</h4>
                                                            </div>
                                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body custom-modal-body new-employee-field">
                                                            <form class="editForm" data-id="{{ $drawer->id }}"
                                                                action="{{ route('admin.drawers.update', $drawer->id) }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="mb-3">
                                                                    <label class="form-label">Rack*</label>
                                                                    <select class="select" name="rack_id">
                                                                        <option value="">Choose</option>
                                                                        @foreach ($racks as $rack)
                                                                            <option value="{{ $rack->id }}"
                                                                                {{ $rack->id == $drawer->rack_id ? 'selected' : '' }}>
                                                                                {{ $rack->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Drawer Name*</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $drawer->name }}" name="name">
                                                                </div>

                                                                <div class="modal-footer-btn">
                                                                    <button type="button" class="btn btn-cancel me-2"
                                                                        data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-submit">Save
                                                                        Changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Edit drawer -->

                                    <!-- Drawer Products Modal -->
                                    <div class="modal fade" id="drawer-products-{{ $drawer->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div
                                                    class="modal-header border-0 custom-modal-header justify-content-between">
                                                    <div class="page-title">
                                                        <h4>Products in {{ $drawer->name }}</h4>
                                                    </div>
                                                    <button type="button" class="close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body custom-modal-body">
                                                    <div class="border rounded shadow-sm">
                                                        <div class="p-3 border-bottom bg-light">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <h5 class="fw-bold mb-0">Available Products</h5>
                                                                <span class="badge bg-primary">Total:
                                                                    {{ $drawer->available_products_count ?? 0 }}
                                                                    units</span>
                                                            </div>
                                                        </div>
                                                        <div class="p-0">
                                                            <div class="px-3 py-2">
                                                                <div
                                                                    class="d-flex justify-content-between fw-bold border-bottom pb-2 mb-2">
                                                                    <div>Product</div>
                                                                    <div>Quantity</div>
                                                                </div>

                                                                @php
                                                                    $availableProducts =
                                                                        $drawer->available_products ?? [];
                                                                    $totalQuantity = 0;
                                                                @endphp

                                                                @forelse($availableProducts as $product)
                                                                    @php
                                                                        $totalQuantity += $product->available_quantity;
                                                                    @endphp
                                                                    <div class="d-flex justify-content-between py-2 border-bottom">
                                                                        <div class="productimgname gap-1">
                                                                            <a href="javascript:void(0);" class="product-img stock-img">
                                                                                <img src="{{ $product->thumbnail ?: asset('build/img/no-image.svg') }}"
                                                                                    alt="product" style="width: 50px; height: 50px;">
                                                                            </a>
                                                                            <a href="javascript:void(0);">{{ $product->name }}</a>
                                                                        </div>
                                                                        <div><span
                                                                                class="badge bg-secondary">{{ $product->available_quantity }}</span>
                                                                        </div>
                                                                    </div>
                                                                @empty
                                                                    <div class="text-center py-3">
                                                                        <p class="mb-0 text-muted">No products found in
                                                                            this drawer</p>
                                                                    </div>
                                                                @endforelse

                                                                @if (count($availableProducts) > 0)
                                                                    <div class="d-flex justify-content-between py-2 mt-2">
                                                                        <div class="fw-bold">Total</div>
                                                                        <div><span
                                                                                class="badge bg-primary">{{ $totalQuantity }}</span>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Drawer Products Modal -->
                                @empty
                                   <tr class="text-center">
                                    <td colspan="7">No Drawer Found</td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                        <x-pagination :paginator="$drawers" />
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>

    <!-- Add Drawer -->
    <div class="modal fade" id="add-drawer">
        <div class="modal-dialog modal-dialog-centered custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header justify-content-between">
                            <div class="page-title">
                                <h4>Create Drawer</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"
                                onclick="$('#storeForm')[0].reset()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body new-employee-field">
                            <form action="{{ route('admin.drawers.store') }}" method="POST"
                                enctype="multipart/form-data" id="storeForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Rack*</label>
                                    <select class="select" name="rack_id">
                                        <option value="">Choose</option>
                                        @foreach ($racks as $rack)
                                            <option value="{{ $rack->id }}">{{ $rack->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Drawer Name*</label>
                                    <input type="text" name="name" class="form-control">
                                </div>
                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-submit" id="submit_btn">Create Drawer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ajax call for store
        $(document).ready(function() {
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
                        $('#add-brand').modal('hide');
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
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

            $('.editForm').submit(function(e) {
                e.preventDefault();
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
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                }).fail(function(xhr) {
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
        });
    </script>
@endpush
