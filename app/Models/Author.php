<?php

namespace App\Models;

use App\Models\Traits\LinkBuilder;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use LinkBuilder;

    /**
     * @var int $perPage
     */
    protected $perPage = 5;

    /**
     * @var array $hidden
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * Author constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->route = 'authors';
    }

    /**
     * get books
     */
    public function books()
    {
        return $this->belongsToMany('App\Models\Book', 'author_book', 'author_id', 'book_id');
    }

    /**
     * setter for $title_for_route in trait LinkBuilder
     */
    public function getTitleForRouteAttribute()
    {
        $this->title_for_route = $this->first_name . ' ' . $this->last_name;
    }
}
