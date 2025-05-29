<?php

declare(strict_types=1);

namespace Varvara\Framework\Routing;

class Route
{
    public array $routes = [];
    public function __construct(private readonly string $uri, private readonly string $method, private string $class, private string $classMethod)
    {

    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getClassMethod(): string
    {
        return $this->classMethod;
    }
    public array $params = [] {
        get {
            return $this->params;
        }
        set {
            $this->params = $value;
        }
    }

    public function setParams(array $params): Route
    {
        $this->params = $params;
        return $this;
    }

}
