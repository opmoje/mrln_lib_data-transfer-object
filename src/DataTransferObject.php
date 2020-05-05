<?php


namespace merlinface\DataTransfer;


use ReflectionClass;
use ReflectionProperty;

abstract class DataTransferObject
{
    public function __construct(array $parameters = [])
    {
        $class = new ReflectionClass(static::class);

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty){
            $property = $reflectionProperty->getName();
            $converted = $this->camelCaseTo_snake_case($property);
            $this->{$property} = $parameters[$converted];
        }
    }

    private function camelCaseTo_snake_case(string $input) {
        return strtolower(
            preg_replace(
                '/([^A-Z])([A-Z])/',
                "$1_$2",
                $input
            )
        );
    }
}