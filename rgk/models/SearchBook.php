<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Book;

/**
 * SearchBook represents the model behind the search form about `app\models\Book`.
 */
class SearchBook extends Book
{

    public $date_from;
    public $date_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['id', 'author_id'], 'integer'],
            [['date_from', 'date_to'], 'filter', 'filter' => function($date) {
                $ts = strtotime($date);
                return $ts < 1 ? null : date('Y-m-d', $ts);
            }],
            [['date_create', 'date_update', 'preview', 'date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Book::find()->with('author');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'name' => $this->name,
            'author_id' => $this->author_id,
        ]);

        $query->andFilterWhere(['like', 'preview', $this->preview]);

        if ($this->date_to !== null && $this->date_from !== null) {
            $query->andFilterWhere(['between', 'date', $this->date_from, $this->date_to]);
        } elseif ($this->date_to !== null) {
            $query->andFilterWhere(['<=', 'date', $this->date_to]);
        } elseif ($this->date_from !== null) {
            $query->andFilterWhere(['>=', 'date', $this->date_from]);
        }

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'date_from' => 'Дата от',
            'date_to' => 'Дата до',
        ]);
    }
}
