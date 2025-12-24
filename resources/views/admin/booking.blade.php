@extends('admin.layouts.app')

@section('content')
<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">All Bookings</h5>
    <input type="text" id="search" class="form-control w-25" placeholder="Search patient name...">
  </div>

  <div class="card-body table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Patient</th>
          <th>Doctor</th>
          <th>Date</th>
          <th>Time</th>
          <th>Status</th>
          <th>Price</th>
          <th>Payment</th>
          <th></th>
        </tr>
      </thead>
      <tbody id="bookingsTable"></tbody>
    </table>
    <div class="text-center mt-2" id="pagination"></div>
  </div>
</div>

<!-- Payment Details Modal -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Payment Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="paymentDetailsBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
let page = 1;

function loadBookings(){
    $.get(`/api/admin/bookings/data?page=${page}&search=${$('#search').val()}`, res=>{
        $('#bookingsTable').html('');
        if(res.data.length === 0){
            $('#bookingsTable').html(`<tr><td colspan="9" class="text-center text-muted">No results found</td></tr>`);
        } else {
            res.data.forEach(b=>{
                $('#bookingsTable').append(`
                    <tr>
                        <td>${b.id}</td>
                        <td>${b.user.name}</td>
                        <td>${b.doctor.name}</td>
                        <td>${b.booking_date}</td>
                        <td>${b.booking_time}</td>
                        <td>${b.status}</td>
                        <td>$${b.price}</td>
                        <td>${b.payment ? b.payment.status : 'Unpaid'}</td>
                        <td>
                            <button onclick="deleteBooking(${b.id})" class="btn btn-sm btn-danger">Delete</button>
                            ${b.payment ? `<button onclick="showPaymentDetails(${b.id})" class="btn btn-sm btn-info ms-1">View Payment</button>` : ''}
                        </td>
                    </tr>
                `)
            });
        }

        $('#pagination').html(res.links);
        $('#pagination a').click(function(e){
            e.preventDefault();
            page = $(this).attr('href').split('page=')[1];
            loadBookings();
        });
    })
}

// Search
$('#search').on('keyup', ()=>{ page=1; loadBookings(); })

function deleteBooking(id){
    if(!confirm('Delete booking?')) return;
    $.ajax({
        url:`/api/admin/bookings/${id}`,
        type:'DELETE',
        data:{_token:$('meta[name=csrf-token]').attr('content')},
        success: loadBookings
    });
}

// Show Payment Details
function showPaymentDetails(bookingId){
    $.get(`/api/admin/bookings/${bookingId}/payment`, function(res){
        const booking = res.booking;
        const payment = res.payment;

        if(!payment){
            $('#paymentDetailsBody').html('<div class="text-center text-muted">No payment found.</div>');
        } else {
            const formattedDate = new Date(payment.created_at).toLocaleString('en-US',{
                day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'
            });

            $('#paymentDetailsBody').html(`
                <table class="table table-bordered">
                    <tr><th>Patient</th><td>${booking.user.name} (${booking.user.email})</td></tr>
                    <tr><th>Doctor</th><td>${booking.doctor.name} (${booking.doctor.email})</td></tr>
                    <tr><th>Amount</th><td>$${payment.amount}</td></tr>
                    <tr><th>Status</th><td>${payment.status}</td></tr>
                    <tr><th>Payment Method</th><td>${payment.payment_method.name}</td></tr>
                    <tr><th>Date</th><td>${formattedDate}</td></tr>
                </table>
            `);
        }

        var modal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
        modal.show();
    });
}

loadBookings();
</script>
@endpush
