<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleSearch */
/* @var $form yii\widgets\ActiveForm */

?>

<li article-id="<?php echo $model->id; ?>" class="article-search-result-item">
    <a href="<?php echo $model->url; ?>">
        <?php echo $model->title; ?>
    </a>
</li>









