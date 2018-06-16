<?php

namespace BluePsyduckTest\Common\Data;

use BluePsyduck\Common\Data\DataContainer;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * The PHPUnit test of the data container class.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 *
 * @coversDefaultClass \BluePsyduck\Common\Data\DataContainer
 */
class DataContainerTest extends TestCase
{
    /**
     * Provides the data for the construct test.
     * @return array
     */
    public function provideConstruct(): array
    {
        return [
            [['abc' => 'def'], ['abc' => 'def']],
            [null, []]
        ];
    }

    /**
     * Tests the constructing and getting the data.
     * @param mixed $data
     * @param array $expectedData
     * @covers ::__construct
     * @covers ::getData
     * @dataProvider provideConstruct
     */
    public function testConstructAndGet($data, array $expectedData)
    {
        $dataContainer = new DataContainer($data);
        $this->assertSame($expectedData, $dataContainer->getData());
    }

    /**
     * Tests getting a value.
     * @covers ::get
     */
    public function testGet()
    {
        $dataContainer = new DataContainer([
            'abc' => true,
            'def' => 42,
            'ghi' => 'jkl',
            'first' => [
                'second' => [
                    'mno' => 13.37,
                    'pqr' => ['stu', 'vwx']
                ]
            ]
        ]);

        $this->assertSame(true, $dataContainer->get('abc'));
        $this->assertSame(42, $dataContainer->get(['def']));
        $this->assertSame('jkl', $dataContainer->get(['ghi']));
        $this->assertSame(13.37, $dataContainer->get(['first', 'second', 'mno']));
        $this->assertSame(['stu', 'vwx'], $dataContainer->get(['first', 'second', 'pqr']));
        $this->assertSame(null, $dataContainer->get(['none']));
        $this->assertSame($this, $dataContainer->get(['none'], $this));
    }

    /**
     * Tests getting a boolean.
     * @covers ::getBoolean
     */
    public function testGetBoolean()
    {
        $dataContainer = new DataContainer([
            'abc' => true,
            'def' => false,
            'ghi' => 42,
            'first' => [
                'second' => [
                    'jkl' => true
                ]
            ]
        ]);

        $this->assertSame(true, $dataContainer->getBoolean('abc'));
        $this->assertSame(false, $dataContainer->getBoolean(['def']));
        $this->assertSame(true, $dataContainer->getBoolean(['ghi']));
        $this->assertSame(true, $dataContainer->getBoolean(['first', 'second', 'jkl']));
        $this->assertSame(false, $dataContainer->getBoolean(['none']));
        $this->assertSame(true, $dataContainer->getBoolean(['none'], true));
    }

    /**
     * Tests getting a integer.
     * @covers ::getInteger
     */
    public function testGetInteger()
    {
        $dataContainer = new DataContainer([
            'abc' => 42,
            'def' => -21,
            'ghi' => '1337',
            'first' => [
                'second' => [
                    'jkl' => 27
                ]
            ]
        ]);

        $this->assertSame(42, $dataContainer->getInteger('abc'));
        $this->assertSame(-21, $dataContainer->getInteger(['def']));
        $this->assertSame(1337, $dataContainer->getInteger(['ghi']));
        $this->assertSame(27, $dataContainer->getInteger(['first', 'second', 'jkl']));
        $this->assertSame(0, $dataContainer->getInteger(['none']));
        $this->assertSame(123, $dataContainer->getInteger(['none'], 123));
    }

    /**
     * Tests getting a float.
     * @covers ::getFloat
     */
    public function testGetFloat()
    {
        $dataContainer = new DataContainer([
            'abc' => 4.2,
            'def' => -2.1,
            'ghi' => '13.37',
            'first' => [
                'second' => [
                    'jkl' => 27
                ]
            ]
        ]);

        $this->assertSame(4.2, $dataContainer->getFloat('abc'));
        $this->assertSame(-2.1, $dataContainer->getFloat(['def']));
        $this->assertSame(13.37, $dataContainer->getFloat(['ghi']));
        $this->assertSame(27., $dataContainer->getFloat(['first', 'second', 'jkl']));
        $this->assertSame(0., $dataContainer->getFloat(['none']));
        $this->assertSame(12.3, $dataContainer->getFloat(['none'], 12.3));
    }

