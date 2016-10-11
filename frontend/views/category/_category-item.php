<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */

?>

<?php $articles_count = \common\models\Article::getArticlesCount($model->id);
if($articles_count > 0) { ?>
    <li>
        <?php echo Html::a($model->title, ['article/category', 'id' => $model->id], [
            'class' => 'label label-light '.$model->label_class
        ]); ?>
        <span class="label label-light label-default pull-right"><?php echo \common\models\Article::getArticlesCount($model->id) ;?></span>
    </li>
<?php } ?>


