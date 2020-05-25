<?php


namespace merlinface\DataTransfer;


use ReflectionClass;
use ReflectionProperty;

abstract class DataTransferObject
{
    public $__cid;
    public $__emit;

    public function __construct(array $parameters = [])
    {
        $class = new ReflectionClass(static::class);

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $property = $reflectionProperty->getName();
            $converted = $this->camelCaseTo_snake_case($property);
            if (!empty($parameters[$converted])) {
                $this->{$property} = $parameters[$converted];
            }
            elseif (!empty($parameters[$property])) {
                $this->{$property} = $parameters[$property];
            }
        }
    }

    // correlation id - unique identifier that indicates which request message this reply is for
    public function getCorrelationId(): ?string
    {
        return !empty($this->__cid) ? $this->__cid : null;
    }

    public function getWhoEmitted(): ?string
    {
        return !empty($this->__emit) ? $this->__emit : null;
    }

    private function camelCaseTo_snake_case(string $input)
    {
        return strtolower(
            preg_replace(
                '/([^A-Z])([A-Z])/',
                "$1_$2",
                $input
            )
        );
    }
}