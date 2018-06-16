<?php

declare(strict_types=1);

namespace BluePsyduck\Common\Test;

use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

/**
 * The trait helping with accessing protected and private members in tests.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
trait ReflectionTrait
{
    /**
     * Injects a property value into an object.
     * @param object|string $object
     * @param string $name
     * @param mixed $value
     * @return $this
     * @throws ReflectionException
     */
    protected function injectProperty($object, string $name, $value)
    {
        $reflectedProperty = new ReflectionProperty($object, $name);
        $reflectedProperty->setAccessible(true);
        $reflectedProperty->setValue($object, $value);
        return $this;
    }

    /**
     * Extracts a property value from an object.
     * @param object|string $object
     * @param string $name
     * @return mixed
     * @throws ReflectionException
     */
    protected function extractProperty($object, string $name)
    {
        $reflectedProperty = new ReflectionProperty($object, $name);
        $reflectedProperty->setAccessible(true);
        return $reflectedProperty->getValue($object);
    }

    /**
     * Invokes a method on an object.
     * @param object|string $object
     * @param string $name
     * @param mixed ...$params
     * @return mixed
     * @throws ReflectionException
     */
    protected function invokeMethod($object, string $name, ...$params)
    {
        $reflectedMethod = new ReflectionMethod($object, $name);
        $reflectedMethod->setAccessible(true);
        return $reflectedMethod->invokeArgs($object, $params);
    }
}
