<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    
    public function store(Request $request)
    {
        
       $user= Auth::user();
       $phone = $request->input('phone');
       $name = $request->input('name');

        if ($request->hasfile('image')) {
            $path = $request->file('image')->store('images/users', 'public');
            $user->image = 'storage/' . $path;
           
        } 
        $user->name= $name;
        $user->phone = $phone;
        $user->save();
        return back()->with('msg', "User Created");
    }
}
