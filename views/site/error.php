<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="page vertical-align text-center">
    <div class="page-content vertical-align-middle">
            <div class="site-error">
                <header>
                    <h1 class="animation-slide-top"><?= Html::encode($this->title) ?></h1>

                    <div class="alert alert-danger">
                        <?= nl2br(Html::encode($message)) ?>
                    </div>
                </header>
                <p class="error-advise">
                    Вышеупомянутая ошибка произошла, когда веб-сервер обрабатывал ваш запрос.
                </p>
                <a class="btn btn-success btn-round mb-5" href="/my">Личный кабинет</a>
                <a class="btn btn-primary btn-round mb-5" href="/">Главная страница</a>

            <footer class="page-copyright">
                <p>© 2019. All RIGHT RESERVED.</p>
            </footer>
        </div>
    </div>
</div>
