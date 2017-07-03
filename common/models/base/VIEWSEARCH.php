<?php

namespace common\models\base;

use Yii;




/**
 * This is the base model class for table "VIEW_SEARCH".
 *
 * @property integer $EMPRESA_ID
 * @property string $EMPRESA_NOME
 * @property string $BUSCA_TEXTO
 * @property string $TIPO
 * @property string $IMG
 */
class VIEWSEARCH extends \common\models\GlobalModel
{
 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EMPRESA_ID'], 'integer'],
            [['CATEGORIA_ID'], 'integer'],
            [['CATEGORIA_NOME'], 'string'],
            [['EMPRESA_NOME', 'BUSCA_TEXTO'], 'string', 'max' => 50],
            [['TIPO'], 'string', 'max' => 7],
            [['IMG'], 'string', 'max' => 100],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'VIEW_SEARCH';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'EMPRESA_ID' => 'Empresa ID',
            'CATEGORIA_ID' => 'Categoria ID',
            'CATEGORIA_NOME' => 'Categoria Nome',
            'EMPRESA_NOME' => 'Empresa Nome',
            'BUSCA_TEXTO' => 'Busca Texto',
            'TIPO' => 'Tipo',
            'IMG' => 'Img',
        ];
    }


}
