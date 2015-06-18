<?php

namespace Util;

/**
 * Class Model
 * @package Util
 *
 */
abstract class Model
{

    public $attributes;

    const TABLE = '';
    const PK = '';

    /**
     * @return array
     */
    abstract public function fields();

    public function __construct()
    {
        $this->attributes = array_reduce($this->fields(), function($result, $val) {
            $result[$val] = '';
            return $result;
        }, []);
    }

    public function __get($name)
    {
        if (in_array($name, static::fields())) {
            return $this->attributes[$name];
        }
        return null;
    }

    public function __set($name, $value)
    {
        if (in_array($name, static::fields())) {
            $this->attributes[$name] = $value;
        }
    }

    /**
     * @return static
     */
    protected static function instantiate()
    {
        $class = get_class();
        return new $class;
    }

    protected function fill($data)
    {
        foreach ($data as $key => $val) {
            if (in_array($key, $this->fields())) {
                $this->$key = $val;
            }
        }
        return $this;
    }
}