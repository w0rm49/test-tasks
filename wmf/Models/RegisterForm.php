<?php

namespace Models;


use Util\Form;

/**
 * Class RegisterForm
 * @package Models
 * @property string
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property string $gender
 * @property string $birth
 * @property string $password
 * @property array $photo
 */
class RegisterForm extends Form
{

    /**
     * Validation parameters for fields. format [ fieldName => [ filter, parameters, errorMessage ], ... ]
     * @return array
     */
    public function fields()
    {
        return [
            'name' => [
                ["required", [], "requiredField"],
                ["max", [255], "maxLengthField"],
                ["regexp", ['^[a-zA-Zа-яА-Я\\\'\-\ ]+$'], "nameSymbols"],
                ["regexp", ['^[^\\\'\-]'], "nameBeginningApos"],
                ["regexp", ['[^\-]$'], "nameTailDash"],
            ],
            'surname' => [
                ["required", [], "requiredField"],
                ["max", [255], "maxLengthField"],
                ["regexp", ['^[a-zA-Zа-яА-Я\\\'\-\ ]+$'], "nameSymbols"],
                ["regexp", ['^[^\\\'\-]'], "nameSymbols"],
                ["regexp", ['[^\-]$'], "nameSymbols"],
            ],
            "email" => [
                ["required", [], "requiredField"],
                ["max", [255], "maxLengthField"],
                ["regexp", ['^([\w-]+\.)*[\w-]+@[\w-]+(\.[\w_-]+)*\.[a-zA-Z]{2,10}$'], "wrongEmail"],
                ["uniqueMail", [], "mailTaken"]
            ],
            'gender' => [
                ["variants", [['male', 'female']], 'wrongGender']
            ],
            'birth' => [
                ["required", [], "requiredField"],
                ["date", [], "wrongDate"]
            ],
            'photo' => [
                ["mime", ["image\/.*"], 'onlyImages'],
                ["size", [1024 * 1024 * 2], 'photoSize']
            ],
            'password' => [
                ['min', [6], 'minLengthPass'],
                ['max', [255], 'maxLengthPass'],
                ['equals', ['password2'], 'passwordsNotMatch']
            ],
            'password2' => [],
        ];
    }

    public function export()
    {
        if (!$this->valid) {
            return false;
        }
        return [
            'email' => $this->email,
            'name' => $this->name,
            'surname' => $this->surname,
            'gender' => $this->gender === 'male' ? 0 : 1,
            'birth' => $this->birth,
            'photo' => $this->handlePhoto(),
            'password' => \App::obj()->auth->hashPassword($this->password)
        ];
    }

    private function handlePhoto()
    {
        if (!is_array($this->photo) || $this->photo['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        $ext = pathinfo($this->photo['name'], PATHINFO_EXTENSION);
        $newFileName = \App::obj()->getConfig('uploadDir') . DIRECTORY_SEPARATOR . md5($this->email) . '.' . $ext;
        $webPath = str_replace([$_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR], ['', '/'], $newFileName);
        $moved = move_uploaded_file($this->photo['tmp_name'], $newFileName);
        return $moved === false ? null : $webPath;
    }

}