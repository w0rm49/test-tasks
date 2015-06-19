<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "books".
 *
 * @property string $id
 * @property string $name
 * @property string $date_create
 * @property string $date_update
 * @property string $preview
 * @property string $date
 * @property string $author_id
 * @property Author $author
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['date_create', 'date_update', 'date'], 'safe'],
            [['author_id'], 'integer'],
            [['name', 'preview'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID книги',
            'name' => 'Название книги',
            'date_create' => 'Дата создания записи',
            'date_update' => 'Дата обновления записи',
            'preview' => 'Превью',
            'date' => 'Дата выхода книги',
            'author_id' => 'ID автора',
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->date_create = date('Y-m-d H:i:s');
        }
        $this->date_update = date('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }

    public function getAuthor()
    {
        return $this->hasOne(Author::className(), ['id' => 'author_id']);
    }
}
