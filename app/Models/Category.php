<?php

namespace App\Models;

use App\Models\Traits\LinkBuilder;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use LinkBuilder;

    /**
     * @var array $subitems
     */
    public $subitems = [];

    /**
     * @var \App\User $user
     */
    protected $user = null;

    /**
     * @var array $hidden
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * Category constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->user = request()->user();
        $this->route = 'category';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function books()
    {
        return $this->belongsToMany('App\Models\Book', 'category_book');
    }

    /**
     * Get the Categories associated with the parent's `id`
     */
    public function childrenCategories()
    {
        return $this->hasMany(self::class, 'parent', 'id');
    }

    /**
     * Get the parent associated with the Category`s parent_id`
     */
    public function parentCategory()
    {
        return $this->belongsTo(self::class, 'parent', 'id');
    }

    /**
     * @return array
     */
    public function getSubitemsAttribute()
    {
        return $this->subitems;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        $categories = $this->childrenCategories;
        foreach ($categories as $category) {
            $categories = $categories->merge($category->getChildren());
        }
        return $categories;
    }

    /**
     *
     */
    public function getTitleForRouteAttribute()
    {
        $this->title_for_route = $this->name;
    }
}
