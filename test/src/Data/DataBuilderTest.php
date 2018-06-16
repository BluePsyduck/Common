<?php

namespace BluePsyduckTest\Common\Data;

use ArrayIterator;
use BluePsyduck\Common\Data\DataBuilder;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * The PHPUnit test of the data builder class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 *
 * @coversDefaultClass \BluePsyduck\Common\Data\DataBuilder
 */
class DataBuilderTest extends TestCase
{
    /**
     * Tests the constructing.
     */
    public function testConstruct()
    {
        $dataBuilder = new DataBuilder();
        $this->assertSame([], $dataBuilder->getData());
    }

    /**
     * Tests setting a value.
     */
    public function testSet()
    {
        $dataBuilder = new DataBuilder();
        $this->assertSame($dataBuilder, $dataBuilder->set('abc', 'def'));
        $this->assertSame($dataBuilder, $dataBuilder->set('ghi', 42));
        $this->assertSame($dataBuilder, $dataBuilder->set('jkl', 13.37));
        $this->assertSame($dataBuilder, $dataBuilder->set('mno', true));
        $this->assertSame($dataBuilder, $dataBuilder->set('fail', 'pqr', 'pqr'));
        $this->assertSame($dataBuilder, $dataBuilder->set('stu', '42', 42));

        $expectedData = [
            'abc' => 'def',
            'ghi' => 42,
            'jkl' => 13.37,
            'mno' => true,
            'stu' => '42'
        ];
        $this->assertSame($expectedData, $dataBuilder->getData());
    }

    /**
     * Tests setting a boolean value.
     */
    public function testSetBoolean()
    {
        $dataBuilder = new DataBuilder();
        $this->assertSame($dataBuilder, $dataBuilder->setBoolean('abc', true));
        $this->assertSame($dataBuilder, $dataBuilder->setBoolean('def', false));
        $this->assertSame($dataBuilder, $dataBuilder->setBoolean('fail', true, true));
        $this->assertSame($dataBuilder, $dataBuilder->setBoolean('fail', false, false));
        $this->assertSame($dataBuilder, $dataBuilder->setBoolean('ghi', 42));

        $expectedData = [
            'abc' => true,
            'def' => false,
            'ghi' => true
        ];
        $this->assertSame($expectedData, $dataBuilder->getData());
    }

    /**
     * Tests setting an integer value.
     */
    public function testSetInteger()
    {
        $dataBuilder = new DataBuilder();
        $this->assertSame($dataBuilder, $dataBuilder->setInteger('abc', 42));
        $this->assertSame($dataBuilder, $dataBuilder->setInteger('def', -21));
        $this->assertSame($dataBuilder, $dataBuilder->setInteger('ghi', 0));
        $this->assertSame($dataBuilder, $dataBuilder->setInteger('fail', 1337, 1337));
        $this->assertSame($dataBuilder, $dataBuilder->setInteger('jkl', '27'));

        $expectedData = [
            'abc' => 42,
            'def' => -21,
            'ghi' => 0,
            'jkl' => 27
        ];
        $this->assertSame($expectedData, $dataBuilder->getData());
    }

    /**
     * Tests setting a float value.
     */
    public function testSetFloat()
    {
        $dataBuilder = new DataBuilder();
        $this->assertSame($dataBuilder, $dataBuilder->setFloat('abc', 4.2));
        $this->assertSame($dataBuilder, $dataBuilder->setFloat('def', -2.1));
        $this->assertSame($dataBuilder, $dataBuilder->setFloat('ghi', 0.));
        $this->assertSame($dataBuilder, $dataBuilder->setFloat('fail', 13.37, 13.37));
        $this->assertSame($dataBuilder, $dataBuilder->setFloat('jkl', '2.7'));

        $expectedData = [
            'abc' => 4.2,
            'def' => -2.1,
            'ghi' => 0.,
            'jkl' => 2.7
        ];
        $this->assertSame($expectedData, $dataBuilder->getData());
    }

    /**
     * Tests setting a string value.
     */
    public function testSetString()
    {
        $dataBuilder = new DataBuilder();
        $this->assertSame($dataBuilder, $dataBuilder->setString('abc', 'def'));
        $this->assertSame($dataBuilder, $dataBuilder->setString('ghi', ''));
        $this->assertSame($dataBuilder, $dataBuilder->setString('fail', 'jkl', 'jkl'));
        $this->assertSame($dataBuilder, $dataBuilder->setString('mno', 42));

        $expectedData = [
            'abc' => 'def',
            'ghi' => '',
            'mno' => '42',
        ];
        $this->assertSame($expectedData, $dataBuilder->getData());
    }

    /**
     * Tests setting a date and time value.
     */
    public function testSetDateTime()
    {
        $dataBuilder = new DataBuilder();
        $this->assertSame(
            $dataBuilder,
            $dataBuilder->setDateTime('abc', new DateTime('2038-01-19 03:14:07'), 'Y-m-d')
        );
        $this->assertSame(
            $dataBuilder,
            $dataBuilder->setDateTime('def', new DateTime('2038-01-19 03:14:07'), 'Y-m-d H:i:s')
        );
        $this->assertSame(
            $dataBuilder,
            $dataBuilder->setDateTime('fail', new DateTime('2038-01-19 03:14:07'), 'Y-m-d', '2038-01-19')
        );

        $expectedData = [
            'abc' => '2038-01-19',
            'def' => '2038-01-19 03:14:07'
        ];
        $this->assertSame($expectedData, $dataBuilder->getData());
    }

    /**
     * Tests setting an array.
     */
    public function testSetArray()
    {
        $dataBuilder = new DataBuilder();

        $value = [
            'abc' => 42,
            'def' => 13.37,
            'ghi' => 'jkl'
        ];
        $this->assertSame($dataBuilder, $dataBuilder->setArray('foo', $value));
        $this->assertSame($dataBuilder, $dataBuilder->setArray('bar', $value, 'strval'));
        $this->assertSame($dataBuilder, $dataBuilder->setArray('baz', new ArrayIterator($value)));
        $this->assertSame($dataBuilder, $dataBuilder->setArray('mno', []));
        $this->assertSame($dataBuilder, $dataBuilder->setArray('fail', 42));
        $this->assertSame($dataBuilder, $dataBuilder->setArray('fail', [], null, []));

        $expectedData = [
            'foo' => [
                'abc' => 42,
                'def' => 13.37,
                'ghi' => 'jkl'
            ],
            'bar' => [
                'abc' => '42',
                'def' => '13.37',
                'ghi' => 'jkl'
            ],
            'baz' => [
                'abc' => 42,
                'def' => 13.37,
                'ghi' => 'jkl'
            ],
            'mno' => []
        ];
        $this->assertSame($expectedData, $dataBuilder->getData());
    }
}
