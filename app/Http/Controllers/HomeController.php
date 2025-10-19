<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Mail\RequestMail;
use App\Models\About;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Keysearch;
use App\Models\Menu;
use App\Models\Product;
use App\Models\Reseller;
use App\Models\Sidebar;
use App\Traits\RenderMenu;
use App\Traits\RenderSidebarFilter;
use App\Traits\RenderSidebarSubcategory;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    use RenderMenu, RenderSidebarSubcategory, RenderSidebarFilter;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $menu_navbar = $this->render_menu(Menu::all());
        View::share('menu_navbar', $menu_navbar);
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
    public function category($menu_slug)
    {
        $menu = Menu::where('slug', $menu_slug)->firstOrFail(); //menu
        $list_category = Category::where('menu_id', $menu->id)->get();
        if ($menu->layout == 1) {
            return view('home.cat_layout1', compact('menu', 'list_category'));
        } elseif ($menu->layout == 2) {
            return view('home.cat_layout2', compact('menu', 'list_category'));
        } elseif ($menu->layout == 3) {
            return view('home.cat_layout3', compact('menu', 'list_category'));
        } elseif ($menu->layout == 4) {
            return view('home.cat_layout4', compact('menu', 'list_category'));
        } elseif ($menu->layout == 5) {
            return view('home.cat_layout5', compact('menu', 'list_category'));
        } elseif ($menu->layout == 6) {
            return view('home.full_technical_support', compact('menu', 'list_category'));
        }
    }
    public function list_product(Request $request, $menu_slug = "", $category_slug = "")
    {
        Paginator::useBootstrapFive();
        $orderBy = "id";
        $orderByValue = "desc";
        $select_value = 0;
        if ($request->has('select_value')) {
            $select_value = (int) $request->input('select_value');
            $orderBy = "name";
            if ($select_value === 1) {
                $orderByValue = "asc";
            } elseif ($select_value === 2) {
                $orderByValue = "desc";

            } elseif ($select_value === 0) {
                $orderBy = "id";
                $orderByValue = "desc";
            }
        }

        $query = Product::where('warehouse_status', 'In Stock')
            ->where('privacy', 'Public');

        if ($menu_slug != "" && $category_slug != "") {
            $menu = Menu::where('slug', $menu_slug)->firstOrFail();
            $category = Category::where('slug', $category_slug)->firstOrFail();
            $query->where('category_id', $category->id);
        }

        $list_product_for_subcategory = (clone $query)->get();

        $s = $request->input('s', '');
        $sub_cat_slug = $request->input('sub_cat_slug', '');
        if ($s && !$sub_cat_slug) {
            $keysearch = Keysearch::where('keyword', $s)->first();
            if ($keysearch) {
                $keysearch->increment('search_count');
            }
            $query->where(function ($q) use ($s) {
                $q->whereHas('keysearches', function ($q2) use ($s) {
                    $q2->where('keyword', 'like', "%{$s}%");
                })
                    ->orWhere('name', 'like', "%{$s}%");
            });
        }


        if ($sub_cat_slug) {
            $query->whereHas('sidebars', function ($q) use ($sub_cat_slug) {
                $q->where('slug', $sub_cat_slug)->where('type', 'subcategory');
            });
        }

        $list_product_for_filter = (clone $query)->get();

        $list_slug = $request->input('list_slug', []);
        if (!empty($list_slug)) {
            $query->whereHas('sidebars', function ($q) use ($list_slug) {
                $q->whereIn('slug', $list_slug)->where('type', 'filter');
            });
        }

        $list_product_without_pagin = (clone $query)->get();
        $countProduct = count($list_product_without_pagin);

        $query->orderBy($orderBy, $orderByValue);
        $list_product = $query->paginate(36);

        foreach ($list_product as $product) {
            foreach ($product->media as $media) {
                if ($media->media_type == "thumbnail") {
                    $product->thumbnail = $media->file_path;
                    $product['category_slug'] = $product->category->slug;
                    $product['menu_slug'] = $product->category->menu->slug;
                }
            }
        }

        $sidebar['list_subcategory'] = [];
        $sidebar['list_filter'] = [];
        foreach ($list_product_for_filter as $product) {
            foreach ($product->sidebars as $item) {
                if ($item->type == "filter") {
                    $parent_filter = Sidebar::where('id', $item->parent_id)->firstOrFail();
                    $sidebar['list_filter'][$parent_filter->title] = $parent_filter;
                    $sidebar['list_filter'][$item->title] = $item;
                }
            }
        }
        foreach ($list_product_for_subcategory as $product) {
            if ($product->sidebars) {
                foreach ($product->sidebars as $item) {
                    if ($item->type == "subcategory") {
                        $sidebar['list_subcategory'][$item->title] = $item;
                    }
                }
            }
        }
        $list_subcategory = $this->render_sidebar_subcat($sidebar['list_subcategory'], $sub_cat_slug);
        $list_filter = $this->render_sidebar_filter($sidebar['list_filter'], $list_slug);

        if ($request->ajax) {
            $pagin = $list_product->appends([
                's' => $s,
                'select_value' => $select_value,
                'list_slug' => $list_slug,
                'sub_cat_slug' => $sub_cat_slug
            ])->links('vendor.pagination.bootstrap-5')->render();

            $result = [
                'list_product' => $list_product,
                'list_filter' => $list_filter,
                'countProduct' => $countProduct,
                'pagin' => $pagin,
                'list_slug' => $list_slug,
                'sub_cat_slug' => $sub_cat_slug,
                's' => $s
            ];
            return response()->json($result);
        } else {
            if (isset($category)) {
                return view('home.list_product', compact(
                    'menu',
                    'category',
                    'countProduct',
                    'list_product',
                    'list_subcategory',
                    'list_filter',
                    'list_slug',
                    'sub_cat_slug',
                    's'
                ));
            } else {
                return view('home.list_product', compact(
                    'countProduct',
                    'list_product',
                    'list_subcategory',
                    'list_filter',
                    'list_slug',
                    'sub_cat_slug',
                    's'
                ));
            }
        }
    }
    public function suggestions(Request $request)
    {
        $list_product = Product::where('warehouse_status', 'In Stock')->where('privacy', 'Public');
        if ($request->get('category_id') != "") {
            $category = Category::find($request->get('category_id'));
            if ($category) {
                $list_product->where('category_id', $category->id);
            }
        }
        $k = $request->get('q');
        $list_product = $list_product->with([
            'keysearches' => function ($q) use ($k) {
                $q->where('keyword', 'like', "%{$k}%")
                    ->orderBy('search_count', 'desc')
                    ->limit(10);
            }
        ])->get();

        $resultes = [];

        foreach ($list_product as $product) {
            foreach ($product->keysearches as $keysearch) {
                $resultes[$keysearch->keyword] = $keysearch->keyword;
            }
        }

        $resultes = array_values($resultes);

        return response()->json([
            'type' => 'suggestions',
            'data' => $resultes
        ]);
    }
    public function add_cart_ajax(Request $request)
    {
        $id = $request->id;
        $qty = $request->qty;
        $product = Product::find($id);
        foreach ($product->media as $item) {
            if ($item->media_type == 'thumbnail') {
                $thumbnail = $item->file_path;
            }
        }

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => 0,
            'options' => [
                'thumbnail' => $thumbnail,
                'url' => url('category/' . $product->category->menu->slug . '/' . $product->category->slug . '/' . $product->slug)
            ]
        ]);
        $result = ['cartCount' => Cart::count()];
        return response()->json($result);
    }
    public function detail_product(Request $request, $menu_slug, $category_slug, $product_slug)
    {

        $product = Product::where('slug', $product_slug)->first();
        foreach ($product->media as $media) {
            if ($media->media_type != "thumbnail") {
                $product->media->file_path = $media->file_path;
            }
        }
        // dd($product->media);

        $relative_products = Product::whereHas('category', function ($q) use ($category_slug) {
            $q->where('slug', $category_slug);
        })
            ->where('warehouse_status', 'In Stock')
            ->where('privacy', 'Public')
            ->where('slug', '!=', $product_slug)
            ->orderBy('id', 'desc')
            ->get();

        foreach ($relative_products as $relative_product) {
            foreach ($relative_product->media as $media) {
                if ($media->media_type == "thumbnail") {
                    $relative_product->thumbnail = $media->file_path;
                }
            }
        }

        return view('home.detail_product', compact(
            'product',
            'relative_products',
            'menu_slug',
            'category_slug'
        ));
    }
    function quote(Request $request)
    {
        $list_product = Product::withTrashed()
            ->where('warehouse_status', 'In Stock')
            ->where('privacy', 'Public')
            ->get();

        if ($request->action) {
            if ($request->action == 'delete') {
                $r = 0;
                foreach (Cart::content() as $row) {
                    if ($row->rowId == $request->rowId) {
                        $r = 1;
                    }
                }
                if ($r == 1) {
                    Cart::remove($request->rowId);
                    $result = [
                        'cartCount' => Cart::count(),
                    ];
                    return response()->json($result);
                } else {
                    $result = [
                        'cartCount' => Cart::count(),
                    ];
                    return response()->json($result);
                }
            } elseif ($request->action == "update") {
                Cart::update($request->rowId, $request->inputValue);
                $result = [
                    'cartCount' => Cart::count(),
                ];
                return response()->json($result);
            }
        }
        foreach (Cart::content() as $row) {
            $check = [];
            $row->options->warehouse_status = "Out of Stock";
            foreach ($list_product as $product) {
                if ($row->id == $product->id) {
                    if ($product->deleted_at == null) {
                        $row->options->warehouse_status = "In Stock";
                    }
                    $check[] = $row->rowId;
                }
            }
            if (count($check) == 0) {
                Cart::remove($row->rowId);
            }
        }
        return view('home.quote');
    }
    function request()
    {
        return view('home.request');
    }
    function sendRequestMail(Request $request)
    {
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'company_name' => $request->company_name,
            'phone_number' => $request->phone,
            'email' => $request->email,
            'approximate_date' => $request->approximate_date,
            'approximate_return' => $request->approximate_return,
            'messages' => $request->message,
            'list_cart' => Cart::content()
        ];
        $request->validate(
            [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'approximate_date' => ['required', 'string', 'max:255'],
                'approximate_return' => ['required', 'string', 'max:255']
            ]
        );
        $request = \App\Models\Request::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'company_name' => $request->company_name,
            'phone_number' => $request->phone,
            'email' => $request->email,
            'approximate_date' => $request->approximate_date,
            'approximate_return' => $request->approximate_return,
            'message' => $request->message
        ]);
        foreach (Cart::content() as $row) {
            $request->cart()->create([
                'name' => $row->name,
                'qty' => $row->qty,
                'thumbnail' => $row->options->thumbnail,
                'url' => $row->options->url,
                'product_id' => $row->id,
            ]);
        }
        Mail::to("giacuong14042003@gmail.com")->send(new RequestMail($data, '[crystalav.com] You have a new request!'));
        Mail::to($request->email)->send(new RequestMail($data, '[crystalav.com] Thank you for your request!'));
        return redirect('quote')->with('status', 'Sent successfully! We will contact you within a few hours.');
    }

    function contact()
    {
        return view('home.contact');
    }
    function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255',
            'message' => ['required'],
        ]);
        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'messages' => $request->message,
        ];
        Mail::to("giacuong14042003@gmail.com")->send(new ContactMail($data));
        return redirect('contact')->with('status', 'Sent message successfully!');
    }
}
