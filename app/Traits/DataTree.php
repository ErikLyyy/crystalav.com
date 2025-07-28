<?php

namespace App\Traits;

trait DataTree
{
    use HasChild;
    public function data_tree($listCategories, $parent_id = 0, $level = 0)
    {
        $result = array();
        foreach ($listCategories as $v) {
            if ($v->parent_id == $parent_id) {
                $v['level'] = $level;
                $result[] = $v;
                if ($this->has_child($listCategories, $v->id)) {
                    $result_child = $this->data_tree($listCategories, $v->id, $level + 1);
                    $result = array_merge($result, $result_child);
                }
            }
        }
        return $result;
    }
}
