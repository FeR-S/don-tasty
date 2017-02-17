<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = 'Новая тема для статьи';
$this->params['breadcrumbs'][] = ['label' => 'Новые темы', 'url' => ['question-index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<br>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'body')->textarea([
            'rows' => 4,
    ]); ?>

    <div class="form-group">
        <?php echo Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
