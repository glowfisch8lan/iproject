<?php

namespace app\modules\typography\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\typography\models\Orders;

/**
 * TypographyOrdersSearch represents the model behind the search form of `app\modules\typography\models\TypographyOrders`.
 */
class OrdersSearch extends Orders
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sender_unit_id', 'receiver', 'receiver_unit_id', 'comment'], 'integer'],
            [['sender', 'file_uuid'], 'safe'],
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
        $query = Orders::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'sender_unit_id' => $this->sender_unit_id,
            'receiver' => $this->receiver,
            'receiver_unit_id' => $this->receiver_unit_id,
            'comment' => $this->comment,
        ]);

        $query->andFilterWhere(['like', 'sender', $this->sender])
            ->andFilterWhere(['like', 'file_uuid', $this->file_uuid]);

        return $dataProvider;
    }
}
