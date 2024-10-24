<?php

namespace app\modules\informasi\models;

use Yii;

/**
 * This is the model class for table "informasi".
 *
 * @property integer $infoId
 * @property string $infoAlias
 * @property string $infoJudul
 * @property string $infoIsi
 * @property string $infoIsPublish
 * @property string $infoCreate
 * @property string $infoUpdate
 */
class Informasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'informasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['infoAlias', 'infoJudul', 'infoIsi', 'infoIsPublish', 'infoCreate'], 'required'],
            [['infoIsi', 'infoIsPublish'], 'string'],
            [['infoCreate', 'infoUpdate'], 'safe'],
            [['infoAlias', 'infoJudul'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'infoId' => 'Info ID',
            'infoAlias' => 'Info Alias',
            'infoJudul' => 'Judul',
            'infoIsi' => 'Isi/Uraian',
            'infoIsPublish' => 'Publish?',
            'infoCreate' => 'Tanggal Buat',
            'infoUpdate' => 'Tanggal Ubah',
        ];
    }
}
