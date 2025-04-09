@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Product Edit" button="Back to Product" back-button-route="admin.products.index" />

            <!-- /update -->
            <form id="updateForm" action="{{ route('admin.products.update', $product->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body add-product pb-0">
                        <div class="accordion-card-one accordion" id="accordionExample">
                            <div class="accordion-item">
                                <div class="accordion-header" id="headingOne">
                                    <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                        aria-controls="collapseOne">
                                        <div class="addproduct-icon">
                                            <h5><i data-feather="info" class="add-info"></i><span>Product Information</span>
                                            </h5>
                                            <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                    class="chevron-down-add"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <div class="add-newplus">
                                                        <label class="form-label">Category</label>
                                                        <a href="javascript:void(0);" data-bs-toggle="modal"
                                                            data-bs-target="#add-units-category"><i
                                                                data-feather="plus-circle"
                                                                class="plus-down-add"></i><span>Add
                                                                New</span></a>
                                                    </div>
                                                    <select class="select" name="category_id">
                                                        <option value="">Choose</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <div class="add-newplus">
                                                        <label class="form-label">Brand</label>
                                                        <a href="javascript:void(0);" data-bs-toggle="modal"
                                                            data-bs-target="#add-units-brand"><i data-feather="plus-circle"
                                                                class="plus-down-add"></i><span>Add New</span></a>
                                                    </div>
                                                    <select class="select" name="brand_id">
                                                        <option value="">Choose</option>
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand->id }}"
                                                                {{ $brand->id == $product->brand_id ? 'selected' : '' }}>
                                                                {{ $brand->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Product Name*</label>
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ $product->name }}">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Editor -->
                                        <div class="col-lg-12">
                                            <div class="input-blocks summer-description-box transfer mb-3">
                                                <label>Description</label>
                                                <textarea class="form-control h-100" rows="5" name="description">{{ $product->description }}</textarea>
                                                {{-- <p class="mt-1">Maximum 60 Characters</p> --}}
                                            </div>
                                        </div>
                                        <!-- /Editor -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-card-one accordion" id="accordionExample2">
                            <div class="accordion-item">
                                <div class="accordion-header" id="headingTwo">
                                    <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                        aria-controls="collapseTwo">
                                        <div class="text-editor add-list">
                                            <div class="addproduct-icon list icon">
                                                <h5><i data-feather="life-buoy" class="add-info"></i><span>Pricing &
                                                        Stocks</span></h5>
                                                <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                        class="chevron-down-add"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo"
                                    data-bs-parent="#accordionExample2">
                                    <div class="accordion-body">

                                        <div class="tab-content" id="pills-tabContent">
                                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                                aria-labelledby="pills-home-tab">
                                                <div class="row">
                                                    <div class="col-lg-4 col-sm-6 col-12">
                                                        <div class="input-blocks add-product">
                                                            <label>Purchase Price*</label>
                                                            <input type="text" class="form-control"
                                                                name="purchase_price"
                                                                value="{{ $product->purchase_price }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-6 col-12">
                                                        <div class="input-blocks add-product">
                                                            <label>Sale Price*</label>
                                                            <input type="text" class="form-control" name="sale_price"
                                                                value="{{ $product->sale_price }}">
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="accordion-card-one accordion" id="accordionExample3">
                                                    <div class="accordion-item">
                                                        <div class="accordion-header" id="headingThree">
                                                            <div class="accordion-button" data-bs-toggle="collapse"
                                                                data-bs-target="#collapseThree"
                                                                aria-controls="collapseThree">
                                                                <div class="addproduct-icon list">
                                                                    <h5><i data-feather="image"
                                                                            class="add-info"></i><span>Images</span></h5>
                                                                    <a href="javascript:void(0);"><i
                                                                            data-feather="chevron-down"
                                                                            class="chevron-down-add"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div id="collapseThree" class="accordion-collapse collapse show"
                                                            aria-labelledby="headingThree"
                                                            data-bs-parent="#accordionExample3">
                                                            <div class="accordion-body">
                                                                <div class="text-editor add-list add">
                                                                    <div class="col-lg-12">
                                                                        <div class="add-choosen gap-2">

                                                                            <!-- Thumbnail Upload -->
                                                                            <div class="input-blocks">
                                                                                <div class="image-upload"
                                                                                 style="width: 180px; height: 180px;">
                                                                                    <input type="file"
                                                                                     class="file-input" name="thumbnail">
                                                                                    <div class="image-uploads">
                                                                                        <i data-feather="plus-circle"
                                                                                         class="plus-down-add me-0"></i>
                                                                                        <h4>Add Thumbnail *</h4>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="phone-img d-none">
                                                                                    <img class="image-preview"
                                                                                     src="{{ $product->thumbnail }}" alt="image">
                                                                                    <a href="javascript:void(0);"
                                                                                     class="remove-product">
                                                                                        <i data-feather="x" class="x-square-add"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                            <!-- Existing Product Images -->
                                                                            @if ($product->images && count($product->images) > 0)
                                                                                @foreach ($product->images as $image)
                                                                                    <div class="input-blocks">
                                                                                        <div class="image-upload">
                                                                                            <input type="file" class="file-input" name="images[]">
                                                                                            <div class="image-uploads">
                                                                                                <i data-feather="plus-circle"
                                                                                                 class="plus-down-add me-2"></i>
                                                                                                <h4 class="mb-0">Add Images</h4>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="phone-img">
                                                                                            <img class="image-preview" src="{{ $image->url }}" alt="Product Image">
                                                                                            <a href="javascript:void(0);" class="remove-product"
                                                                                             data-id="{{ $image->id }}">
                                                                                                <i data-feather="x" class="x-square-add"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            @else
                                                                                <div class="input-blocks">
                                                                                    <div class="image-upload">
                                                                                        <input type="file" class="file-input" name="images[]">
                                                                                        <div class="image-uploads">
                                                                                            <i data-feather="plus-circle" class="plus-down-add me-2"></i>
                                                                                            <h4 class="mb-0">Add Images</h4>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="phone-img d-none">
                                                                                    <img class="image-preview"
                                                                                        src="" alt="image">
                                                                                    <a href="javascript:void(0);"><i
                                                                                            data-feather="x"
                                                                                            class="x-square-add remove-product"></i></a>
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
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="btn-addproduct mb-4">
                        <button type="button" class="btn btn-cancel me-2">Cancel</button>
                        <button type="submit" class="btn btn-submit" id="submit_btn">Update Product</button>
                    </div>
                </div>
            </form>
            <!-- /update -->

        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            feather.replace();

            $('.file-input').on('change', function(event) {
                var file = event.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    var $imageUpload = $(this).closest('.image-upload');
                    var $phoneImg = $imageUpload.siblings('.phone-img');
                    var $imagePreview = $phoneImg.find('.image-preview');

                    reader.onload = function(e) {
                        $imagePreview.attr('src', e.target.result);
                        $imageUpload.addClass('d-none');
                        $phoneImg.removeClass('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            });

            $('.remove-product').on('click', function() {
                var $phoneImg = $(this).closest('.phone-img');
                var $imageUpload = $phoneImg.siblings('.image-upload');
                var $fileInput = $imageUpload.find('.file-input');

                $fileInput.val('');
                $phoneImg.addClass('d-none');
                $imageUpload.removeClass('d-none');
                $phoneImg.find('.image-preview').attr('src', '');
                return false;
            });

            $('.image-preview').each(function () {
                var $imagePreview = $(this);
                var src = $imagePreview.attr('src');
                var $phoneImg = $imagePreview.closest('.phone-img');
                var $imageUpload = $phoneImg.siblings('.image-upload');

                if (src && src.trim() !== '') {
                    $phoneImg.removeClass('d-none'); 
                    $imageUpload.addClass('d-none'); 
                }
            });
        });


        $('#updateForm').submit(function(e) {
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
                        window.location.href = response.redirectUrl || `{{ route('admin.products.index') }}`;
                    }, 1000);
                } else {
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
    </script>
@endpush
