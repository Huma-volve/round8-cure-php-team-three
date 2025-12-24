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
                        <a href="" class="btn btn-success float-right">View All Doctors</a>
                    </div>
                    <div class="card-body">
                        <table class="table table-dark text-center">
                            <thead>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                            </thead>
                            <tbody>
                                 @foreach ($doctors as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->email}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
