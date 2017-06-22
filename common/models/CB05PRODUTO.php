<?php

namespace common\models;

use Yii;
use common\models\base\CB05PRODUTO as BaseCB05PRODUTO;

/**
 * This is the model class for table "CB05_PRODUTO".
 */
class CB05PRODUTO extends BaseCB05PRODUTO
{
	public $ITEM;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB05_EMPRESA_ID', 'CB05_NOME_CURTO', 'CB05_TITULO'], 'required'],
            [['CB05_EMPRESA_ID', 'CB05_ATIVO'], 'integer'],
            [['CB05_DESCRICAO', 'CB05_IMPORTANTE'], 'string'],
            [['CB05_NOME_CURTO'], 'string', 'max' => 15],
            [['CB05_TITULO'], 'string', 'max' => 30],
            [['ITEM'], 'safe'],
            
            
        ]);
    }
    
  public static function getItem($id) {
        return explode(',', (($item = CB12ITEMCATEGEMPRESA::findBySql(
                        "SELECT GROUP_CONCAT(CB12_ITEM_ID) AS ITEM
                        FROM CB12_ITEM_CATEG_EMPRESA
                        WHERE CB12_PRODUTO_ID = " . $id . "
                        GROUP BY CB12_PRODUTO_ID")->one())) ? $item->ITEM : '');
    }

    public function getProdutoVariacao($empresa, $produto = null) {
        $retorno = [];
        $where = ['CB05_EMPRESA_ID' => $empresa];
        if ($produto) {
            $where['CB05_ID'] = $produto;
        }

        $modelProduto = $this->find()
                ->where($where)
                ->orderBy('CB05_NOME_CURTO')
                ->all();

        foreach ($modelProduto as $value) {
            $at = $value->getAttributes();

            $retorno[$at['CB05_ID']]['PRODUTO'] = $at;

            $modelVariacao = CB06VARIACAO::find()
                    ->where(['CB06_PRODUTO_ID' => $at['CB05_ID']])
                    ->orderBy('CB06_DESCRICAO')
                    ->all();

            if ($modelVariacao) {
                foreach ($modelVariacao as $v) {
                    $retorno[$at['CB05_ID']]['PRODUTO']['VARIACAO'][] = $v->getAttributes();
                }
            }
        }
        return $retorno;
    }

    public function saveProduto($data) {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            // dados do produto
            $this->setAttributes($data);
            $this->save();

            // dados dos itens do produto (exclui e cadastra)
            CB12ITEMCATEGEMPRESA::deleteAll(['CB12_PRODUTO_ID' => $this->CB05_ID]);
            foreach ($data['ITEM-PRODUTO'] as $item) {
                $CB12ITEMCATEGEMPRESA = new CB12ITEMCATEGEMPRESA();
                $CB12ITEMCATEGEMPRESA->setAttributes(['CB12_PRODUTO_ID' => $this->CB05_ID, 'CB12_ITEM_ID' => $item]);
                $CB12ITEMCATEGEMPRESA->save();
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
	
}
