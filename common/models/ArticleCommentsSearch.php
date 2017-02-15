<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ArticleComments;
use yii\helpers\Html;

/**
 * ArticleCommentsSearch represents the model behind the search form about `common\models\ArticleComments`.
 */
class ArticleCommentsSearch extends ArticleComments
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'article_id', 'status'], 'integer'],
            [['body'], 'required', 'message' => 'Для публикации Вашего комментария, заполните это поле.'],
            [['created_at', 'updated_at', 'body'], 'safe'],
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
        $query = ArticleComments::find();

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
            'article_id' => $this->article_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'body', $this->body]);

        return $dataProvider;
    }


    public function safeNewComment($article_model)
    {
        if($this->validate()){
            $model = new ArticleComments();
            $model->body = Html::encode(strip_tags($this->body));
            if(!Yii::$app->user->isGuest) {
                $model->user_id = Yii::$app->user->identity->getId();
            }
            $model->article_id = $article_model->id;

            var_dump($this->getAttributes());die;
        };

        return false;
    }
}
