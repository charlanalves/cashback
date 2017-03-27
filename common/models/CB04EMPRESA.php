<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB04_EMPRESA".
 *
 * @property integer $CB04_ID
 * @property string $CB04_NOME
 * @property integer $CB04_CATEGORIA_ID
 * @property string $CB04_FUNCIONAMENTO
 * @property string $CB04_OBSERVACAO
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
 *
 * @property CB01TRANSACAO[] $cB01TRANSACAOs
 * @property CB10CATEGORIA $cB04CATEGORIA
 * @property CB05PRODUTO[] $cB05PRODUTOs
 * @property CB09FORMAPAGEMPRESA[] $cB09FORMAPAGEMPRESAs
 * @property CB08FORMAPAGAMENTO[] $cB09FORMAPAGs
 * @property CB12ITEMCATEGEMPRESA[] $cB12ITEMCATEGEMPRESAs
 * @property CB13FOTOEMPRESA[] $cB13FOTOEMPRESAs
 */
class CB04EMPRESA extends \common\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB04_EMPRESA';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB04_NOME', 'CB04_CATEGORIA_ID', 'CB04_FUNCIONAMENTO', 'CB04_OBSERVACAO', 'CB04_END_LOGRADOURO', 'CB04_END_BAIRRO', 'CB04_END_CIDADE', 'CB04_END_UF', 'CB04_END_NUMERO', 'CB04_END_COMPLEMENTO', 'CB04_END_CEP'], 'required'],
            [['CB04_CATEGORIA_ID', 'CB04_STATUS', 'CB04_QTD_FAVORITO', 'CB04_QTD_COMPARTILHADO'], 'integer'],
            [['CB04_FUNCIONAMENTO', 'CB04_OBSERVACAO'], 'string'],
            [['CB04_NOME', 'CB04_END_LOGRADOURO', 'CB04_END_BAIRRO', 'CB04_END_CIDADE', 'CB04_END_COMPLEMENTO'], 'string', 'max' => 50],
            [['CB04_END_UF'], 'string', 'max' => 2],
            [['CB04_END_NUMERO'], 'string', 'max' => 5],
            [['CB04_END_CEP'], 'string', 'max' => 8],
            [['CB04_CATEGORIA_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB10CATEGORIA::className(), 'targetAttribute' => ['CB04_CATEGORIA_ID' => 'CB10_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB04_ID' => Yii::t('app', 'Cb04  ID'),
            'CB04_NOME' => Yii::t('app', 'Cb04  Nome'),
            'CB04_CATEGORIA_ID' => Yii::t('app', 'Cb04  Categoria  ID'),
            'CB04_FUNCIONAMENTO' => Yii::t('app', 'Cb04  Funcionamento'),
            'CB04_OBSERVACAO' => Yii::t('app', 'Cb04  Observacao'),
            'CB04_STATUS' => Yii::t('app', 'Cb04  Status'),
            'CB04_QTD_FAVORITO' => Yii::t('app', 'Cb04  Qtd  Favorito'),
            'CB04_QTD_COMPARTILHADO' => Yii::t('app', 'Cb04  Qtd  Compartilhado'),
            'CB04_END_LOGRADOURO' => Yii::t('app', 'Cb04  End  Logradouro'),
            'CB04_END_BAIRRO' => Yii::t('app', 'Cb04  End  Bairro'),
            'CB04_END_CIDADE' => Yii::t('app', 'Cb04  End  Cidade'),
            'CB04_END_UF' => Yii::t('app', 'Cb04  End  Uf'),
            'CB04_END_NUMERO' => Yii::t('app', 'Cb04  End  Numero'),
            'CB04_END_COMPLEMENTO' => Yii::t('app', 'Cb04  End  Complemento'),
            'CB04_END_CEP' => Yii::t('app', 'Cb04  End  Cep'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB01TRANSACAOs()
    {
        return $this->hasMany(CB01TRANSACAO::className(), ['CB01_EMPRESA_ID' => 'CB04_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB04CATEGORIA()
    {
        return $this->hasOne(CB10CATEGORIA::className(), ['CB10_ID' => 'CB04_CATEGORIA_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB05PRODUTOs()
    {
        return $this->hasMany(CB05PRODUTO::className(), ['CB05_EMPRESA_ID' => 'CB04_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB09FORMAPAGEMPRESAs()
    {
        return $this->hasMany(CB09FORMAPAGEMPRESA::className(), ['CB09_EMPRESA_ID' => 'CB04_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB09FORMAPAGs()
    {
        return $this->hasMany(CB08FORMAPAGAMENTO::className(), ['CB08_ID' => 'CB09_FORMA_PAG_ID'])->viaTable('CB09_FORMA_PAG_EMPRESA', ['CB09_EMPRESA_ID' => 'CB04_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB12ITEMCATEGEMPRESAs()
    {
        return $this->hasMany(CB12ITEMCATEGEMPRESA::className(), ['CB12_EMPRESA_ID' => 'CB04_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB13FOTOEMPRESAs()
    {
        return $this->hasMany(CB13FOTOEMPRESA::className(), ['CB13_EMPRESA_ID' => 'CB04_ID']);
    }

    /**
     * @inheritdoc
     * @return CB04EMPRESAQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB04EMPRESAQuery(get_called_class());
    }
    
    

    /**
     * @inheritdoc
     */
    public static function getEmpresas($a = [])
    {
        $categoria = $item = '';
        
        if (!empty($a['categoria'])) {
            $categoria = " AND CB04_CATEGORIA_ID = " . (int)$a['categoria'];

            if (!empty($a['item'])) {
                $item = " AND CB12_ITEM_ID IN (" . implode(',', $a['item']) . ")";
            }
        }
        
        $sql = "
        SELECT 
            CB04_ID,
            CB04_NOME,
            CB04_QTD_FAVORITO,
            CB04_QTD_COMPARTILHADO,
            CB04_END_LOGRADOURO,
            CB04_END_BAIRRO,
            CB04_END_CIDADE,
            CB04_END_UF,
            CB04_END_NUMERO,
            CB04_NOME,
            FORMAT(MAX(CB07_PERCENTUAL), 0, 'de_DE') AS CASHBACK
        FROM CB04_EMPRESA
            LEFT JOIN CB05_PRODUTO ON(CB05_EMPRESA_ID = CB04_ID)
            LEFT JOIN CB06_VARIACAO ON(CB06_PRODUTO_ID = CB05_ID)
            LEFT JOIN CB10_CATEGORIA ON(CB10_ID = CB04_CATEGORIA_ID)
            LEFT JOIN CB12_ITEM_CATEG_EMPRESA ON(CB12_EMPRESA_ID = CB04_ID OR CB05_ID = CB12_PRODUTO_ID)
            LEFT JOIN CB07_CASH_BACK ON(CB07_PRODUTO_ID = CB05_ID  OR CB07_VARIACAO_ID = CB06_ID)
        WHERE CB04_EMPRESA.CB04_STATUS = 1 $categoria $item
            GROUP BY CB04_ID, CB04_NOME, CB04_QTD_FAVORITO, CB04_QTD_COMPARTILHADO, CB04_END_LOGRADOURO, 
            CB04_END_BAIRRO, CB04_END_CIDADE, CB04_END_UF, CB04_END_NUMERO, CB04_NOME";
        
        $command = \Yii::$app->db->createCommand($sql);
        return $command->queryAll();
    }
    
    
    
    
    
}
