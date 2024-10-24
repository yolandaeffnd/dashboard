<?php

namespace app\modules\maping\models;

use Yii;

/**
 * This is the model class for table "sireg_jalur".
 *
 * @property integer $idJalur
 * @property string $kodeJalur
 * @property string $namaJalur
 *
 * @property RefJalurMap[] $refJalurMaps
 * @property RefJalur[] $idJalurs
 */
class SiregJalur extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sireg_jalur';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kodeJalur'], 'string', 'max' => 5],
            [['namaJalur'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idJalur' => 'Id Jalur',
            'kodeJalur' => 'Kode Jalur',
            'namaJalur' => 'Nama Jalur',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefJalurMaps()
    {
        return $this->hasMany(RefJalurMap::className(), ['mapIdJalur' => 'idJalur']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdJalurs()
    {
        return $this->hasMany(RefJalur::className(), ['idJalur' => 'idJalur'])->viaTable('ref_jalur_map', ['mapIdJalur' => 'idJalur']);
    }
}
