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
            $category = Category::find($id);
            if (!empty($category)) {
                return view('admin.categoryTemplate', [
                    'category' => $category
                ]);
            } else {
                abort(404);
            }
        } else {
            abort(403);
        }
    }

    public function getCreateCategoryTemplateForAdmin(Request $request)
    {
        if ($request->user()->can('create', Category::class)) {
            $category = new Category();
            $category->parent = 0;
            $category->name = "";
            $category->id = 0;
            return view('admin.categoryTemplate', [
                'category' => $category
            ]);
        } else {
            abort(403);
        }
    }

    public function getCategories(Request $request)
    {
        $data = $request->all();
        $data['parent_id'] = strip_tags($data['parent_id']);
        $exclude_ids = [];
        if (!empty($data['exclude_ids'])) {
            foreach ($data['exclude_ids'] as $item) {
                $exclude_ids[] = strip_tags($item);
            }
        }
        $categories = Category::all();
        foreach ($categories as $category) {
            $category->append(array('routes', 'subitems'));
        }
        $categories = build_tree($categories, $data['parent_id'], $exclude_ids);
        return response()->json($categories);
    }

    public function deleteCategory($id = null, Request $request)
    {
        if ($request->user()->can('delete', Category::class)) {
            $category = Category::find($id);
            if (!empty($category)) {
                $new_parent_cat_id = 0;
                if (!empty($category->parentCategory->id)) {
                    $new_parent_cat_id = $category->parentCategory->id;
                }
                foreach ($category->childrenCategories as $child) {
                    $child->parent = $new_parent_cat_id;
                    $child->save();
                }
                $category->books()->detach();
                $category->delete();
                return response()->json(['status' => 'success']);
            }
            abort(404);
        } else {
            abort(403);
        }
    }

    // добавить валидацию

    public function createCategory(Request $request)
    {
        if ($request->user()->can('create', Category::class)) {
            $data = $request->all();
            $category = new Category();
            $category->parent = strip_tags($data['parent']);
            $category->name = strip_tags($data['name']);
            if($category->save()){
                return response()->json(['save' => true]);
            } else {
                return response()->json(['save' => false]);
            }
        } else {
            abort(403);
        }
    }

    public function updateCategory(Request $request)
    {
        if ($request->user()->can('edit', Category::class)) {
            $data = $request->all();
            $category = Category::find(strip_tags($data['id']));
            if(!empty($category)) {
                $category->parent = strip_tags($data['parent']);
                $category->name = strip_tags($data['name']);
                if ($category->save()) {
                    return response()->json(['save' => true]);
                } else {
                    return response()->json(['save' => false]);
                }
            } else {
                return response()->json(['save' => false]);
            }
        } else {
            abort(403);
        }
    }
}
