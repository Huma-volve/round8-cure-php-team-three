@extends('layouts.master')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-6">
    <div class="card">
     <div class="card-header">
            {{-- @php($doctorsList = isset($doctors) ? $doctors : collect()) --}}
            <div class="float-left">
                Doctors
                <span class="badge badge-info">
                    {{-- {{ $doctorsList instanceof \Illuminate\Support\Collection ? $doctorsList->count() : (is_array($doctorsList) ? count($doctorsList) : 0) }} --}}
                    {{$doctors->count()}}
                </span>
            </div>
            <a href="{{ route('doctors.index') }}" class="btn btn-success float-right">View All Doctors</a>
     </div>
      <div class="card-body">
          @if (session('doctor_message'))
                <h4 class="alert alert-success text-center">{{session('doctor_message')}}</h4>
            @endif

            <table class="table table-dark text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                @if ($doctors)
                    @foreach ($doctors as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr class="alert alert-danger text-center">No Doctors Yet</tr>
                @endif
                </tbody>
            </table>
      </div>
        
    </div>
</div>

<div class="col-md-6">
    <div class="card">
     <div class="card-header">
            <div class="float-left">
                Helpers
                <span class="badge badge-info">
                    {{$helpers->count()}}
                </span>
            </div>
            <a href={{ route('helper.index') }} class="btn btn-success float-right">View All Helpers</a>
     </div>
      <div class="card-body">
            @if (session('helper_message'))
                <h4 class="alert alert-success text-center">{{session('helper_message')}}</h4>
            @endif
            <table class="table table-dark text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                @if ($helpers)
                    @foreach ($helpers as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr class="alert alert-danger text-center">No Helpers Yet</tr>
                @endif
                </tbody>
            </table>
      </div>
        
    </div>
</div>
</div>
</div>
@endsection
