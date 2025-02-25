@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Product List" sub-title="Manage Your Products" button="Back to Product"
                back-button-route="admin.products.index" />

            <!-- /add -->
            <form id="storeForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
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
                                                    <label class="form-label">Zone*</label>
                                                    <select class="select">
                                                        <option>Choose</option>
                                                        @foreach ($zones as $zone)
                                                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
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
                                                        <option>Choose</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}
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
                                                        <option>Choose</option>
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Product Name*</label>
                                                    <input type="text" class="form-control" name="name">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Editor -->
                                        <div class="col-lg-12">
                                            <div class="input-blocks summer-description-box transfer mb-3">
                                                <label>Description</label>
                                                <textarea class="form-control h-100" rows="5" name="description"></textarea>
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
                                                            <label>Purchase Price</label>
                                                            <input type="text" class="form-control"
                                                                name="purchase_price">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-sm-6 col-12">
                                                        <div class="input-blocks add-product">
                                                            <label>Sale Price</label>
                                                            <input type="text" class="form-control" name="sale_price">
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
                                                                            <div class="input-blocks">
                                                                                <div class="image-upload"
                                                                                    style="width: 180px; height: 180px;">
                                                                                    <input type="file"
                                                                                        class="file-input"
                                                                                        name="thumbnail">
                                                                                    <div class="image-uploads">
                                                                                        <i data-feather="plus-circle"
                                                                                            class="plus-down-add me-0"></i>
                                                                                        <h4>Add Thumbnail *</h4>
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
                                                                            <div class="input-blocks">
                                                                                <div class="image-upload">
                                                                                    <input type="file"
                                                                                        class="file-input"
                                                                                        name="images[]">
                                                                                    <div class="image-uploads">
                                                                                        <i data-feather="plus-circle"
                                                                                            class="plus-down-add me-0"></i>
                                                                                        <h4>Add Images</h4>
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
                                                                            <div class="input-blocks">
                                                                                <div class="image-upload">
                                                                                    <input type="file"
                                                                                        class="file-input"
                                                                                        name="images[]">
                                                                                    <div class="image-uploads">
                                                                                        <i data-feather="plus-circle"
                                                                                            class="plus-down-add me-0"></i>
                                                                                        <h4>Add Images</h4>
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
                        <button type="submit" class="btn btn-submit" id="submit_btn">Save Product</button>
                    </div>
                </div>
            </form>
            <!-- /add -->

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
                    var $phoneImg = $imageUpload.next('.phone-img');
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
                var $imageUpload = $phoneImg.prev('.image-upload');
                var $fileInput = $imageUpload.find('.file-input');

                $fileInput.val('');
                $phoneImg.addClass('d-none');
                $imageUpload.removeClass('d-none');
            });
        });


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
