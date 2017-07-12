<?php
namespace console\controllers;
use Yii;
use yii\console\Controller;
use common\components\rbac\UserRoleRule;
class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll(); //удаляем старые данные
        //Создадим для примера права для доступа к админке
        $dashboard = $auth->createPermission('dashboard');
        $dashboard->description = 'Админ панель';
        $auth->add($dashboard);
        //Включаем наш обработчик
        $rule = new UserRoleRule();
        $auth->add($rule);
        //Добавляем роли
        $user = $auth->createRole('user');
        $user->description = 'Пользователь';
        $user->ruleName = $rule->name;
        $auth->add($user);
        $student = $auth->createRole('student');
        $student->description = 'student';
        $student->ruleName = $rule->name;
        $auth->add($student);
        //Добавляем потомков
        $auth->addChild($student, $user);
        $auth->addChild($student, $dashboard);
        $admin = $auth->createRole('admin');
        $admin->description = 'admin';
        $admin->ruleName = $rule->name;
        $auth->add($admin);
        $auth->addChild($admin, $student);
    }
}


//public function actionInit()
//{
//    $auth = Yii::$app->authManager;
//    $auth->removeAll(); //������� ������ ������
//    //�������� ��� ������� ����� ��� ������� � �������
//    $dashboard = $auth->createPermission('dashboard');
//    $dashboard->description = '����� ������';
//    $auth->add($dashboard);
//    //�������� ��� ����������
//    $rule = new UserRoleRule();
//    $auth->add($rule);
//    //��������� ����
//    $user = $auth->createRole('user');
//    $user->description = 'user';
//    $user->ruleName = $rule->name;
//    $auth->add($user);
//    $student = $auth->createRole('student');
//    $student->description = 'student';
//    $student->ruleName = $rule->name;
//    $auth->add($student);
//    $university = $auth->createRole('university');
//    $university->description = 'university';
//    $university->ruleName = $rule->name;
//    $auth->add($university);
//    //��������� ��������
//    $auth->addChild($student, $user);
//    $auth->addChild($university, $student);
//    $auth->addChild($university, $dashboard);
//    $admin = $auth->createRole('admin');
//    $admin->description = 'admin';
//    $admin->ruleName = $rule->name;
//    $auth->add($admin);
//    $auth->addChild($admin, $university);
//}