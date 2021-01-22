<?php

namespace app\modules\staff\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\staff\models\Vacancies;

/**
 * VacanciesSearch represents the model behind the search form of `app\modules\staff\models\Vacancies`.
 */
class VacanciesSearch extends Vacancies
{

    public $unit;
    public $position;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unit_id', 'position_id'], 'integer'],
            [['unit', 'position'], 'safe'],
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

      $query = Vacancies::find();
      $query->joinWith(['unit', 'position']);

      $dataProvider = new ActiveDataProvider([
          'query' => $query,
          'pagination' => [
              'forcePageParam' => false,
              'pageSizeParam' => false,
              'pageSize' => 10
          ]
      ]);


      $dataProvider->sort->attributes['position'] = [
          // The tables are the ones our relation are configured to
          // in my case they are prefixed with "tbl_"
          'asc' => ['staff_positions.name' => SORT_ASC],
          'desc' => ['staff_positions.name' => SORT_DESC],
      ];

      $dataProvider->sort->attributes['unit'] = [
          // The tables are the ones our relation are configured to
          // in my case they are prefixed with "tbl_"
          'asc' => ['staff_units.name' => SORT_ASC],
          'desc' => ['staff_units.name' => SORT_DESC],
      ];

      $this->load($params);

      if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

      // grid filtering conditions
      $query->andFilterWhere([
          'unit_id' => $this->unit_id,
          'position_id' => $this->position_id,
      ])
      ->andFilterWhere(['staff_units.name' => $this->unit])
      ->andFilterWhere(['staff_positions.name' => $this->position]);

      return $dataProvider;
  }
}
