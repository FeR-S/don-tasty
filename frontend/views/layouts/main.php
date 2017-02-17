<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="yandex-verification" content="e9fed1796d9b482a" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script type="text/javascript" src="//vk.com/js/api/openapi.js?139"></script>
    <script type="text/javascript">
//        window.onload = function () {
            VK.init({apiId: 5877934, onlyWidgets: true});
//        }
    </script>
</head>
<body class="home">

<?php
NavBar::begin([
    'brandLabel' => 'Judlit',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar navbar-inverse navbar-fixed-top headroom',
    ],
]);
$menuItems = [
    ['label' => 'Главная', 'url' => ['/']],
//    ['label' => 'О проекте', 'url' => ['/site/about']],
//    ['label' => 'Контакты', 'url' => ['/site/contact']],
];
if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'Статьи', 'url' => ['/articles']];
//    $menuItems[] = ['label' => 'Регистрация', 'url' => ['/site/signup']];
    $menuItems[] = ['label' => 'Вход', 'url' => ['/site/login']];
} else {
    $menuItems[] = ['label' => 'Категории', 'url' => ['/category/index']];
    $menuItems[] = ['label' => 'Статьи', 'url' => ['/article/index']];
    $menuItems[] = ['label' => 'Новые темы', 'url' => ['/article/question-index']];
    $menuItems[] = '<li>'
        . Html::beginForm(['/site/logout'], 'post')
        . Html::submitButton(
            'Выход (' . Yii::$app->user->identity->username . ')'
        )
        . Html::endForm()
        . '</li>';
}
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
]);
NavBar::end();
?>


<?php $this->beginBody() ?>


<?php if (Yii::$app->controller->id == 'site' and Yii::$app->controller->action->id == 'index') { ?>
    <!-- Header -->
    <!--    <header id="head">-->
    <!--        <div class="container">-->
    <!--            <div class="row">-->
    <!--                <h1 class="lead">AWESOME, CUSTOMIZABLE, FREE</h1>-->
    <!--                <p class="tagline">PROGRESSUS: free business bootstrap template by <a-->
    <!--                        href="http://www.gettemplate.com/?utm_source=progressus&amp;utm_medium=template&amp;utm_campaign=progressus">GetTemplate</a>-->
    <!--                </p>-->
    <!--                <p><a class="btn btn-default btn-lg" role="button">MORE INFO</a> <a class="btn btn-action btn-lg"-->
    <!--                                                                                    role="button">DOWNLOAD NOW</a></p>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </header>-->
    <!-- /Header -->

    <!-- Intro -->
    <div class="white-row">
        <div class="container text-center">
            <br> <br>
            <h2 class="thin">Юридическая грамотность</h2>
            <p class="text-muted">
                Правовая сторона неприятных ситуаций, с которыми мы, порой, сталкиваемся в жизни.<br>
                В статьях данного сервиса представлены краткие ответы на возникающие в таких ситуациях вопросы - ничего
                лишнего.
            </p>

            <br>

            <!--  SEARCH FORM  -->
            <?php
            $model = new \common\models\ArticleSearch();
            $model->scenario = \common\models\ArticleSearch::SCENARIO_PUBLIC_SEARCH;
            echo $this->render('/article/_article-search-form', [
                'model' => $model
            ]);
            ?>
            <!--  END SEARCH FORM  -->

            <p class="text-muted">
                Не нашли что искали? <a href="#lets-comment">предложите тему для статьи</a>.
            </p>

        </div>
    </div>
    <!-- /Intro-->

    <!--    <div class="jumbotron top-space">-->
    <!--        <div class="container">-->
    <!---->
    <!--            <h3 class="text-center thin">Reasons to use this template</h3>-->
    <!---->
    <!--            <div class="row">-->
    <!--                <div class="col-md-3 col-sm-6 highlight">-->
    <!--                    <div class="h-caption"><h4><i class="fa fa-cogs fa-5"></i>Bootstrap-powered</h4></div>-->
    <!--                    <div class="h-body text-center">-->
    <!--                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque aliquid adipisci aspernatur.-->
    <!--                            Soluta quisquam dignissimos earum quasi voluptate. Amet, dignissimos, tenetur vitae dolor-->
    <!--                            quam iusto assumenda hic reprehenderit?</p>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                <div class="col-md-3 col-sm-6 highlight">-->
    <!--                    <div class="h-caption"><h4><i class="fa fa-flash fa-5"></i>Fat-free</h4></div>-->
    <!--                    <div class="h-body text-center">-->
    <!--                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores, commodi, sequi quis ad-->
    <!--                            fugit omnis cumque a libero error nesciunt molestiae repellat quos perferendis numquam-->
    <!--                            quibusdam rerum repellendus laboriosam reprehenderit! </p>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                <div class="col-md-3 col-sm-6 highlight">-->
    <!--                    <div class="h-caption"><h4><i class="fa fa-heart fa-5"></i>Creative Commons</h4></div>-->
    <!--                    <div class="h-body text-center">-->
    <!--                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, vitae, perferendis,-->
    <!--                            perspiciatis nobis voluptate quod illum soluta minima ipsam ratione quia numquam eveniet eum-->
    <!--                            reprehenderit dolorem dicta nesciunt corporis?</p>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--                <div class="col-md-3 col-sm-6 highlight">-->
    <!--                    <div class="h-caption"><h4><i class="fa fa-smile-o fa-5"></i>Author's support</h4></div>-->
    <!--                    <div class="h-body text-center">-->
    <!--                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias, excepturi, maiores, dolorem-->
    <!--                            quasi reprehenderit illo accusamus nulla minima repudiandae quas ducimus reiciendis odio-->
    <!--                            sequi atque temporibus facere corporis eos expedita? </p>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!---->
    <!--        </div>-->
    <!--    </div>-->
<?php } ?>


