<?php

namespace nadar\calendar\models;

use yii\base\Model;

class LoginModelForm extends Model
{
    public $password;

    public function rules()
    {
        return [
            [['password'], 'required'],
        ];
    }
}