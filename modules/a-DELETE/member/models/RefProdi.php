<?php

namespace app\modules\member\models;

use Yii;

/**
 * This is the model class for table "ref_prodi".
 *
 * @property integer $prodiId
 * @property string $prodiNama
 * @property integer $prodiFakId
 * @property string $prodiCreate
 * @property string $prodiUpdate
 *
 * @property RefProdi $prodiFak
 * @property RefProdi[] $refProdis
 */
class RefProdi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_prodi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prodiId', 'prodiNama', 'prodiFakId', 'prodiCreate'], 'required'],
            [['prodiId', 'prodiFakId'], 'integer'],
            [['prodiCreate', 'prodiUpdate'], 'safe'],
            [['prodiNama'], 'string', 'max' => 200],
            [['prodiFakId'], 'exist', 'skipOnError' => true, 'targetClass' => RefProdi::className(), 'targetAttribute' => ['prodiFakId' => 'prodiId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prodiId' => 'Prodi ID',
            'prodiNama' => 'Prodi Nama',
            'prodiFakId' => 'Prodi Fak ID',
            'prodiCreate' => 'Prodi Create',
            'prodiUpdate' => 'Prodi Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdiFak()
    {
        return $this->hasOne(RefProdi::className(), ['prodiId' => 'prodiFakId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefProdis()
    {
        return $this->hasMany(RefProdi::className(), ['prodiFakId' => 'prodiId']);
    }
}
