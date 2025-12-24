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
                        <div class="float-right">
                            <a href={{route('doctors.create')}} class="btn btn-success">Create New Doctor</a>
                            <a href={{route('home')}} class="btn btn-primary">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-dark text-center">
                            <thead>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Operations</th>
                            </thead>
                            <tbody>
                                @foreach ($doctors as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>{{$item->email}}</td>
                                    <td>
                                        <a href={{route('doctors.show')}} class="btn btn-success">show</a>
                                        <a href="" class="btn btn-warning">edit</a>
                                        <a href={{route('doctors.delete')}} class="btn btn-danger">delete</a>
                                    </td>
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