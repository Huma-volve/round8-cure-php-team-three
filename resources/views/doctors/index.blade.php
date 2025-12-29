    @extends('layouts.master')

    @section('content')
        <div class="container">
            <div class="row">
            <div class="col-md-10 m-auto">
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
                        @if (session('doctor_message'))
                        <h4 class="alert alert-success text-center">{{session('doctor_message')}}</h4>
                        @endif
                            <table class="table table-dark text-center table-responsive-lg">
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
                                        <td>{{$item->user->name}}</td>
                                        <td>{{$item->user->email}}</td>
                                        <td class="d-flex justify-content">
                                            <a href={{route('doctors.show',$item->id)}} class="btn btn-success mr-2">show</a>
                                            <a href={{route('doctors.edit',$item->id)}} class="btn btn-warning">edit</a>
                                            <form action={{route('doctors.destroy',$item->id)}} method="post" enctype="multipart/form-data">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-danger ml-2 mr-2">delete</button>
                                            </form>
                                            {{-- <a href={{route('assign.helpers',$item->id)}} class="btn btn-info mr-l">Assign Helpers</a> --}}
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