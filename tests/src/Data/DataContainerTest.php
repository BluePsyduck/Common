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
 * @coversDefaultClass BluePsyduck\Common\Data\DataContainer
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
     * @dataProvider provideConstruct
     */
    public function testConstructAndGet($data, array $expectedData)
    {
        $dataContainer = new DataContainer($data);
        $this->assertEquals($expectedData, $dataContainer->getData());
    }

    /**
     * Tests getting a value.
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

        $this->assertEquals(true, $dataContainer->get('abc'));
        $this->assertEquals(42, $dataContainer->get(['def']));
        $this->assertEquals('jkl', $dataContainer->get(['ghi']));
        $this->assertEquals(13.37, $dataContainer->get(['first', 'second', 'mno']));
        $this->assertEquals(['stu', 'vwx'], $dataContainer->get(['first', 'second', 'pqr']));
        $this->assertEquals(null, $dataContainer->get(['none']));
        $this->assertEquals($this, $dataContainer->get(['none'], $this));
    }

    /**
     * Tests getting a boolean.
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

        $this->assertEquals(true, $dataContainer->getBoolean('abc'));
        $this->assertEquals(false, $dataContainer->getBoolean(['def']));
        $this->assertEquals(true, $dataContainer->getBoolean(['ghi']));
        $this->assertEquals(true, $dataContainer->getBoolean(['first', 'second', 'jkl']));
        $this->assertEquals(false, $dataContainer->getBoolean(['none']));
        $this->assertEquals(true, $dataContainer->getBoolean(['none'], true));
    }

    /**
     * Tests getting a integer.
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

        $this->assertEquals(42, $dataContainer->getInteger('abc'));
        $this->assertEquals(-21, $dataContainer->getInteger(['def']));
        $this->assertEquals(1337, $dataContainer->getInteger(['ghi']));
        $this->assertEquals(27, $dataContainer->getInteger(['first', 'second', 'jkl']));
        $this->assertEquals(0, $dataContainer->getInteger(['none']));
        $this->assertEquals(123, $dataContainer->getInteger(['none'], 123));
    }

    /**
     * Tests getting a float.
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

        $this->assertEquals(4.2, $dataContainer->getFloat('abc'));
        $this->assertEquals(-2.1, $dataContainer->getFloat(['def']));
        $this->assertEquals(13.37, $dataContainer->getFloat(['ghi']));
        $this->assertEquals(27., $dataContainer->getFloat(['first', 'second', 'jkl']));
        $this->assertEquals(0, $dataContainer->getFloat(['none']));
        $this->assertEquals(12.3, $dataContainer->getFloat(['none'], 12.3));
    }

    /**
     * Tests getting a string.
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

        $this->assertEquals('def', $dataContainer->getString('abc'));
        $this->assertEquals('42', $dataContainer->getString(['ghi']));
        $this->assertEquals('mno', $dataContainer->getString(['first', 'second', 'jkl']));
        $this->assertEquals('', $dataContainer->getString(['none']));
        $this->assertEquals('pqr', $dataContainer->getString(['none'], 'pqr'));
    }

    /**
     * Tests getting a DateTime.
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

        $this->assertEquals(['def' => 'ghi'], $dataContainer->getArray('abc'));
        $this->assertEquals(['jkl' => 'mno'], $dataContainer->getArray(['first', 'second']));
        $this->assertEquals([], $dataContainer->getArray('pqr'));
        $this->assertEquals(['stu' => 'vwx'], $dataContainer->getArray('pqr', ['stu' => 'vwx']));
        $this->assertEquals([], $dataContainer->getArray('none'));
        $this->assertEquals(['stu' => 'vwx'], $dataContainer->getArray('none', ['stu' => 'vwx']));
    }

    /**
     * Tests getting an object array-
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
