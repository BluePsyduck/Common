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
 * @coversDefaultClass BluePsyduck\Common\Data\DataBuilder
 */
class DataBuilderTest extends TestCase
{
    /**
     * Tests the constructing.
     */
    public function testConstruct()
    {
        $dataBuilder = new DataBuilder();
        $this->assertEquals([], $dataBuilder->getData());
    }

    /**
     * Tests setting a value.
     */
    public function testSet()
    {
        $dataBuilder = new DataBuilder();
        $this->assertEquals($dataBuilder, $dataBuilder->set('abc', 'def'));
        $this->assertEquals($dataBuilder, $dataBuilder->set('ghi', 42));
        $this->assertEquals($dataBuilder, $dataBuilder->set('jkl', 13.37));
        $this->assertEquals($dataBuilder, $dataBuilder->set('mno', true));
        $this->assertEquals($dataBuilder, $dataBuilder->set('fail', 'pqr', 'pqr'));
        $this->assertEquals($dataBuilder, $dataBuilder->set('stu', '42', 42));

        $expectedData = [
            'abc' => 'def',
            'ghi' => 42,
            'jkl' => 13.37,
            'mno' => true,
            'stu' => '42'
        ];
        $this->assertEquals($expectedData, $dataBuilder->getData());
    }

    /**
     * Tests setting a boolean value.
     */
    public function testSetBoolean()
    {
        $dataBuilder = new DataBuilder();
        $this->assertEquals($dataBuilder, $dataBuilder->setBoolean('abc', true));
        $this->assertEquals($dataBuilder, $dataBuilder->setBoolean('def', false));
        $this->assertEquals($dataBuilder, $dataBuilder->setBoolean('fail', true, true));
        $this->assertEquals($dataBuilder, $dataBuilder->setBoolean('fail', false, false));
        $this->assertEquals($dataBuilder, $dataBuilder->setBoolean('ghi', 42));

        $expectedData = [
            'abc' => true,
            'def' => false,
            'ghi' => true
        ];
        $this->assertEquals($expectedData, $dataBuilder->getData());
    }

    /**
     * Tests setting an integer value.
     */
    public function testSetInteger()
    {
        $dataBuilder = new DataBuilder();
        $this->assertEquals($dataBuilder, $dataBuilder->setInteger('abc', 42));
        $this->assertEquals($dataBuilder, $dataBuilder->setInteger('def', -21));
        $this->assertEquals($dataBuilder, $dataBuilder->setInteger('ghi', 0));
        $this->assertEquals($dataBuilder, $dataBuilder->setInteger('fail', 1337, 1337));
        $this->assertEquals($dataBuilder, $dataBuilder->setInteger('jkl', '27'));

        $expectedData = [
            'abc' => 42,
            'def' => -21,
            'ghi' => 0,
            'jkl' => 27
        ];
        $this->assertEquals($expectedData, $dataBuilder->getData());
    }

    /**
     * Tests setting a float value.
     */
    public function testSetFloat()
    {
        $dataBuilder = new DataBuilder();
        $this->assertEquals($dataBuilder, $dataBuilder->setFloat('abc', 4.2));
        $this->assertEquals($dataBuilder, $dataBuilder->setFloat('def', -2.1));
        $this->assertEquals($dataBuilder, $dataBuilder->setFloat('ghi', 0.));
        $this->assertEquals($dataBuilder, $dataBuilder->setFloat('fail', 13.37, 13.37));
        $this->assertEquals($dataBuilder, $dataBuilder->setFloat('jkl', '2.7'));

        $expectedData = [
            'abc' => 4.2,
            'def' => -2.1,
            'ghi' => 0.,
            'jkl' => 2.7
        ];
        $this->assertEquals($expectedData, $dataBuilder->getData());
    }

    /**
     * Tests setting a string value.
     */
    public function testSetString()
    {
        $dataBuilder = new DataBuilder();
        $this->assertEquals($dataBuilder, $dataBuilder->setString('abc', 'def'));
        $this->assertEquals($dataBuilder, $dataBuilder->setString('ghi', ''));
        $this->assertEquals($dataBuilder, $dataBuilder->setString('fail', 'jkl', 'jkl'));
        $this->assertEquals($dataBuilder, $dataBuilder->setString('mno', 42));

        $expectedData = [
            'abc' => 'def',
            'ghi' => '',
            'mno' => '42',
        ];
        $this->assertEquals($expectedData, $dataBuilder->getData());
    }

    /**
     * Tests setting a date and time value.
     */
    public function testSetDateTime()
    {
        $dataBuilder = new DataBuilder();
        $this->assertEquals(
            $dataBuilder,
            $dataBuilder->setDateTime('abc', new DateTime('2038-01-19 03:14:07'), 'Y-m-d')
        );
        $this->assertEquals(
            $dataBuilder,
            $dataBuilder->setDateTime('def', new DateTime('2038-01-19 03:14:07'), 'Y-m-d H:i:s')
        );
        $this->assertEquals(
            $dataBuilder,
            $dataBuilder->setDateTime('fail', new DateTime('2038-01-19 03:14:07'), 'Y-m-d', '2038-01-19')
        );

        $expectedData = [
            'abc' => '2038-01-19',
            'def' => '2038-01-19 03:14:07'
        ];
        $this->assertEquals($expectedData, $dataBuilder->getData());
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
        $this->assertEquals($dataBuilder, $dataBuilder->setArray('foo', $value));
        $this->assertEquals($dataBuilder, $dataBuilder->setArray('bar', $value, 'strval'));
        $this->assertEquals($dataBuilder, $dataBuilder->setArray('baz', new ArrayIterator($value)));
        $this->assertEquals($dataBuilder, $dataBuilder->setArray('mno', []));
        $this->assertEquals($dataBuilder, $dataBuilder->setArray('fail', 42));
        $this->assertEquals($dataBuilder, $dataBuilder->setArray('fail', [], null, []));

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
        $this->assertEquals($expectedData, $dataBuilder->getData());
    }
}
