<?php

namespace app\controllers;
use app\models\Admin;

class AdminController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\Admin';
    public $enableCsrfValidation = false;
    private $salt = 'PG#SgHj3Ba';
    public function actions()
    {
        $actions = parent::actions();
        foreach($actions as $k => $v) unset($actions[$k]);
        return $actions;
    }
    public function actionAuth($login, $pwd)
    {
        if($o_admin = Admin::find()->where(['login' => $login, 'password' => md5($login.$pwd)])->one()){
            $o_admin->token = md5($login.$this->salt.(time()-mt_rand(1000,100000)));
            $o_admin->save();
            return $this->asJson(['status' => 'ok', 'data' => ['token' => $o_admin->token]]);
        }
        else{
            return $this->asJson(['status' => 'error', 'error_code' => 2]);
        }
    }
}
