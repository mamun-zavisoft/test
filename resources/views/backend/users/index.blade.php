<?php $page = 'users'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            
            <x-breadcrumb title="User List" sub-title="Manage Your Users" permission="user-create" button="Add New User" button-route="users.create" />


            <!-- filter -->
            <div class="card table-list-card">
                    <x-filter />
                    <!-- /Filter -->

                    <div class="table-responsive" id="dataTable">
                        <x-users.table :users="$users"/>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.confirm-text').on('click', function(e) {
                e.preventDefault();

                // Get the associated delete form
                var deleteForm = $(this).closest('tr').find('.delete-form');

                // Show the SweetAlert confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, submit the form
                        deleteForm.submit();
                    }
                });
            });

        });
    </script>
@endpush
