<?php

namespace Varvara\Framework\Routing;

class RouteCollection
{
    private array $routes = [];

    public function add(Route $router): self
    {
        $this->routes[] = $router;

        return $this;
    }

    public function getAll(): array
    {
        return $this->routes;
    }
}
