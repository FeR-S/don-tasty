<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\PjaxAsset;
use kartik\form\ActiveForm;
use yii\captcha\Captcha;

//PjaxAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\ArticleSearch */
/* @var $form yii\widgets\ActiveForm */

Pjax::begin(['enablePushState' => false, 'id' => 'question-pjax']);

$form = ActiveForm::begin([
//    'method' => 'post',
        'id' => 'question_form',
        'options' => ['data-pjax' => true],
//    'fieldConfig' => ['autoPlaceholder' => true],
//    'enableClientValidation' => true,
//        'enableAjaxValidation' => true,
//        'fullSpan' => true,
        'action' => '/article/question',
    ]
); ?>

<div class="row">
    <div class="col-sm-4">
        <?php

        echo $form->field($model, 'title');

        echo $form->field($model, 'body')->textarea([
                'rows' => 3
        ]);

        echo $form->field($model, 'verifyCode')->widget(Captcha::className(), [
            'template' => '<div class="row"><div class="col-xs-6">{image}</div><div class="col-xs-6">{input}</div></div>',
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
        </div>

    </div>
</div>

<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>


