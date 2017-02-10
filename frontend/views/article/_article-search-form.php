<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\PjaxAsset;
use kartik\form\ActiveForm;

PjaxAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\ArticleSearch */
/* @var $form yii\widgets\ActiveForm */

Pjax::begin(['enablePushState' => false, 'id' => 'articles-search-pjax']);

$form = ActiveForm::begin([
    'method' => 'post',
    'id' => 'article_search_form',
    'fieldConfig' => ['autoPlaceholder' => true],
    'enableClientValidation' => true,
    'enableAjaxValidation' => false,
    'fullSpan' => true,
    'action' => '/article/search',
], ['options' => ['data-pjax' => false]]); ?>

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
            'template' => '<div id="articles-search-form-result">{input}<div class="search-result">' . $searchResult . '</div></div>',
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


