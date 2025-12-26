@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 m-auto">

            <div class="card mt-5 mx-auto">
                <div class="card-header">
                    <h3 class="mb-4 mt-2 float-left">Edit Availability Slots</h3>
                    <a href="{{ route('profile.view') }}" class="btn btn-primary float-right mt-2">
                        Back
                    </a>
                </div>

                <div class="card-body">

                    <form action={{ route('update.slots') }} method="POST">
                        @csrf
                        @method('put')

                        <div id="slots-wrapper">
                            @foreach($doctor->availability_slots as $index => $slot)
                                <div class="card mb-3 p-3">
                                    <div class="form-group">
                                        <label>Day</label>
                                        <input type="text"
                                               name="availability_slots[{{ $index }}][day]"
                                               class="form-control"
                                               value="{{ $slot['day'] }}">
                                    </div>

                                    <div class="form-group">
                                        <label>From</label>
                                        <input type="time"
                                               name="availability_slots[{{ $index }}][from]"
                                               class="form-control"
                                               value="{{ $slot['from'] }}">
                                    </div>

                                    <div class="form-group">
                                        <label>To</label>
                                        <input type="time"
                                               name="availability_slots[{{ $index }}][to]"
                                               class="form-control"
                                               value="{{ $slot['to'] }}">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-success">
                            Save Slots
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
