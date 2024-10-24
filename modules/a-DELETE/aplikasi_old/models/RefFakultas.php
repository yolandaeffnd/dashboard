<?php

namespace app\modules\aplikasi\models;

use Yii;

/**
 * This is the model class for table "ref_fakultas".
 *
 * @property integer $idFak
 * @property string $namaFak
 * @property string $createDate
 *
 * @property AppUserData[] $appUserDatas
 * @property AppUser[] $idUsers
 * @property RefProgramStudi[] $refProgramStudis
 */
class RefFakultas extends \yii\db\ActiveRecord {

    public $arrUserData;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ref_fakultas';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['namaFak'], 'required'],
            [['createDate'], 'safe'],
            [['namaFak'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'idFak' => 'Id Fak',
            'namaFak' => 'Nama Fak',
            'createDate' => 'Create Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppUserDatas() {
        return $this->hasMany(AppUserData::className(), ['idFak' => 'idFak']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUsers() {
        return $this->hasMany(AppUser::className(), ['idUser' => 'idUser'])->viaTable('app_user_data', ['idFak' => 'idFak']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefProgramStudis() {
        return $this->hasMany(RefProgramStudi::className(), ['idFak' => 'idFak']);
    }

}
