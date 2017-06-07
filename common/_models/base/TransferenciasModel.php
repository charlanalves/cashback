<?php

namespace common\models\base;


use \Yii;

/*** 
* Essa classe será útil quando eventualmente acontecer uma alteração na tabela 
* da mesma, pois será possivel atualizar as rules e relations através 
* do gerador de CRUD.  
*
* NOTA:
* Gentileza não alterar as funções dessa classe, 
* pois, após regera-la pelo gerador de CRUD todos os métodos inseridos 
* manualmente serão substituidos pelas funções padrões
*
* Para modificações, sobrescreva o método desejado no modelo específico.
* 
*
 * @property integer $PAG04_ID
 * @property integer $PAG04_ID_TRANSACAO
 * @property string $PAG04_COD_TRANS_ADQ
 * @property string $PAG04_VLR_TRANS
 * @property string $PAG04_VLR_TRANS_LIQ
 * @property string $PAG04_VLR_EMPRESA
 * @property string $PAG04_VLR_CLIENTE
 * @property string $PAG04_VLR_ADMIN
 * @property string $PAG04_DT_PREV_DEP_CONTA_BANC_MASTER
 * @property string $PAG04_DT_DEP_CONTA_BANC_MASTER
 * @property string $PAG04_DT_PREV_DEP_CONTA_VIRTUAL_MASTER
 * @property string $PAG04_DT_DEP_CONTA_VIRTUAL_MASTER
 * @property string $PAG04_DT_PREV_DEP_SUBCONTA_VIRTUAL
 * @property string $PAG04_DT_DEP_SUBCONTA_VIRTUAL
 */
class TransferenciasModel extends \common\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PAG04_ID_TRANSACAO', 'PAG04_COD_TRANS_ADQ', 'PAG04_VLR_TRANS', 'PAG04_VLR_TRANS_LIQ', 'PAG04_VLR_EMPRESA', 'PAG04_VLR_CLIENTE', 'PAG04_VLR_ADMIN'], 'required'],
            [['PAG04_ID_TRANSACAO'], 'integer'],
            [['PAG04_VLR_TRANS', 'PAG04_VLR_TRANS_LIQ', 'PAG04_VLR_EMPRESA', 'PAG04_VLR_CLIENTE', 'PAG04_VLR_ADMIN'], 'number'],
            [['PAG04_DT_PREV_DEP_CONTA_BANC_MASTER', 'PAG04_DT_DEP_CONTA_BANC_MASTER', 'PAG04_DT_PREV_DEP_CONTA_VIRTUAL_MASTER', 'PAG04_DT_DEP_CONTA_VIRTUAL_MASTER', 'PAG04_DT_PREV_DEP_SUBCONTA_VIRTUAL', 'PAG04_DT_DEP_SUBCONTA_VIRTUAL'], 'safe'],
            [['PAG04_COD_TRANS_ADQ'], 'string', 'max' => 500]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'PAG04_TRANSFERENCIAS';
    }
    
 	public static function colFlagAtivo()
    {
                return 'PAG04_FLG_ATIVO';
    }
    

    
  

}
