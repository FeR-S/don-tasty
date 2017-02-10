<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleSearch */
/* @var $form yii\widgets\ActiveForm */

var_dump($dataProvider->getModels());die;


echo \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'summary' => false,
    'options' => [
        'class' => 'row'
    ],
    'itemOptions' => [
        'class' => 'col-xs-12',
    ],
    'itemView' => '/article/_article-item',
]);


