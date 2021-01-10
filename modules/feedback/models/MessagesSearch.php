<?php

namespace app\modules\feedback\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\Messages;

/**
 * MessagesSearch represents the model behind the search form of `app\modules\feedback\models\Messages`.
 */
class MessagesSearch extends Messages
{
    public $unit;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'unit_id'], 'integer'],
            [['sender', 'subject', 'text', 'callback', 'unit'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Messages::find();
        $query->joinWith(['unit']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
        $dataProvider->sort->attributes['unit'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['staff_units.name' => SORT_ASC],
            'desc' => ['staff_units.name' => SORT_DESC],
        ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'unit_id' => $this->unit_id,
        ]);

        $query->andFilterWhere(['like', 'sender', $this->sender])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'callback', $this->callback]);

        return $dataProvider;
    }
}
