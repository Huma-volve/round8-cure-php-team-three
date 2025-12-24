@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            Doctor
                            <span class="badge badge-info">1</span>
                        </div>
                        <div class="float-right">
                            <a href="" class="btn btn-success">Create New Doctor</a>
                            <a href={{route('doctor.index')}} class="btn btn-primary">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-dark text-center">
                            <thead>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Password</th>
                            </thead>
                            <tbody>
                                
                                <tr>
                                    <td>{{$doctor->id}}</td>
                                    <td>{{$doctor->name}}</td>
                                    <td>{{$doctor->email}}</td>
                                    <td>{{$doctor->password}}</td>
                                   
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection