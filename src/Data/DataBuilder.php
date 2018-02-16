<?php

namespace BluePsyduck\Common\Data;

use DateTime;
use Traversable;

/**
 * A class able to build up an array while ensuring specific data types.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class DataBuilder
{
    /**
     * The built data array.
     * @var array
     */
    protected $data = [];

    /**
     * Returns the built data array.
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets a value to the builder.
     * @param string $key
     * @param mixed $value
     * @param mixed $ignoredDefaultValue
     * @return $this
     */
    public function set(string $key, $value, $ignoredDefaultValue = null)
    {
        if ($value !== $ignoredDefaultValue) {
            $this->data[$key] = $value;
        }
        return $this;
    }

    /**
     * Sets a boolean value to the builder.
     * @param string $key
     * @param mixed $value
     * @param bool|null $ignoredDefaultValue
     * @return $this
     */
    public function setBoolean(string $key, $value, bool $ignoredDefaultValue = null)
    {
        $this->set($key, (bool) $value, $ignoredDefaultValue);
        return $this;
    }

    /**
     * Sets an integer value to the builder.
     * @param string $key
     * @param mixed $value
     * @param int|null $ignoredDefaultValue
     * @return $this
     */
    public function setInteger(string $key, $value, int $ignoredDefaultValue = null)
    {
        $this->set($key, (int) $value, $ignoredDefaultValue);
        return $this;
    }

    /**
     * Sets a float value to the builder.
     * @param string $key
     * @param mixed $value
     * @param float|null $ignoredDefaultValue
     * @return $this
     */
    public function setFloat(string $key, $value, float $ignoredDefaultValue = null)
    {
        $this->set($key, (float) $value, $ignoredDefaultValue);
        return $this;
    }

    /**
     * Sets a string value to the builder.
     * @param string $key
     * @param mixed $value
     * @param string|null $ignoredDefaultValue
     * @return $this
     */
    public function setString(string $key, $value, string $ignoredDefaultValue = null)
    {
        $this->set($key, (string) $value, $ignoredDefaultValue);
        return $this;
    }

    /**
     * Sets a date and time value to the builder.
     * @param string $key
     * @param mixed $value
     * @param string $format
     * @param string|null $ignoredDefaultValue
     * @return $this
     */
    public function setDateTime(string $key, $value, string $format, string $ignoredDefaultValue = null)
    {
        if ($value instanceof DateTime) {
            $this->set($key, $value->format($format), $ignoredDefaultValue);
        }
        return $this;
    }

    /**
     * Sets an array of values to the builder.
     * @param string $key
     * @param mixed $value
     * @param callable|null $callback
     * @param array|null $ignoredDefaultValue
     * @return $this
     */
    public function setArray(string $key, $value, callable $callback = null, array $ignoredDefaultValue = null)
    {
        if ($value instanceof Traversable) {
            $value = iterator_to_array($value);
        }
        if (is_array($value)) {
            if (is_callable($callback)) {
                $value = array_map($callback, $value);
            }
            $this->set($key, $value, $ignoredDefaultValue);
        }
        return $this;
    }
}