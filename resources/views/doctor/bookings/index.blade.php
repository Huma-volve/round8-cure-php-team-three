@extends('doctor.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">My Bookings</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="bookings-table"></tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    loadBookings();
});

function loadBookings() {
    $.get('/api/doctor/bookings', function (bookings) {
        $('#bookings-table').html(`
            <tr>
                <td colspan="5" class="text-center">Loading...</td>
            </tr>
        `);

        bookings.forEach(b => {
            $('#bookings-table').append(`
                <tr>
                    <td>${b.user.name}</td>
                    <td>${b.booking_date}</td>
                    <td>${b.booking_time}</td>
                    <td>${statusBadge(b.status)}</td>
                    <td>${actionButtons(b)}</td>
                </tr>
            `);
        });
    });
}

function changeStatus(id, status) {
    $.ajax({
        url: `/api/doctor/bookings/${id}/status`,
        type: 'PATCH',
        data: { status },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function () {
            loadBookings();
        }
    });
}

function statusBadge(status) {
    switch (status) {
        case 'Upcoming':
            return '<span class="badge badge-success">Upcoming</span>';
        case 'Completed':
            return '<span class="badge badge-primary">Completed</span>';
        case 'Cancelled':
            return '<span class="badge badge-danger">Cancelled</span>';
        default:
            return status;
    }
}

function actionButtons(booking) {
    if (booking.status === 'Upcoming') {
        return `
            <button class="btn btn-sm btn-danger"
                onclick="changeStatus(${booking.id}, 'Cancelled')">
                Cancel
            </button>

            <button class="btn btn-sm btn-success"
                onclick="changeStatus(${booking.id}, 'Completed')">
                Complete
            </button>
        `;
    }

    return '<span class="text-muted">No actions</span>';
}


</script>
@endpush
