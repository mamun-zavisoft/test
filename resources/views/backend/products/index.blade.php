@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Product List" sub-title="Manage Your Products" button="Add Product"
                button-route="admin.products.create" />

            <!-- /product list -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>

                    </div>

                    <!-- /Filter -->
                    <div class="table-responsive product-list">
                        <table class="table datanew">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Sale Price</th>
                                    <th>Qty</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="productimgname">
                                                <a href="javascript:void(0);" class="product-img stock-img">
                                                    <img src="{{ $product->thumbnail ?: asset('build/img/no-image.svg') }}"
                                                        alt="product">
                                                </a>
                                                <a href="javascript:void(0);">{{ $product->name }}</a>
                                            </div>
                                        </td>
                                        <td>{{ $product->category?->name }}</td>
                                        <td>{{ $product->brand?->name }}</td>
                                        <td>৳ {{ $product->sale_price }}</td>
                                        <td>{{ $product->total_available_quantity }}</td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 edit-icon  p-2" href="{{ url('product-details') }}" data-bs-toggle="modal"
                                                data-bs-target="#products-{{ $product->id }}">
                                                    <i data-feather="eye" class="feather-eye"></i>
                                                </a>
                                                <a class="me-2 p-2"
                                                    href="{{ route('admin.products.edit', $product->id) }}">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="confirm-text2 p-2" href="javascript:void(0);">
                                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                                    </a>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="products-{{ $product->id }}" tabindex="-1" aria-labelledby="productDetailsLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                            <div class="modal-content shadow-lg rounded-3" style="width: 90vw; max-width: 1200px; height: 95vh;">
                                                <div class="modal-header bg-white text-dark border-0 rounded-top d-flex align-items-center">
                                                    <h5 class="modal-title me-3" id="productDetailsLabel">Product Details</h5>
                                                    <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body p-4" style="max-height: 90vh; overflow-y: auto;">
                                                    <div class="d-flex flex-column flex-md-row align-items-center mb-4">
                                                        <div class="me-md-4 mb-3 mb-md-0 text-center">
                                                            <img src="{{ $product->thumbnail ?: asset('build/img/no-image.svg') }}" alt="product" class="img-fluid rounded" style="max-height: 200px;">
                                                        </div>
                                                        <div>
                                                            <h3 class="fw-bold mb-2">{{ $product->name }}</h3>
                                                            <p class="text-muted mb-2"><strong>Category:</strong> {{ $product->category?->name }}</p>
                                                            <p class="text-muted mb-2"><strong>Brand:</strong> {{ $product->brand?->name }}</p>
                                                            <p class="text-muted mb-2"><strong>Status:</strong> 
                                                                <span class="badge bg-{{ $product->status == 1 ? 'success' : 'warning' }}">
                                                                    {{ $product->status == 1 ? 'Active' : 'Inactive' }}
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-wrap gap-4">
                                                        <div class="p-4 border rounded shadow-sm flex-grow-1 bg-light">
                                                            <h5 class="fw-bold mb-2">Purchase Price</h5>
                                                            <p class="text-primary fs-4 fw-bold">{{ number_format($product->purchase_price, 2) }}</p>
                                                        </div>
                                                        <div class="p-4 border rounded shadow-sm flex-grow-1 bg-light">
                                                            <h5 class="fw-bold mb-2">Sale Price</h5>
                                                            <p class="text-success fs-4 fw-bold">{{ number_format($product->sale_price, 2) }}</p>
                                                        </div>
                                                        <div class="p-4 border rounded shadow-sm flex-grow-1 bg-light">
                                                            <h5 class="fw-bold mb-2">Available Quantity</h5>
                                                            <p class="text-dark fs-4 fw-bold">{{ $product->total_available_qty }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Modal Footer -->
                                                <div class="modal-footer justify-content-end">
                                                <button type="button" class="btn btn-secondary me-2" onclick="window.print()">
                                                    <i class="fas fa-print me-1"></i> Print
                                                </button>
                                                <button type="button" class="btn btn-primary">
                                                    <i class="fas fa-download me-1"></i> Download PDF
                                                </button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>
@endsection
