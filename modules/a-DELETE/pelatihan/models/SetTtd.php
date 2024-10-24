<?php

namespace app\modules\pelatihan\models;

use Yii;

/**
 * This is the model class for table "set_ttd".
 *
 * @property integer $ttdId
 * @property string $ttdKode
 * @property string $ttdJabatan
 * @property string $ttdNama
 * @property string $ttdNip
 * @property string $ttdPosisi
 * @property string $ttdLastUpdate
 */
class SetTtd extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'set_ttd';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ttdKode', 'ttdJabatan', 'ttdNama', 'ttdNip', 'ttdPosisi'], 'required'],
            [['ttdPosisi'], 'string'],
            [['ttdLastUpdate'], 'safe'],
            [['ttdKode'], 'string', 'max' => 20],
            [['ttdJabatan'], 'string', 'max' => 250],
            [['ttdNama'], 'string', 'max' => 150],
            [['ttdNip'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ttdId' => 'Ttd ID',
            'ttdKode' => 'Ttd Kode',
            'ttdJabatan' => 'Ttd Jabatan',
            'ttdNama' => 'Ttd Nama',
            'ttdNip' => 'Ttd Nip',
            'ttdPosisi' => 'Ttd Posisi',
            'ttdLastUpdate' => 'Ttd Last Update',
        ];
    }
}
