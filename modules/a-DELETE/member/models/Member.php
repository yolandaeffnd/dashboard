<?php

namespace app\modules\member\models;

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
 * @property string $memberPddId
 * @property string $memberFoto
 * @property string $memberMemberKatId
 * @property string $memberIsAkunPortal 
 * @property string $memberAkunPortal
 * @property string $memberDsnNip
 * @property string $memberDsnNidn
 * @property string $memberDsnProdiId
 * @property string $memberDsnFakId
 * @property string $memberMhsAngkatan
 * @property string $memberMhsNim
 * @property integer $memberMhsProdiId
 * @property integer $memberMhsFakId
 * @property string $memberPassword
 * @property string $memberIsAktif
 * @property integer $memberGroupId
 * @property string $memberCreate
 * @property string $memberUpdate
 *
 * @property LatPeserta[] $latPesertas
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
            [['memberNama', 'memberJenkel', 'memberTglLahir', 'memberTmpLahir', 'memberEmail', 'memberTelp','memberPddId','memberIsAkunPortal', 'memberMemberKatId'], 'required'],
            [['memberJenkel', 'memberMemberKatId', 'memberIsAktif'], 'string'],
            [['memberId','memberAkunPortal','memberTglLahir', 'memberCreate', 'memberUpdate','memberDsnNip', 'memberDsnNidn', 'memberDsnProdiId', 'memberDsnFakId'], 'safe'],
            [['memberMhsProdiId', 'memberMhsFakId', 'memberGroupId'], 'integer'],
            [['memberNama'], 'string', 'max' => 150],
            [['memberEmail'], 'email'],
            [['memberTmpLahir'], 'string', 'max' => 200],
            [['memberTelp', 'memberFoto'], 'string', 'max' => 100],
            [['memberMhsAngkatan'], 'string', 'max' => 4],
            [['memberMhsNim'], 'string', 'max' => 10],
            [['memberPassword'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'memberId' => 'Member ID',
            'memberNama' => 'Nama',
            'memberJenkel' => 'L/P',
            'memberTglLahir' => 'Tanggal Lahir',
            'memberTmpLahir' => 'Tempat Lahir',
            'memberEmail' => 'Email',
            'memberTelp' => 'Telp',
            'memberPddId' => 'Pendidikan Terakhir',
            'memberFoto' => 'Member Foto',
            'memberMemberKatId' => 'Kategori Member',
            'memberIsAkunPortal'=>'Akun Portal?',
            'memberDsnNip' => 'NIP',
            'memberDsnNidn' => 'NIDN',
            'memberDsnProdiId' => 'Program Studi',
            'memberDsnFakId' => 'Fakultas',
            'memberMhsAngkatan' => 'Angkatan',
            'memberMhsNim' => 'NIM',
            'memberMhsProdiId' => 'Program Studi',
            'memberMhsFakId' => 'Fakultas',
            'memberAkunPortal'=>'Member Akun Portal',
            'memberPassword' => 'Member Password',
            'memberIsAktif' => 'Status',
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
    public function getMemberMemberKat() {
        return $this->hasOne(MemberKategori::className(), ['memberKatId' => 'memberMemberKatId']);
    }
}
