<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\RecipeSearchAdmin */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recipes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recipe-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Recipe', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'user_id',
            'category_id',
            'created_at',
            // 'updated_at',
            // 'status',
            // 'slug',
            // 'description',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
