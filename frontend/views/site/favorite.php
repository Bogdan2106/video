<?php

/* @var $this yii\web\View */
/** @var array \common\models\Like $models */

use yii\helpers\Html;

$this->title = 'Favorite video';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="body-content">
        <div class="row">
            <?
            if (count($models) != 0) {
                foreach ($models as $model) {
                    $video = $model->video;
                    ?>
                    <div class="col-lg-4">
                        <div class="video" data-id="<?= $video->id ?>">
                            <?= $video->name ?>
                            <video width="400" height="300" controls="controls"
                                   poster="/backend/web/<?= \common\models\Image::findOne($video->image_id)->path ?>">
                                <source src="/backend/web/<?= $video->path ?>"
                                        type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
                            </video>
                            <p>
                                <button class="btn btn-sm btn-warning"><?= $video->hasLiked() ? 'Dislike' : 'Like' ?></button>
                            </p>
                        </div>

                    </div>
                <? }
            } else echo 'empty';
            ?>
        </div>
    </div>
<?
$this->registerJsFile('/js/button.js', ['depends' => ['yii\web\JqueryAsset']]);