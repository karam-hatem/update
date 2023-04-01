<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(auth()->user()->role == 'admin'){
            $restaurants = Restaurant::all();
        }else{
            $restaurants = auth()->user()->restaurants;
        }

        return view('restaurant.index',["restaurants"=>$restaurants]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('restaurant.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $restaurant = new Restaurant;
        $restaurant->title = $request->input('title');
        $restaurant->description = $request->input('description');
        $restaurant->email = $request->input('email');
        $restaurant->address = $request->input('address');
        $restaurant->phone = $request->input('phone');
        if ($request->hasfile('image')) {
            $path = $request->file('image')->store('images/restaurants', 'public');
            $restaurant->image = 'storage/' . $path;
        } else {
            $restaurant->image = '';
        }
        $restaurant->user_id = ($request->input('user_id')?$request->input('user_id'):Auth::user()->id);
        $restaurant->save();
        //return back()->with('msg',"Restaurant Created");
        return redirect('/menu');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function edit(Restaurant $restaurant)
    {
        $users = User::all();
        return view('restaurant.edit', compact('restaurant','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restaurant $restaurant)
    {
    $restaurant->title = $request->input('title');
    $restaurant->description = $request->input('description');
    $restaurant->phone = $request->input('phone');
    $restaurant->email = $request->input('email');
    $restaurant->address = $request->input('address');
    $restaurant->status = $request->has('status') ? 1 : 0;
    $restaurant->user_id =(Auth::user()->id?Auth::user()->id:$request->input('user_id')); // Assuming the user is authenticated
    // Handle image upload
    if ($request->hasfile('image')) {
        $path = $request->file('image')->store('images/restaurants', 'public');
        $restaurant->image = 'storage/' . $path;
    } else {
        $restaurant->image = '';
    }

    $restaurant->save();

    return redirect()->route('restaurant.index')->with('success', 'Restaurant updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant)
    {
        //
    }

    public function all_restaurants_public()
    {
        return view("restaurant.all_restaurants_public",["restaurants"=>Restaurant::all()]);
    }

    public function menus(Restaurant $restaurant)
    {
        $menus = $restaurant->menus;

        return view('restaurant.menus', compact('menus'));
    }

    public function public_view(Restaurant $restaurant)
    {
        $menu = $restaurant->menus()->where('status',1)->first();
        $qr=QrCode::generate(route('public_view', ['restaurant' => $restaurant->id]));
        return view("restaurant.public_view",["menu"=>$menu,"qr"=>$qr]);
    }


}
