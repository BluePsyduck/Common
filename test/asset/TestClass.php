<?php

declare(strict_types=1);

namespace BluePsyduckTestAsset\Common;

/**
 * A class for testing the reflections.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class TestClass
{
    /**
     * A protected property.
     * @var mixed
     */
    protected $protectedProperty;

    /**
     * Sets the protected property.
     * @param mixed $protectedProperty
     * @return $this
     */
    public function setProtectedProperty($protectedProperty)
    {
        $this->protectedProperty = $protectedProperty;
        return $this;
    }

    /**
     * Returns the protected property.
     * @return mixed
     */
    public function getProtectedProperty()
    {
        return $this->protectedProperty;
    }

    /**
     * A protected method adding two numbers.
     * @param int $a
     * @param int $b
     * @return int
     */
    protected function protectedMethod(int $a, int $b): int
    {
        return $a + $b;
    }
}
