<?php

namespace App\Traits;

trait HasChild
{
    public function has_child($listCategories, $id)
    {
        foreach ($listCategories as $v) {
            if ($v->parent_id == $id) {
                return true;
            }
        }
        return false;
    }
}
