<?php

namespace common\models;

use common\models\Article;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ArticleSearch represents the model behind the search form about `common\models\Article`.
 */
class ArticleSearch extends Article
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'category_id', 'rating', 'views', 'status'], 'integer'],
            [['title', 'body', 'created_at', 'updated_at', 'source', 'slug'], 'safe'],
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
     * @param $params
     * @param bool $public
     * @return ActiveDataProvider
     */
    public function search($params, $public = false)
    {
        $query = Article::find();

        if ($public) {
            $query->where(['status' => Article::STATUS_PUBLIC]);
        }

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
            'user_id' => $this->user_id,
            'category_id' => $this->category_id,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
//            'rating' => $this->rating,
//            'views' => $this->views,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'source', $this->source]);

        return $dataProvider;
    }
}
