<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'username',
            'first_name',
            'last_name',
//            'auth_key',
//            'password_hash',
//            'password_reset_token',
            // 'email:email',
            [
                'attribute' => 'role',
                'value' => function($model){
                    return \common\models\User::getRoles()[$model->role];
                },
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return \common\models\User::getStatuses()[$model->status];
                },
            ],
            [
                'attribute' => 'specialization',
                'value' => function($model){
                    return $model->specialization ? \common\models\User::getSpecializations()[$model->specialization] : '-';
                },
            ],
            // 'age',
            // 'work_experience',
            // 'city',
//             'specialization',
             'created_at',
             'updated_at',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
