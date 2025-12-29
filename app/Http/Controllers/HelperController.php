<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Helper;

class HelperController extends Controller
{
     public function index(){

        $helpers = Helper::all();

        return view ('helpers.index',['helpers' => $helpers]);
    }

     public function create()
    {
        $helper = User::doesntHave('doctor')->get();

        return view ('helpers.create',['helper' => $helper]);
    }

    public function store(Request $request)
    {

        $validation = $request->validate([
        
            'user_id' => [
                'required',
                'exists:users,id',
                'unique:helpers,user_id'
            ],
        ]);;

        Helper::create($validation);

        User::find($request->user_id)->assignRole('helper');

        return redirect()->route('home')
            ->with('helper_message', 'Helper assigned successfully');
    }
   
}
