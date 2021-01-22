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

    public $senderUnit;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sender_unit_id','receiver', 'receiver_unit_id', 'comment', 'status'], 'integer'],
            [['sender', 'file_uuid'], 'safe'],
            [['senderUnit'], 'safe'],
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
        $query->joinWith(['senderUnit']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        $dataProvider->sort->attributes['senderUnit'] = [
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
            'sender_unit_id' => $this->sender_unit_id,
            'receiver' => $this->receiver,
            'receiver_unit_id' => $this->receiver_unit_id,
            'comment' => $this->comment,
            'status' => $this->status,
            'sender_unit_id' => $this->senderUnit
        ]);

        $query
            //->andFilterWhere(['like', 'sender_unit_id', $this->senderUnit])
            ->andFilterWhere(['like', 'file_uuid', $this->file_uuid]);

        return $dataProvider;
    }
}
