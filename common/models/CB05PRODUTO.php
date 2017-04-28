<?php

namespace common\models;

use Yii;
use common\models\CB12ITEMCATEGEMPRESA;

/**
 * This is the model class for table "CB05_PRODUTO".
 *
 * @property integer $CB05_ID
 * @property integer $CB05_EMPRESA_ID
 * @property string $CB05_NOME_CURTO
 * @property string $CB05_TITULO
 * @property string $CB05_DESCRICAO
 * @property string $CB05_IMPORTANTE
 * @property integer CB05_ATIVO
 *
 * @property CB04EMPRESA $cB05EMPRESA
 * @property CB07CASHBACK[] $cB07CASHBACKs
 * @property CB12ITEMCATEGEMPRESA[] $cB12ITEMCATEGEMPRESAs
 * @property CB14FOTOPRODUTO[] $cB14FOTOPRODUTOs
 */
class CB05PRODUTO extends \common\models\GlobalModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'CB05_PRODUTO';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['CB05_EMPRESA_ID', 'CB05_NOME_CURTO', 'CB05_TITULO'], 'required'],
            [['CB05_EMPRESA_ID', 'CB05_ATIVO'], 'integer'],
            [['CB05_DESCRICAO', 'CB05_IMPORTANTE'], 'string'],
            [['CB05_NOME_CURTO'], 'string', 'max' => 15],
            [['CB05_TITULO'], 'string', 'max' => 30],
            [['CB05_EMPRESA_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB04EMPRESA::className(), 'targetAttribute' => ['CB05_EMPRESA_ID' => 'CB04_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'CB05_ID' => Yii::t('app', 'ID'),
            'CB05_EMPRESA_ID' => Yii::t('app', 'Empresa'),
            'CB05_TITULO' => Yii::t('app', 'Titulo'),
            'CB05_NOME_CURTO' => Yii::t('app', 'Nome Curto'),
            'CB05_DESCRICAO' => Yii::t('app', 'Descrição'),
            'CB05_IMPORTANTE' => Yii::t('app', 'Importante'),
            'CB05_ATIVO' => Yii::t('app', 'Ativo'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB05EMPRESA() {
        return $this->hasOne(CB04EMPRESA::className(), ['CB04_ID' => 'CB05_EMPRESA_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB07CASHBACKs() {
        return $this->hasMany(CB07CASHBACK::className(), ['CB07_PRODUTO_ID' => 'CB05_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB12ITEMCATEGEMPRESAs() {
        return $this->hasMany(CB12ITEMCATEGEMPRESA::className(), ['CB12_PRODUTO_ID' => 'CB05_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB14FOTOPRODUTOs() {
        return $this->hasMany(CB14FOTOPRODUTO::className(), ['CB14_PRODUTO_ID' => 'CB05_ID']);
    }

    /**
     * @inheritdoc
     * @return CB05PRODUTOQuery the active query used by this AR class.
     */
    public static function find() {
        return new CB05PRODUTOQuery(get_called_class());
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
