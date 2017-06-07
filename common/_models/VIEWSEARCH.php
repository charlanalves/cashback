<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "VIEW_SEARCH".
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
    public static function tableName()
    {
        return 'VIEW_SEARCH';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EMPRESA_ID'], 'integer'],
            [['EMPRESA_NOME', 'BUSCA_TEXTO'], 'string', 'max' => 50],
            [['TIPO'], 'string', 'max' => 7],
            [['IMG'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'EMPRESA_ID' => Yii::t('app', 'Empresa  ID'),
            'EMPRESA_NOME' => Yii::t('app', 'Empresa  Nome'),
            'BUSCA_TEXTO' => Yii::t('app', 'Busca  Texto'),
            'TIPO' => Yii::t('app', 'Tipo'),
            'IMG' => Yii::t('app', 'Imagem'),
        ];
    }

    /**
     * @inheritdoc
     * @return VIEWSEARCHQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new VIEWSEARCHQuery(get_called_class());
    }
}
