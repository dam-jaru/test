<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin".
 *
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string $token
 * @property string $last_time
 */
class Admin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password', 'token', 'last_time'], 'required'],
            [['last_time'], 'safe'],
            [['login', 'password', 'token'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'token' => 'Token',
            'last_time' => 'Last Time',
        ];
    }
}
