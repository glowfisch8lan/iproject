<?php

namespace app\modules\staff\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
//use app\modules\staff\models\State;

/**
 * StateSearch represents the model behind the search form of `app\modules\staff\models\State`.
 */
class StateSearch extends State
{

    public $workers;
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'vacancies_id'], 'integer'],
            [['workers'], 'safe']
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
        $query = State::find();
        $query->joinWith(['vacancies','workers']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 10
            ]
        ]);
        $dataProvider->sort->attributes['vacancies.position'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['staff_vacancies.position_id' => SORT_ASC],
            'desc' => ['staff_vacancies.position_id' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['vacancies.unit'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['staff_vacancies.unit_id' => SORT_ASC],
            'desc' => ['staff_vacancies.unit_id' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['workers'] = [
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
        $fio = explode(' ',$this->workers);
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
