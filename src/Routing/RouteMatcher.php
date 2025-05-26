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
            $uri = $route->getUri();

            $pattern = preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $uri);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $requestUri, $matches)) {
                $params = [];
                foreach ($matches as $key => $value) {
                    if (!is_int($key)) {
                        $params[$key] = $value;
                    }
                }
                $route->setParams($params);

                return $route;
            }
        }
        throw new Exception('Route not found');
    }

}
