@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 m-auto">  
              <h4 class="text-center">Assign New Helper <span class="btn btn-outline-primary"><a href={{route('home')}}>Back</a></span></h4>
              <form action={{route('helper.store')}} method="post" enctype="multipart/form-data" class="text-center">
                @csrf
                
                 <select name="user_id" id="user_id" class="form-control mt-3">
                    <option value="">Choose Helper</option>
               
                    @foreach ($helper as $item)
                    <option value={{$item->id}}>{{$item->name}}</option>
                    @endforeach
              
                </select>
                   @error('user_id')
                    <h4 class="alert alert-danger text-center">{{$message}}</h4>
                @enderror 
                <input type="submit" class="btn btn-success btn-block mt-3 mb-3" value="Create">
              </form>
            </div>
        </div>
    </div>
@endsection