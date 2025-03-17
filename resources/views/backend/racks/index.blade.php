<?php $page = 'rack-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb-modal title="Rack List" sub-title="Manage Your Rack" button="Add Rack" modal-id="add-rack" />

            <!-- /Rack list -->
            <div class="card table-list-card">
                <div class="card-body">
                    <x-filter />

                    <!-- /Filter -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="no-sort">SL</th>
                                    <th>Name</th>
                                    <th>Available Stored Quantity</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                @forelse ($racks as $rack)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration + $racks->firstItem() - 1 }}
                                        </td>
                                        <td>{{ $rack->name }}</td>
                                        <td>{{ $rack->total_products_count }}</td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 edit-icon  p-2" href="#" data-bs-toggle="modal"
                                                data-bs-target="#drawers-{{ $rack->id }}">
                                                    <i data-feather="eye" class="feather-eye"></i>
                                                </a>
                                                <a class="me-2 p-2" href="#" data-bs-toggle="modal"
                                                    data-bs-target="#edit-rack-{{ $rack->id }}">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.racks.destroy', $rack->id) }}"
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

                                    <!-- Edit Brand -->
                                    <div class="modal fade" id="edit-rack-{{ $rack->id }}">
                                        <div class="modal-dialog modal-dialog-centered custom-modal-two">
                                            <div class="modal-content">
                                                <div class="page-wrapper-new p-0">
                                                    <div class="content">
                                                        <div class="modal-header border-0 custom-modal-header justify-content-between">
                                                            <div class="page-title">
                                                                <h4>Edit Rack</h4>
                                                            </div>
                                                            <button type="button" class="close" data-bs-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body custom-modal-body new-employee-field">
                                                            <form class="editForm" data-id="{{ $rack->id }}"
                                                                action="{{ route('admin.racks.update', $rack->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="mb-3">
                                                                    <label class="form-label">Name*</label>
                                                                    <input type="text" class="form-control"
                                                                        value="{{ $rack->name }}" name="name">
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
                                    <!-- Edit Brand -->

                                    <!-- Rack Products Modal -->
                                    <div class="modal fade" id="drawers-{{ $rack->id }}">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="page-wrapper-new p-0">
                                                    <div class="content">
                                                        <div class="modal-header border-0 custom-modal-header justify-content-between">
                                                            <div class="page-title">
                                                                <h4>Rack: {{ $rack->name }}</h4>
                                                            </div>
                                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body custom-modal-body">
                                                            <div class="border rounded shadow-sm">
                                                                <div class="p-3 border-bottom bg-light">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <h5 class="fw-bold mb-0">Drawers</h5>
                                                                        <span class="badge bg-primary">Total Drawers: {{ $rack->drawers->count() }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="p-0">
                                                                    @forelse($rack->drawers as $drawer)
                                                                    <div class="border-bottom">
                                                                        <div class="d-flex justify-content-between align-items-center p-3">
                                                                            <div>
                                                                                <h6 class="mb-0 fw-bold">{{ $drawer->name }}</h6>
                                                                            </div>
                                                                            <div class="d-flex align-items-center">
                                                                                <span class="badge bg-info me-3">{{ $drawer->available_products_count }} units</span>
                                                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#drawer-products-{{ $drawer->id }}">
                                                                                    <i data-feather="eye" class="feather-eye"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @empty
                                                                    <div class="p-3 text-center">
                                                                        <p class="mb-0 text-muted">No drawers found for this rack</p>
                                                                    </div>
                                                                    @endforelse
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Rack Products Modal -->

                                    <!-- Drawer Products Modals (One for each drawer) -->
                                    @foreach($rack->drawers as $drawer)
                                    <div class="modal fade" id="drawer-products-{{ $drawer->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header border-0 custom-modal-header justify-content-between">
                                                    <div class="page-title">
                                                        <h4>Products in {{ $drawer->name }}</h4>
                                                    </div>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body custom-modal-body">
                                                    <div class="border rounded shadow-sm">
                                                        <div class="p-3 border-bottom bg-light">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <h5 class="fw-bold mb-0">Available Products</h5>
                                                                <span class="badge bg-primary">Total: {{ $drawer->available_products_count }} units</span>
                                                            </div>
                                                        </div>
                                                        <div class="p-0">
                                                            <div class="px-3 py-2">
                                                                <div class="d-flex justify-content-between fw-bold border-bottom pb-2 mb-2">
                                                                    <div>Product</div>
                                                                    <div>Quantity</div>
                                                                </div>
                                                                
                                                                @php
                                                                    $availableProducts = $drawer->available_products;
                                                                @endphp
                                                                
                                                                @forelse($availableProducts as $product)
                                                                    <div class="d-flex justify-content-between py-2 border-bottom">
                                                                        <div class="productimgname gap-1">
                                                                            <a href="javascript:void(0);" class="product-img stock-img">
                                                                                <img src="{{ $product->thumbnail ?: asset('build/img/no-image.svg') }}"
                                                                                    alt="product" style="width: 50px; height: 50px;">   
                                                                            </a>
                                                                            <a href="javascript:void(0);">{{ $product->name }}</a>
                                                                        </div>
                                                                        <div><span class="badge bg-secondary">{{ $product->available_quantity }}</span></div>
                                                                    </div>
                                                                @empty
                                                                <div class="text-center py-3">
                                                                    <p class="mb-0 text-muted">No products found in this drawer</p>
                                                                </div>
                                                                @endforelse
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <!-- End Drawer Products Modals -->
                                @empty
                                    <tr class="text-center">
                                        <td colspan="7">No Rack Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <x-pagination :paginator="$racks" />
                    </div>
                </div>
            </div>
            <!-- /Rack list -->
        </div>
    </div>

    <!-- Add Rack -->
    <div class="modal fade" id="add-rack">
        <div class="modal-dialog modal-dialog-centered custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header justify-content-between">
                            <div class="page-title">
                                <h4>Create Rack</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="$('#storeForm')[0].reset()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body new-employee-field">
                            <form action="{{ route('admin.racks.store') }}" method="POST"
                                id="storeForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name*</label>
                                    <input type="text" name="name" class="form-control">
                                </div>

                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-submit" id="submit_btn">Create Rack</button>
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
                        $('#add-rack').modal('hide');
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
                        $('.edit-rack').modal('hide');
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
                });
            });
        });
    </script>
@endpush
