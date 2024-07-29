<?php

declare(strict_types=1);

namespace App\Libraries;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Router\Exceptions\MethodNotFoundException;
use ReflectionClass;
use ReflectionException;

trait AutoRouterTrait
{
    public function route(...$params)
    {
        if (empty($params)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $method = array_shift($params);

        if (empty($method)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $httpVerb = request()->getMethod();

        if ($httpVerb !== "POST" && $httpVerb !== "GET") {
            throw PageNotFoundException::forPageNotFound();
        }

        $methodPascalCase = preg_replace_callback('/-(.?)/', function ($matches) {
            return ucfirst($matches[1]);
        }, ucfirst(strtolower($method)));

        if (!$this->isValidMethod($methodPascalCase)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $class = "\\" . $this::class;
        $classMethod = strtolower($httpVerb) . $methodPascalCase;

        if (!method_exists($this, $classMethod)
            || !in_array($classMethod, get_class_methods($class), true)) {
            throw PageNotFoundException::forPageNotFound();
        }

        try {
            $this->checkParameters($class, $classMethod, $params);
        } catch (MethodNotFoundException $e) {
            throw PageNotFoundException::forControllerNotFound($class, $classMethod);
        }

        return $this->{$classMethod}(...$params);
    }

    private function isValidMethod(string $segment): bool
    {
        return (bool)preg_match('/^[a-zA-Z][a-zA-Z0-9]*$/', $segment);
    }

    private function checkParameters($controller, $method, $params): void
    {
        try {
            $refClass = new ReflectionClass($controller);
        } catch (ReflectionException) {
            throw PageNotFoundException::forControllerNotFound($controller, $method);
        }

        try {
            $refMethod = $refClass->getMethod($method);
            $refParams = $refMethod->getParameters();
        } catch (ReflectionException) {
            throw new MethodNotFoundException();
        }

        if (!$refMethod->isPublic()) {
            throw new MethodNotFoundException();
        }

        if (count($refParams) < count($params)) {
            throw new PageNotFoundException(
                'The param count in the URI are greater than the controller method params.'
                    . ' Handler:' . $controller . '::' . $method
                    . ', URI:' . current_url()
            );
        }
    }
}