<div class="container" style="padding-top: 50px">
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <?= Alert::widget() ?>

    <?= $content ?>
</div>

<!-- Social links. @TODO: replace by link/instructions in template -->
<!--<section id="social">-->
<!--    <div class="container">-->
<!--        <div class="wrapper clearfix">-->
<!-- AddThis Button BEGIN -->
<!--            <div class="addthis_toolbox addthis_default_style">-->
<!--                <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>-->
<!--                <a class="addthis_button_tweet"></a>-->
<!--                <a class="addthis_button_linkedin_counter"></a>-->
<!--                <a class="addthis_button_google_plusone" g:plusone:size="medium"></a>-->
<!--            </div>-->
<!-- AddThis Button END -->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->
<!-- /social links -->


<?php if (Yii::$app->controller->id == 'site' and Yii::$app->controller->action->id == 'index') { ?>
<!-- Intro -->
<div class="white-row" style="background: #fff; padding-top: 20px">
    <div class="container" id="lets-comment">
        <h2 class="thin">Предложите тему для статьи</h2>
        <p class="text-muted">
            Напишите, на какой вопрос Вы хотели бы получить лаконичный ответ с юридической точки зрения, и мы обязательно его рассмотрим.
        </p>

        <?php

        $model = new \frontend\models\QuestionForm();
        echo $this->render('/article/_question-form', [
            'model' => $model
        ]);

        ?>
<!--        <div id="new-artices-ideas"></div>-->
<!--        <script type="text/javascript">-->
<!--            window.onload = function () {-->
<!--                VK.Widgets.Comments("new-artices-ideas", {-->
<!--                    limit: 10,-->
<!--                    attach: "*"-->
<!--                }, 'new-artices-ideas-main-page');-->
<!--            }-->
<!--        </script>-->
    </div>
</div>
<!-- /Intro-->
<?php } ?>



<footer id="footer" class="top-space">

    <div class="footer1">
        <div class="container">
            <div class="row">

                <div class="col-md-6 widget">
                    <h3 class="widget-title">Judlit.ru</h3>
                    <div class="widget-body">
                        <p>Юридическая грамотность</p>
                    </div>
                </div>

                <div class="col-md-3 widget">
                    <h3 class="widget-title">Контакты</h3>
                    <div class="widget-body">
                        <p>
                            <a href="mailto:#">info@judlit.ru</a><br>
                        </p>
                    </div>
                </div>

                <!--                <div class="col-md-3 widget">-->
                <!--                    <h3 class="widget-title">Мы в соц. сетях</h3>-->
                <!--                    <div class="widget-body">-->
                <!--                        <p class="follow-me-icons">-->
                <!--                            <a href=""><i class="fa fa-twitter fa-2"></i></a>-->
                <!--                            <a href=""><i class="fa fa-dribbble fa-2"></i></a>-->
                <!--                            <a href=""><i class="fa fa-github fa-2"></i></a>-->
                <!--                            <a href=""><i class="fa fa-facebook fa-2"></i></a>-->
                <!--                        </p>-->
                <!--                    </div>-->
                <!--                </div>-->


            </div> <!-- /row of widgets -->
        </div>
    </div>

    <div class="footer2">
        <div class="container">
            <div class="row">

                <div class="col-md-6 widget">
                    <div class="widget-body">
                        <p class="simplenav">
                            <a href="/">Главная</a> |
                            <!--                            <a href="/site/contact">Контакты</a> |-->
                            <a href="/articles">Статьи</a>
                        </p>
                    </div>
                </div>

                <div class="col-md-6 widget">
                    <div class="widget-body">
                        <p class="text-right">
                            Все права защищены &copy; 2016, <b style="color: #fff">judlit.ru</b>.
                        </p>
                    </div>
                </div>

            </div> <!-- /row of widgets -->
        </div>
    </div>

</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
