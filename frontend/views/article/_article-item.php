<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="thumbnail">
    <img style="height: 200px; width: 100%; display: block;"
         src="<?php echo $model->getImagePath($model->id); ?>"
         data-holder-rendered="true">
    <div class="caption">
        <h4><?php echo $model->title; ?></h4>
        <p><?php echo mb_substr($model->body, 0, 100); ?>...</p>
        <p><?php echo Html::a('Подробнее', ['article/view', 'id' => $model->id], [
                'class' => 'btn btm-sm btn-primary'
            ]); ?></p>
    </div>
</div>
