<table class="table">
    <thead>
        <tr>
            <th class="no-sort">SL</th>
            <th>Name</th>
            <th>Price</th>
            <th>Code</th>
            <th>Description</th>
            {{-- <th>Created On</th> --}}
            @permission(['service-chart-edit', 'service-chart-delete'])
            <th class="no-sort">Action</th>
            @endpermission
        </tr>
    </thead>
    <tbody id="tbody">
        @forelse ($serviceCharts as $serviceChart)
            <tr>
                <td>
                    {{ $loop->iteration + $serviceCharts->firstItem() - 1 }}
                </td>
                <td>{{ $serviceChart->name }}</td>
                <td>{{ number_format($serviceChart->price) }}</td>
                <td>{{ $serviceChart->code ?? 'N/A' }}</td>
                <td>{{ $serviceChart->description }}</td>
                {{-- <td>{{ $serviceChart->created_at?->format('d M Y') }}</td> --}}
                <td class="action-table-data">
                    <div class="edit-delete-action">
                        @permission('service-chart-update')
                        <a class="me-2 p-2" href="#" data-bs-toggle="modal"
                            data-bs-target="#edit-serviceChart-{{ $serviceChart->id }}">
                            <i data-feather="edit" class="feather-edit"></i>
                        </a>
                        @endpermission
                        @permission('service-chart-delete')
                        <form action="{{ route('admin.service-charts.destroy', $serviceChart->id) }}"
                            method="post" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <a class="confirm-text2 p-2" href="javascript:void(0);">
                                <i data-feather="trash-2" class="feather-trash-2"></i>
                            </a>
                        </form>
                        @endpermission
                    </div>
                </td>
            </tr>

            <!-- Edit Brand -->
            <div class="modal fade" id="edit-serviceChart-{{ $serviceChart->id }}">
                <div class="modal-dialog modal-dialog-centered custom-modal-two">
                    <div class="modal-content">
                        <div class="page-wrapper-new p-0">
                            <div class="content">
                                <div class="modal-header border-0 custom-modal-header justify-content-between">
                                    <div class="page-title">
                                        <h4>Edit Service Chart</h4>
                                    </div>
                                    <button type="button" class="close" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body custom-modal-body new-employee-field">
                                    <form class="editForm" data-id="{{ $serviceChart->id }}"
                                        action="{{ route('admin.service-charts.update', $serviceChart->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label class="form-label">Name*</label>
                                            <input type="text" class="form-control"
                                                value="{{ $serviceChart->name }}" name="name">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Price*</label>
                                            <input type="text" class="form-control"
                                                value="{{ $serviceChart->price }}" name="price">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Code</label>
                                            <input type="text" class="form-control"
                                                value="{{ $serviceChart->code }}" name="code">
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="input-blocks summer-description-box transfer mb-3">
                                                <label>Description</label>
                                                <textarea class="form-control h-100" rows="5" name="description">{{ $serviceChart->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer-btn">
                                            <button type="button" class="btn btn-cancel me-2"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-submit">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit Brand -->
            @empty
            <tr class="text-center">
            <td colspan="7">No Service Chart Found</td>
        </tr>
        @endforelse

    </tbody>
</table>
<x-pagination :paginator="$serviceCharts" />