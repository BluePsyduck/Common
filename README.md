# BluePsyduck's Common Library

This library contains some classes commonly used in other projects.

## DataBuilder

The data builder is a class helping with building up an array with different values, ensuring that the values have the 
correct type. With all methods a default value can be specified which should be ignored and not added to the data array.

The data builder provides several methods for automatically casting the set values:

* **setBoolean():** Sets a boolean value.
* **setInteger():** Sets an integer number.
* **setFloat():** Sets a floating point number.
* **setString():** Sets a string value.
* **setDateTime():** Sets a date and time value in the specified format. If the passed value is not a `DateTime`
  instance, the value gets ignored automatically.
* **setArray():** Sets an array of value, optionally with a mapping method to e.g. cast each value.

###### Example

```php
<?php
use BluePsyduck\Common\Data\DataBuilder;

$dataBuilder = new DataBuilder();

// Set some values
$dataBuilder->setInteger('foo', 42)
            ->setString('bar', 'baz');

// Set a variable value unless it is a specific one
$age1 = 21;
$age2 = -1;
$dataBuilder->setInteger('age1', $age1, -1)
            ->setInteger('age2', $age2, -1); // $age2 will get ignored.

// Set a date and time value
$date = new DateTime('2038-01-19 03:14:07');
$dataBuilder->setDateTime('creationTime', $date, 'Y-m-d'); // Will set '2038-01-17' as value.

// Set an array of strings
$dataBuilder->setArray('data', ['foo', 'bar', 42], 'strval'); // Casts all array values to a string.
```

## DataContainer

The data container wraps around a deep array and is able to access and map the elements of the array without the need 
to check if any level key exists. All methods provide a default value to fall back to when a key is not defined.

The data container provides several methods to automatically map the data:

* **getBoolean():** Reads a boolean value.
* **getInteger():** Reads an integer number.
* **getFloat():** Reads a floating point number.
* **getString():** Reads a string value.
* **getDateTime():** Reads a date and time value. An integer value will be interpreted as Unix timestamp, a string value 
  as date string.
* **getArray():** Reads an array value.
* **getObject():** Reads the value and returns a new `DataContainer` instance with that value. 
* **getObjectArray():** Reads an array value and casts every item to a new instance of `DataContainer`.


###### Example

```php
<?php
use BluePsyduck\Common\Data\DataContainer;

$data = [
    'foo' => 'bar',
    'first' => [
        'second' => 42
    ]
];
$dataContainer = new DataContainer($data);

// Accessing the first level
echo $dataContainer->getString('foo'); // 'bar'

// Accessing the second level
echo $dataContainer->getObject('first')->getInteger('second'); // 42
echo $dataContainer->getInteger(['first', 'second']); // 42

// Default values for undefined keys
echo $dataContainer->getFloat('missing', 13.37); // 13.37 
```