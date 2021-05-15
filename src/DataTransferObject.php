<?php


namespace merlinface\DataTransfer;


use ReflectionClass;
use ReflectionProperty;

abstract class DataTransferObject
{
    public $__cid;
    public $__emit;
    public $__to;

    public function __construct(array $parameters = [])
    {
        $class = new ReflectionClass(static::class);

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $property = $reflectionProperty->getName();
            $converted = $this->camelCaseTo_snake_case($property);
            if (isset($parameters[$converted])) {
                $this->{$property} = $parameters[$converted];
            }
            elseif (isset($parameters[$property])) {
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

    public function getEmittedFor(): ?string
    {
        return $this->__to ?? null;
    }

    public function getWhichServiceEmitted(): ?string
    {
        if (!empty($this->__emit) && preg_match('/^([^.]+)/', $this->__emit, $match)) {
            return (string)$match[1];
        }

        return null;
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