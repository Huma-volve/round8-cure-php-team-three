@extends('admin.layouts.app')

@section('content')

<h3 class="mb-3">Payments</h3>

<div class="card mb-3">
  <div class="card-body d-flex gap-2">
      <input type="text" id="search" class="form-control w-25" placeholder="Search patient or doctor">
      <select id="filterStatus" class="form-select w-auto">
          <option value="">All</option>
          <option value="pending">Pending</option>
          <option value="success">Success</option>
          <option value="failed">Failed</option>
      </select>
      <button onclick="loadPayments()" class="btn btn-primary">Search</button>
  </div>
</div>

<table class="table table-hover">
<thead>
<tr>
<th>Patient</th><th>Doctor</th><th>Method</th><th>Amount</th><th>Status</th><th>Date</th><th>Action</th>
</tr>
</thead>
<tbody id="payments-table"></tbody>
</table>

@endsection

@push('scripts')
<script>
function loadPayments(){
    $.get('/api/admin/payments/data',{
        q:$('#search').val(),
        status:$('#filterStatus').val()
    },function(data){
        $('#payments-table').html('');
        data.forEach(p=>{
            $('#payments-table').append(`
            <tr>
                <td>${p.booking.user.name}</td>
                <td>${p.booking.doctor.name}</td>
                <td>${p.payment_method.name}</td>
                <td>${p.amount}$</td>
                <td><span class="badge bg-${p.status=='success'?'success':(p.status=='pending'?'warning':'danger')}">${p.status}</span></td>
                <td>${new Date(p.created_at).toLocaleString()}</td>
                <td><button onclick="deletePayment(${p.id})" class="btn btn-sm btn-danger">Delete</button></td>
            </tr>`);
        });
    });
}

function deletePayment(id){
    if(!confirm('Delete payment?')) return;
    $.ajax({
        url:'/api/admin/payments/'+id,
        type:'DELETE',
        headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},
        success:loadPayments
    });
}

loadPayments();
</script>
@endpush
