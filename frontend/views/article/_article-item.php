<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$date = strtotime($model->created_at);

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */

if ($index % $modelIndex == $modelKey) { ?>
    <section class="blog-post">
        <div class="panel panel-default">
            <!--        <div>-->
            <!--            <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span>-->
            <!--        </div>-->
            <img src="<?php echo $model->getImageUrl(); ?>" data-holder-rendered="true">
            <div class="panel-body">
                <div class="blog-post-meta">
                    <span
                        class="label label-light <?php echo $model->category->label_class; ?>"><?php echo $model->category->title; ?></span>
                    <p class="blog-post-date pull-right"><?php echo date('d', $date) . ' ' . \common\models\Article::getRussianMonths()[date('n', $date)] . ', ' . date('Y', $date); ?></p>
                </div>
                <div class="blog-post-content">

                    <a href="<?php echo $model->url; ?>">
                        <h2 class="blog-post-title"><?php echo $model->title; ?></h2>
                    </a>
                    <p><?php echo mb_substr($model->announcement, 0, 200); ?></p>
                    <?php echo Html::a('Подробнее', $model->url, []); ?>
                    <!--                    <a class="blog-post-share pull-right" href="#">-->
                    <!--                        <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>-->
                    <!--                    </a>-->
                </div>
            </div>
        </div>
    </section>
<?php } ?>