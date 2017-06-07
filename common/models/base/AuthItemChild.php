<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "auth_item_child".
 *
 * @property string $parent
 * @property string $child
 *
 * @property common\models\AuthItem $parent0
 * @property common\models\AuthItem $child0
 */
class AuthItemChild extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'child'], 'required'],
            [['parent', 'child'], 'string', 'max' => 64],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_item_child';
    }

  
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent' => 'Parent',
            'child' => 'Child',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent0()
    {
        return $this->hasOne(\common\models\AuthItem::className(), ['name' => 'parent']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChild0()
    {
        return $this->hasOne(\common\models\AuthItem::className(), ['name' => 'child']);
    }
    

}
