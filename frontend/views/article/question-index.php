<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Предложения тем для статей';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Предложить новую', ['question-create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//            'id',
            'title',
            'body',
//            'user_id',
//            'category_id',
            'created_at',
            // 'updated_at',
            // 'source',
            // 'rating',
            // 'views',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => "{update}",
                'buttons' => [
                    'update' => function ($url, $model) {
                        return \yii\helpers\Html::a('Подготовить статью', 'update/' . $model->id,
                            ['data-pjax' => '0']);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
