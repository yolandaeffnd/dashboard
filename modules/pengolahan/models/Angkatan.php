<?php

namespace app\modules\pengolahan\models;

use Yii;

/**
 * This is the model class for table "ref_angkatan".
 *
 * @property string $angkatan
 */
class Angkatan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_angkatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['angkatan'], 'required'],
            [['angkatan'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'angkatan' => 'Angkatan',
        ];
    }
}
