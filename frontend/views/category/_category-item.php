<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */

?>
<li>
    <?php echo Html::a($model->title, ['article/category', 'id' => $model->id], [
        'class' => 'label label-light label-primary'
    ]); ?>
    <span class="label label-light label-default pull-right">8</span>
</li>
