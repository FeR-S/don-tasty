<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="article-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php echo $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(\common\models\Category::getCategories(), 'id', 'title')); ?>

    <?php echo $form->field($model, 'announcement')->widget(TinyMce::className(), [
        'options' => ['rows' => 6],
        'language' => 'ru',
        'clientOptions' => [
            'plugins' => [
                'advlist autolink lists link image',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime paste code'
            ],
            'menubar' => false,
            'maxLength' => 10,
            'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
        ]
    ]); ?>

    <?php echo $form->field($model, 'body')->widget(TinyMce::className(), [
        'options' => ['rows' => 10],
        'language' => 'ru',
        'clientOptions' => [
            'plugins' => [
                'advlist autolink lists link image',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime paste code'
            ],
            'menubar' => false,
            'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
        ]
    ]); ?>


    <?php echo $form->field($model, 'source')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'image')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
        ],
        'pluginOptions' => !$model->isNewRecord ? [
            'showPreview' => true,
            'showCaption' => true,
            'showRemove' => true,
            'removeClass' => 'btn btn-danger fileinput-remove article-image-remove-button',
            'removeIcon' => '<i class="glyphicon glyphicon-trash" model_id="' . $model->id . '"></i>',
            'showUpload' => false,
            'mainClass' => 'input-group-sm',
            'showClose' => false,
            'initialPreview' => $model->getImagePath(),
            'initialPreviewAsData' => true,
            'initialCaption' => $model->id . '.jpg',
            'overwriteInitial' => true,
            'initialPreviewConfig' => [
                'caption' => $model->id . '.jpg',
            ],
        ] : []
    ]); ?>

    <br>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
