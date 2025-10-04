<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function __contruct()
    {
        // Paginator::useBootstrapFour();

        // function render_menu($data, $category = "", $url = "category", $parent_id = 0)
        // {
        //     $result = "";
        //     foreach ($data as $main) {
        //         if ($main->parent_id == $parent_id) {
        //             if (has_child($data, $main->id)) {
        //                 $result .= "<li class='nav-item dropdown'>";
        //                 $main_slug = url($url, $main->slug);
        //                 $result .= "<a href='$main_slug' class='nav-link dropdown-toggle category' role='button'
        //                                 data-bs-toggle='dropdown' aria-expanded='false' data-menu='$main->slug'>$main->title</a>";
        //                 $result .= "<ul class='dropdown-menu'>";
        //                 foreach ($data as $sub) {
        //                     if ($sub->parent_id == $main->id) {
        //                         $sub_slug = url($url, $sub->slug);
        //                         $result .= "<li><a class='dropdown-item category' data-menu='$sub->slug' href='$sub_slug'>$sub->title</a>
        //                                 </li>";
        //                     }
        //                 }
        //                 $result .= "</ul>";
        //                 $result .= "</li>";
        //             } else {
        //                 $result .= "<li class='nav-item'>";
        //                 $main_slug = url($url, $main->slug);
        //                 $result .= "<a href='$main_slug' data-menu='$main->slug' class='nav-link category'>$main->title</a>";
        //                 $result .= "</li>";
        //             }
        //         }
        //     }
        //     return $result;
        // }
        // function render_sidebar_cat($data, $parent_id = 0, $category = "", $slug = "", $sidebar_title = "")
        // {
        //     $url = "category/" . $category . "/" . $slug;
        //     $result = "<ul class='list-cat'>";
        //     foreach ($data as $cat) {
        //         if ($cat->parent_id == $parent_id) {
        //             if (has_child($data, $cat->id)) {
        //                 $result .= "<li>";
        //                 $result .= "<div class='cat-name sidebar-cat-title'>";
        //                 $result .= "<a class='sidebar-cat-title sidebar-item' href='$cat->slug'>$cat->title</a>";
        //                 $result .= "<span class='material-symbols-outlined'>add</span>";
        //                 $result .= "</div>";
        //                 $result .= "<ul class='list-sub-cat list-cat-wp'>";
        //                 foreach ($data as $sub_cat) {
        //                     if ($sub_cat->parent_id == $cat->id) {
        //                         $result .= "<li><a href='$sub_cat->slug' class='sidebar-item'>$sub_cat->title</a></li>";
        //                     }
        //                 }
        //                 $result .= "</ul>";
        //                 $result .= "</li>";
        //             } else {
        //                 $result .= "<li><a href='$cat->slug' class='sidebar-item'>$cat->title</a></li>";
        //             }
        //         }
        //     }
        //     return $result;
        // }
        // function render_sidebar_option($data, $parent_id = 0, $category = "", $slug = "", $list_slug = [])
        // {
        //     $url = "home/" . $category . "/" . $slug;
        //     $result = "<div class='sidebar_option_wp'>";
        //     foreach ($data as $cat) {
        //         if ($cat->parent_id == $parent_id) {
        //             $result .= "<div class='sidebar_options'>";
        //             $result .= "<h3 class='sidebar-cat-title'>$cat->title<span class='material-symbols-outlined opened'>remove</span></h3>";
        //             if (has_child($data, $cat->id)) {
        //                 $result .= "<div class='list-cat-wp'>";
        //                 $result .= "<div class='btn-clear'><span>CLEAR SELECTION</span></div>";
        //                 $result .= "<div class='filter'>";
        //                 foreach ($data as $sub_cat) {
        //                     if ($sub_cat->parent_id == $cat->id) {
        //                         $result .= "<div class='form-checkbox' data-slug='$sub_cat->slug'>";
        //                         $result .= "<input type='checkbox' class='filter-item' name='sidebar_option[]' id='$sub_cat->slug-$sub_cat->id' value='$sub_cat->slug'>";
        //                         $result .= "<label for='$sub_cat->slug-$sub_cat->id'>$sub_cat->title</label>";
        //                         $result .= "</div>";
        //                     }
        //                 }
        //                 $result .= "</div>";
        //                 $result .= "</div>";
        //             }
        //             $result .= "</div>";
        //         }
        //     }
        //     $result .= "</div>";
        //     return $result;
        // }
    }
}
