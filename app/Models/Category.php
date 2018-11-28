<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $user = null;

    public $subitems = [];

    public $routes = [];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->user = request()->user();
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
     * @return array
     */
    public function getRoutesAttribute()
    {
        if ($this->user->can('get-view-link', $this)) {
            $this->routes['view'] = route('category.view', ['name' => str_slug($this->name), 'id' => $this->id]);
        }
        if ($this->user->can('get-edit-link', $this)) {
            $this->routes['edit'] = route('admin.category.edit', ['id' => $this->id]);
        }
        $this->routes['delete'] = false;
        if ($this->user->can('get-delete-link', $this)) {
            $this->routes['delete'] = true;
        }
        return $this->routes;
    }

    public function getChildren()
    {
        $categories = $this->childrenCategories;
        foreach ($categories as $category) {
            $categories = $categories->merge($category->getChildren());
        }
        return $categories;
    }
}
