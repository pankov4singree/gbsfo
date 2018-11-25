<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    /**
     * get books
     */
    public function books(){
        return $this->belongsToMany('App\Models\Book', 'author_book', 'author_id', 'book_id');
    }
}
