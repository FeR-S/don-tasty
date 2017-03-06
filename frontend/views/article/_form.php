<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\ArrayHelper;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs(<<<JS
     
$(document).on('click', '.article-image-remove-button', function(){
        var item = $(this).find('[model_id]'),
            article_id = item.attr('model_id');
       
            $.ajax({
                type: 'post',
                url: '/article/remove-image',
                data: {
                    article_id: article_id,
                }
            }).success(function(result){
                alert('Изображение '+ result +' удалено!');
            });
});

JS
    , \yii\web\View::POS_READY);

?>

<div class="article-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php echo $form->field($model, 'title', [
        'template' => "{label}<small class='sub-description'>Основной элемент в индексации статьи. Должен соответствовать топовым запросам поисковых систем на данную тему.</small>\n{input}\n{hint}\n{error}",
    ])->textInput(['maxlength' => true]) ?>

    <?php

    if (empty($model->sub_title)) {
        $model->sub_title = 'Что говорить и как вести себя в этой ситуации?';
    }

    echo $form->field($model, 'sub_title', [
        'template' => "{label}<small class='sub-description'>Не участвует в индексации статьи. Является уточнением к основному заголовку.</small>\n{input}\n{hint}\n{error}",
    ])->textInput(['maxlength' => true]); ?>

    <!--    --><?php //echo $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(\common\models\Category::getCategories(), 'id', 'title')); ?>

    <?php echo $form->field($model, 'announcement', [
        'template' => "{label}<small class='sub-description'>Основная и краткая информация по теме статьи.</small>\n{input}\n{hint}\n{error}",
    ])->widget(TinyMce::className(), [
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
            'toolbar' => 'undo redo | bold italic | bullist numlist | link'
        ]
    ]); ?>

    <?php echo $form->field($model, 'body', [
        'template' => "{label}<small class='sub-description'>Более подробная и юридически обоснованная информация по теме статьи.</small>\n{input}\n{hint}\n{error}",
    ])->widget(TinyMce::className(), [
        'options' => ['rows' => 10],
        'language' => 'ru',
        'clientOptions' => [
            'plugins' => [
                'advlist autolink lists link image',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime paste code'
            ],
            'menubar' => false,
            'toolbar' => 'undo redo | bold italic | bullist numlist | link'
        ]
    ]); ?>

    <?php echo $form->field($model, 'source', [
        'template' => "{label}<small class='sub-description'>Указать ссылки на ресурсы или названия нормативных документов с используемой информацией.</small>\n{input}\n{hint}\n{error}",
    ])->widget(TinyMce::className(), [
        'options' => [
            'rows' => 5,
            'placeholder' => 'ссылки на источники, через запятую'
        ],
        'language' => 'ru',
        'clientOptions' => [
            'plugins' => [
                'advlist autolink lists link',
            ],
            'menubar' => false,
            'toolbar' => 'link'
        ]
    ]); ?>

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
            'initialPreview' => $model->getImageUrl(),
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
        <?php echo Html::submitButton($model->isNewRecord ? 'Отправить на модерацию' : 'Сохранить изменения', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
