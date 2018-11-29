<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use Validator;

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
        $authors = Author::paginate();
        foreach ($authors as $author) {
            $author->append(array('routes'));
        }
        return response()->json($authors);
    }
}
