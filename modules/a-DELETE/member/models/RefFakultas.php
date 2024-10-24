<?php

namespace app\modules\member\models;

use Yii;

/**
 * This is the model class for table "ref_fakultas".
 *
 * @property integer $fakId
 * @property string $fakNama
 * @property string $fakCreate
 * @property string $fakUpdate
 */
class RefFakultas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_fakultas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fakId', 'fakNama', 'fakCreate'], 'required'],
            [['fakId'], 'integer'],
            [['fakCreate', 'fakUpdate'], 'safe'],
            [['fakNama'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fakId' => 'Fak ID',
            'fakNama' => 'Fak Nama',
            'fakCreate' => 'Fak Create',
            'fakUpdate' => 'Fak Update',
        ];
    }
}
