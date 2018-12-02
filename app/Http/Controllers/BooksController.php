<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBooksTemplate()
    {
        return view('frontend.booksTemplate');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBooksTemplateForAdmin()
    {
        return view('admin.booksTemplate');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreateBookTemplateForAdmin(Request $request)
    {
        if ($request->user()->can('create', Book::class)) {
            $book = new Book();
            $book->id = 0;
            $book->name = "";
            $book->photo = '';
            $book->append(array('category_ids', 'author_ids'));
            return view('admin.bookTemplate', [
                'book' => $book
            ]);
        } else {
            abort(403);
        }
    }

    /**
     * @param null $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBookTemplate($name = null, $id = null, Request $request)
    {
        $book = Book::find($id);
        if (!empty($book)) {
            $book->photo = $book->getPhotoUrl();
            foreach ($book->categories as &$category) {
                $category->append('routes');
            }
            unset($category);
            foreach ($book->authors as &$author) {
                $author->append('routes');
            }
            unset($author);
            return view('frontend.bookTemplate', [
                'book' => $book
            ]);
        } else {
            abort(404);
        }
    }

    /**
     * @param null $id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBookTemplateForAdmin($id = null, Request $request)
    {
        if ($request->user()->can('edit', Book::class)) {
            $book = Book::find($id);
            if (!empty($book)) {
                $book->photo = $book->getPhotoUrl();
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBook(Request $request)
    {
        if ($request->user()->can('edit', Book::class)) {
            $validator_array = array(
                'id' => array('required', 'integer', 'min:1'),
                'category_ids' => array('JSON'),
                'author_ids' => array('JSON'),
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
                if (!empty($data['category_ids'])) {
                    $data['category_ids'] = json_decode($data['category_ids']);
                } else {
                    $data['category_ids'] = [];
                }
                if (!empty($data['author_ids'])) {
                    $data['author_ids'] = json_decode($data['author_ids']);
                } else {
                    $data['author_ids'] = [];
                }
                $book->name = $data['name'];
                if ($request->hasFile('photo')) {
                    $book->setNewPhoto($data['photo']);
                }
                if ($book->save()) {
                    $book->authors()->sync($data['author_ids']);
                    $book->categories()->sync($data['category_ids']);
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createBook(Request $request)
    {
        if ($request->user()->can('create', Book::class)) {
            $validator_array = array(
                'category_ids' => array('JSON'),
                'author_ids' => array('JSON'),
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
            $book = new Book();
            if (!empty($data['category_ids'])) {
                $data['category_ids'] = json_decode($data['category_ids']);
            } else {
                $data['category_ids'] = [];
            }
            if (!empty($data['author_ids'])) {
                $data['author_ids'] = json_decode($data['author_ids']);
            } else {
                $data['author_ids'] = [];
            }
            $book->name = $data['name'];
            if ($request->hasFile('photo')) {
                $book->setNewPhoto($data['photo']);
            } else {
                $book->photo = "";
            }
            if ($book->save()) {
                $book->authors()->sync($data['author_ids']);
                $book->categories()->sync($data['category_ids']);
                return response()->json(['save' => true, 'url' => route('admin.books.edit', ['id' => $book->id])]);
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
    public function getBooks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => array('required', 'integer', 'min:0'),
            'category_id' => array('integer', 'min:1'),
            'author_id' => array('integer', 'min:1')
        ]);
        if ($validator->fails()) {
            abort(404);
        }
        $data = $validator->valid();
        $books = Book::orderBy('created_at', 'DESC');
        if (!empty($data['category_id'])) {
            $books->whereHas('categories', function ($query) use ($data) {
                $query->where('category_id', '=', $data['category_id']);
            });
        }
        if (!empty($data['author_id'])) {
            $books->whereHas('authors', function ($query) use ($data) {
                $query->where('author_id', '=', $data['author_id']);
            });
        }
        if (!empty($request->user()) && $data['page'] == 0) {
            $books = $books->get();
        } else {
            $books = $books->paginate();
        }
        foreach ($books as $book) {
            $book->append(array('routes'));
        }
        return response()->json($books);
    }

    /**
     * @param null $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBook($id = null, Request $request)
    {
        if ($request->user()->can('delete', Book::class)) {
            $book = Book::find($id);
            if (!empty($book)) {
                $book->authors()->detach();
                $book->categories()->detach();
                $book->deletePhoto();
                $book->delete();
                return response()->json(['status' => 'success']);
            }
            abort(404);
        } else {
            abort(403);
        }
    }
}
