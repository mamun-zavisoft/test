<table class="table">
    <thead>
        <tr>
            <th>SL</th>
            <th>Product</th>
            <th>Category</th>
            <th>Brand</th>
            <th>Sale Price</th>
            <th>Qty</th>
            <th class="no-sort">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($products as $product)
            <tr>
                <td>{{ $loop->iteration + $products->firstItem() - 1 }}</td>
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
                        <a class="me-2 edit-icon  p-2" href="{{ url('product-details') }}"
                            data-bs-toggle="modal" data-bs-target="#products-{{ $product->id }}">
                            <i data-feather="eye" class="feather-eye"></i>
                        </a>
                        {{-- @permission('product-update') --}}
                        <a class="me-2 p-2" href="{{ route('admin.products.edit', $product->id) }}">
                            <i data-feather="edit" class="feather-edit"></i>
                        </a>
                        {{-- @endpermission --}}
                        <form action="{{ route('admin.products.destroy', $product->id) }}"
                            class="delete-form" method="post">
                            @csrf
                            @method('DELETE')
                            <a class="confirm-text2 p-2" href="javascript:void(0);">
                                <i data-feather="trash-2" class="feather-trash-2"></i>
                            </a>
                        </form>
                    </div>
                </td>
            </tr>
            <div class="modal fade" id="products-{{ $product->id }}" tabindex="-1"
                aria-labelledby="productDetailsLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content shadow-lg rounded-3"
                        style="width: 90vw; max-width: 1200px; height: 95vh;">
                        <div
                            class="modal-header bg-white text-dark border-0 rounded-top d-flex align-items-center justify-content-between">
                            <h5 class="modal-title me-3" id="productDetailsLabel">Product Details
                            </h5>
                            <button type="button" class="close border-0" data-bs-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-4" style="max-height: 90vh; overflow-y: auto;">
                            <div class="d-flex flex-column flex-md-row align-items-center mb-4">
                                <div class="me-md-4 mb-3 mb-md-0 text-center">
                                    <img src="{{ $product->thumbnail ?: asset('build/img/no-image.svg') }}"
                                        alt="product" class="img-fluid rounded"
                                        style="max-height: 200px;">
                                </div>
                                <div>
                                    <h3 class="fw-bold mb-2">{{ $product->name }}</h3>
                                    <p class="text-muted mb-2"><strong>Category:</strong>
                                        {{ $product->category?->name }}</p>
                                    <p class="text-muted mb-2"><strong>Brand:</strong>
                                        {{ $product->brand?->name }}</p>
                                    <p class="text-muted mb-2"><strong>Status:</strong>
                                        <span
                                            class="badge bg-{{ $product->status == 1 ? 'success' : 'warning' }}">
                                            {{ $product->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-4 mb-4">
                                <div class="p-4 border rounded shadow-sm flex-grow-1 bg-light">
                                    <h5 class="fw-bold mb-2">Purchase Price</h5>
                                    <p class="text-primary fs-4 fw-bold">
                                        {{ number_format($product->purchase_price) }}</p>
                                </div>
                                <div class="p-4 border rounded shadow-sm flex-grow-1 bg-light">
                                    <h5 class="fw-bold mb-2">Sale Price</h5>
                                    <p class="text-success fs-4 fw-bold">
                                        {{ number_format($product->sale_price) }}</p>
                                </div>
                                <div class="p-4 border rounded shadow-sm flex-grow-1 bg-light">
                                    <h5 class="fw-bold mb-2">Available Quantity</h5>
                                    <p class="text-dark fs-4 fw-bold">
                                        {{ $product->total_available_qty }}</p>
                                </div>
                            </div>

                            <!-- Inventory Locations Section -->
                            <div class="mt-4 mb-4 border rounded shadow-sm">
                                <div class="p-3 border-bottom bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="fw-bold mb-0">Inventory Locations</h5>
                                        <span class="badge bg-primary">Total:
                                            {{ $product->total_available_qty }}</span>
                                    </div>
                                </div>
                                <div class="p-0">
                                    <!-- Rack #1 -->
                                    @foreach ($product->getAvailableRacks() as $rack)
                                        <div class="border-bottom">
                                            <div
                                                class="d-flex justify-content-between align-items-center p-3">
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $rack->name }}
                                                    </h6>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span
                                                        class="badge bg-info me-3">{{ $rack->available_quantity }}
                                                        units</span>
                                                    <button type="button"
                                                        class="btn btn-sm btn-primary"
                                                        onclick="toggleDrawer('drawer-{{ $product->id }}-{{ $rack->id }}')">
                                                        <i data-feather="eye"
                                                            class="feather-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Drawer Details (Hidden by default) -->
                                            <div id="drawer-{{ $product->id }}-{{ $rack->id }}"
                                                class="p-3 bg-light border-top"
                                                style="display: none;">
                                                <div class="px-2">
                                                    <div
                                                        class="d-flex justify-content-between fw-bold mb-2">
                                                        <div>Drawer</div>
                                                        <div>Quantity</div>
                                                    </div>
                                                    @foreach ($product->getDrawersInRack($rack->id) as $drawer)
                                                        <div
                                                            class="d-flex justify-content-between py-2 border-bottom">
                                                            <div>{{ $drawer->name }}</div>
                                                            <div><span
                                                                    class="badge bg-secondary">{{ $drawer->available_quantity }}</span>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <tr class="text-center">
            <td colspan="7">No Product Found</td>
        </tr>
        @endforelse
    </tbody>
</table>
<x-pagination :paginator="$products" />