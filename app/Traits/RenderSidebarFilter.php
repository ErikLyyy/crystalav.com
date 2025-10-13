<?php

namespace App\Traits;

trait RenderSidebarFilter
{
    use HasChild;
    function render_sidebar_filter($data, $list_check = [], $parent_id = 0)
    {
        $data = collect($data)->sortBy('id');
        $result = "<div class='sidebar_filter'>";
        foreach ($data as $cat) {
            if ($cat->parent_id == $parent_id) {
                $result .= "<h3 class='sidebar-cat-title'>$cat->title<span><svg xmlns='http://www.w3.org/2000/svg' height='20px' viewBox='0 -960 960 960'
                             width='20px' fill='#fff'>
                             <path d='M232-444v-72h496v72H232Z' />
                         </svg></span></h3>";
                if ($this->has_child($data, $cat->id)) {
                    $result .= "<div class='list-cat-wp'>";
                    $result .= "<div class='btn-clear'><button class='clear-filter'>CLEAR SELECTION</button></div>";
                    $result .= "<div class='filter'>";
                    foreach ($data as $sub_cat) {
                        if ($sub_cat->parent_id == $cat->id) {
                            $result .= "<div class='form-checkbox'>";
                            // $checked = in_array($sub_cat->slug, $list_check) ? 'checked' : '';
                            $checked = "";
                            foreach ($list_check as $check) {
                                if ($check == $sub_cat->slug) {
                                    $checked = "checked";
                                }
                            }
                            $result .= "<input type='checkbox' name='sidebar_filter[]' id='{$sub_cat->slug}-{$sub_cat->id}' value='{$sub_cat->slug}' {$checked}>";
                            $result .= "<label for='$sub_cat->slug-$sub_cat->id'>$sub_cat->title</label>";
                            $result .= "</div>";
                        }
                    }
                    $result .= "</div>";
                    $result .= "</div>";
                }
            }
        }
        $result .= "</div>";
        return $result;
    }
}

