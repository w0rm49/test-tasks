<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "authors".
 *
 * @property string $id
 * @property string $firstname
 * @property string $lastname
 */
class Author extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'authors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname'], 'required'],
            [['firstname', 'lastname'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Имя автора',
            'lastname' => 'Фамилия автора',
        ];
    }

    public static function getVariants()
    {
        return ArrayHelper::map(self::find()->all(), 'id', function($author) {
            return $author->firstname . ' ' . $author->lastname;
        });
    }
}
