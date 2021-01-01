<?php
use yii\helpers\Html;
use app\modules\system\SystemAsset;

$bundle = SystemAsset::register($this);
?>
<div class="col-md-12">
    <div class="card mb-3">
        <img class="card-img-top" src="<?php echo $bundle->baseUrl ?>/img/image-wide.svg" alt="Card image cap">
        <div class="card-body">
            <h5 class="card-title">A card with image on top</h5>
            <p class="card-text">A fluid card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
            <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
        </div>
    </div>
</div>

<div class="col-md-6 col-lg-6">
    <div class="card">
        <div class="card-header">Alerts with Icon and Title</div>
        <div class="card-body">
            <p class="card-title"></p>
            <div class="alert alert-primary">
                <h5 class="alert-title"><i class="fas fa-info"></i> Primary</h5>
                This is a primary alert.
            </div>
            <div class="alert alert-secondary">
                <h5 class="alert-title"><i class="fas fa-question-circle"></i> Secondary</h5>
                This is a secondary alert.
            </div>
            <div class="alert alert-success">
                <h5 class="alert-title"><i class="fas fa-check"></i> Success</h5>
                This is a success alert.
            </div>
            <div class="alert alert-danger">
                <h5 class="alert-title"><i class="fas fa-exclamation-triangle"></i> Danger</h5>
                This is a danger alert.
            </div>
        </div>
    </div>
</div>
