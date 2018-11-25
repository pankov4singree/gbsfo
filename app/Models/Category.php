<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $appends = ['subitems'];

    protected $user = null;

    public $subitems = [];

    public $routes = [];

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
        return $this->belongsToMany('App\Models\Book');
    }

    /**
     * Get the Categories associated with the parent's `id`
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent', 'id');
    }

    /**
     * Get the parent associated with the Category`s parent_id`
     */
    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    /**
     * @return array
     */
    public function getSubitemsAttribute()
    {
        return $this->subitems;
    }

    public function getRoutesAttribute()
    {
        if ($this->user->can('get-view-link', $this)) {
            $this->routes['view'] = route('category.view', ['name' => str_slug($this->name), 'id' => $this->id]);
        }
        if ($this->user->can('get-edit-link', $this)) {
            $this->routes['edit'] = route('admin.category.edit', ['id' => $this->id]);
        }
        return $this->routes;
    }
}
