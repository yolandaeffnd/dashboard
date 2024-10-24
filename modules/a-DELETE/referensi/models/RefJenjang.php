<?php

namespace app\modules\referensi\models;

use Yii;

/**
 * This is the model class for table "ref_jenjang".
 *
 * @property integer $jenjId
 * @property string $jenjNama
 * @property string $jenjDeskripsi
 *
 * @property RefProdi[] $refProdis
 */
class RefJenjang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_jenjang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jenjId', 'jenjNama'], 'required'],
            [['jenjId'], 'integer'],
            [['jenjNama'], 'string', 'max' => 200],
            [['jenjDeskripsi'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jenjId' => 'Jenj ID',
            'jenjNama' => 'Jenj Nama',
            'jenjDeskripsi' => 'Jenj Deskripsi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefProdis()
    {
        return $this->hasMany(RefProdi::className(), ['prodiJenjId' => 'jenjId']);
    }
}
