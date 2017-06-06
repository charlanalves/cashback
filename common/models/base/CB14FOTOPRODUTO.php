<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB14_FOTO_PRODUTO".
 *
 * @property integer $CB14_ID
 * @property integer $CB14_PRODUTO_ID
 * @property integer $CB14_CAPA
 * @property string $CB14_URL
 *
 * @property common\models\CB05PRODUTO $cB14PRODUTO
 */
class CB14FOTOPRODUTO extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB14_PRODUTO_ID', 'CB14_CAPA', 'CB14_URL'], 'required'],
            [['CB14_PRODUTO_ID', 'CB14_CAPA'], 'integer'],
            [['CB14_URL'], 'string', 'max' => 50],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB14_FOTO_PRODUTO';
    }

   

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB14_ID' => 'Cb14  ID',
            'CB14_PRODUTO_ID' => 'Cb14  Produto  ID',
            'CB14_CAPA' => 'Cb14  Capa',
            'CB14_URL' => 'Cb14  Url',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB14PRODUTO()
    {
        return $this->hasOne(\common\models\CB05PRODUTO::className(), ['CB05_ID' => 'CB14_PRODUTO_ID']);
    }
    

}
