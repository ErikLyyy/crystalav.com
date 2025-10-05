<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Reseller;
use App\Traits\RenderMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

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
        $menu = $this->render_menu(Menu::all());
        View::share('menu', $menu);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $about_us = About::orderBy('id', 'desc')->get();
        $service = Menu::where('title', 'Service')->firstOrFail();
        $list_service = Menu::where('parent_id', $service->id)->get();
        $resellers = Reseller::all();
        return view('home', compact('about_us', 'list_service', 'resellers'));
    }
    public function category($slug)
    {
        $category_parent = Menu::where('slug', $slug)->firstOrFail(); //menu
        $list_category = Category::where('menu_id', $category_parent->id)->get();
        if ($category_parent->layout == 1) {
            return view('home.cat_layout1', compact('category_parent', 'list_category'));
        } elseif ($category_parent->layout == 2) {
            return view('home.cat_layout2', compact('category_parent', 'list_category'));
        } elseif ($category_parent->layout == 3) {
            return view('home.cat_layout3', compact('category_parent', 'list_category'));
        } elseif ($category_parent->layout == 4) {
            return view('home.cat_layout4', compact('category_parent', 'list_category'));
        } elseif ($category_parent->layout == 5) {
            return view('home.cat_layout5', compact('category_parent', 'list_category'));
        } elseif ($category_parent->layout == 6) {
            return view('home.full_technical_support', compact('category_parent', 'list_category'));
        }
    }
}
