<?php

namespace Varvara\Framework\Routing;

use Exception;

class RouteMatcher
{
    public function __construct(private RouteCollection $collection)
    {

    }

    /**
     * @throws Exception
     */
    public function match($requestUri): Route
    {
        foreach ($this->collection->getAll() as $route) {
            if ($route->getUri() === $requestUri) {
                return $route;
            }
        }
        throw new Exception('Route not found');
    }
}
