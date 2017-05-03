<?php

namespace common\modules\pagamento\models\base;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use \common\models\GlobalModel;

/**
 * This is the base model class for table "pag01_transacao".
 *
 * @property integer $ID_TRANSACAO
 * @property string $COD_TRANSACAO
 * @property string $GATEWAY
 * @property string $FORMA_PAG
 * @property string $HASH_RECEBEDOR_PRIMARIO
 * @property string $HASH_RECEBEDOR_SECUNDARIO
 * @property string $VALOR_TOTAL
 * @property string $COMPRADOR_NOME
 * @property string $COMPRADOR_DATA_NASCIMENTO
 * @property string $COMPRADOR_EMAIL
 * @property string $COMPRADOR_CPF
 * @property integer $COMPRADOR_TEL_DDD
 * @property integer $COMPRADOR_TEL_NUMERO
 * @property string $ENDERECO_LOGRADOURO
 * @property string $ENDERECO_NUMERO
 * @property string $ENDERECO_BAIRRO
 * @property string $ENDERECO_CEP
 * @property string $ENDERECO_CIDADE
 * @property string $ENDERECO_UF
 * @property string $ENDERECO_PAIS
 * @property string $ENDERECO_COMPLEMENTO
 * @property string $CARTAO_TOKEN
 * @property string $CARTAO_NOME
 * @property integer $CARTAO_NUM_PARCELA
 * @property string $CARTAO_VLR_PARCELA
 * @property integer $TRANSACAO_STATUS
 * @property string $TRANSACAO_DT_CADASTRO
 * @property string $TOKEN_GATEWAY
 */
class Pag01Transacao extends GlobalModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['COD_TRANSACAO', 'GATEWAY', 'FORMA_PAG', 'HASH_RECEBEDOR_PRIMARIO', 'HASH_RECEBEDOR_SECUNDARIO', 'VALOR_TOTAL', 'COMPRADOR_NOME', 'COMPRADOR_DATA_NASCIMENTO', 'COMPRADOR_EMAIL', 'COMPRADOR_CPF', 'COMPRADOR_TEL_DDD', 'COMPRADOR_TEL_NUMERO', 'ENDERECO_LOGRADOURO', 'ENDERECO_BAIRRO', 'ENDERECO_CEP', 'ENDERECO_CIDADE', 'ENDERECO_UF', 'ENDERECO_COMPLEMENTO'], 'required'],
            [['VALOR_TOTAL', 'CARTAO_VLR_PARCELA'], 'number'],
            [['COMPRADOR_DATA_NASCIMENTO'], 'safe'],
//            [['TRANSACAO_DT_CADASTRO'], 'safe'],
            [['COMPRADOR_TEL_DDD', 'COMPRADOR_TEL_NUMERO', 'CARTAO_NUM_PARCELA', 'TRANSACAO_STATUS'], 'integer'],
            [['COD_TRANSACAO', 'GATEWAY', 'FORMA_PAG', 'HASH_RECEBEDOR_PRIMARIO', 'HASH_RECEBEDOR_SECUNDARIO', 'COMPRADOR_NOME', 'COMPRADOR_EMAIL', 'ENDERECO_LOGRADOURO', 'ENDERECO_BAIRRO', 'ENDERECO_CIDADE', 'ENDERECO_COMPLEMENTO', 'CARTAO_NOME'], 'string', 'max' => 100],
            [['COMPRADOR_CPF'], 'string', 'max' => 14],
            [['ENDERECO_NUMERO'], 'string', 'max' => 5],
            [['ENDERECO_CEP'], 'string', 'max' => 9],
            [['ENDERECO_UF'], 'string', 'max' => 2],
            [['ENDERECO_PAIS'], 'string', 'max' => 3],
            [['CARTAO_TOKEN'], 'string', 'max' => 32],
            [['TOKEN_GATEWAY'], 'string', 'max' => 250],
//            [['lock'], 'default', 'value' => '0'],
//            [['lock'], 'mootensai\components\OptimisticLockValidator']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'PAG01_TRANSACAO';
    }

    /**
     * 
     * @return string
     * overwrite function optimisticLock
     * return string name of field are used to stored optimistic lock 
     * 
     */
    public function optimisticLock() {
        return 'lock';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID_TRANSACAO' => 'Id  Transacao',
            'COD_TRANSACAO' => 'Cod  Transacao',
            'GATEWAY' => 'Gateway',
            'FORMA_PAG' => 'Forma  Pag',
            'HASH_RECEBEDOR_PRIMARIO' => 'Hash  Recebedor  Primario',
            'HASH_RECEBEDOR_SECUNDARIO' => 'Hash  Recebedor  Secundario',
            'VALOR_TOTAL' => 'Valor  Total',
            'COMPRADOR_NOME' => 'Comprador  Nome',
            'COMPRADOR_DATA_NASCIMENTO' => 'Comprador  Data  Nascimento',
            'COMPRADOR_EMAIL' => 'Comprador  Email',
            'COMPRADOR_CPF' => 'Comprador  Cpf',
            'COMPRADOR_TEL_DDD' => 'Comprador  Tel  Ddd',
            'COMPRADOR_TEL_NUMERO' => 'Comprador  Tel  Numero',
            'ENDERECO_LOGRADOURO' => 'Endereco  Logradouro',
            'ENDERECO_NUMERO' => 'Endereco  Numero',
            'ENDERECO_BAIRRO' => 'Endereco  Bairro',
            'ENDERECO_CEP' => 'Endereco  Cep',
            'ENDERECO_CIDADE' => 'Endereco  Cidade',
            'ENDERECO_UF' => 'Endereco  Uf',
            'ENDERECO_PAIS' => 'Endereco  Pais',
            'ENDERECO_COMPLEMENTO' => 'Endereco  Complemento',
            'CARTAO_TOKEN' => 'Cartao  Token',
            'CARTAO_NOME' => 'Cartao  Nome',
            'CARTAO_NUM_PARCELA' => 'Cartao  Num  Parcela',
            'CARTAO_VLR_PARCELA' => 'Cartao  Vlr  Parcela',
            'TRANSACAO_STATUS' => 'Transacao  Status',
            'TRANSACAO_DT_CADASTRO' => 'Transacao  Dt  Cadastro',
            'TOKEN_GATEWAY' => 'Token Gateway'
        ];
    }

/**
     * @inheritdoc
     * @return array mixed
     */ 
//    public function behaviors()
//    {
//        return [
//            'timestamp' => [
//                'class' => TimestampBehavior::className(),
//                'createdAtAttribute' => 'TRANSACAO_DT_CADASTRO',
//                'updatedAtAttribute' => 'TRANSACAO_DT_CADASTRO',
//                'value' => new \yii\db\Expression('NOW()'),
//            ],
//            'blameable' => [
//                'class' => BlameableBehavior::className(),
//                'createdByAttribute' => 'created_by',
//                'updatedByAttribute' => 'updated_by',
//            ],
//            'uuid' => [
//                'class' => UUIDBehavior::className(),
//                'column' => 'id',
//            ],
//        ];
//    }

    /**
     * @inheritdoc
     * @return \app\models\Pag01TransacaoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\Pag01TransacaoQuery(get_called_class());
    }
}
