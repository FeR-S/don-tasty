<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;
/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row" style="margin-top: 30px">
    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <?= ListView::widget([
                    'dataProvider' => $articles,
                    'summary' => false,
                    'options' => [
                        'class' => 'row'
                    ],
                    'itemOptions' => [
                        'class' => 'col-xs-12',
                    ],
                    'itemView' => '/article/_article-item',
                    'viewParams' => [
                        'modelKey' => 0,
                        'modelIndex' => 2
                    ]
                ]) ?>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <?= ListView::widget([
                    'dataProvider' => $articles,
                    'summary' => false,
                    'options' => [
                        'class' => 'row'
                    ],
                    'itemOptions' => [
                        'class' => 'col-xs-12',
                    ],
                    'itemView' => '/article/_article-item',
                    'viewParams' => [
                        'modelKey' => 1,
                        'modelIndex' => 2
                    ]
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="sidebar-module">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>Категории</h4>
                    <ol class="categories list-unstyled">
                        <?= ListView::widget([
                            'dataProvider' => $categories,
                            'summary' => false,
                            'options' => [
                                'class' => 'categories list-unstyled',
                                'tag' => 'ol'
                            ],
                            'itemView' => '/category/_category-item',
                        ]) ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

