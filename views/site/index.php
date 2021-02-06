<?php

/* @var $this yii\web\View */

$this->title = 'ДВЮИ МВД России';
//use Yii;
use app\assets\AppAsset;

$bundle = AppAsset::register($this);

$js = <<< JS
    $(document).ready(function(){
        $('.jumbotron').css("background-image", "linear-gradient( rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.4)),  url('$bundle->baseUrl/img/slider/ee6ce773cc95aa4b0fd2af8dace82893.jpg')");
    });
JS;

$this->registerJs( $js, $position = yii\web\View::POS_END, $key = null );

?>

    <div class="jumbotron">
        <h1>Дальневосточный юридический институт<br> МВД России</h1>
        <p class="lead">Высшее военно-учебное заведение, основанное 21 июля 1921 года, осуществляющее подготовку офицерских кадров для органов внутренних дел Министерства внутренних дел Российской Федерации.</p>
        <p><a class="btn btn-primary" href="/my" role="button">Личный кабинет</a></p>

        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">

            <a class="carousel-control-prev" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>

            <a class="carousel-control-next" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <div class="row marketing">
        <div class="col-lg-6">
            <div class="link-icon" style="background-image: url('<?=$bundle->baseUrl?>/img/icon/4232eb9547f61ab68bba4dadff59216c.png');"></div>
            <h4><a href="//lms-fu.dvuimvd.ru" target="_blank">ЭИОС - ФПД, ФЮ</a></h4>
            <p>Среда для обучения курсантов и слушателей факультетов правоохранительной деятельности и юриспруденции.</p>

            <div class="link-icon" style="background-image: url('<?=$bundle->baseUrl?>/img/icon/6bb199e2df8ba93419aee016f43ea0bf.png');"></div>
            <h4><a href="//av.dvuimvd.ru" target="_blank">Автор-ВУЗ</a></h4>
            <p>Комплексная система автоматизации учебного процесса, электронный журнал</p>

            <div class="link-icon" style="background-image: url('<?=$bundle->baseUrl?>/img/icon/4232eb9547f61ab68bba4dadaf59216c.png');"></div>
            <h4><a href="https://trueconf.ru/" target="_blank">TrueConf</a></h4>
            <p> Программная система унифицированных коммуникаций для видеоконференций в локальной сети и через интернет</p>

            <div class="link-icon" style="background-image: url('<?=$bundle->baseUrl?>/img/icon/4232eb9547f61ab68bba4dadff59212c.png');"></div>
            <h4><a href="https://bigbluebutton.org/" target="_blank">Bigbluebutton</a></h4>
            <p>BigBlueButton - это система веб-конференций с открытым исходным кодом для онлайн-обучения</p>
        </div>

        <div class="col-lg-6">
            <div class="link-icon" style="background-image: url('<?=$bundle->baseUrl?>/img/icon/4232eb9547f61ab68bba4dadff59216c.png');"></div>
            <h4><a href="//lms.dvuimvd.ru" target="_blank">ЭИОС - ФПиПК</a></h4>
            <p>Среда для обучения слушателей факультетов переподготовки и повышения квалификации, заочного обучения, профессиональной подготовки и адъюнктуры</p>

            <div class="link-icon" style="background-image: url('<?=$bundle->baseUrl?>/img/icon/ea82410c7a9991816b5eeeebe195e20a.ico');"></div>
            <h4><a href="//biblio.dvuimvd.ru" target="_blank">Библиотека</a></h4>
            <p>Автоматизированная информационно-библиотечная система (АИБС) «МАРК-SQL».</p>

            <div class="link-icon" style="background-image: url('<?=$bundle->baseUrl?>/img/icon/6c72f5c2bcf135a03cf336014e8ad128.jpg');"></div>
            <h4><a href="//btrx.dvuimvd.ru" target="_blank">Корпоративный портал</a></h4>
            <p>Внутренний портал учебного заведения</p>


        </div>
    </div>


