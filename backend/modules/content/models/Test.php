<?php
/**
 * Created by PhpStorm.
 * User: EDZ
 * Date: 2020/11/29
 * Time: 15:18
 */

namespace content\models;


class Test extends \yii\db\ActiveRecord
{
    public function rules()
    {
        return [
            ['scene_str','string']
        ];
    }
}