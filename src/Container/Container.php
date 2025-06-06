<?php

namespace Pluma\Container;

/**
 * Simple Dependency Injection Container
 */
class Container
{
    /**
     * @var array The container bindings
     */
    protected array $bindings = [];
    
    /**
     * @var array The resolved instances
     */
    protected array $instances = [];
    
    /**
     * Bind a type into the container
     * 
     * @param string $abstract The abstract type
     * @param mixed $concrete The concrete implementation
     * @param bool $shared Whether the binding should be shared
     * @return void
     */
    public function bind(string $abstract, $concrete = null, bool $shared = false): void
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }
        
        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'shared' => $shared,
        ];
    }
    
    /**
     * Register a shared binding in the container
     * 
     * @param string $abstract The abstract type
     * @param mixed $concrete The concrete implementation
     * @return void
     */
    public function singleton(string $abstract, $concrete = null): void
    {
        $this->bind($abstract, $concrete, true);
    }
    
    /**
     * Set an instance in the container
     * 
     * @param string $abstract The abstract type
     * @param mixed $instance The instance
     * @return void
     */
    public function set(string $abstract, $instance): void
    {
        $this->instances[$abstract] = $instance;
    }
    
    /**
     * Resolve a type from the container
     * 
     * @param string $abstract The abstract type
     * @return mixed The resolved instance
     * @throws \Exception If the type cannot be resolved
     */
    public function get(string $abstract)
    {
        // If we have an instance, return it
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }
        
        // If we don't have a binding, try to instantiate the class
        if (!isset($this->bindings[$abstract])) {
            if (class_exists($abstract)) {
                return $this->build($abstract);
            }
            
            throw new \Exception("No binding registered for {$abstract}");
        }
        
        // Get the concrete implementation
        $concrete = $this->bindings[$abstract]['concrete'];
        
        // If the concrete is a closure, execute it
        if ($concrete instanceof \Closure) {
            $instance = $concrete($this);
        } else {
            $instance = $this->build($concrete);
        }
        
        // If the binding is shared, store the instance
        if ($this->bindings[$abstract]['shared']) {
            $this->instances[$abstract] = $instance;
        }
        
        return $instance;
    }
    
    /**
     * Build a concrete instance of a class
     * 
     * @param string $concrete The concrete class
     * @return object The built instance
     * @throws \Exception If the class cannot be instantiated
     */
    protected function build(string $concrete)
    {
        // Create a reflection class
        $reflector = new \ReflectionClass($concrete);
        
        // Check if the class is instantiable
        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$concrete} is not instantiable");
        }
        
        // Get the constructor
        $constructor = $reflector->getConstructor();
        
        // If there is no constructor, just return a new instance
        if (is_null($constructor)) {
            return new $concrete();
        }
        
        // Get the constructor parameters
        $parameters = $constructor->getParameters();
        
        // If there are no parameters, just return a new instance
        if (count($parameters) === 0) {
            return new $concrete();
        }
        
        // Build the dependencies
        $dependencies = [];
        
        foreach ($parameters as $parameter) {
            // Get the parameter type
            $type = $parameter->getType();
            
            // If the parameter has no type hint, check if it has a default value
            if (is_null($type)) {
                // If the parameter is optional, use the default value
                if ($parameter->isOptional()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new \Exception("Cannot resolve parameter {$parameter->getName()} without type hint");
                }
                
                continue;
            }
            
            // Get the type name
            $typeName = $type->getName();
            
            // If the type is a built-in type and the parameter is optional, use the default value
            if ($type->isBuiltin() && $parameter->isOptional()) {
                $dependencies[] = $parameter->getDefaultValue();
                continue;
            }
            
            // If the type is a built-in type and the parameter is not optional, throw an exception
            if ($type->isBuiltin() && !$parameter->isOptional()) {
                throw new \Exception("Cannot resolve parameter {$parameter->getName()} of type {$typeName}");
            }
            
            // Resolve the dependency
            $dependencies[] = $this->get($typeName);
        }
        
        // Create a new instance with the dependencies
        return $reflector->newInstanceArgs($dependencies);
    }
    
    /**
     * Check if a binding exists
     * 
     * @param string $abstract The abstract type
     * @return bool Whether the binding exists
     */
    public function has(string $abstract): bool
    {
        return isset($this->bindings[$abstract]) || isset($this->instances[$abstract]);
    }
}