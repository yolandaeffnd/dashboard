<?php

namespace app\modules\maping\models;

use Yii;

/**
 * This is the model class for table "ref_fakultas".
 *
 * @property integer $fakId
 * @property string $fakNama
 * @property string $fakDb
 * @property string $fakCreate
 * @property string $fakUpdate
 *
 * @property AppUserData[] $appUserDatas
 * @property AppUser[] $idUsers
 * @property RefProdiNasional[] $refProdiNasionals
 * @property SiregProdi[] $siregProdis
 */
class Fakultas extends \yii\db\ActiveRecord
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
            [['fakNama', 'fakCreate'], 'required'],
            [['fakCreate', 'fakUpdate'], 'safe'],
            [['fakNama'], 'string', 'max' => 250],
            [['fakDb'], 'string', 'max' => 50],
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
            'fakDb' => 'Fak Db',
            'fakCreate' => 'Fak Create',
            'fakUpdate' => 'Fak Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppUserDatas()
    {
        return $this->hasMany(AppUserData::className(), ['unitId' => 'fakId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUsers()
    {
        return $this->hasMany(AppUser::className(), ['idUser' => 'idUser'])->viaTable('app_user_data', ['unitId' => 'fakId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefProdiNasionals()
    {
        return $this->hasMany(RefProdiNasional::className(), ['prodiFakId' => 'fakId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiregProdis()
    {
        return $this->hasMany(SiregProdi::className(), ['idFak' => 'fakId']);
    }
}
