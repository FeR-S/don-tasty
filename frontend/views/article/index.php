<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статьи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('Добавить статью', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php

    $columns = [
        'title',
        [
            'label' => 'Категория',
            'attribute' => 'category_id',
            'value' => function ($model) {
                return $model->category ? $model->category->title : '-';
            },
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\Category::getCategories(), 'id', 'title'),
        ],
        [
            'label' => 'Автор статьи',
            'attribute' => 'user_id',
            'value' => function ($model) {
                return $model->user ? $model->user->last_name : '-';
            },
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->where(['role' => \common\models\User::ROLE_LAWYER, 'status' => \common\models\User::STATUS_ACTIVE])->all(), 'id', 'last_name'),
        ],
        // 'created_at',
        // 'updated_at',
        // 'source',
        // 'rating',
        // 'views',
        [
            'attribute' => 'status',
            'value' => function ($model) {
                return \common\models\Article::getStatuses()[$model->status];
            },
            'filter' => \common\models\Article::getStatuses(),
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => "{update}"
//                'buttons' => [
//                    'view' => function ($url, $model) {
//                        return \yii\helpers\Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $model->url,
//                            ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
//                    }
//                ],
        ],
    ];

    if (\common\models\User::isAdmin(Yii::$app->user->identity->role)) {
        array_unshift($columns, 'id', 'slug');
    }

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
</div>
