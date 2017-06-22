<?php

namespace common\models;

use Yii;
use common\models\base\CB06VARIACAO as BaseCB06VARIACAO;

/**
 * This is the model class for table "CB06_VARIACAO".
 */
class CB06VARIACAO extends BaseCB06VARIACAO
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB06_PRODUTO_ID', 'CB06_DESCRICAO', 'CB06_PRECO', 'CB06_PRECO_PROMOCIONAL', 'CB06_DINHEIRO_VOLTA'], 'required'],
            [['CB06_PRODUTO_ID'], 'integer'],
            [['CB06_PRECO', 'CB06_PRECO_PROMOCIONAL', 'CB06_DINHEIRO_VOLTA'], 'number'],
            [['CB06_TITULO'], 'string', 'max' => 500],
            [['CB06_DESCRICAO'], 'string', 'max' => 30],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB06_ID' => Yii::t('app', 'ID'),
            'CB06_PRODUTO_ID' => Yii::t('app', 'Produto'),
            'CB06_DESCRICAO' => Yii::t('app', 'Descri��o'),
            'CB06_PRECO' => Yii::t('app', 'Pre�o original'),
            'CB06_PRECO_PROMOCIONAL' => Yii::t('app', 'Pre�o promocional'),
            'CB06_DINHEIRO_VOLTA' => Yii::t('app', 'Dinheiro de volta'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB07CASHBACKs()
    {
        return $this->hasMany(CB07CASHBACK::className(), ['CB07_VARIACAO_ID' => 'CB06_ID']);
    }

    /**
     * @inheritdoc
     * @return CB06VARIACAOQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB06VARIACAOQuery(get_called_class());
    }

    /**
     * @inheritdoc
     * @return
     */
    public static function getPromocao($url, $filter)
    {
        // filter categoria e ordenacao
        $filterBind = (empty($filter['cat']) || empty($filter['ord'])) ? false : true;
        
       /* $sql = "
            SELECT 
                MAX(CB06_VARIACAO.CB06_DINHEIRO_VOLTA) AS CB06_DINHEIRO_VOLTA,
                CB04_EMPRESA.CB04_ID,
                CB04_EMPRESA.CB04_NOME,
                CB04_EMPRESA.CB04_END_COMPLEMENTO,
                --concat('" . $url . "', CB04_EMPRESA.CB04_URL_LOGOMARCA) AS CB04_URL_LOGOMARCA,
                --concat('" . $url . "', CB14_FOTO_PRODUTO.CB14_URL) AS CB14_URL,
                CB04_EMPRESA.CB04_URL_LOGOMARCA AS CB04_URL_LOGOMARCA,
                CB14_FOTO_PRODUTO.CB14_URL AS CB14_URL,
                CB05_PRODUTO.CB05_ID,
                CB05_PRODUTO.CB05_TITULO,
                CB06_VARIACAO.CB06_DESCRICAO
            FROM CB06_VARIACAO
            INNER JOIN CB05_PRODUTO ON(CB05_PRODUTO.CB05_ID = CB06_VARIACAO.CB06_PRODUTO_ID AND CB05_PRODUTO.CB05_ATIVO = 1)
            INNER JOIN CB14_FOTO_PRODUTO ON(CB14_FOTO_PRODUTO.CB14_PRODUTO_ID = CB05_PRODUTO.CB05_ID AND CB14_FOTO_PRODUTO.CB14_CAPA = 1)
            INNER JOIN CB04_EMPRESA ON(CB04_EMPRESA.CB04_ID = CB05_PRODUTO.CB05_EMPRESA_ID AND CB04_EMPRESA.CB04_STATUS = 1)
            " . (!$filterBind ? "" : "WHERE CB04_CATEGORIA_ID = :categoria") . "
            GROUP BY CB04_EMPRESA.CB04_NOME,CB04_EMPRESA.CB04_ID
            ORDER BY " . (!$filterBind ? 'CB06_DINHEIRO_VOLTA DESC' : ":ordem");
        */
        
        
      $sql = " 
       		SELECT CB04_EMPRESA.CB04_ID, CB04_EMPRESA.CB04_NOME, CB04_EMPRESA.CB04_END_COMPLEMENTO, - - CONCAT(  '" . $url . "', CB04_EMPRESA.CB04_URL_LOGOMARCA ) AS CB04_URL_LOGOMARCA, - - CONCAT(  '" . $url . "', CB14_FOTO_PRODUTO.CB14_URL ) AS CB14_URL, CB04_EMPRESA.CB04_URL_LOGOMARCA AS CB04_URL_LOGOMARCA, CB14_FOTO_PRODUTO.CB14_URL AS CB14_URL, CB05_PRODUTO.CB05_ID, CB05_PRODUTO.CB05_TITULO, CB06_VARIACAO.CB06_DESCRICAO
			FROM CB06_VARIACAO
			INNER JOIN CB05_PRODUTO ON ( CB05_PRODUTO.CB05_ID = CB06_VARIACAO.CB06_PRODUTO_ID ) 
			INNER JOIN CB14_FOTO_PRODUTO ON ( CB14_FOTO_PRODUTO.CB14_PRODUTO_ID = CB05_PRODUTO.CB05_ID ) 
			INNER JOIN CB04_EMPRESA ON ( CB04_EMPRESA.CB04_ID = CB05_PRODUTO.CB05_EMPRESA_ID ) 
			GROUP BY CB06_DESCRICAO";
        $command = \Yii::$app->db->createCommand($sql);
        if ($filterBind) {
            $command->bindValue(':categoria', $filter['cat']);
            $command->bindValue(':ordem', \Yii::$app->u->filterOrder($filter['ord']));
        }
        return $command->queryAll();
    }
	
}
