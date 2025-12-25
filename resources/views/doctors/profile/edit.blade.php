@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Edit Availability Slots</h3>

    <form action={{route('update.slots') }} method="POST">
        @csrf
        <div id="slots-wrapper">
            @foreach($doctor->availability_slots as $index => $slot)
                <div class="card mb-3 p-3">
                    <div class="form-group">
                        <label>Day</label>
                        <input type="text" name="availability_slots[{{ $index }}][day]"
                               class="form-control"
                               value="{{ $slot['day'] }}">
                    </div>

                    <div class="form-group">
                        <label>From</label>
                        <input type="time" name="availability_slots[{{ $index }}][from]"
                               class="form-control"
                               value="{{ $slot['from'] }}">
                    </div>

                    <div class="form-group">
                        <label>To</label>
                        <input type="time" name="availability_slots[{{ $index }}][to]"
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
@endsection
