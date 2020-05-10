<?php


namespace merlinface\DataTransfer;


use ReflectionClass;
use ReflectionProperty;

abstract class DataTransferObject
{
    // correlation id - unique identifier that indicates which request message this reply is for
    public $__cid;
    // transaction id - unique identifier of first message in the chain
    public $__tid;
    // the name of the service emitting the event.
    public $__emit;
    // The name of the service consuming the event.
    public $__consume;

    public function __construct(array $parameters = [])
    {
        $class = new ReflectionClass(static::class);

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $property = $reflectionProperty->getName();
            $converted = $this->camelCaseTo_snake_case($property);
            if (!empty($parameters[$converted])) {
                $this->{$property} = $parameters[$converted];
            }
        }
    }

    public function getCorrelationId(): ?string
    {
        return $this->__cid;
    }

    public function setCorrelationId($cid): self
    {
        $this->__cid = $cid;

        return $this;
    }

    public function getTransactionId(): ?string
    {
        return $this->__tid;
    }

    public function setTransactionId($tid): self
    {
        $this->__tid = $tid;

        return $this;
    }

    public function getWhoEmitted(): ?string
    {
        return $this->__emit;
    }

    public function setWhoEmitted($emit): self
    {
        $this->__emit = $emit;

        return $this;
    }

    public function getWhoConsumed(): ?string
    {
        return $this->__consume;
    }

    public function setWhoConsumed(string $consume): self
    {
        $this->__consume = $consume;

        return $this;
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