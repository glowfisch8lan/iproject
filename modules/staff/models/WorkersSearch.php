<?php

namespace app\modules\staff\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\staff\models\Workers;

/**
 * WorkersSearch represents the model behind the search form of `app\modules\staff\models\Workers`.
 */
class WorkersSearch extends Workers
{

    public $worker;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['firstname', 'middlename', 'lastname', 'birthday', 'personal_fields', 'additinal_lists', 'worker'], 'safe'],
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
        $query = Workers::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->attributes['worker'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['staff_workers.lastname' => SORT_ASC],
            'desc' => ['staff_workers.lastname' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $fio = explode(' ',$this->worker);
        foreach($fio as $value){
            $query->andFilterWhere([
                    'or',
                    ['like', 'staff_workers.lastname', $value],
                    ['like', 'staff_workers.middlename', $value],
                    ['like', 'staff_workers.firstname', $value]]
            );
        }


        return $dataProvider;
    }
}
