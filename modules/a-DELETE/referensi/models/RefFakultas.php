<?php

namespace app\modules\referensi\models;

use Yii;

/**
 * This is the model class for table "ref_fakultas".
 *
 * @property string $fakId
 * @property string $fakNama
 * @property string $fakCreate
 * @property string $fakUpdate
 *
 * @property RefProdi[] $refProdis
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
            [['fakCreate', 'fakUpdate','fakId'], 'safe'],
            [['fakNama'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fakId' => 'Kode',
            'fakNama' => 'Fakultas',
            'fakCreate' => 'Fak Create',
            'fakUpdate' => 'Fak Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefProdis()
    {
        return $this->hasMany(RefProdi::className(), ['prodiFakId' => 'fakId']);
    }
}
