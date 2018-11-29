<?php

namespace App\Models\Traits;

trait LinkBuilder
{
    public $routes = [];

    protected $route;

    protected $title_for_route;

    /**
     * @return array
     */
    public function getRoutesAttribute()
    {
        $this->getTitleForRouteAttribute();
        if (empty($this->title_for_route) || empty($this->route)) {
            trigger_error('When you would get attributes "route" for model, you must set properties "route" and "title_for_route"');
        }
        if ($this->user->can('get-view-link', $this)) {
            $this->routes['view'] = route($this->route . '.view', ['name' => str_slug($this->title_for_route), 'id' => $this->id]);
        }
        if ($this->user->can('get-edit-link', $this)) {
            $this->routes['edit'] = route('admin.' . $this->route . '.edit', ['id' => $this->id]);
        }
        $this->routes['delete'] = false;
        if ($this->user->can('get-delete-link', $this)) {
            $this->routes['delete'] = true;
        }
        return $this->routes;
    }

    /**
     * @void
     */
    abstract public function getTitleForRouteAttribute();

}
