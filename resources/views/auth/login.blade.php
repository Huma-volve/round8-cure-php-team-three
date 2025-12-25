<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
        
   <div class="container">
        <div class="row mt-5">
            <div class="col-md-8 mt-5 mx-auto mb-4">
                <h4 class="m-auto text-center mb-5">Login Page</h4>
                
                @if (session('login_message'))
                    <h4 class="alert alert-danger text-center">{{session('login_message')}}</h4>
                @endif
                
                <form action={{route('login')}} method="Post">
                    @csrf
                    <input type="email" name="email" class="form-control mt-5 mb-4" placeholder="Enter Your Email">

                     @error('email')
                    <h4 class="alert alert-danger text-center">{{$message}}</h4>
                    @enderror   
                    
                    <input type="password" name="password" class="form-control mt-3 mb-4" placeholder="Enter Your Password">

                     @error('password')
                    <h4 class="alert alert-danger text-center">{{$message}}</h4>
                     @enderror 

                    <input type="submit" value="Login" class=" btn btn-success btn-block mt-3">
                </form>
            </div>
        </div>

        
        
        {{-- $user = App\Models\User::where('email','doctor@example.com')->first();
$user = App\Models\User::where('email', 'freeda.kshlerin@example.net')->first();
$user->password = Hash::make('12345678');
$user->save(); --}}


<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>        
</body>
</html>