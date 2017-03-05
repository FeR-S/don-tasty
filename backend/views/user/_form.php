<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs(<<<JS
     
$(document).on('click', '.user-image-remove-button', function(){
        var item = $(this).find('[model_id]'),
            user_id = item.attr('model_id');
       
            $.ajax({
                type: 'post',
                url: '/user/remove-image',
                data: {
                    user_id: user_id,
                }
            }).success(function(result){
                alert('Изображение '+ result +' удалено!');
            });
});

JS
    , \yii\web\View::POS_READY);

?>

<div class="user-form">

    <?php  $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(); ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'age')->textInput() ?>

    <?= $form->field($model, 'work_experience')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'specialization')->dropDownList(\common\models\User::getSpecializations()); ?>

    <?php echo $form->field($model, 'status')->dropDownList(\common\models\User::getStatuses()); ?>

    <?php echo $form->field($model, 'role')->dropDownList(\common\models\User::getRoles()); ?>

    <?php echo $form->field($model, 'image')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
        ],
        'pluginOptions' => !$model->isNewRecord ? [
            'showPreview' => true,
            'showCaption' => true,
            'showRemove' => true,
            'removeClass' => 'btn btn-danger fileinput-remove user-image-remove-button',
            'removeIcon' => '<i class="glyphicon glyphicon-trash" model_id="' . $model->id . '"></i>',
            'showUpload' => false,
            'mainClass' => 'input-group-sm',
            'showClose' => false,
            'initialPreview' => $model->getImageUrl(),
            'initialPreviewAsData' => true,
            'initialCaption' => $model->id . '.jpg',
            'overwriteInitial' => true,
            'initialPreviewConfig' => [
                'caption' => $model->id . '.jpg',
            ],
        ] : []
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
