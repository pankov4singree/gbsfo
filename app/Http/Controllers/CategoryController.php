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
}
