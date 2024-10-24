<?php

namespace app\modules\informasi\models;

use Yii;

/**
 * This is the model class for table "broadcast".
 *
 * @property integer $bcId
 * @property string $bcTo
 * @property string $bcJudul
 * @property string $bcIsi
 * @property string $bcCreate
 */
class Broadcast extends \yii\db\ActiveRecord {

    public $bcKategori;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'broadcast';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            //[['bcTo', 'bcJudul', 'bcIsi', 'bcCreate'], 'required'],
                [['bcIsi'], 'string'],
                [['bcTo', 'bcJudul', 'bcIsi', 'bcCreate', 'bcKategori'], 'safe'],
                [['bcJudul'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bcId' => 'Bc ID',
            'bcTo' => 'Tujuan',
            'bcJudul' => 'Judul',
            'bcIsi' => 'Uraian/Isi',
            'bcCreate' => 'Tanggal Kirim',
            'bcKategori' => 'Kategori Broadcast'
        ];
    }

}
