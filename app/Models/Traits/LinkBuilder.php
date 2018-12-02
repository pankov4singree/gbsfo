<?php

namespace App\Models\Traits;

trait LinkBuilder
{
    /**
     * @var array $routes
     */
    public $routes = [];

    /**
     * @var string $route
     */
    protected $route;

    /**
     * @var string $title_for_route
     */
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
        $this->routes['view'] = route($this->route . '.view', ['name' => str_slug($this->title_for_route), 'id' => $this->id]);
        $this->routes['edit'] = route('admin.' . $this->route . '.edit', ['id' => $this->id]);
        return $this->routes;
    }

    /**
     * @void
     */
    abstract public function getTitleForRouteAttribute();

}
