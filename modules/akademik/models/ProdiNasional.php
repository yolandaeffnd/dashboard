<?php

namespace app\modules\akademik\models;

use Yii;

/**
 * This is the model class for table "ref_prodi_nasional".
 *
 * @property string $prodiKode
 * @property string $prodiNama
 * @property string $prodiJenjang
 * @property integer $prodiFakId
 * @property string $prodiStatus
 *
 * @property RefFakultas $prodiFak
 */
class ProdiNasional extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_prodi_nasional';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prodiKode', 'prodiNama', 'prodiJenjang', 'prodiFakId', 'prodiStatus'], 'required'],
            [['prodiFakId'], 'integer'],
            [['prodiStatus'], 'string'],
            [['prodiKode'], 'string', 'max' => 10],
            [['prodiNama'], 'string', 'max' => 200],
            [['prodiJenjang'], 'string', 'max' => 15],
            [['prodiFakId'], 'exist', 'skipOnError' => true, 'targetClass' => RefFakultas::className(), 'targetAttribute' => ['prodiFakId' => 'fakId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prodiKode' => 'Prodi Kode',
            'prodiNama' => 'Prodi Nama',
            'prodiJenjang' => 'Prodi Jenjang',
            'prodiFakId' => 'Prodi Fak ID',
            'prodiStatus' => 'Prodi Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdiFak()
    {
        return $this->hasOne(RefFakultas::className(), ['fakId' => 'prodiFakId']);
    }
}
