<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "PAG03_ADQUIRENTES".
 *
 * @property integer $PAG03_ID
 * @property string $PAG03_NOME
 * @property string $PAG03_PERC_DEBTO_ECO
 * @property string $PAG03_PERC_DEBTO_POS
 * @property string $PAG03_PERC_CREDITO_ECO
 * @property string $PAG03_PERC_CREDITO_POS
 * @property string $PAG03_VLR_ANTI_FRAUDE
 * @property string $PAG03_OUTROS_VLR
 */
class PAG03ADQUIRENTES extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PAG03_ID', 'PAG03_NOME', 'PAG03_PERC_DEBTO_ECO', 'PAG03_PERC_DEBTO_POS', 'PAG03_PERC_CREDITO_ECO', 'PAG03_PERC_CREDITO_POS', 'PAG03_VLR_ANTI_FRAUDE', 'PAG03_OUTROS_VLR'], 'required'],
            [['PAG03_ID'], 'integer'],
            [['PAG03_PERC_DEBTO_ECO', 'PAG03_PERC_DEBTO_POS', 'PAG03_PERC_CREDITO_ECO', 'PAG03_PERC_CREDITO_POS', 'PAG03_VLR_ANTI_FRAUDE', 'PAG03_OUTROS_VLR'], 'number'],
            [['PAG03_NOME'], 'string', 'max' => 50],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'PAG03_ADQUIRENTES';
    }

   /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB08FORMAPAGAMENTOs()
    {
        return $this->hasMany(common\models\CB08FORMAPAGAMENTO::className(), ['CB08_ID_ADQ' => 'PAG03_ID']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PAG03_ID' => 'Pag03  ID',
            'PAG03_NOME' => 'Pag03  Nome',
            'PAG03_PERC_DEBTO_ECO' => 'Pag03  Perc  Debto  Eco',
            'PAG03_PERC_DEBTO_POS' => 'Pag03  Perc  Debto  Pos',
            'PAG03_PERC_CREDITO_ECO' => 'Pag03  Perc  Credito  Eco',
            'PAG03_PERC_CREDITO_POS' => 'Pag03  Perc  Credito  Pos',
            'PAG03_VLR_ANTI_FRAUDE' => 'Pag03  Vlr  Anti  Fraude',
            'PAG03_OUTROS_VLR' => 'Pag03  Outros  Vlr',
        ];
    }


}
