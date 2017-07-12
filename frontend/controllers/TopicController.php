<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.06.2017
 * Time: 14:20
 */

namespace frontend\controllers;


use common\models\Topic;
use common\models\Video;
use yii\web\Controller;


class TopicController extends Controller
{
    public function actionView($id){
        $topic = Topic::findOne($id);
        $video = Video::findAll(['topic_id' => $topic->id,]);
        return $this->render('view', ['topic' => $topic, 'video' => $video]);
    }
}