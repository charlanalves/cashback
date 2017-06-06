<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "CB08_FORMA_PAGAMENTO".
 *
 * @property integer $CB08_ID
 * @property string $CB08_NOME
 * @property string $CB08_URL_IMG
 * @property integer $CB08_STATUS
 *
 * @property common\models\CB09FORMAPAGTOEMPRESA[] $cB09FORMAPAGTOEMPRESAs
 */
class CB08FORMAPAGAMENTO extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB08_NOME', 'CB08_URL_IMG'], 'required'],
            [['CB08_STATUS'], 'integer'],
            [['CB08_NOME'], 'string', 'max' => 20],
            [['CB08_URL_IMG'], 'string', 'max' => 50],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB08_FORMA_PAGAMENTO';
    }

  

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB08_ID' => 'Cb08  ID',
            'CB08_NOME' => 'Cb08  Nome',
            'CB08_URL_IMG' => 'Cb08  Url  Img',
            'CB08_STATUS' => 'Cb08  Status',
        ];
    }
    
  /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB08IDADQ()
    {
        return $this->hasOne(\app\models\PAG03ADQUIRENTES::className(), ['PAG03_ID' => 'CB08_ID_ADQ']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB09FORMAPAGTOEMPRESAs()
    {
        return $this->hasMany(\common\models\CB09FORMAPAGTOEMPRESA::className(), ['CB09_ID_FORMA_PAG' => 'CB08_ID']);
    }
    

}
