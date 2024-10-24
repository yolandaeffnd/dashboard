<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "app_group".
 *
 * @property integer $idGroup
 * @property string $namaGroup
 * @property string $ketGroup
 * @property string $isMemberGroup
 *
 * @property AppGroupMenu[] $appGroupMenus
 * @property AppGroupView[] $appGroupViews
 * @property AppGroupView[] $appGroupViews0
 * @property AppGroup[] $idGroupViews
 * @property AppGroup[] $idGroups
 * @property AppUser[] $appUsers
 * @property Member[] $members
 */
class AppGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['namaGroup'], 'required'],
            [['ketGroup', 'isMemberGroup'], 'string'],
            [['namaGroup'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idGroup' => 'Id Group',
            'namaGroup' => 'Nama Group',
            'ketGroup' => 'Ket Group',
            'isMemberGroup' => 'Is Member Group',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppGroupMenus()
    {
        return $this->hasMany(AppGroupMenu::className(), ['idGroup' => 'idGroup']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppGroupViews()
    {
        return $this->hasMany(AppGroupView::className(), ['idGroup' => 'idGroup']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppGroupViews0()
    {
        return $this->hasMany(AppGroupView::className(), ['idGroupView' => 'idGroup']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdGroupViews()
    {
        return $this->hasMany(AppGroup::className(), ['idGroup' => 'idGroupView'])->viaTable('app_group_view', ['idGroup' => 'idGroup']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdGroups()
    {
        return $this->hasMany(AppGroup::className(), ['idGroup' => 'idGroup'])->viaTable('app_group_view', ['idGroupView' => 'idGroup']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppUsers()
    {
        return $this->hasMany(AppUser::className(), ['idGroup' => 'idGroup']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(Member::className(), ['memberGroupId' => 'idGroup']);
    }
}
