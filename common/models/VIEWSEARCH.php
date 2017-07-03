<?php

namespace common\models;

use Yii;
use common\models\base\VIEWSEARCH as BaseVIEWSEARCH;

/**
 * This is the model class for table "VIEW_SEARCH".
 */
class VIEWSEARCH extends BaseVIEWSEARCH
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['EMPRESA_ID'], 'integer'],
            [['CATEGORIA_ID'], 'integer'],
            [['CATEGORIA_NOME'], 'string'],
            [['EMPRESA_NOME', 'BUSCA_TEXTO'], 'string', 'max' => 50],
            [['TIPO'], 'string', 'max' => 7],
            [['IMG'], 'string', 'max' => 100],
        ]);
    }
    
    
    public static function getPromotionsByCategory($idCategory)
    {
        $sql = "SELECT CB06_PRECO_PROMOCIONAL, CB06_DINHEIRO_VOLTA, CB06_PRECO, CB06_DESCRICAO, CB06_PRODUTO_ID, CB04_ID, CB04_NOME, CB14_URL
                FROM CB06_VARIACAO
                INNER JOIN CB05_PRODUTO ON(CB05_ATIVO = 1 AND CB05_ID = CB06_PRODUTO_ID)
                INNER JOIN CB04_EMPRESA ON(CB04_STATUS = 1 AND CB04_ID = CB05_EMPRESA_ID)
                LEFT JOIN CB14_FOTO_PRODUTO ON(	CB14_PRODUTO_ID = CB05_ID AND CB14_CAPA = 1)
                WHERE CB04_CATEGORIA_ID = :idCategory
                ORDER BY CB06_DINHEIRO_VOLTA DESC, CB04_NOME";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':idCategory', $idCategory);
        return $command->query()->readAll();
    }
    
}
