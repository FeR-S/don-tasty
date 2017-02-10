<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\form\ActiveField;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleSearch */
/* @var $form yii\widgets\ActiveForm */

Pjax::begin(['enablePushState' => false, 'id' => 'articles-search-pjax']);

$form = ActiveForm::begin([
    'id' => 'article_search_form',
//    'type' => ActiveForm::TYPE_INLINE,
    'fullSpan' => true,
    'action' => '/article/search',
    'options' => [
        'data-pjax' => true
    ],
//    'enableAjaxValidation' => true,
//    'validationUrl' => '/article/search',
]); ?>

<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
        <?php


        $searchResult = isset($dataProvider) ? \yii\widgets\ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_search-result-item',
            'options' => [
                'tag' => 'ul',
                'class' => 'articles-list'
            ],
            'layout' => "{items}",
            'itemOptions' => [
                'tag' => false,
            ],
            'emptyText' => 'Ничего не найдено...',
            'emptyTextOptions' => [
                'tag' => 'li',
                'class' => 'list-empty'
            ],
        ]) : '';


        echo $form->field($model, 'title', [
            'template' => '<div id="articles-search-form-result">{input}<div class="search-result">'.$searchResult.'</div></div>',
            'addon' => [
                'append' => [
                    'content' => Html::submitButton('Поиск', ['class' => 'btn btn-primary']),
                    'asButton' => true
                ]
            ]
        ]); ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
<?php Pjax::end(); ?>


