@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Purchase List" sub-title="Manage Your Purchases" button="Add Purchase"
                button-route="admin.purchases.create" />

            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <label>Select Product</label>
                            <select class="form-control select" id="productSelect">
                                <option value="">Choose Product</option>
                                <!-- Products will be dynamically loaded here -->
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Select Rack</label>
                            <select class="form-control select" id="rackSelect">
                                <option value="">Choose Rack</option>
                                <!-- Racks will be dynamically loaded here -->
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>Select Drawer</label>
                            <select class="form-control select" id="drawerSelect">
                                <option value="">Choose Drawer</option>
                                <!-- Drawers will be dynamically loaded based on selected Rack -->
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-lg-4">
                            <label>Quantity</label>
                            <input type="number" class="form-control" id="quantityInput" placeholder="Enter Quantity">
                        </div>
                        <div class="col-lg-4 d-flex align-items-end">
                            <button class="btn btn-primary" id="assignDrawer">Assign to Drawer</button>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Rack</th>
                                    <th>Drawer</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="assignedDrawers">
                                <!-- Assigned products will be listed here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-purchase">
        <div class="modal-dialog purchase modal-dialog-centered stock-adjust-modal">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Add Purchase</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body">
                            <form action="purchase-list">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="input-blocks add-product">
                                            <label>Supplier Name</label>
                                            <div class="row">
                                                <div class="col-lg-10 col-sm-10 col-10">
                                                    <select class="select">
                                                        <option>Select Customer</option>
                                                        <option>Apex Computers</option>
                                                        <option>Dazzle Shoes</option>
                                                        <option>Best Accessories</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-sm-2 col-2 ps-0">
                                                    <div class="add-icon tab">
                                                        <a href="javascript:void(0);"><i data-feather="plus-circle"
                                                                class="feather-plus-circles"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="input-blocks">
                                            <label>Purchase Date</label>

                                            <div class="input-groupicon calender-input">
                                                <i data-feather="calendar" class="info-img"></i>
                                                <input type="text" class="datetimepicker" placeholder="Choose">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="input-blocks">
                                            <label>Product Name</label>
                                            <select class="select">
                                                <option>Choose</option>
                                                <option>Shoe</option>
                                                <option>Mobile</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="input-blocks">
                                            <label>Reference No</label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-blocks">
                                            <label>Product Name</label>
                                            <input type="text" placeholder="Please type product code and select">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="modal-body-table">
                                            <div class="table-responsive">
                                                <table class="table  datanew">
                                                    <thead>
                                                        <tr>
                                                            <th>Product</th>
                                                            <th>Qty</th>
                                                            <th>Purchase Price($)</th>
                                                            <th>Discount($)</th>
                                                            <th>Tax(%)</th>
                                                            <th>Tax Amount($)</th>
                                                            <th>Unit Cost($)</th>
                                                            <th>Total Cost(%)</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr>
                                                            <td class="p-5"></td>
                                                            <td class="p-5"></td>
                                                            <td class="p-5"></td>
                                                            <td class="p-5"></td>
                                                            <td class="p-5"></td>
                                                            <td class="p-5"></td>
                                                            <td class="p-5"></td>
                                                            <td class="p-5"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="input-blocks">
                                                <label>Order Tax</label>
                                                <input type="text" value="0">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="input-blocks">
                                                <label>Discount</label>
                                                <input type="text" value="0">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="input-blocks">
                                                <label>Shipping</label>
                                                <input type="text" value="0">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="input-blocks">
                                                <label>Status</label>
                                                <select class="select">
                                                    <option>Choose</option>
                                                    <option>Received</option>
                                                    <option>Pending</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="input-blocks summer-description-box">
                                        <label>Notes</label>
                                        <div id="summernote"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="modal-footer-btn">
                                        <button type="button" class="btn btn-cancel me-2"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
