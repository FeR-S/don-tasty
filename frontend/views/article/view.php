<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['list']];
$this->params['breadcrumbs'][] = ['label' => $model->category->title, 'url' => ['category', 'id' => $model->category->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row" style="margin-top: 30px">
    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
        <section class="blog-post">
            <div class="panel panel-default ">
                <div class="panel-body">
                    <div class="blog-post-meta">
                        <span class="label label-light <?php echo $model->category->label_class; ?>"><?php echo $model->category->title; ?></span>
                        <p class="blog-post-date pull-right"><?php echo $model->created_at; ?></p>
                    </div>
                    <div class="blog-post-content">
                        <h2 class="blog-post-title"><?php echo $model->title; ?></h2>
<!--                        <blockquote>--><?php //echo $model->announcement; ?><!--</blockquote>-->

                        <div class="bs-callout bs-callout-danger tezis">
                            <h4>Тезис:</h4>
                            <div class="text">
                                <?php echo $model->announcement; ?>
                            </div>
                        </div>

                        <p><?php echo $model->body; ?></p>
                        <p><b>Источник: </b><?php echo $model->source; ?></p>
                        <!--                        <p>--><?php //echo $model->user->username; ?><!--</p>-->
                    </div>
                </div>
                <img class="post-view-image" src="<?php echo $model->getImagePath(); ?>" data-holder-rendered="true">
            </div>
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

<?php

if (!Yii::$app->user->isGuest) {
//    if(Yii::$app->user->identity->role == \common\models\User::ROLE_ADMIN){
    echo Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
//    }
}

?>

