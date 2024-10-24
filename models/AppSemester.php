<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "app_semester".
 *
 * @property integer $smtId
 * @property string $smtNama
 * @property string $smtIsAktif
 */
class AppSemester extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_semester';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['smtId', 'smtNama'], 'required'],
            [['smtId'], 'integer'],
            [['smtIsAktif'], 'string'],
            [['smtNama'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'smtId' => 'Smt ID',
            'smtNama' => 'Smt Nama',
            'smtIsAktif' => 'Smt Is Aktif',
        ];
    }
}
