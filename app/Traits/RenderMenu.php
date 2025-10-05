<?php

namespace App\Traits;

trait RenderMenu
{
    use HasChild;
    function render_menu($data, $category = "", $url = "category", $parent_id = 0)
    {
        $result = "";
        foreach ($data as $main) {
            if ($main->parent_id == $parent_id) {
                if ($this->has_child($data, $main->id)) {
                    $result .= "<li class='dropdown'>";
                    $main_slug = url($url, $main->slug);
                    $result .= "<a href='$main_slug' class='dropdown-toggle'
                                        >$main->title</a>";
                    $result .= "<ul class='sub-menu'>";
                    foreach ($data as $sub) {
                        if ($sub->parent_id == $main->id) {
                            $sub_slug = url($url, $sub->slug);
                            $result .= "<li><a href='$sub_slug'>$sub->title</a>
                                        </li>";
                        }
                    }
                    $result .= "</ul>";
                    $result .= "</li>";
                } else {
                    $result .= "<li>";
                    $main_slug = url($url, $main->slug);
                    $result .= "<a href='$main_slug'>$main->title</a>";
                    $result .= "</li>";
                }
            }
        }
        return $result;
    }
}

