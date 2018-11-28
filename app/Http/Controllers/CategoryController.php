<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Rules\AvailableParentForCategory;
use Validator;

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
        $validator = Validator::make($request->all(), [
            'parent_id' => array('required', 'integer'),
            'exclude_ids' => array('array'),
            'exclude_ids.*' => array('integer')
        ]);
        if ($validator->fails()) {
            return response()->json(['save' => false, 'errors' => $validator->errors()]);
        }
        $data = $validator->valid();
        $categories = Category::all();
        foreach ($categories as $category) {
            $category->append(array('routes', 'subitems'));
        }
        $categories = build_tree($categories, $data['parent_id'], $data['exclude_ids']);
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
            $validator = Validator::make($request->all(), [
                'name' => array('required', 'max:255'),
                'parent' => array('required', 'integer'),
                'id' => array('required', 'integer')
            ]);
            if ($validator->fails()) {
                return response()->json(['save' => false, 'errors' => $validator->errors()]);
            }
            $data = $validator->valid();
            $category = new Category();
            $category->parent = $data['parent'];
            $category->name = $data['name'];
            if ($category->save()) {
                return response()->json(['save' => true, 'url' => route('admin.category.edit', ['id' => $category->id])]);
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
            $validator = Validator::make($request->all(), [
                'id' => array('required', 'integer')
            ]);
            if ($validator->fails()) {
                return response()->json(['save' => false, 'errors' => $validator->errors()]);
            }
            $data = $validator->valid();

            $category = Category::find($data['id']);

            $validator = Validator::make($request->all(), [
                'name' => array('required', 'max:255'),
                'parent' => array('required', 'integer', new AvailableParentForCategory($category))
            ]);
            if ($validator->fails()) {
                return response()->json(['save' => false, 'errors' => $validator->errors()]);
            }
            $data = $validator->valid();

            if (!empty($category)) {
                $category->parent = $data['parent'];
                $category->name = $data['name'];
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
