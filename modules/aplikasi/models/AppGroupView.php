<?php

namespace app\modules\aplikasi\models;

use Yii;

/**
 * This is the model class for table "app_group_view".
 *
 * @property integer $idGroup
 * @property integer $idGroupView
 */
class AppGroupView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_group_view';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idGroup', 'idGroupView'], 'required'],
            [['idGroup', 'idGroupView'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idGroup' => 'Id Group',
            'idGroupView' => 'Id Group View',
        ];
    }
}
