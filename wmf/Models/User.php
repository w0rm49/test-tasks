<?php

namespace Models;
use Util\Model;


/**
 * Class User
 *
 * @property integer $user_id
 * @property string $email
 * @property string $password
 * @property string $name
 * @property string $surname
 * @property string $birth
 * @property integer $gender
 * @property string $photo
 */
class User extends Model{

    const TABLE = 'users';
    const PK = 'user_id';

    public function fields()
    {
        return ['user_id', 'email', 'password', 'name', 'surname', 'birth', 'gender', 'photo'];
    }

    public function save()
    {
        $fields = '`' . implode('`, `', $this->fields()) . '`';
        $sql = "UPDATE `users` ({$fields}) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        /** @var $st \mysqli_stmt */
        $st = \App::obj()->db->prepare($sql);
        $st->bind_param('isssssis',
            $this->user_id, $this->email, $this->password, $this->name,
            $this->surname, $this->birth, $this->gender, $this->photo);
        $st->execute();
    }

    /**
     * @param $data array
     * @return self|null
     */
    public static function create($data)
    {
        /** @var $db \Util\Db */
        $db = \App::obj()->db;
        $id = $db->insert(self::TABLE, $data);
        return (int) $id;
    }

    /**
     * @param $email
     * @return null|User
     */
    public static function findByEmail($email)
    {
        /** @var $db \Util\Db */
        $db = \App::obj()->db;
        $data = $db->query("SELECT * FROM `users` WHERE `email` = :email", [':email' => $email]);
        if (count($data) > 0) {
            $class = get_class();
            $obj = new $class;
            $obj->fill($data[0]);
            return $obj;
        }
        return null;
    }

    /**
     * @param $email
     * @param $password
     * @return bool|int
     */
    public static function checkUser($email, $password) {
        /** @var $db \Util\Db */
        $db = \App::obj()->db;
        $data = $db->query("SELECT `email`, `password` FROM `users` WHERE `email` = :email", [':email' => $email]);
        if (count($data) === 0) {
            return false;
        }
        return \App::obj()->auth->checkPassword($password, $data[0]['password']);
    }

    public static function checkEmail($email)
    {
        /** @var $db \Util\Db */
        $db = \App::obj()->db;
        $data = $db->query("SELECT `email` FROM `users` WHERE `email` = :email", [':email' => $email]);
        return (count($data) === 0);
    }

    public static function find($pk)
    {
        $pkField = static::PK;
        $sql = sprintf("SELECT * FROM %s WHERE `%s` = :%s", static::TABLE, static::PK, static::PK);
        /** @var $db \Util\Db */
        $db = \App::obj()->db;
        $data = $db->query($sql, [":{$pkField}" => $pk]);
        $class = get_class();
        $obj = new self;
        return (count($data) > 0)
            ? $obj->fill($data[0])
            : null;
    }

}