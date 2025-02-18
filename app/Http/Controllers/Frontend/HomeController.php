<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Gallery;
use App\Models\Menu;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    //
    public function RestaurantDetails($id){
        $client = Client::find($id);
        $menus = Menu::where('client_id',$client->id)->get()->filter(function($menu){
            return $menu->products->isNotEmpty();
        });

        $gallerys = Gallery::where('client_id',$client->id)->get();
        return view('frontend.details_page',compact('client','menus','gallerys'));
    }

    
}