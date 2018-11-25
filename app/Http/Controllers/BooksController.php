<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function getBooksTemplate(){
        return view('booksTemplate');
    }

    public function getBooksTemplateForAdmin(){
        return view('admin.booksTemplate');
    }
}
