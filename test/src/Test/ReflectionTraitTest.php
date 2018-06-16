<?php

declare(strict_types=1);

namespace BluePsyduckTest\Common\Test;

use BluePsyduck\Common\Test\ReflectionTrait;
use BluePsyduckTestAsset\Common\TestClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;

/**
 * The PHPUnit test of the reflection trait.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 * @coversDefaultClass \BluePsyduck\Common\Test\ReflectionTrait
 */
class ReflectionTraitTest extends TestCase
{
    /**
     * Tests the injectProperty method.
     * @covers ::injectProperty
     * @throws ReflectionException
     */
    public function testInjectProperty()
    {
        $value = 42;
        $class = new TestClass();

        /* @var ReflectionTrait|MockObject $reflectionTrait */
        $reflectionTrait = $this->getMockBuilder(ReflectionTrait::class)
                                ->getMockForTrait();

        $reflectedMethod = new ReflectionMethod($reflectionTrait, 'injectProperty');
        $reflectedMethod->setAccessible(true);

        $this->assertSame(
            $reflectionTrait,
            $reflectedMethod->invoke($reflectionTrait, $class, 'protectedProperty', $value)
        );
        $this->assertSame($value, $class->getProtectedProperty());
    }

    /**
     * Tests the extractProperty method.
     * @covers ::extractProperty
     * @throws ReflectionException
     */
    public function testExtractProperty()
    {
        $value = 42;
        $class = new TestClass();
        $class->setProtectedProperty($value);

        /* @var ReflectionTrait|MockObject $reflectionTrait */
        $reflectionTrait = $this->getMockBuilder(ReflectionTrait::class)
                                ->getMockForTrait();

        $reflectedMethod = new ReflectionMethod($reflectionTrait, 'extractProperty');
        $reflectedMethod->setAccessible(true);

        $this->assertSame($value, $reflectedMethod->invoke($reflectionTrait, $class, 'protectedProperty'));
    }

    /**
     * Tests the invokeMethod method.
     * @covers ::invokeMethod
     * @throws ReflectionException
     */
    public function testInvokeMethod()
    {
        $value1 = 42;
        $value2 = 21;
        $expectedResult = 63;
        $class = new TestClass();

        /* @var ReflectionTrait|MockObject $reflectionTrait */
        $reflectionTrait = $this->getMockBuilder(ReflectionTrait::class)
                                ->getMockForTrait();

        $reflectedMethod = new ReflectionMethod($reflectionTrait, 'invokeMethod');
        $reflectedMethod->setAccessible(true);

        $this->assertSame(
            $expectedResult,
            $reflectedMethod->invoke($reflectionTrait, $class, 'protectedMethod', $value1, $value2)
        );
    }
}
