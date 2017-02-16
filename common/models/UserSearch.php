<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'role', 'status', 'specialization'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'age', 'email', 'first_name', 'last_name', 'work_experience', 'city', 'created_at', 'updated_at'], 'safe'],
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
        $query = User::find();

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
//            'id' => $this->id,
            'role' => $this->role,
            'status' => $this->status,
//            'age' => $this->age,
            'specialization' => $this->specialization,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
//            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
//            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
//            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
//            ->andFilterWhere(['like', 'work_experience', $this->work_experience])
            ->andFilterWhere(['like', 'city', $this->city]);

        return $dataProvider;
    }
}
