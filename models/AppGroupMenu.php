<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "app_group_menu".
 *
 * @property integer $idMenu
 * @property integer $idGroup
 * @property string $actionFn
 */
class AppGroupMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_group_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idMenu', 'idGroup', 'actionFn'], 'required'],
            [['idMenu', 'idGroup'], 'integer'],
            [['actionFn'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idMenu' => 'Id Menu',
            'idGroup' => 'Id Group',
            'actionFn' => 'Action Fn',
        ];
    }
}
