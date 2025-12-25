@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        @php($doctorsList = isset($doctors) ? $doctors : collect())
                        <div class="float-left">
                            Doctors
                            <span class="badge badge-info">{{ $doctorsList instanceof \Illuminate\Support\Collection ? $doctorsList->count() : (is_array($doctorsList) ? count($doctorsList) : 0) }}</span>
                        </div>
                        <a href="{{ route('doctors.index') }}" class="btn btn-success float-right">View All Doctors</a>
                    </div>
                    <div class="card-body">
                        <table class="table table-dark text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($doctorsList as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3">لا يوجد أطباء لعرضهم</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection
