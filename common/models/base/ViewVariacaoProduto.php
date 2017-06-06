<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "view_variacao_produto".
 *
 * @property integer $CB06_ID
 * @property integer $CB05_ID
 * @property string $CB06_TITULO
 * @property string $CB06_DESCRICAO
 * @property string $CB06_PRECO
 * @property string $CB07_PERCENTUAL
 * @property string $VALOR_CB
 */
class ViewVariacaoProduto extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB06_ID', 'CB05_ID'], 'integer'],
            [['CB06_TITULO', 'CB06_DESCRICAO', 'CB06_PRECO'], 'required'],
            [['CB06_PRECO', 'CB07_PERCENTUAL', 'VALOR_CB'], 'number'],
            [['CB06_TITULO'], 'string', 'max' => 500],
            [['CB06_DESCRICAO'], 'string', 'max' => 30],
            
            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'view_variacao_produto';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB06_ID' => 'Cb06  ID',
            'CB05_ID' => 'Cb05  ID',
            'CB06_TITULO' => 'Cb06  Titulo',
            'CB06_DESCRICAO' => 'Cb06  Descricao',
            'CB06_PRECO' => 'Cb06  Preco',
            'CB07_PERCENTUAL' => 'Cb07  Percentual',
            'VALOR_CB' => 'Valor  Cb',
        ];
    }


}
