<?php

use yii\widgets\ListView;
use yii\bootstrap\Html;

/* @var $this yii\web\View */

$this->title = 'Judlit - юридическая грамотность.';

?>

<!-- Intro -->
<!--<div class="container text-center">-->
<!--    <br> <br>-->
<!--    <h2 class="thin">The best place to tell people why they are here</h2>-->
<!--    <p class="text-muted">-->
<!--        The difference between involvement and commitment is like an eggs-and-ham breakfast:<br>-->
<!--        the chicken was involved; the pig was committed.-->
<!--    </p>-->
<!--</div>-->
<!-- /Intro-->

<div class="">
    <h2 class="thin">Последние статьи <small></small></h2>
    <br>
    <br>
</div>
<!--<h2>Последние статьи</h2>-->

<div class="row" style="    min-height: 300px;">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <?= ListView::widget([
            'dataProvider' => $articles,
            'summary' => false,
            'options' => [
                'class' => 'row'
            ],
            'itemOptions' => [
                'class' => 'col-xs-12',
            ],
            'itemView' => '/article/_article-item',
            'viewParams' => [
                'modelKey' => 0,
                'modelIndex' => 3
            ]
        ]) ?>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <?= ListView::widget([
            'dataProvider' => $articles,
            'summary' => false,
            'options' => [
                'class' => 'row'
            ],
            'itemOptions' => [
                'class' => 'col-xs-12',
            ],
            'itemView' => '/article/_article-item',
            'viewParams' => [
                'modelKey' => 1,
                'modelIndex' => 3
            ]
        ]) ?>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <?= ListView::widget([
            'dataProvider' => $articles,
            'summary' => false,
            'options' => [
                'class' => 'row'
            ],
            'itemOptions' => [
                'class' => 'col-xs-12',
            ],
            'itemView' => '/article/_article-item',
            'viewParams' => [
                'modelKey' => 2,
                'modelIndex' => 3
            ]
        ]) ?>
    </div>
</div>

<section class="blog-post">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="blog-post-content">
                <h2 class="blog-post-title">О сервисе</h2>

                <p>В повседневной жизни каждый из нас сталкивается с десятками ситуаций, где пригодятся знания в
                    юридической сфере. Страховая компания отказывается выплачивать возмещение? Приобрели квартиру в
                    новостройке и не можете заселиться из-за «замороженного» строительства? Жилье затопили
                    безответственные соседи сверху?</p>

                <p>Во всех этих случаях, как и во множестве других, следует сохранять хладнокровие и четко понимать, как
                    нужно действовать, чтобы выйти из ситуации с минимальными потерями. Неприятности всегда случаются
                    неожиданно – к ним нельзя подготовиться заранее, однако мы помогаем людям быстро разобраться в своей
                    проблеме, оценить ее с точки зрения законодательства, определить свои дальнейшие шаги и преодолеть
                    все трудности.</p>

                <p>Наш сервис позволяет не только оперативно получить ответ на нужный вопрос, но и вникнуть во все
                    правовые аспекты своей проблемы, услышать мнение юристов, которые уже сталкивались с такими случаями
                    и на собственном опыте знают, что нужно делать.</p>

                <br>
                <hr>

                <div class="row">
                    <div class="col-md-4 highlight">
                        <div class="h-caption"><h4><i class="fa fa-cogs fa-5"></i>Полное соответствие<br>законодательству</h4></div>
                        <div class="h-body text-center">
                            <p>Все материалы, размещенные на данном сервисе, написаны компетентными специалистами и полностью соответствуют нынешним нормам российского законодательства.
                                Нормативно-правовая база непостоянна, с течением времени законы корректируются и изменяются в любой сфере. Именно поэтому мы уделяем особое внимание актуальности материалов и, реагируя на обновления в законодательстве, редактируем и дополняем каждую публикацию.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 highlight">
                        <div class="h-caption"><h4><i class="fa fa-flash fa-5"></i>Профессиональная<br>верификация статей</h4></div>
                        <div class="h-body text-center">
                            <p>Помимо проверки на соответствие законодательству, мы следим за содержанием и информативностью материалов. Мы контролируем, чтобы вы получали только качественный и грамотно сформулированный ответ, поэтому ищем и исправляем все неточности в материалах перед публикацией.
                                Кроме того, все наши материалы после написания проверяются квалифицированными юристами, которые в случае необходимости вносят правки и уточнения, основанные как на личном опыте, так и на российской судебной практике.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4 highlight">
                        <div class="h-caption"><h4><i class="fa fa-heart fa-5"></i>Лаконичный ответ<br>на каждый вопрос</h4></div>
                        <div class="h-body text-center">
                            <p>Все материалы, опубликованные в сервисе, написаны по единой структуре. В первую очередь в тексте дается емкий и лаконичный ответ на главный вопрос, которому посвящен материал, а затем приводятся аргументы, выстраивается логическая цепочка, прикрепляются ссылки на упомянутые нормативно-правовые акты.
                                Благодаря такой форме подачи вам не приходится тратить много времени, перечитывая тысячи символов для того, чтобы узнать ответ на интересующий вопрос.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>








