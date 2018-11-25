<?php

use Illuminate\Database\Eloquent\Collection;

/**
 * @param string $uri
 * @return bool
 */
function current_page($uri = '/')
{
    if (request()->route()->getName() == $uri)
        return true;
    return false;
}

/**
 * @param $elements
 * @param string $id_name
 * @param string $parent_name
 * @return array
 */
function sorted_array($elements, $id_name = "id", $parent_name = "parent"){
    $new = [];
    foreach ($elements as $element) {
            $new[$element->$parent_name][$element->$id_name] = $element;
    }
    return $new;
}

/**
 * @param $elements
 * @param int $parent_id
 * @param array $exclude_children
 * @param string $id_name
 * @param string $parent_name
 * @return Collection
 */
function build_tree($elements, $parent_id = 0, $exclude_children = array(), $id_name = "id", $parent_name = "parent")
{
    $new = [];
    foreach ($elements as $element) {
        if (!in_array($element->$parent_name, $exclude_children)) {
            $new[$element->$parent_name][$element->$id_name] = $element;
        }
    }
    return build_tree_with_sorted_array($new, $parent_id, $id_name);
}

/**
 * @param $elements
 * @param int $parent_id
 * @param string $id_name
 * @return Collection
 */
function build_tree_with_sorted_array($elements, $parent_id = 0, $id_name = "id")
{
    $new = [];
    if (!empty($elements[$parent_id])) {
        foreach ($elements[$parent_id] as $element) {
            $new[$element->id] = $element;
            $new[$element->id]->subitems = build_tree_with_sorted_array($elements, $element->$id_name, $id_name);
        }
        return (new Collection($new));
    } else
        return (new Collection($new));
}

/**
 * @param $val
 */
function pre($val)
{
    echo '<pre>';
    var_dump($val);
    echo '</pre>';
}