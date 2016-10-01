<?php

use yii\widgets\ListView;
use yii\bootstrap\Html;

/* @var $this yii\web\View */

$this->title = 'Judlit';

?>

<div class="bs-callout bs-callout-info">
    <h4>Judlit - главная страница</h4>
    <p>Будь юридически подкованным. Ответы на вопросы щекотливых ситуаций. Будьте вкурсе своих прав. "Я не потерплю
        преступного ущемления своих прав"</p>
</div>


<?= ListView::widget([
    'dataProvider' => $articles,
    'summary' => false,
    'options' => [
        'class' => 'row'
    ],
    'itemOptions' => [
        'class' => 'col-lg-3 col-md-3 col-sm-6 col-xs-12',
    ],
    'itemView' => '/article\_article-item',
]) ?>
