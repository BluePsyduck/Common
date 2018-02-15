<?php

namespace BluePsyduck\Common\Data;

use DateTime;

/**
 * A container class wrapping around a data array offering casting methods.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class DataContainer
{
    /**
     * The data array of the container.
     * @var array
     */
    protected $data = [];

    /**
     * Initializes the data container.
     * @param array $data
     */
    public function __construct($data)
    {
        if (is_array($data)) {
            $this->data = $data;
        }
    }

    /**
     * Returns the data array to work with.
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Returns a value from the data container.
     * @param string|string[]
     * @param mixed $default
     * @return mixed
     */
    public function get($keys, $default = null)
    {
        $result = $this->data;
        foreach (is_array($keys) ? $keys : [$keys] as $key) {
            if (is_array($result) && isset($result[$key])) {
                $result = $result[$key];
            } else {
                $result = $default;
                break;
            }
        }
        return $result;
    }

    /**
     * Returns a boolean value from the data container.
     * @param string|string[] $keys
     * @param bool $default
     * @return bool
     */
    public function getBoolean($keys, bool $default = false): bool
    {
        return (bool)$this->get($keys, $default);
    }

    /**
     * Returns an integer value from the data container.
     * @param string|string[] $keys
     * @param int $default
     * @return int
     */
    public function getInteger($keys, int $default = 0): int
    {
        return (int)$this->get($keys, $default);
    }

    /**
     * Returns a floating point number from the data container.
     * @param string|string[] $keys
     * @param float $default
     * @return float
     */
    public function getFloat($keys, float $default = 0.): float
    {
        return (float)$this->get($keys, $default);
    }

    /**
     * Returns a string from the data container.
     * @param string|string[] $keys
     * @param string $default
     * @return string
     */
    public function getString($keys, string $default = ''): string
    {
        return trim((string)$this->get($keys, $default));
    }

    /**
     * Returns a DateTime instance from the data container. A string value is interpreted as date string, an integer
     * value is interpreted as UNIX timestamp.
     * @param string|string[] $keys
     * @param DateTime|null $default
     * @return DateTime|null
     */
    public function getDateTime($keys, DateTime $default = null)
    {
        $value = $this->getString($keys);
        if (empty($value)) {
            $result = $default;
        } elseif ($value === (string)(int)$value) {
            $result = new DateTime();
            $result->setTimestamp((int)$value);
        } else {
            $result = new DateTime($value);
        }
        return $result;
    }

    /**
     * Returns an object from the data container. The result is always a new data container.
     * @param string|string[] $keys
     * @return DataContainer
     */
    public function getObject($keys): DataContainer
    {
        return $this->createObject($this->get($keys));
    }

    /**
     * Returns an array of values from the data containers.
     * @param string|string[] $keys
     * @param array $default
     * @return array
     */
    public function getArray($keys, array $default = []): array
    {
        $result = $this->get($keys, $default);
        if (!is_array($result)) {
            $result = $default;
        }
        return $result;
    }

    /**
     * Returns an array of objects from the data container. All values of the resulting array are casted to new
     * data container instances.
     * @param string|string[] $keys
     * @return array|DataContainer[]
     */
    public function getObjectArray($keys): array
    {
        return array_map(function ($value) {
            return $this->createObject($value);
        }, $this->getArray($keys));
    }

    /**
     * Creates a new instance with the specified data.
     * @param mixed $data
     * @return DataContainer
     */
    protected function createObject($data): DataContainer
    {
        return new static($data);
    }
}