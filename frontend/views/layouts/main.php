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
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
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
    ['label' => 'Главная', 'url' => ['/site/index']],
//    ['label' => 'О проекте', 'url' => ['/site/about']],
    ['label' => 'Контакты', 'url' => ['/site/contact']],
];
if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'Регистрация', 'url' => ['/site/signup']];
    $menuItems[] = ['label' => 'Вход', 'url' => ['/site/login']];
} else {

    if (Yii::$app->user->identity->role == \common\models\User::ROLE_ADMIN) {
        $menuItems[] = ['label' => 'Статьи', 'url' => ['/article/index']];
    }
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
            <h2 class="thin">The best place to tell people why they are here</h2>
            <p class="text-muted">
                The difference between involvement and commitment is like an eggs-and-ham breakfast:<br>
                the chicken was involved; the pig was committed.
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
<section id="social">
    <div class="container">
        <div class="wrapper clearfix">
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style">
                <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
                <a class="addthis_button_tweet"></a>
                <a class="addthis_button_linkedin_counter"></a>
                <a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
            </div>
            <!-- AddThis Button END -->
        </div>
    </div>
</section>
<!-- /social links -->

<footer id="footer" class="top-space">

    <div class="footer1">
        <div class="container">
            <div class="row">

                <div class="col-md-3 widget">
                    <h3 class="widget-title">Contact</h3>
                    <div class="widget-body">
                        <p>+234 23 9873237<br>
                            <a href="mailto:#">some.email@somewhere.com</a><br>
                            <br>
                            234 Hidden Pond Road, Ashland City, TN 37015
                        </p>
                    </div>
                </div>

                <div class="col-md-3 widget">
                    <h3 class="widget-title">Follow me</h3>
                    <div class="widget-body">
                        <p class="follow-me-icons">
                            <a href=""><i class="fa fa-twitter fa-2"></i></a>
                            <a href=""><i class="fa fa-dribbble fa-2"></i></a>
                            <a href=""><i class="fa fa-github fa-2"></i></a>
                            <a href=""><i class="fa fa-facebook fa-2"></i></a>
                        </p>
                    </div>
                </div>

                <div class="col-md-6 widget">
                    <h3 class="widget-title">Text widget</h3>
                    <div class="widget-body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi, dolores, quibusdam
                            architecto voluptatem amet fugiat nesciunt placeat provident cumque accusamus itaque
                            voluptate modi quidem dolore optio velit hic iusto vero praesentium repellat commodi ad id
                            expedita cupiditate repellendus possimus unde?</p>
                        <p>Eius consequatur nihil quibusdam! Laborum, rerum, quis, inventore ipsa autem repellat
                            provident assumenda labore soluta minima alias temporibus facere distinctio quas adipisci
                            nam sunt explicabo officia tenetur at ea quos doloribus dolorum voluptate reprehenderit
                            architecto sint libero illo et hic.</p>
                    </div>
                </div>

            </div> <!-- /row of widgets -->
        </div>
    </div>

    <div class="footer2">
        <div class="container">
            <div class="row">

                <div class="col-md-6 widget">
                    <div class="widget-body">
                        <p class="simplenav">
                            <a href="#">Home</a> |
                            <a href="about.html">About</a> |
                            <a href="sidebar-right.html">Sidebar</a> |
                            <a href="contact.html">Contact</a> |
                            <b><a href="signup.html">Sign up</a></b>
                        </p>
                    </div>
                </div>

                <div class="col-md-6 widget">
                    <div class="widget-body">
                        <p class="text-right">
                            Copyright &copy; 2014, Your name. Designed by <a href="http://gettemplate.com/"
                                                                             rel="designer">gettemplate</a>
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
