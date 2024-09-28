<?php

namespace app\controllers;
use Yii;
use app\models\User;
use app\models\Admin;

class UserController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\User';
    public $enableCsrfValidation = false;
    
    public function actions()
    {
        $actions = parent::actions();
        foreach($actions as $k => $v) unset($actions[$k]);
        return $actions;
    }

    public function actionIndex($token) 
    {
        if(Admin::find()->where(['token' => $token])->one()){
            $users = User::find()->all();
            return $this->asJson(['status' => 'ok', 'data' => $users]);
        }
        else{
            return $this->asJson(['status' => 'error', 'error_code' => 1]);
        }
    }

    public function actionView($token, $id=0)
    {
        if(Admin::find()->where(['token' => $token])->one()){
            if($id){
                $values = User::find()->where(['id' => $id])->one();
                $user = [
                    ['name' => 'id', 'value' => $values->id, 'type' => 'hidden', 'placeholder' => ''],
                    ['name' => 'login', 'value' => $values->login, 'type' => 'text', 'placeholder' => 'login'],
                    ['name' => 'name', 'value' => $values->name, 'type' => 'text', 'placeholder' => 'name'],
                    ['name' => 'password', 'value' => '', 'type' => 'text', 'placeholder' => 'password'],
                    ['name' => 'active', 'value' => $values->active, 'type' => 'text', 'placeholder' => 'act - 1/deact - 0'],
                ];
            }
            else{
                $user = [
                    ['name' => 'login', 'value' => '', 'placeholder' => 'login'],
                    ['name' => 'name', 'value' => '', 'placeholder' => 'name'],
                    ['name' => 'password', 'value' => '', 'placeholder' => 'password'],
                    ['name' => 'active', 'value' => '', 'placeholder' => 'act - 1/deact - 0'],
                ];
            }
            return $this->asJson(['status' => 'ok', 'data' => $user]);
        }
        else{
            return $this->asJson(['status' => 'error', 'error_code' => 1]);
        }
    }
    public function actionSave($token)
    {
        if(Admin::find()->where(['token' => $token])->one()){
            if(strlen(Yii::$app->request->post('name'))==0)return $this->asJson(['status' => 'error', 'error_code' => 5]);
            if(strlen(Yii::$app->request->post('login'))==0)return $this->asJson(['status' => 'error', 'error_code' => 6]);
            if(Yii::$app->request->post('id') !== null) {
                $user = User::find()->where(['id' => Yii::$app->request->post('id')])->one();
            }
            else {
                if(strlen(Yii::$app->request->post('password'))==0)return $this->asJson(['status' => 'error', 'error_code' => 4]);
                $user = new User();
            }
            $user->login = Yii::$app->request->post('login');
            if(strlen(Yii::$app->request->post('password'))>0) $user->password = md5(Yii::$app->request->post('login').Yii::$app->request->post('password'));
            $user->name = Yii::$app->request->post('name');
            if(Yii::$app->request->post('active') != null) $user->active = 1;
            else $user->active = 0;
            $user->save();
            return $this->asJson(['status' => 'ok', 'data' => $user]);
        }
        else{
            return $this->asJson(['status' => 'error', 'error_code' => 1]);
        }
    }
    public function actionDeact($token){
        if(Admin::find()->where(['token' => $token])->one()){
            $id = Yii::$app->request->post('id');
            if($user = User::find()->where(['id' => $id])->one()){
                $user->active = 0;
                $user->save();
                return $this->asJson(['status' => 'ok', 'data' => $user]);
            }
            else{
                return $this->asJson(['status' => 'error', 'error_code' => 3]);
            }
        }
        else{
            return $this->asJson(['status' => 'error', 'error_code' => 1]);
        }
    }
    public function actionAct($token){
        if(Admin::find()->where(['token' => $token])->one()){
            $id = Yii::$app->request->post('id');
            if($user = User::find()->where(['id' => $id])->one()){
                $user->active = 1;
                $user->save();
                return $this->asJson(['status' => 'ok', 'data' => $user]);
            }
            else{
                return $this->asJson(['status' => 'error', 'error_code' => 3]);
            }
        }
        else{
            return $this->asJson(['status' => 'error', 'error_code' => 1]);
        }
    }
    public function actionEditname($token){
        if(Admin::find()->where(['token' => $token])->one()){
            $id = Yii::$app->request->post('id');
            if($user = User::find()->where(['id' => $id])->one()){
                $user->name = Yii::$app->request->post('newval');
                $user->save();
                return $this->asJson(['status' => 'ok', 'data' => $user]);
            }
            else{
                return $this->asJson(['status' => 'error', 'error_code' => 3]);
            }
        }
        else{
            return $this->asJson(['status' => 'error', 'error_code' => 1]);
        }
    }
    public function actionEditlogin($token){
        if(Admin::find()->where(['token' => $token])->one()){
            $id = Yii::$app->request->post('id');
            if($user = User::find()->where(['id' => $id])->one()){
                $user->login = Yii::$app->request->post('newval');
                $user->save();
                return $this->asJson(['status' => 'ok', 'data' => $user]);
            }
            else{
                return $this->asJson(['status' => 'error', 'error_code' => 3]);
            }
        }
        else{
            return $this->asJson(['status' => 'error', 'error_code' => 1]);
        }
    }
    public function actionDel($token){
        if(Admin::find()->where(['token' => $token])->one()){
            $id = Yii::$app->request->post('id');
            if($user = User::find()->where(['id' => $id])->one()){
                $user->delete();
                return $this->asJson(['status' => 'ok', 'data' => []]);
            }
            else{
                return $this->asJson(['status' => 'error', 'error_code' => 3]);
            }
        }
        else{
            return $this->asJson(['status' => 'error', 'error_code' => 1]);
        }
    }
}