    /**
     * Tests getting a string.
     * @covers ::getString
     */
    public function testGetString()
    {
        $dataContainer = new DataContainer([
            'abc' => 'def',
            'ghi' => 42,
            'first' => [
                'second' => [
                    'jkl' => 'mno'
                ]
            ]
        ]);

        $this->assertSame('def', $dataContainer->getString('abc'));
        $this->assertSame('42', $dataContainer->getString(['ghi']));
        $this->assertSame('mno', $dataContainer->getString(['first', 'second', 'jkl']));
        $this->assertSame('', $dataContainer->getString(['none']));
        $this->assertSame('pqr', $dataContainer->getString(['none'], 'pqr'));
    }

    /**
     * Tests getting a DateTime.
     * @covers ::getDateTime
     */
    public function testGetDateTime()
    {
        $dataContainer = new DataContainer([
            'abc' => 1234567890,
            'def' => '2038-01-19 03:14:07',
            'first' => [
                'second' => [
                    'ghi' => 123465789,
                    'jkl' => '2038-01-18 03:14:07'
                ]
            ]
        ]);

        $this->assertEquals(new DateTime('2009-02-13 23:31:30'), $dataContainer->getDateTime('abc'));
        $this->assertEquals(new DateTime('2038-01-19 03:14:07'), $dataContainer->getDateTime(['def']));
        $this->assertEquals(
            new DateTime('1973-11-30 00:03:09'),
            $dataContainer->getDateTime(['first', 'second', 'ghi'])
        );
        $this->assertEquals(
            new DateTime('2038-01-18 03:14:07'),
            $dataContainer->getDateTime(['first', 'second', 'jkl'])
        );
        $this->assertEquals(null, $dataContainer->getDateTime(['none']));
        $this->assertEquals(
            new DateTime('2038-01-17 03:14:07'),
            $dataContainer->getDateTime(['none'], new DateTime('2038-01-17 03:14:07'))
        );
    }

    /**
     * Tests getting an object.
     * @covers ::getObject
     * @covers ::createObject
     */
    public function testGetObject()
    {
        $dataContainer = new DataContainer([
            'abc' => [
                'def' => 'ghi'
            ],
            'first' => [
                'second' => [
                    'jkl' => 'mno'
                ]
            ]
        ]);

        $this->assertEquals(new DataContainer(['def' => 'ghi']), $dataContainer->getObject('abc'));
        $this->assertEquals(new DataContainer(['jkl' => 'mno']), $dataContainer->getObject(['first', 'second']));
        $this->assertEquals(new DataContainer([]), $dataContainer->getObject('none'));
    }

    /**
     * Tests getting an array.
     * @covers ::getArray
     */
    public function testGetArray()
    {
        $dataContainer = new DataContainer([
            'abc' => [
                'def' => 'ghi'
            ],
            'first' => [
                'second' => [
                    'jkl' => 'mno'
                ]
            ],
            'pqr' => 42
        ]);

        $this->assertSame(['def' => 'ghi'], $dataContainer->getArray('abc'));
        $this->assertSame(['jkl' => 'mno'], $dataContainer->getArray(['first', 'second']));
        $this->assertSame([], $dataContainer->getArray('pqr'));
        $this->assertSame(['stu' => 'vwx'], $dataContainer->getArray('pqr', ['stu' => 'vwx']));
        $this->assertSame([], $dataContainer->getArray('none'));
        $this->assertSame(['stu' => 'vwx'], $dataContainer->getArray('none', ['stu' => 'vwx']));
    }

    /**
     * Tests getting an object array-
     * @covers ::getObjectArray
     * @covers ::createObject
     */
    public function testGetObjectArray()
    {
        $dataContainer = new DataContainer([
            'abc' => [
                ['def'],
                ['ghi']
            ],
            'first' => [
                'second' => [
                    ['jkl'],
                    ['mno']
                ]
            ],
            'pqr' => 42
        ]);

        $this->assertEquals(
            [new DataContainer(['def']), new DataContainer(['ghi'])],
            $dataContainer->getObjectArray('abc')
        );
        $this->assertEquals(
            [new DataContainer(['jkl']), new DataContainer(['mno'])],
            $dataContainer->getObjectArray(['first', 'second'])
        );
        $this->assertEquals([], $dataContainer->getObjectArray(['pqr']));
        $this->assertEquals([], $dataContainer->getObjectArray(['none']));
    }
}
