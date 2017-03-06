<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории статей';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h2><?= Html::encode($this->title) ?></h2>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php

    $columns = [
        'title',
        [
            'label' => 'Количество статей',
            'value' => function ($model) {
                return \common\models\Article::getArticlesCount($model->id);
            }
        ],
    ];

    if (\common\models\User::isAdmin(Yii::$app->user->identity->role)) {
        array_unshift($columns,
            'id',
            'slug',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => "{update}"
            ]);
    }

    echo GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
</div>
