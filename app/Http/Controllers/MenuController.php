<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->role == 'admin'){
            $menus = Menu::all();
        }else{
            $menus = auth()->user()->menus;
        }


        return view('menu.index',["menus"=>$menus]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->role == 'admin'){
            $restaurants = Restaurant::all();
        }else{
            $restaurants = auth()->user()->restaurants;
        }

        return view('menu.create',['restaurants'=>$restaurants]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $menu= new Menu;
        $menu->title= $request->input('title');
        $menu->restaurant_id = $request->input('restaurant_id');
        $menu->save();
        return back()->with('msg',"Menu Created");

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {

    }




    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        if(auth()->user()->role == 'admin'){
            $restaurants = Restaurant::all();
        }else{
            $restaurants = auth()->user()->restaurants;
        }
        return view('menu.edit', compact('menu', 'restaurants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        $menu->title = $request->input('title');
        $menu->status = $request->input('status') == 1;
        $menu->restaurant_id = $request->input('restaurant_id');
        $menu->save();
        return redirect()->route('menu.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        $menu->sections()->delete();
        $menu->delete();
        return back();
    }


    public function update_status(Menu $menu,Request $request)
    {

        if($request->input('status') == '1'){
            DB::table('menus')->update(['status' => 0]);
            $menu->status= 1;
            $menu->save();

        }
        if($request->input('status') == '0'){

            $menu->status= 0;
            $menu->save();

        }
        return back()->with('msg',"Menu Upadted");

    }

    function public_view(Menu $menu){
        return view("menu.public",["menu"=>$menu]);
    }
}
