<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['list']];
$this->params['breadcrumbs'][] = ['label' => $model->category->title, 'url' => $model->category->url];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row" style="margin-top: 30px">
    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
        <section class="blog-post">
            <div class="panel panel-default ">
                <div class="panel-body blog-current-post">
                    <div class="blog-post-meta">
                        <span class="label label-light <?php echo $model->category->label_class; ?>"><?php echo $model->category->title; ?></span>
                        <p class="blog-post-date pull-right"><?php echo $model->created_at; ?></p>
                    </div>
                    <h2 class="blog-post-title"><?php echo $model->title; ?>
                        <?php if (!empty($model->sub_title)) echo '<br><small>' . $model->sub_title . '</small>'; ?>
                    </h2>
                    <div class="blog-post-tezis">
                        <?php echo $model->announcement; ?>
                    </div>
                    <div class="blog-post-content">
                        <?php echo $model->body; ?>
                        <!--                        <p>--><?php //echo $model->user->username; ?><!--</p>-->
                    </div>
                </div>

                <div class="image-blog-post" style="height: 300px; background: url(<?php echo $model->getImageUrl(); ?>) no-repeat center; background-size: cover;"></div>
<!--                <img class="post-view-image" src="--><?php //echo $model->getImageUrl(); ?><!--" data-holder-rendered="true">-->

                <?php if (!empty($model->source)): ?>
                    <div class="panel-body blog-current-post">
                        <div class="blog-post-content source">
                            Для написания данной статьи были использованы следующие материалы: <br>
                            <span><?php echo strip_tags($model->source, '<a>'); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
        </section>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
        <div class="sidebar-module">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>Категории</h4>
                    <ol class="categories list-unstyled">
                        <?= ListView::widget([
                            'dataProvider' => $categories,
                            'summary' => false,
                            'options' => [
                                'class' => 'categories list-unstyled',
                                'tag' => 'ol'
                            ],
                            'itemView' => '/category/_category-item',
                        ]) ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-12">
        <section class="blog-comments">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>Опыт наших читателей <br><small>Поделитесь своим опытом - расскажите, сталкивались ли Вы с такой ситуацией в жизни.</small></h4>
                    <!--                    <div class="blog-post-content">-->
                    <div id="articles-comment-<?php echo $model->id; ?>"></div>
                    <script type="text/javascript">
                        window.onload = function () {
                            VK.init({apiId: 5877934, onlyWidgets: true});
                            VK.Widgets.Comments("articles-comment-<?php echo $model->id; ?>", {limit: 10, attach: "*"}, <?php echo $model->id; ?>);
                        }
                    </script>
<!--                    --><?php
//
//                    \yii\widgets\Pjax::begin(['enablePushState' => false, 'id' => 'articles-search-pjax']);
//
//                    echo ListView::widget([
//                        'dataProvider' => $article_comments,
//                        'summary' => false,
//                        'options' => [
//                            'class' => 'row'
//                        ],
//                        'itemOptions' => [
//                            'class' => 'col-xs-12',
//                        ],
//                        'itemView' => '/article_comments/_article-comment-item',
//                        'emptyText' => 'Ничего не найдено...',
//                        'emptyTextOptions' => [
//                            'class' => 'col-xs-12 article-comment-empty-message'
//                        ],
//                    ]); ?>
                    <!--                    </div>-->
                    <!--                    <div class="blog-post-content">-->

<!--                    --><?php
//
//                    $form = \kartik\form\ActiveForm::begin(['id' => 'article-comment-form', 'options' => ['data-pjax' => true]]);
//                    echo $form->field($article_comments_model, 'body', [])->textarea(['maxlength' => true])->label(false);
//                    echo Html::submitButton('Опубликовать', ['class' => 'btn btn-success']);
//
//                    \kartik\form\ActiveForm::end();
//                    \yii\widgets\Pjax::end();
//
//                    ?>
                    <!--                    </div>-->
                </div>
            </div>
        </section>
    </div>
</div>

<?php

if (!Yii::$app->user->isGuest) {
//    if(Yii::$app->user->identity->role == \common\models\User::ROLE_ADMIN){
    echo Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
//    }
}

?>

