<?php

namespace app\modules\referensi\models;

use Yii;

/**
 * This is the model class for table "ref_prodi".
 *
 * @property string $prodiId
 * @property string $prodiNama
 * @property string $prodiFakId
 * @property string $prodiCreate
 * @property string $prodiUpdate
 *
 * @property RefFakultas $prodiFak
 */
class RefProdi extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ref_prodi';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['prodiId', 'prodiNama','prodiJenjId', 'prodiFakId', 'prodiCreate'], 'required'],
                [['prodiCreate', 'prodiUpdate', 'prodiId', 'prodiFakId'], 'safe'],
                [['prodiNama'], 'string', 'max' => 200],
                [['prodiFakId'], 'exist', 'skipOnError' => true, 'targetClass' => RefFakultas::className(), 'targetAttribute' => ['prodiFakId' => 'fakId']],
                [['prodiJenjId'], 'exist', 'skipOnError' => true, 'targetClass' => RefJenjang::className(), 'targetAttribute' => ['prodiJenjId' => 'jenjId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'prodiId' => 'Kode',
            'prodiNama' => 'Program Studi',
            'prodiJenjId'=>'Jenjang',
            'prodiFakId' => 'Fakultas',
            'prodiCreate' => 'Prodi Create',
            'prodiUpdate' => 'Prodi Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdiFak() {
        return $this->hasOne(RefFakultas::className(), ['fakId' => 'prodiFakId']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdiJenj() {
        return $this->hasOne(RefJenjang::className(), ['jenjId' => 'prodiJenjId']);
    }

}
