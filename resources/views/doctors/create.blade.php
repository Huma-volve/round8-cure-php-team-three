@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 m-auto">
              <h4 class="text-center">Create New Doctor</h4>
              <form action={{route('doctors.store')}} method="post" enctype="multipart/form-data" class="text-center">
                <input type="text" placeholder="ID" class="form-control mt-3">
                <input type="text" placeholder="Name" class="form-control mt-3">
                <input type="text" placeholder="Email" class="form-control mt-3">
                <input type="text" placeholder="Password" class="form-control mt-3">
                <input type="text" placeholder="Phone" class="form-control mt-3">
                <input type="text" placeholder="License Number" class="form-control mt-3">
                <input type="number" placeholder="Session Price" class="form-control mt-3">
                <input type="number" placeholder="Availability Slots" class="form-control mt-3">
                <input type="location" placeholder="Clinic Location" class="form-control mt-3">
                <input type="submit" class="btn btn-success btn-block mt-3 mb-3" value="Create">
              </form>
            </div>
        </div>
    </div>
@endsection