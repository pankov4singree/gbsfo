<?php

namespace App\Models;

use App\Models\Traits\LinkBuilder;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use LinkBuilder;

    protected $perPage = 5;

    /**
     * @var array $hidden
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * @var \App\User $user
     */
    protected $user = null;

    /**
     * Author constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->user = request()->user();
        $this->route = 'author';
    }

    /**
     * get books
     */
    public function books()
    {
        return $this->belongsToMany('App\Models\Book', 'author_book', 'author_id', 'book_id');
    }

    /**
     *
     */
    public function getTitleForRouteAttribute()
    {
        $this->title_for_route = $this->first_name . ' ' . $this->last_name;
    }
}
