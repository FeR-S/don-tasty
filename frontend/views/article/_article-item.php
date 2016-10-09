<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */

var_dump($key);

?>
<section class="blog-post">
    <div class="panel panel-default">
        <!--        <div>-->
        <!--            <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span>-->
        <!--        </div>-->
        <img src="<?php echo $model->getImagePath($model->id); ?>" data-holder-rendered="true">
        <div class="panel-body">
            <div class="blog-post-meta">
                <span class="label label-light label-info"><?php echo $model->category->title; ?></span>
                <p class="blog-post-date pull-right"><?php echo $model->created_at; ?></p>
            </div>
            <div class="blog-post-content">

                <a href="/article/view/<?php echo $model->id; ?>">
                    <h2 class="blog-post-title"><?php echo $model->title; ?></h2>
                </a>
                <p><?php echo mb_substr($model->body, 0, 100); ?></p>
                <?php echo Html::a('Подробнее', ['article/view', 'id' => $model->id], [
                ]); ?>
                <a class="blog-post-share pull-right" href="#">
                    <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                </a>
            </div>
        </div>
    </div>
</section>