<?php

use yii\widgets\ListView;
use yii\bootstrap\Html;

/* @var $this yii\web\View */

$this->title = 'Judlit - юридическая грамотность.';

?>

<div class="">
    <h2 class="thin">Последние статьи <small></small></h2>
    <br>
    <br>
</div>
<!--<h2>Последние статьи</h2>-->

<div class="row" style="    min-height: 300px;">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
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
                'modelIndex' => 3
            ]
        ]) ?>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
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
                'modelIndex' => 3
            ]
        ]) ?>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
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
                'modelKey' => 2,
                'modelIndex' => 3
            ]
        ]) ?>
    </div>
</div>



