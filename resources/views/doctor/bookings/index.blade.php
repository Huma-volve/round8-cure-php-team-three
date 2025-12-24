@extends('doctor.layouts.app')

@section('content')

<div class="form-check form-switch mb-3">
  <input class="form-check-input" type="checkbox" id="darkModeToggle">
  <label class="form-check-label" for="darkModeToggle">Dark Mode</label>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#1E1E1E; color:#E0E0E0;">
        <h3 class="card-title mb-0">My Bookings</h3>
        <select id="filterStatus" class="form-select w-auto">
            <option value="">All Status</option>
            <option value="Upcoming">Upcoming</option>
            <option value="Completed">Completed</option>
            <option value="Cancelled">Cancelled</option>
        </select>
    </div>



    <div class="card mb-3 shadow-sm">
        <div class="card-body d-flex flex-wrap gap-2 align-items-center">

        <input type="text" id="searchText" class="form-control w-25" placeholder="Search patient name...">

        <input type="date" id="fromDate" class="form-control">
        <input type="date" id="toDate" class="form-control">

        <button onclick="applySearch()" class="btn btn-primary px-4">Search</button>
        <button onclick="resetSearch()" class="btn btn-outline-secondary">Reset</button>

        </div>
    </div>



    <div class="card-body table-responsive">
        <table class="table table-striped table-hover mb-0">
            <thead style="background-color:#1A1A1A; color:#E0E0E0;">
                <tr>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="bookings-table">
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        <i class="fas fa-spinner fa-spin me-2"></i> Loading...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="rescheduleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="background-color:#2A2A2A; color:#E0E0E0; border-radius:0.5rem;">
      <div class="modal-header">
        <h5 class="modal-title">Reschedule Booking</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="rescheduleForm">
            <input type="hidden" id="rescheduleBookingId">
            <div class="mb-3">
                <label for="newDate" class="form-label">New Date</label>
                <input type="date" class="form-control" id="newDate" required>
            </div>
            <div class="mb-3">
                <label for="newTime" class="form-label">New Time</label>
                <input type="time" class="form-control" id="newTime" required>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="bookingToast" class="custom-toast">
    <div class="toast-body" id="toastMessage"></div>
</div>


@endsection

