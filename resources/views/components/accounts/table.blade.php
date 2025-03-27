<table class="table">
    <thead>
        <tr>
            <th class="no-sort">SL</th>
            <th>Name</th>
            <th>Type</th>
            <th>Balance</th>
            <th>Created On</th>
            <th class="no-sort">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($accounts as $account)
        <tr>
            <td>
                {{ $loop->iteration + $accounts->firstItem() - 1 }}
            </td>
            <td>{{ $account->name }}</td>
            <td>{{ $account->type == 1 ? 'Cash' : 'Bank' }}</td>
            <td>{{ $account->balance }}</td>
            <td>{{ $account->created_at->format('d M Y') }}</td>
            <td class="action-table-data">
                <div class="edit-delete-action">
                    <a class="me-2 p-2" href="#" data-bs-toggle="modal" data-bs-target="#edit-account-{{ $account->id }}">
                        <i data-feather="edit" class="feather-edit"></i>
                    </a>
                    <form action="{{ route('admin.accounts.destroy', $account->id) }}" method="post" class="delete-form">
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
        <div class="modal fade" id="edit-account-{{ $account->id }}">
            <div class="modal-dialog modal-dialog-centered custom-modal-two">
                <div class="modal-content">
                    <div class="page-wrapper-new p-0">
                        <div class="content">
                            <div class="modal-header border-0 custom-modal-header">
                                <div class="page-title">
                                    <h4>Edit Account</h4>
                                </div>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body custom-modal-body new-employee-field">
                                <form action="{{ route('admin.accounts.update', $account->id) }}" method="POST" class="editForm">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label">Name*</label>
                                        <input type="text" class="form-control" value="{{ $account->name }}"
                                            name="name">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Type*</label>
                                        <select class="select" name="type">
                                            <option value="1"{{ $account->type == 1 ? 'selected' : '' }}>Cash</option>
                                            <option value="2"{{ $account->type == 2 ? 'selected' : '' }}>Bank</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Balance</label>
                                        <input type="text" class="form-control"
                                            value="{{ $account->balance }}" name="balance" readonly>
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
                <td colspan="7">No Account Found</td>
            </tr>
        @endforelse
    </tbody>
</table>
<x-pagination :paginator="$accounts" />
