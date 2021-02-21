<?php

namespace app\modules\metrica\models\analyze;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\metrica\models\analyze\Analyze;

/**
 * AnalyzeSearch represents the model behind the search form of `app\modules\metrica\models\analyze\Analyze`.
 */
class AnalyzeSearch extends Analyze
{

    public $pattern;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'pattern_id', 'value', 'status'], 'integer'],
            [['url', 'pattern'], 'safe'],
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
        $query = Analyze::find();
        $query->joinWith(['pattern']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['pattern'] = [
            'asc' => ['metrica_patterns.name' => SORT_ASC],
            'desc' => ['metrica_patterns.name' => SORT_DESC],
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
            'pattern_id' => $this->pattern_id,
            'value' => $this->value,
        ]);

        $query->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}