@push('styles')
<style>
.toastx{
    position: fixed;
    top: 18px;
    left: 50%;
    transform: translateX(-50%);
    min-width: 280px;
    background: #1f2933;
    color: #fff;
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 500;
    box-shadow: 0 20px 40px rgba(0,0,0,.4);
    opacity: 0;
    pointer-events: none;
    z-index: 9999;
}
.toastx.show{
    animation: toastIn .4s forwards, toastOut .4s forwards 3s;
}
@keyframes toastIn{
    from{opacity:0;transform:translate(-50%,-15px);}
    to{opacity:1;transform:translate(-50%,0);}
}
@keyframes toastOut{
    to{opacity:0;transform:translate(-50%,-15px);}
}
.toast-success{background:#16a34a;}
.toast-error{background:#dc2626;}

body.dark-mode {
    background-color: #121212 !important;
    color: #E0E0E0 !important;
}

body.dark-mode .card {
    background-color: #1E1E1E !important;
    color: #E0E0E0 !important;
    border-radius: 0.5rem;
}

body.dark-mode .table {
    color: #E0E0E0 !important;
    border-color: #333 !important;
}

body.dark-mode .table thead {
    background-color: #1A1A1A !important;
    border-bottom: 2px solid #444 !important;
}

body.dark-mode .table td, body.dark-mode .table th {
    border-color: #444 !important;
}

body.dark-mode .badge.bg-success { background-color: #388E3C !important; }
body.dark-mode .badge.bg-primary { background-color: #1976D2 !important; }
body.dark-mode .badge.bg-danger { background-color: #D32F2F !important; }
body.dark-mode .badge.bg-warning { background-color: #F9A825 !important; color:#121212; }

.btn-sm:hover { opacity: 0.85; transition:0.2s; }
#bookingToast {
    z-index: 1080;
    top: 70px;
    right: 20px;
    min-width: 250px;
    max-width: 350px;
}
tr.rescheduled {
    background-color: rgba(0, 123, 255, 0.1); /* خلفية فاتحة للـ Dark Mode */
}


</style>
@endpush

<audio id="successSound" preload="auto">
    <source src="https://cdn.pixabay.com/audio/2022/03/15/audio_7c9a3f1c7a.mp3" type="audio/mpeg">
</audio>


@push('scripts')
<script>
$(function(){

    $('#darkModeToggle').change(function(){
        $('body').toggleClass('dark-mode', this.checked);
    });

    $('#filterStatus').change(function(){
        loadBookings({
            status: $(this).val()
        });
    });


    $('#rescheduleForm').submit(function(e){
        e.preventDefault();
        let id = $('#rescheduleBookingId').val();
        let date = $('#newDate').val();
        let time = $('#newTime').val();

        $.ajax({
            url:`/api/doctor/bookings/${id}/reschedule`,
            type:'PATCH',
            data:{booking_date:date, booking_time:time},
            headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
            success:function(){
                bootstrap.Modal.getInstance(document.getElementById('rescheduleModal')).hide();
                loadBookings();
                showToast('Booking rescheduled successfully', 'success');
            },
            error:function(xhr){
                showToast('Error: '+xhr.responseText, 'danger');
            }
        });
    });

    loadBookings();
});

function loadBookings(filters = {}){
    $('#bookings-table').html(`<tr><td colspan="5" class="text-center">Loading...</td></tr>`);

    $.get('/api/doctor/bookings', filters, function(bookings){
        $('#bookings-table').html('');

        if(bookings.length === 0){
            $('#bookings-table').html(`<tr><td colspan="5" class="text-center text-muted">No results</td></tr>`);
            return;
        }

        bookings.forEach(b=>{
            $('#bookings-table').append(`
            <tr>
                <td>${b.user.name}</td>
                <td>${b.booking_date}</td>
                <td>${b.booking_time}</td>
                <td>${statusBadge(b.status)}</td>
                <td>$${b.price.toFixed(2)}</td>
                <td>${actionButtons(b)}</td>
            </tr>`);
        });
    });
}

function changeStatus(id, status) {
    $.ajax({
        url: `/api/doctor/bookings/${id}/status`,
        type: 'PATCH',
        data: { status },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },

        success: function () {
            if(status === 'Cancelled'){
                toast('Booking has been cancelled','error');
            }
            if(status === 'Completed'){
                toast('Booking completed successfully');
            }
            loadBookings();
        },
        error: function(){
            toast('Action failed','error');
        }
    });
}


function openRescheduleModal(id,date,time){
    $('#rescheduleBookingId').val(id);
    $('#newDate').val(date);
    $('#newTime').val(time);
    var myModal = new bootstrap.Modal(document.getElementById('rescheduleModal'));
    myModal.show();
}

function statusBadge(status){
    switch(status){
        case 'Upcoming': return '<span class="badge bg-success">Upcoming</span>';
        case 'Completed': return '<span class="badge bg-primary">Completed</span>';
        case 'Cancelled': return '<span class="badge bg-danger">Cancelled</span>';
        case 'Rescheduled': return '<span class="badge bg-info">Rescheduled</span>';
        default: return '<span class="badge bg-secondary">'+status+'</span>';
    }
}

function actionButtons(booking){
    if(booking.status === 'Upcoming' || booking.status === 'Rescheduled'){
        return `
            <button class="btn btn-sm btn-danger me-1" onclick="changeStatus(${booking.id},'Cancelled')"><i class="fas fa-times"></i></button>
            <button class="btn btn-sm btn-success me-1" onclick="changeStatus(${booking.id},'Completed')"><i class="fas fa-check"></i></button>
        `;
    }
    return '<span class="text-muted">No actions</span>';
}

function showToast(msg,type='success'){
    const toast = document.getElementById('bookingToast');
    toast.className = 'custom-toast ' + type;
    document.getElementById('toastMessage').innerText = msg;

    setTimeout(()=> toast.classList.add('show'),10);
    setTimeout(()=> toast.classList.remove('show'),3000);
}

function toast(msg,type='success'){
    const t = document.getElementById('toastx');
    t.className = 'toastx toast-'+type+' show';
    t.innerText = msg;
    document.getElementById('successSound').play();
}

function applySearch(){
    loadBookings({
        q: $('#searchText').val(),
        from: $('#fromDate').val(),
        to: $('#toDate').val()
    });
}

function resetSearch(){
    $('#searchText,#fromDate,#toDate').val('');
    loadBookings();
}



</script>
@endpush
