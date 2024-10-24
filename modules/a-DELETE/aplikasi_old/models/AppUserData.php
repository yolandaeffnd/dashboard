<?php

namespace app\modules\aplikasi\models;

use Yii;

/**
 * This is the model class for table "app_user_data".
 *
 * @property integer $idUser
 * @property integer $idUnit
 */
class AppUserData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_user_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idUser', 'idFak'], 'required'],
            [['idUser', 'idFak'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idUser' => 'Id User',
            'idFak' => 'Id Fakultas',
        ];
    }
}
