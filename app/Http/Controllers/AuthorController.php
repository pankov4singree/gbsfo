<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAuthorsTemplateForAdmin()
    {
        return view('admin.authorsTemplate');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthors(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => array('required', 'integer', 'min:0'),
        ]);
        if ($validator->fails()) {
            abort(404);
        }
        $data = $validator->valid();
        if (!empty($request->user()) && $data['page'] == 0) {
            $authors = Author::orderBy('created_at', 'DESC')->get();
        } else {
            $authors = Author::orderBy('created_at', 'DESC')->paginate();
        }
        foreach ($authors as $author) {
            $author->append(array('routes'));
        }
        return response()->json($authors);
    }

    /**
     * @param null $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAuthorTemplateForAdmin($id = null, Request $request)
    {
        if ($request->user()->can('edit', Author::class)) {
            $author = Author::find($id);
            if (!empty($author)) {
                return view('admin.authorTemplate', [
                    'author' => $author
                ]);
            } else {
                abort(404);
            }
        } else {
            abort(403);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAuthor(Request $request)
    {
        if ($request->user()->can('create', Author::class)) {
            $validator = Validator::make($request->all(), [
                'first_name' => array('required', 'max:255'),
                'last_name' => array('required', 'max:255'),
            ]);
            if ($validator->fails()) {
                return response()->json(['save' => false, 'errors' => $validator->errors()]);
            }
            $data = $validator->valid();
            $author = new Author();
            $author->first_name = $data['first_name'];
            $author->last_name = $data['last_name'];
            if ($author->save()) {
                return response()->json(['save' => true, 'url' => route('admin.author.edit', ['id' => $author->id])]);
            } else {
                return response()->json(['save' => false]);
            }
        } else {
            abort(403);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAuthor(Request $request)
    {
        if ($request->user()->can('edit', Author::class)) {
            $validator = Validator::make($request->all(), [
                'id' => array('required', 'integer', 'min:1'),
                'first_name' => array('required', 'max:255'),
                'last_name' => array('required', 'max:255'),
            ]);
            if ($validator->fails()) {
                return response()->json(['save' => false, 'errors' => $validator->errors()]);
            }
            $data = $validator->valid();
            $author = Author::find($data['id']);
            if (!empty($author)) {
                $author->first_name = $data['first_name'];
                $author->last_name = $data['last_name'];
                if ($author->save()) {
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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreateAuthorTemplateForAdmin(Request $request)
    {
        if ($request->user()->can('create', Author::class)) {
            $author = new Author();
            $author->first_name = "";
            $author->last_name = "";
            $author->id = 0;
            return view('admin.authorTemplate', [
                'author' => $author
            ]);
        } else {
            abort(403);
        }
    }

    public function deleteAuthor($id = null, Request $request)
    {
        if ($request->user()->can('delete', Author::class)) {
            $author = Author::find($id);
            if (!empty($author)) {
                $author->books()->detach();
                $author->delete();
                return response()->json(['status' => 'success']);
            }
            abort(404);
        } else {
            abort(403);
        }
    }
}
