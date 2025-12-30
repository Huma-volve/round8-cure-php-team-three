    @extends('layouts.master')

    @section('content')
        <div class="container">
            <div class="row">
            <div class="col-md-10 m-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            helpers
                            <span class="badge badge-info">{{count($helpers)}}</span>
                        </div>
                        <div class="float-right">
                          
                            <a href={{route('home')}} class="btn btn-primary">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('helper_message'))
                        <h4 class="alert alert-success text-center">{{session('helper_message')}}</h4>
                        @endif
                            <table class="table table-dark text-center table-responsive-lg">
                                <thead>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile Phone</th>
                                    <th>Profile Photo</th>
                                </thead>
                                <tbody>
                                    @foreach ($helpers as $item)
                                    <tr>
                                        <td>{{$item->id}}</td>   
                                        <td>{{$item->user->name}}</td>
                                        <td>{{$item->user->email}}</td>
                                        <td>{{$item->user->mobile_number}}</td>
                                        <td>{{$item->user->profile_photo}}</td>
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