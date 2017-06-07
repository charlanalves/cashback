<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "PAG02_ITEM_TRANSACAO".
 *
 * @property integer $PAG02_ID_ITEM_TRANSACAO
 * @property string $PAG02_ITEM_COD
 * @property string $PAG02_ITEM_DESC
 * @property integer $PAG02_ITEM_QTD
 * @property string $PAG02_ITEM_VLR
 * @property integer $PAG02_ITEM_STATUS
 * @property string $PAG02_ITEM_DT_INCLUSAO
 * @property integer $PAG02_ID_TRANSACAO
 */
class PAG02ITEMTRANSACAO extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PAG02_ITEM_COD', 'PAG02_ITEM_DESC', 'PAG02_ITEM_VLR', 'PAG02_ID_TRANSACAO'], 'required'],
            [['PAG02_ITEM_QTD', 'PAG02_ITEM_STATUS', 'PAG02_ID_TRANSACAO'], 'integer'],
            [['PAG02_ITEM_VLR'], 'number'],
            [['PAG02_ITEM_DT_INCLUSAO'], 'safe'],
            [['PAG02_ITEM_COD', 'PAG02_ITEM_DESC'], 'string', 'max' => 100],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'PAG02_ITEM_TRANSACAO';
    }

  
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PAG02_ID_ITEM_TRANSACAO' => 'Pag02  Id  Item  Transacao',
            'PAG02_ITEM_COD' => 'Pag02  Item  Cod',
            'PAG02_ITEM_DESC' => 'Pag02  Item  Desc',
            'PAG02_ITEM_QTD' => 'Pag02  Item  Qtd',
            'PAG02_ITEM_VLR' => 'Pag02  Item  Vlr',
            'PAG02_ITEM_STATUS' => 'Pag02  Item  Status',
            'PAG02_ITEM_DT_INCLUSAO' => 'Pag02  Item  Dt  Inclusao',
            'PAG02_ID_TRANSACAO' => 'Pag02  Id  Transacao',
        ];
    }


}
