<?php

/* @var $this yii\web\View */
/** @var string $sentence */

use yii\helpers\Html;

$this->title = 'My favorite video';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= $sentence ?></p>

</div>
