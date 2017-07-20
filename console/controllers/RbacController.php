<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
use common\components\rbac\UserRoleRule;
class RbacController extends Controller
{
    public function actionInit()
    {
//        $auth = Yii::$app->authManager;
//        $auth->removeAll(); //удаляем старые данные
//        //Создадим для примера права для доступа к админке
//        $dashboard = $auth->createPermission('dashboard');
//        $dashboard->description = 'Админ панель';
//        $auth->add($dashboard);
//        //Включаем наш обработчик
//        $rule = new UserRoleRule();
//        $auth->add($rule);
//        //Добавляем роли
//        $user = $auth->createRole('user');
//        $user->description = 'Пользователь';
//        $user->ruleName = $rule->name;
//        $auth->add($user);
//        $student = $auth->createRole('student');
//        $student->description = 'student';
//        $student->ruleName = $rule->name;
//        $auth->add($student);
//        //Добавляем потомков
//        $auth->addChild($student, $user);
//        $auth->addChild($student, $dashboard);
//        $admin = $auth->createRole('admin');
//        $admin->description = 'admin';
//        $admin->ruleName = $rule->name;
//        $auth->add($admin);
//        $auth->addChild($admin, $student);

        $auth = Yii::$app->authManager;
        $auth->removeAll(); //удаляем старые данные

        // Включаем наш обработчик
        $rule = new UserRoleRule();
        $auth->add($rule);

        // Добавляем роли
        $admin = $auth->createRole('admin');
        $admin->ruleName = $rule->name;
        $auth->add($admin);
        $user = $auth->createRole('user');
        $user->ruleName = $rule->name;
        $auth->add($user);

        // Создадим права для доступа
        $dashboard = $auth->createPermission('dashboard');
        $dashboard->description = 'Admin panel';
        $auth->add($dashboard);
//        $video = $auth->createPermission('video');
//        $video->description = 'Has user access to video section';
//        $user->ruleName = $videoRule;
//        $auth->add($video);
        // Даём доступ
//        $auth->addChild($user, $video);
        $auth->addChild($admin, $dashboard);
        // Наследование прав
        $auth->addChild($admin, $user);

    }
}
