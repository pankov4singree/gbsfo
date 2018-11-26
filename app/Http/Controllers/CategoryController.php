<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function getCategoriesTemplateForAdmin()
    {
        return view('admin.categoriesTemplate');
    }

    public function getCategoryTemplateForAdmin($id = null, Request $request)
    {
        if ($request->user()->can('edit', Category::class)) {
            return view('admin.categoryTemplate');
        } else {
            abort(404);
        }
    }

    public function getCategories(Request $request)
    {
        $data = $request->all();
        $data['parent_id'] = strip_tags($data['parent_id']);
        $exclude_id = [];
        if (!empty($data['exclude_id'])) {
            foreach ($data['exclude_id'] as $item) {
                $exclude_id[] = strip_tags($item);
            }
        }
        $categories = Category::all();
        foreach ($categories as $category) {
            $category->append(array('routes'));
        }
        $categories = build_tree($categories, $data['parent_id'], $exclude_id);
        return response()->json($categories);
    }

    public function deleteCategory($id = null, Request $request)
    {
        if ($request->user()->can('delete', Category::class)) {
            $category = Category::find($id);
            $new_parent_cat_id = 0;
            if(!empty($category->parentCategory->id)){
                $new_parent_cat_id = $category->parentCategory->id;
            }
            foreach ($category->childrenCategories as $child){
                $child->parent = $new_parent_cat_id;
                $child->save();
            }
            $category->books()->detach();
            $category->delete();
        } else {
            abort(403);
        }
    }
}
