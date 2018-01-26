<?php

namespace common\models;

use Yii;
use common\models\base\CB09FORMAPAGTOEMPRESA as BaseCB09FORMAPAGTOEMPRESA;

/**
 * This is the model class for table "CB09_FORMA_PAGTO_EMPRESA".
 */
class CB09FORMAPAGTOEMPRESA extends BaseCB09FORMAPAGTOEMPRESA
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(), [
            [['CB09_ID_EMPRESA', 'CB09_ID_FORMA_PAG', 'CB09_PERC_ADQ', 'CB09_PERC_ADMIN', 'CB09_PERC_FUNCIONARIO', 'CB09_PERC_REPRESENTANTE', 'CB09_PERC_FUNC_ADMIN'], 'required'],
            [['CB09_ID_EMPRESA', 'CB09_ID_FORMA_PAG'], 'integer'],
            [['CB09_PERC_ADQ', 'CB09_PERC_ADMIN', 'CB09_PERC_FUNCIONARIO', 'CB09_PERC_REPRESENTANTE', 'CB09_PERC_FUNC_ADMIN'], 'number'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function gridQueryFormaPagamentoMain($param)
    {
        $sql = "SELECT CB08_ID as ID, CB08_ID, CB08_NOME, 
                COALESCE(CB09_ID_FORMA_PAG, 0) as ATIVO,
                COALESCE(CB09_PERC_ADQ , 0) as CB09_PERC_ADQ, 
                COALESCE(CB09_PERC_ADMIN , 0) as CB09_PERC_ADMIN,
                COALESCE(CB09_PERC_FUNCIONARIO , 0) as CB09_PERC_FUNCIONARIO,
                COALESCE(CB09_PERC_REPRESENTANTE , 0) as CB09_PERC_REPRESENTANTE,
                COALESCE(CB09_PERC_FUNC_ADMIN , 0) as CB09_PERC_FUNC_ADMIN
                FROM CB08_FORMA_PAGAMENTO 
                LEFT JOIN CB09_FORMA_PAGTO_EMPRESA ON(CB09_ID_FORMA_PAG = CB08_ID AND CB09_ID_EMPRESA = :empresa)
                WHERE CB08_STATUS = 1 
                ORDER BY CB09_ID_EMPRESA DESC, CB08_NOME";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindParam(':empresa', (!empty($param['empresa']) ? $param['empresa'] : 0 ));
        return $command->query()->readAll();
    }

    /**
     * @inheritdoc
     * IMPORTANT: Nao mudar a ordem das colunas! Se necessario, add no final
     */
    public function gridSettingsFormaPagamentoMain()
    {
        $al = $this->attributeLabels();
        return [
            ['btnsAvailable' => []],
            ['sets' => ['title' => "ATIVO", 'align' => 'center', 'width' => '60', 'type' => 'ch', 'id' => 'ATIVO']],
            ['sets' => ['title' => $al['CB09_ID_FORMA_PAG'], 'align' => 'left', 'width' => '260', 'type' => 'ro', 'id' => 'CB08_NOME']],
            ['sets' => ['title' => $al['CB09_PERC_ADQ'], 'align' => 'right', 'width' => '118', 'type' => 'edn', 'id' => 'CB09_PERC_ADQ']],
            ['sets' => ['title' => $al['CB09_PERC_ADMIN'], 'align' => 'right', 'width' => '118', 'type' => 'edn', 'id' => 'CB09_PERC_ADMIN']],
            ['sets' => ['title' => $al['CB09_PERC_FUNCIONARIO'], 'align' => 'right', 'width' => '118', 'type' => 'edn', 'id' => 'CB09_PERC_FUNCIONARIO']],
            ['sets' => ['title' => $al['CB09_PERC_REPRESENTANTE'], 'align' => 'right', 'width' => '118', 'type' => 'edn', 'id' => 'CB09_PERC_REPRESENTANTE']],
            ['sets' => ['title' => $al['CB09_PERC_FUNC_ADMIN'], 'align' => 'right', 'width' => '118', 'type' => 'edn', 'id' => 'CB09_PERC_FUNC_ADMIN']],
            ['sets' => ['title' => '', 'width' => '0', 'type' => 'ro', 'id' => 'CB08_ID']],
        ];
    }

}
