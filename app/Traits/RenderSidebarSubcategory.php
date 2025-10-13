<?php

namespace App\Traits;

trait RenderSidebarSubcategory
{
    use HasChild;
    function render_sidebar_subcat($data, $current_cat, $parent_id = 0)
    {
        $data = collect($data)->sortBy('id');
        $result = "<ul id='list-cat'>";
        foreach ($data as $cat) {
            if ($cat->parent_id == $parent_id) {
                if ($this->has_child($data, $cat->id)) {
                    $result .= "<li>";
                    $result .= "<div class='cat-name'>";
                    $active = ($cat->slug == $current_cat) ? 'active' : '';

                    $result .= "<a href='$cat->slug' class='$active'>$cat->title</a>";
                    $result .= "<span><svg xmlns='http://www.w3.org/2000/svg' height='20px' viewBox='0 -960 960 960'
                                         width='20px' fill='#000'>
                                         <path d='M444-444H240v-72h204v-204h72v204h204v72H516v204h-72v-204Z' />
                                     </svg></span>";
                    $result .= "</div>";
                    $result .= "<ul class='list-sub-cat list-cat-wp'>";
                    foreach ($data as $sub_cat) {
                        if ($sub_cat->parent_id == $cat->id) {
                            $result .= "<li><a href='$sub_cat->slug'>$sub_cat->title</a></li>";
                        }
                    }
                    $result .= "</ul>";
                    $result .= "</li>";
                } else {
                    $result .= "<li><a href='$cat->slug'>$cat->title</a></li>";
                }
            }
        }
        return $result;
    }
}

