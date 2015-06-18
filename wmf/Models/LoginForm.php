<?php

namespace Models;


use Util\Form;

/**
 * Class LoginForm
 * @package Models
 * @property string $email
 * @property string $password
 */
class LoginForm extends Form{

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'email' => [
                ['max', [255], 'failLogin'],
            ],
            'password' => [
                ['max', [255], 'failLogin'],
            ],
        ];
    }

    public function validate()
    {
        parent::validate();
        if ($this->valid) {
            if (!User::checkUser($this->email, $this->password)) {
                $this->errors['password'] = ['failLogin'];
                $this->valid = false;
            }
        }
        return $this->valid;
    }
}