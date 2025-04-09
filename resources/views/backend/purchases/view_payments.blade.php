<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div>
                <h5>Supplier:
                    {{ $purchase->supplier?->name }}
                </h5>
                <p class="text-muted">Date:
                    {{ $purchase->date }}
                </p>
            </div>
            <div class="text-start">
                <div class="mb-2">
                    <span class="fw-bold">Total Amount:</span>
                    <span class="badge bg-success ms-2">{{ number_format($purchase?->grand_total) }}</span>
                </div>
                <div class="mb-2">
                    <span class="fw-bold">Paid Amount:</span>
                    <span class="badge bg-success ms-2">{{ number_format($purchase?->paid_amount) }}</span>
                </div>
                <div>
                    <span class="fw-bold">Due Amount:</span>
                    <span class="badge bg-info ms-2">{{ number_format($purchase?->due_amount) }}</span>
                </div>
                <div class="mt-2">
                    <span class="fw-bold">Paid Status:</span>
                    <span class="badge {{ $purchase?->paid_status == 'full_paid' ? 'bg-success' : 'bg-warning' }} ms-2">
                        {{ $purchase?->paid_status }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Purchase Details -->
<div class="mt-4">
    <h5 class="mb-3">Payments</h5>
    <div class="d-flex fw-bold border-bottom pb-2">
        <div class="flex-grow-1" style="width: 150px;">Payment Type</div>
        <div class="text-center" style="width: 110px;">Amount</div>
        <div class="text-center" style="width: 110px;">Date</div>
        <div class="text-center" style="width: 110px;">Note</div>
    </div>

    @forelse ($purchase?->payment?->paymentDetails as $paymentDetail)
        <div class="d-flex py-2 border-bottom">
            <div class="flex-grow-1" style="width: 150px;">{{ $paymentDetail?->account?->name }}
            </div>
            <div class="text-center" style="width: 110px;">{{ $paymentDetail?->amount }}</div>
            <div class="text-center" style="width: 110px;">{{ $paymentDetail?->date }}</div>
            <div class="text-center" style="width: 110px;">{!! $paymentDetail?->note ?? 'N/A' !!}
            </div>
        </div>
    @empty
        <div class="py-2 text-center">
            <p class="text-muted">No payment found</p>
        </div>
    @endforelse
</div>

<div class="text-end mt-3">
    <button type="button" class="btn btn-primary add-payment-btn">Add Payment</button>
    <button type="button" class="btn btn-primary" style="display: none;" id="close-btn">Close Form
    </button>
</div>
<div class="payment-form-container mt-3" style="display: none;">
    <form class="payment-form" method="POST" action="{{ route('admin.purchases.payment', $purchase->id) }}">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="payment_type" class="form-label">Payment Type</label>
                <select class="form-select payment_type" name="payment_type" required>
                    <option value="">Select Payment Type
                    </option>
                    <option value="partial_paid">Partial Paid
                    </option>
                    <option value="full_paid">Full Paid
                    </option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="payment_type" class="form-label">Accounts</label>
                <select class="form-select" id="payment_account" name="account_id" required>
                    <option value="">Select Account
                    </option>
                    @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">
                            {{ $account->name }}({{ number_format($account->balance) }} ৳)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3 amount-field" style="display: none;">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter amount">
            </div>

            <div class="col-md-6 mb-3">
                <label for="payment_date" class="form-label">Payment Date</label>
                <input type="date" class="form-control" id="payment_date" name="payment_date"
                    value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="note" class="form-label">Note</label>
                <input type="text" class="form-control" id="note" name="note"
                    placeholder="Payment note (optional)">
            </div>

            <div class="col-12 text-end">
                <button type="button" class="btn btn-secondary me-2 cancel-payment-btn">Cancel</button>
                <button type="submit" class="btn btn-success" id="submit_btn">Submit
                    Payment</button>
            </div>
        </div>
    </form>
</div>

</div>

<script>
    $(document).ready(function() {
        $('.add-payment-btn').on('click', function() {
            $('.payment-form-container').show();
            $(this).hide();
            $('#close-btn').show();
        });

        $('#close-btn').on('click', function() {
            $(this).hide();
            $('.payment-form-container').hide();
            $('.add-payment-btn').show();
        })

        // Hide payment form when Cancel button is clicked
        $('.cancel-payment-btn').on('click', function() {
            $('.payment-form-container').hide();
            $('.add-payment-btn').show();
            $('.amount-field').hide();
            $('#close-btn').hide();
        });

        // Show/hide amount field based on payment type selection
        $('.payment_type').on('change', function() {
            if ($(this).val() === 'partial_paid') {
                $('.amount-field').show();
                $('.amount').attr('required', true);
            } else {
                $('.amount-field').hide();
                $('.amount').attr('required', false);
            }
        });

        // Form submission with AJAX
        $('.payment-form').submit(function(e) {
                e.preventDefault();

                // Disable submit button to prevent multiple submissions
                $('#submit_btn').attr('disabled', true);

                let formData = new FormData(this);

                // For full payment, set amount to due amount
                if ($('.payment_type').val() === 'full_paid') {
                    formData.append('amount', '{{ $purchase->due_amount }}');
                }

                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                }).done(function(response) {
                    if (response.type == 'success') {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.href = response.redirectUrl;
                        }, 1000);
                    } else {
                        $('#submit_btn').prop('disabled', false);
                        toastr.error(response.message);
                    }
                }).fail(function(xhr) {
                    $('#submit_btn').attr('disabled', false);
                    let response = xhr.responseJSON;
                    if (response && response.errors) {
                        $.each(response.errors, function(key, value) {
                            toastr.error(value);
                        });
                    }
                    if (response && response.message) {
                        toastr.error(response.message);
                    }
                });
            });
    });
</script>
