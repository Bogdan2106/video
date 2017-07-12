<?php
use yii\helpers\Html;
use common\models\User;
use yii\bootstrap;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */

$this->title = 'topic';

/** @var User $user */
$user = Yii::$app->user->identity;

?>
<div class="site-index">
    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2><?= $topic->name ?></h2>
                <?
                if (count($video) != 0) {
                    for ($i = 0; $i < count($video); $i++) { ?>
                        <div class="video" data-id="<?= $video[$i]->id ?>">
                            <?= $video[$i]->name ?>
                            <video width="400" height="300" controls="controls"
                                   poster="/backend/web/<?= \common\models\Image::findOne($video[$i]->image_id)->path ?>">
                                <source src="/backend/web/<?= $video[$i]->path ?>"
                                        type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
                            </video>
                            <p>
                                <button class="btn btn-sm btn-warning"><?= $video[$i]->hasLiked() ? 'Dislike' : 'Like' ?></button>
                            </p>
                        </div>
                    <? }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?
$this->registerJsFile('/js/button.js', ['depends' => ['yii\web\JqueryAsset']]);