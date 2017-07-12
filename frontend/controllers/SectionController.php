<?php
/**
 * Created by PhpStorm.
 * User: Default
 * Date: 12/29/2016
 * Time: 7:05 PM
 */

namespace frontend\controllers;


use common\models\Section;
use yii\web\Controller;

class SectionController extends Controller
{
    public function actionView($id){
        $section = Section::findOne($id);

        $topics = $section->getTopics()->all();

        return $this->render('view', [
            'topics' => $topics,
            'section' => $section
        ]);
    }

    public function actionTopic($id){
        return $this->redirect(["/topic/" . $id]);
    }
}