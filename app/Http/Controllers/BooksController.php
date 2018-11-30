<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BooksController extends Controller
{
    public function getBooksTemplate()
    {
        return view('booksTemplate');
    }

    public function getBooksTemplateForAdmin()
    {
        return view('admin.booksTemplate');
    }

    public function getCreateBookTemplateForAdmin(Request $request)
    {
        if ($request->user()->can('create', Book::class)) {
            $book = new Book();
            $book->authors;
            $book->categories;
            $book->name = "";
            $book->photo = "";
            $book->id = 0;
            return view('admin.bookTemplate', [
                'book' => $book
            ]);
        } else {
            abort(403);
        }
    }

    public function getBookTemplateForAdmin($id = null, Request $request)
    {
        if ($request->user()->can('edit', Book::class)) {
            $book = Book::find($id);
            if (!empty($book)) {
                $book->append(array('category_ids', 'author_ids'));
                return view('admin.bookTemplate', [
                    'book' => $book
                ]);
            } else {
                abort(404);
            }
        } else {
            abort(403);
        }
    }

    public function updateBook(Request $request)
    {
        if ($request->user()->can('edit', Book::class)) {
            $validator_array = array(
                'id' => array('required', 'integer', 'min:1'),
                'category_ids' => array('JSON', 'required'),
                'author_ids' => array('JSON', 'required'),
                'name' => array('required', 'max:255')
            );
            if ($request->hasFile('photo')) {
                $validator_array['photo'] = array('image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048');
            }
            $validator = Validator::make($request->all(), $validator_array);
            if ($validator->fails()) {
                return response()->json(['save' => false, 'errors' => $validator->errors()]);
            }
            $data = $validator->valid();
            $book = Book::find($data['id']);
            if (!empty($book)) {
                $data['category_ids'] = json_decode($data['category_ids']);
                $data['author_ids'] = json_decode($data['author_ids']);
                $book->name = $data['name'];
                if ($request->hasFile('photo')) {
                    $book->setNewPhoto($data['photo']);
                }
                $book->authors()->sync($data['author_ids']);
                $book->categories()->sync($data['category_ids']);
                if ($book->save()) {
                    return response()->json(['save' => true]);
                } else {
                    return response()->json(['save' => false]);
                }
            } else {
                abort(404);
            }
        } else {
            abort(403);
        }
    }
}
