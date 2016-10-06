<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="article-view">

    <h4><?= Html::encode($this->title) ?> <span
            class="label label-default"><?php echo $model->category->title; ?></span></h4>
    <p>
        Опубликовано: <?php echo $model->created_at; ?>, <b><?php echo $model->user->username; ?></b>
    </p>

    <br>

    <p>
        <?php echo $model->body; ?>
    </p>

    <br>

    <p>
        <?php echo $model->source; ?>
    </p>

    <p>
        <?php

        if (!Yii::$app->user->isGuest) {
            if(Yii::$app->user->identity->role == \common\models\User::ROLE_ADMIN){
                Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
            }
        }

        ?>
        <!--        --><?php //echo Html::a('Delete', ['delete', 'id' => $model->id], [
        //            'class' => 'btn btn-danger',
        //            'data' => [
        //                'confirm' => 'Are you sure you want to delete this item?',
        //                'method' => 'post',
        //            ],
        //        ]) ?>
    </p>

    <p>
        <?php echo '<img src="' . $model->getImagePath($model->id) . '" />'; ?>
    </p>


    <!--    --><?php //echo DetailView::widget([
    //        'model' => $model,
    //        'attributes' => [
    //            'id',
    //            'title',
    //            'body',
    //            'user_id',
    //            'category_id',
    //            'created_at',
    //            'updated_at',
    //            'source',
    //            'rating',
    //            'views',
    //            'status',
    //        ],
    //    ]) ?>

</div>
