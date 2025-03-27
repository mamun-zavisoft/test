@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Product List" sub-title="Manage Your Products" button="Add Product"
                button-route="admin.products.create" />

            <!-- /Filter -->
            <div class="card table-list-card">
                <x-filter>
                    <div class="col-lg-4 col-sm-3 col-12" style="width: 200px;">
                        <div class="mb-3 add-product">
                            <div class="add-newplus">
                                <label class="form-label">Category</label>
                            </div>
                            <select class="select filter-input" name="category_id">
                                <option value="">Choose</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected($category->id == request()->category_id)>{{ $category->name }}</option>
                                @endforeach    
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-3 col-12 ms-2" style="width: 200px;">
                        <div class="mb-3 add-product">
                            <div class="add-newplus">
                                <label class="form-label">Brand</label>
                            </div>
                            <select class="select filter-input" name="brand_id">
                                <option value="">Choose</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" @selected($brand->id == request()->brand_id)>{{ $brand->name }}</option>
                                @endforeach    
                            </select>
                        </div>
                    </div>
                </x-filter>


                <div class="card-body">

                    <!-- /product list -->
                    <div class="table-responsive product-list" id="dataTable">
                        <x-products.table :products="$products" />
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function toggleDrawer(drawerId) {
            const drawer = document.getElementById(drawerId);
            if (drawer) {
                if (drawer.style.display === "none") {
                    drawer.style.display = "block";
                } else {
                    drawer.style.display = "none";
                }
            }
        }
    </script>
@endpush
