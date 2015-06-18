<?php

namespace Util;


use Models\User;

abstract class Form {

    protected $attributes = [];
    protected $errors = [];
    protected $valid = false;

    /**
     * @return array
     */
    abstract public function fields();

    public function __construct($fields = [])
    {
        $this->fill($fields);
    }

    protected function fill($fields)
    {
        $allowedFields = array_keys($this->fields());
        foreach ($fields as $key => $val) {
            if (in_array($key, $allowedFields)) {
                $this->attributes[$key] = $val;
            }
        }
    }

    public function validate()
    {
        /** @var $that Form */
        $that = $this;
        $this->errors = [];
        $attributes = array_keys($this->fields());

        $tmp = array_reduce($attributes, function($result, $field) use ($that) {
            $parameters = $that->fields()[$field];
            $fieldResult = $that->validateField($that->attributes[$field], $parameters);
            if($fieldResult['errors'] !== []) {
                $result['errors'][$field] = $fieldResult['errors'];
            }
            return [
                'result' => $result['result'] && $fieldResult['result'],
                'errors' => $result['errors']
            ];
        }, ['result' => true, 'errors' => []]);

        $this->valid = $tmp['result'];
        $this->errors = $tmp['errors'];
        return $this->valid;
    }

    public function isValid()
    {
        return $this->valid;
    }

    public function getErrors($attr = false)
    {
        if ($attr === false) {
            return $this->errors;
        }
        return isset($this->errors[$attr]) ? $this->errors[$attr] : [];
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function __get($name) {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }
        return null;
    }

    protected function filter($input) {
        if (!is_array($input)) {
            $input = trim($input);
        }
        return $input;
    }

    protected function validateField($input, $parameters)
    {
        $obj = $this;
        $input = $this->filter($input);

        $check = [
            'regexp' => function ($input, $regexp) {
                return (bool)preg_match('/' . $regexp . '/u', $input);
            },
            'required' => function ($input) {
                return strlen($input) > 0;
            },
            'max' => function ($input, $maxLength) {
                return strlen($input) <= $maxLength;
            },
            'min' => function ($input, $minLength) {
                return strlen($input) >= $minLength;
            },
            'variants' => function ($input, $variants) {
                return in_array($input, $variants);
            },
            'uniqueMail' => function ($input) {
                return User::checkEmail($input);
            },
            'date' => function($input) {
                return strtotime($input) !== false;
            },
            'mime' => function($input, $pattern) {
                if ($input['error'] === 4) {
                    return true;
                }
                if (!is_array($input) || !isset($input['type'])) {
                    return false;
                }
                return (bool)preg_match('/' . $pattern . '/', $input['type']);
            },
            'size' => function($input, $size) {
                if ($input['error'] === 4) {
                    return true;
                }
                if (!is_array($input) || !isset($input['size'])) {
                    return false;
                }
                return $input['size'] <= $size;
            },
            'equals' => function($input, $id) use ($obj) {
                return $obj->filter($obj->attributes[$id]) === $input;
            }
        ];

        return array_reduce($parameters, function($result, $element) use ($input, $check) {
            $func = $check[$element[0]];
            $valid = call_user_func_array(
                $func,
                array_merge([$input], (array)$element[1])
            );

            $errors = $valid ? [] : [$element[2]];
            return [
                'result' => $result['result'] && $valid,
                'errors' => array_merge($result['errors'], $errors)
            ];
        }, [
            'result' => true,
            'errors' => []
        ]);
    }

}