@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            Doctors
                            <span class="badge badge-info">{{count($doctors)}}</span>
                        </div>
                        <a href={{route('doctors.index')}} class="btn btn-success float-right">View All Doctors</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection