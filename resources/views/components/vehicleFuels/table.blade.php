<table class="table">
    <thead>
        <tr>
            <th class="no-sort">SL</th>
            <th>Date</th>
            <th>Reg. No</th>
            <th>ODO Meter</th>
            <th>Fuel Qty</th>
            <th>Fuel Rate</th>
            <th>Total Price</th>
            <th class="no-sort">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($entity as $fueling)
            <tr>
                @if ($entity instanceof \Illuminate\Pagination\LengthAwarePaginator || $entity instanceof \Illuminate\Pagination\Paginator)
                    <td>{{ $loop->iteration + $entity->firstItem() - 1 }}</td>
                @else
                    <td>{{ $loop->iteration }}</td>
                @endif
                <td>{{ $fueling->created_at?->format('d M, Y') }}</td>
                <td><span class="copyable">{{ $fueling->vehicle?->license_plate }}</span></td>
                <td>{{ $fueling->current_odometer }} KM</td>
                <td>{{ $fueling->fuel_qty }} Ltr.</td>
                <td>৳{{ $fueling->fuel_rate }}</td>
                <td>৳{{ $fueling->total_price }}</td>
                <td  class="action-table-data">
                    <div class="edit-delete-action">
                        <a href="{{ route('admin.vehicle-fuels.edit', $fueling->id) }}" class="me-2 p-2 mb-0">
                            <i data-feather="edit" class="feather-edit"></i>
                        </a>
                    <div/>
                </td>
            </tr>
        @empty
            <tr class="text-center">
                <td colspan="8">No Fueling Info Found</td>
            </tr>
        @endforelse

    </tbody>
</table>
@if ($entity instanceof \Illuminate\Pagination\LengthAwarePaginator || $entity instanceof \Illuminate\Pagination\Paginator)
    <x-pagination :paginator="$entity" />
@endif
