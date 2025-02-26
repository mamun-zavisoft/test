@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <x-breadcrumb title="Purchase List" sub-title="Manage Your Purchases" button="Add Purchase"
                button-route="admin.purchases.create" />

            <!-- /purchase list -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>
                        <div class="search-path">
                            <a class="btn btn-filter" id="filter_search">
                                <i data-feather="filter" class="filter-icon"></i>
                                <span><img src="{{ URL::asset('/build/img/icons/closes.svg') }}" alt="img"></span>
                            </a>
                        </div>
                        <div class="form-sort">
                            <i data-feather="sliders" class="info-img"></i>
                            <select class="select">
                                <option>Sort by Date</option>
                                <option>Newest</option>
                                <option>Oldest</option>
                            </select>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="user" class="info-img"></i>
                                        <select class="select">
                                            <option>Choose Supplier Name</option>
                                            <option>Apex Computers</option>
                                            <option>Beats Headphones</option>
                                            <option>Dazzle Shoes</option>
                                            <option>Best Accessories</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="stop-circle" class="info-img"></i>
                                        <select class="select">
                                            <option>Choose Status</option>
                                            <option>Received</option>
                                            <option>Ordered</option>
                                            <option>Pending</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="file" class="info-img"></i>
                                        <select class="select">
                                            <option>Enter Reference</option>
                                            <option>PT001</option>
                                            <option>PT002</option>
                                            <option>PT003</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i class="fas fa-money-bill info-img"></i>
                                        <select class="select">
                                            <option>Choose Payment Status</option>
                                            <option>Paid</option>
                                            <option>Partial</option>
                                            <option>Unpaid</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12 ms-auto">
                                    <div class="input-blocks">
                                        <a class="btn btn-filters ms-auto"> <i data-feather="search"
                                                class="feather-search"></i> Search </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="table-responsive product-list">
                        <table class="table  datanew list">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Supplier Name</th>
                                    <th>Reference</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Grand Total</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Created by</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchases as $purchase)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>{{ $purchase->supplier?->name }}</td>
                                    <td>{{ $purchase->reference_no }}</td>
                                    <td>{{ $purchase->date }}</td>
                                    <td>
                                        @if ($purchase->status == 'pending')
                                            <span class="badges status-badge bg-warning">Pending</span>
                                        @elseif ($purchase->status == 'received')
                                            <span class="badges status-badge bg-success">Received</span>
                                        @endif
                                    </td>
                                    <td>{{ $purchase->grand_total }}</td>
                                    <td>{{ $purchase->paid_amount }}</td>
                                    <td>{{ $purchase->due_amount }}</td>
                                    <td>
                                        @if ($purchase->grand_total == $purchase->paid_amount)
                                            <span class="badge-linesuccess">Paid</span>
                                        @elseif (true)
                                            <span class="badge-linewarning">Partial</span>
                                        @else
                                            <span class="badge-linedanger">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            <a class="me-2 p-2" href="javascript:void(0);">
                                                <i data-feather="eye" class="action-eye"></i>
                                            </a>
                                            <a class="me-2 p-2" data-bs-toggle="modal" data-bs-target="#edit-units">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a class="confirm-text p-2" href="javascript:void(0);">
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!-- /purchase list -->
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
														<a href="javascript:void(0);"><i data-feather="plus-circle" class="feather-plus-circles"></i></a>
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
										<button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
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
