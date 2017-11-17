<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB07_CASH_BACK".
 *
 * @property integer $CB07_ID
 * @property integer $CB07_PRODUTO_ID
 * @property integer $CB07_VARIACAO_ID
 * @property integer $CB07_DIA_SEMANA
 * @property string $CB07_PERCENTUAL
 *
 * @property common\models\CB05PRODUTO $cB07PRODUTO
 * @property common\models\CB06VARIACAO $cB07VARIACAO
 */
class CB07CASHBACK extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB07_PRODUTO_ID', 'CB07_VARIACAO_ID', 'CB07_DIA_SEMANA'], 'integer'],
            [['CB07_DIA_SEMANA', 'CB07_PERCENTUAL'], 'required'],
            [['CB07_PERCENTUAL'], 'number'],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB07_CASH_BACK';
    }

 

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB07_ID' => 'Cb07  ID',
            'CB07_PRODUTO_ID' => 'Cb07  Produto  ID',
            'CB07_VARIACAO_ID' => 'Cb07  Variacao  ID',
            'CB07_DIA_SEMANA' => 'Cb07  Dia  Semana',
            'CB07_PERCENTUAL' => 'Percentual',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB07PRODUTO()
    {
        return $this->hasOne(\common\models\CB05PRODUTO::className(), ['CB05_ID' => 'CB07_PRODUTO_ID']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB07VARIACAO()
    {
        return $this->hasOne(\common\models\CB06VARIACAO::className(), ['CB06_ID' => 'CB07_VARIACAO_ID']);
    }
    

}
