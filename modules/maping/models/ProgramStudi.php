<?php

namespace app\modules\maping\models;

use Yii;

/**
 * This is the model class for table "program_studi".
 *
 * @property string $idProgramStudi
 * @property string $idProdi
 * @property string $idProdiSia
 * @property integer $kodeProdiUniv
 * @property string $namaProgramStudi
 * @property string $idFak
 * @property string $idJur
 * @property string $namaPendekFak
 * @property integer $idJenjang
 * @property string $idRekening
 * @property string $userUbah
 * @property string $tanggalUbah
 * @property string $unitId
 * @property integer $kodeBP
 *
 * @property Biodata[] $biodatas
 * @property BiodataCalon[] $biodataCalons
 * @property BiodataLamo[] $biodataLamos
 * @property HistoryBiodata[] $historyBiodatas
 * @property HistoryBiodata[] $historyBiodatas0
 * @property ProdiJalur[] $prodiJalurs
 * @property JenjangLama $idJenjang0
 * @property Rekening $idRekening0
 * @property User $userUbah0
 * @property Fakultas $idFak0
 * @property TUnit $unit
 * @property Jurusan $idJur0
 * @property SksProgramStudi[] $sksProgramStudis
 * @property Tarif[] $tarifs
 * @property TarifKhusus[] $tarifKhususes
 */
class ProgramStudi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'program_studi';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbSireg');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idProdiSia', 'kodeProdiUniv', 'idFak', 'idJur', 'idJenjang', 'idRekening', 'userUbah', 'unitId', 'kodeBP'], 'integer'],
            [['tanggalUbah'], 'safe'],
            [['idProdi'], 'string', 'max' => 5],
            [['namaProgramStudi'], 'string', 'max' => 255],
            [['namaPendekFak'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idProgramStudi' => 'Id Program Studi',
            'idProdi' => 'Id Prodi',
            'idProdiSia' => 'Id Prodi Sia',
            'kodeProdiUniv' => 'Kode Prodi Univ',
            'namaProgramStudi' => 'Nama Program Studi',
            'idFak' => 'Id Fak',
            'idJur' => 'Id Jur',
            'namaPendekFak' => 'Nama Pendek Fak',
            'idJenjang' => 'Id Jenjang',
            'idRekening' => 'Id Rekening',
            'userUbah' => 'User Ubah',
            'tanggalUbah' => 'Tanggal Ubah',
            'unitId' => 'Unit ID',
            'kodeBP' => 'Kode Bp',
        ];
    }

}
