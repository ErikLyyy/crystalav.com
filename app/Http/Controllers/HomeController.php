<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Menu;
use App\Models\Reseller;
use App\Traits\RenderMenu;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use RenderMenu;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $menu = $this->render_menu(Menu::all());
        $about_us = About::orderBy('id', 'desc')->get();
        $service = Menu::where('title', 'Service')->firstOrFail();
        $list_service = Menu::where('parent_id', $service->id)->get();
        $resellers = Reseller::all();

        return view('home', compact('menu', 'about_us', 'list_service', 'resellers'));
    }
}
