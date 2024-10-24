<?php

namespace app\modules\pelatihan\models;

use Yii;

/**
 * This is the model class for table "member".
 *
 * @property string $memberId
 * @property string $memberNama
 * @property string $memberJenkel
 * @property string $memberTglLahir
 * @property string $memberTmpLahir
 * @property string $memberEmail
 * @property string $memberTelp
 * @property string $memberFoto
 * @property string $memberIsAkunPortal
 * @property integer $memberMemberKatId
 * @property string $memberMhsAngkatan
 * @property string $memberMhsNim
 * @property integer $memberMhsProdiId
 * @property integer $memberMhsFakId
 * @property string $memberAkunPortal
 * @property string $memberPassword
 * @property string $memberIsAktif
 * @property integer $memberGroupId
 * @property string $memberCreate
 * @property string $memberUpdate
 *
 * @property LatPeserta[] $latPesertas
 * @property MemberKategori $memberMemberKat
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['memberId','memberNama', 'memberJenkel', 'memberTglLahir', 'memberTmpLahir', 'memberEmail', 'memberTelp', 'memberIsAkunPortal', 'memberMemberKatId', 'memberPassword', 'memberGroupId', 'memberCreate'], 'required'],
            [['memberJenkel', 'memberIsAkunPortal', 'memberIsAktif'], 'string'],
            [['memberTglLahir', 'memberCreate', 'memberUpdate'], 'safe'],
            [['memberMemberKatId', 'memberMhsProdiId', 'memberMhsFakId', 'memberGroupId'], 'integer'],
            [['memberNama', 'memberEmail'], 'string', 'max' => 150],
            [['memberTmpLahir'], 'string', 'max' => 200],
            [['memberTelp', 'memberFoto'], 'string', 'max' => 100],
            [['memberMhsAngkatan'], 'string', 'max' => 4],
            [['memberMhsNim'], 'string', 'max' => 10],
            [['memberAkunPortal', 'memberPassword'], 'string', 'max' => 32],
            [['memberMemberKatId'], 'exist', 'skipOnError' => true, 'targetClass' => MemberKategori::className(), 'targetAttribute' => ['memberMemberKatId' => 'memberKatId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'memberId' => 'Member ID',
            'memberNama' => 'Member Nama',
            'memberJenkel' => 'Member Jenkel',
            'memberTglLahir' => 'Member Tgl Lahir',
            'memberTmpLahir' => 'Member Tmp Lahir',
            'memberEmail' => 'Member Email',
            'memberTelp' => 'Member Telp',
            'memberFoto' => 'Member Foto',
            'memberIsAkunPortal' => 'Member Is Akun Portal',
            'memberMemberKatId' => 'Member Member Kat ID',
            'memberMhsAngkatan' => 'Member Mhs Angkatan',
            'memberMhsNim' => 'Member Mhs Nim',
            'memberMhsProdiId' => 'Member Mhs Prodi ID',
            'memberMhsFakId' => 'Member Mhs Fak ID',
            'memberAkunPortal' => 'Member Akun Portal',
            'memberPassword' => 'Member Password',
            'memberIsAktif' => 'Member Is Aktif',
            'memberGroupId' => 'Member Group ID',
            'memberCreate' => 'Member Create',
            'memberUpdate' => 'Member Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLatPesertas()
    {
        return $this->hasMany(LatPeserta::className(), ['pesertaMemberId' => 'memberId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberMemberKat()
    {
        return $this->hasOne(MemberKategori::className(), ['memberKatId' => 'memberMemberKatId']);
    }
}
