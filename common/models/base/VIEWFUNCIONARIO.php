<?php

namespace common\models\base;

use Yii;

/**
 * This is the base model class for table "VIEW_FUNCIONARIO".
 *
 * @property integer $CB04_ID
 * @property string $CB04_DADOS_API_TOKEN
 * @property string $CB04_NOME
 * @property integer $CB04_CATEGORIA_ID
 * @property string $CB04_FUNCIONAMENTO
 * @property string $CB04_OBSERVACAO
 * @property string $CB04_URL_LOGOMARCA
 * @property integer $CB04_STATUS
 * @property integer $CB04_QTD_FAVORITO
 * @property integer $CB04_QTD_COMPARTILHADO
 * @property string $CB04_END_LOGRADOURO
 * @property string $CB04_END_BAIRRO
 * @property string $CB04_END_CIDADE
 * @property string $CB04_END_UF
 * @property string $CB04_END_NUMERO
 * @property string $CB04_END_COMPLEMENTO
 * @property string $CB04_END_CEP
 * @property string $CB04_END_LATITUDE
 * @property string $CB04_END_LONGITUDE
 * @property integer $CB04_TIPO
 *
 * @property common\models\CB01TRANSACAO[] $cB01TRANSACAOs
 * @property common\models\CB10CATEGORIA $cB04CATEGORIA
 * @property common\models\CB05PRODUTO[] $cB05PRODUTOs
 * @property common\models\CB09FORMAPAGTOEMPRESA[] $cB09FORMAPAGTOEMPRESAs
 * @property common\models\CB12ITEMCATEGEMPRESA[] $cB12ITEMCATEGEMPRESAs
 * @property common\models\CB13FOTOEMPRESA[] $cB13FOTOEMPRESAs
 * @property common\models\CB15LIKEEMPRESA[] $cB15LIKEEMPRESAs
 * @property common\models\User[] $cB15USERs
 * @property common\models\User[] $users
 */
class VIEWFUNCIONARIO extends \common\models\GlobalModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB04_CNPJ', 'CB04_TEL_NUMERO', 'CB04_NOME', 'CB04_END_LOGRADOURO', 'CB04_END_BAIRRO', 'CB04_END_CIDADE', 'CB04_END_UF', 'CB04_END_NUMERO', 'CB04_END_CEP', 'CB04_EMAIL'], 'required'],
            [['CB04_DADOS_API_TOKEN', 'CB04_FUNCIONAMENTO', 'CB04_OBSERVACAO', 'CB04_COD_CONTA_VIRTUAL'], 'string'],
            [['CB04_CATEGORIA_ID', 'CB04_STATUS', 'CB04_QTD_FAVORITO', 'CB04_QTD_COMPARTILHADO', 'CB04_TIPO'], 'integer'],
            [['CB04_NOME', 'CB04_END_LOGRADOURO', 'CB04_END_BAIRRO', 'CB04_END_CIDADE', 'CB04_END_COMPLEMENTO'], 'string', 'max' => 50],
            [['CB04_URL_LOGOMARCA'], 'string', 'max' => 100],
            [['CB04_END_UF'], 'string', 'max' => 2],
            [['CB04_FLG_DELIVERY'], 'integer', 'min' => 0, 'max' => 1],
            [['CB04_END_NUMERO'], 'string', 'max' => 5],
            [['CB04_END_LONGITUDE', 'CB04_END_LATITUDE'], 'string', 'max' => 20],
            [['CB04_CNPJ'], 'string', 'max' => 11], // salva o CPF do funcionario
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'VIEW_FUNCIONARIO';
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['CB04_ID'];
    }

    /**
     * @inheritdoc
     */
    public  function attributeLabels()
    {

        return [
            'CB04_ID' => 'COD',
            'CB04_DADOS_API_TOKEN' => 'Cb04  Dados  Api  Token',
            'CB04_NOME' => 'Nome',
            'CB04_CATEGORIA_ID' => 'Categoria',
            'CB04_FUNCIONAMENTO' => 'Horários de Funcionamento',
            'CB04_OBSERVACAO' => 'Observação',
            'CB04_URL_LOGOMARCA' => 'Logotipo',
            'CB04_STATUS' => 'Cb04  Status',
            'CB04_QTD_FAVORITO' => 'Cb04  Qtd  Favorito',
            'CB04_QTD_COMPARTILHADO' => 'Cb04  Qtd  Compartilhado',
            'CB04_END_LOGRADOURO' => 'Logradouro',
            'CB04_END_BAIRRO' => 'Bairro',
            'CB04_END_CIDADE' => 'Cidade',
            'CB04_END_UF' => 'UF',
            'CB04_END_NUMERO' => 'Número',
            'CB04_END_COMPLEMENTO' => 'Complemento',
            'CB04_END_CEP' => 'CEP',
            'CB04_CNPJ' => 'CPF',
            'CB04_TEL_NUMERO' => 'Telefone (Com DDD)',
            'CB03_NOME_BANCO' => 'Banco',
            'CB03_AGENCIA' => 'Agência',
            'CB03_NUM_CONTA' => 'Nº Conta',
            'CB03_TP_CONTA' => 'Tipo Conta',
            'CB03_SAQUE_MIN' => 'Saque Mínimo',
            'CB03_SAQUE_MAX' => 'Saque Máximo',
            'CB04_EMAIL' => 'Email',
            'CB04_END_LONGITUDE' => 'Longitude',
            'CB04_END_LATITUDE' => 'Latitude',
            'CB04_FLG_DELIVERY' => 'Delivery',
            'CB04_TIPO' => 'Tipo',
        ];
    }

}
