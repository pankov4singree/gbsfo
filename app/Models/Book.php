<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * get authors
     */
    public function authors(){
        return $this->belongsToMany('App\Models\Author');
    }

    /**
     * get categories
     */
    public function categories(){
        return $this->belongsToMany('App\Models\Category', 'category_book', 'book_id', 'category_id');
    }
}
