<?php

namespace common\models;

use Yii;
use common\models\base\CB06VARIACAO as BaseCB06VARIACAO;

/**
 * This is the model class for table "CB06_VARIACAO".
 */
class CB06VARIACAO extends BaseCB06VARIACAO
{
    
    const SCENARIODELIVERY = 'SCENARIODELIVERY';
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB06_PRODUTO_ID', 'CB06_DESCRICAO', 'CB06_PRECO', 'CB06_PRECO_PROMOCIONAL', 'CB06_DINHEIRO_VOLTA'], 'required'],
            [['CB06_PRODUTO_ID', 'CB06_TEMPO_MIN', 'CB06_TEMPO_MAX', 'CB06_AVALIACAO_ID'], 'integer'],
            [['CB06_DISTRIBUICAO'], 'integer', 'min' => 0, 'max' => 1],
            [['CB06_PRECO', 'CB06_PRECO_PROMOCIONAL', 'CB06_DINHEIRO_VOLTA'], 'number'],
            [['CB06_TITULO'], 'string', 'max' => 500],
            [['CB06_DESCRICAO'], 'string', 'max' => 30],
            [['CB06_DISTRIBUICAO'], 'required', 'on' => self::SCENARIODELIVERY],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB06_ID' => 'Cb06  ID',
            'CB06_PRODUTO_ID' => 'Cb06  Produto  ID',
            'CB06_TITULO' => 'Cb06  Titulo',
            'CB06_DESCRICAO' => 'Cb06  Descricao',
            'CB06_PRECO' => 'Cb06  Preco',
            'CB06_PRECO_PROMOCIONAL' => 'Cb06  Preco  Promocional',
            'CB06_DINHEIRO_VOLTA' => 'Dinheiro de volta',
            'CB06_TEMPO_MIN' => 'Tempo mínimo',
            'CB06_TEMPO_MAX' => 'Tempo máximo',
            'CB06_DISTRIBUICAO' => 'Distribuição',
            'CB06_AVALIACAO_ID' => 'Avaliação'
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
        
        // filter categoria e ordenacao + limit
        $filterBindCat = empty($filter['cat']) ? false : true;
        $filterBindOrd = empty($filter['ord']) ? false : true;
        $limiteInicio = (!empty($filter['limiteInicio'])) ? $filter['limiteInicio'] : '0';
        $limiteQtd = (!empty($filter['limiteQtd'])) ? $filter['limiteQtd'] : '15';
        
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
        
        
      $sql = "  SELECT  
                    MAX(CB06_VARIACAO.CB06_DINHEIRO_VOLTA) AS CB06_DINHEIRO_VOLTA,
                    CB06_ID, 
                    CB06_PRECO_PROMOCIONAL, 
                    CB06_PRECO,
                    CB04_ID, 
                    CB04_NOME, 
                    CB04_END_COMPLEMENTO,
                    -- CONCAT(  '" . $url . "', CB04_EMPRESA.CB04_URL_LOGOMARCA ) AS CB04_URL_LOGOMARCA,
                    -- CONCAT(  '" . $url . "', CB14_FOTO_PRODUTO.CB14_URL ) AS CB14_URL, 
                    CB04_EMPRESA.CB04_URL_LOGOMARCA AS CB04_URL_LOGOMARCA, 
                    CB14_FOTO_PRODUTO.CB14_URL AS CB14_URL, 
                    CB05_ID, 
                    CB05_TITULO, 
                    CB06_DESCRICAO,
                    CB04_END_LATITUDE,
                    CB04_END_LONGITUDE,
                    CB06_PRODUTO_ID
                FROM CB06_VARIACAO
                INNER JOIN CB05_PRODUTO ON (CB05_ATIVO = 1 AND CB05_PRODUTO.CB05_ID = CB06_VARIACAO.CB06_PRODUTO_ID ) 
                INNER JOIN CB04_EMPRESA ON (CB04_STATUS = 1 AND CB04_EMPRESA.CB04_ID = CB05_PRODUTO.CB05_EMPRESA_ID )
                LEFT JOIN (
                    SELECT MIN(CB14_ID) AS CB14_ID, CB14_PRODUTO_ID, CB14_URL 
                    FROM CB14_FOTO_PRODUTO
                    GROUP BY CB14_PRODUTO_ID) CB14_FOTO_PRODUTO ON(CB14_PRODUTO_ID = CB05_ID)
                " . (!$filterBindCat ? "" : "WHERE CB04_CATEGORIA_ID = :categoria") . "
                GROUP BY CB05_ID
                ORDER BY " . (!$filterBindOrd ? 'CB06_DINHEIRO_VOLTA DESC' : ":ordem") . "
                LIMIT $limiteInicio, $limiteQtd";
			
        $command = \Yii::$app->db->createCommand($sql);
        if ($filterBindCat) {
            $command->bindValue(':categoria', $filter['cat']);
        }
        if ($filterBindOrd) {
            $command->bindValue(':ordem', \Yii::$app->u->filterOrder($filter['ord']));
        }
        $result = $command->queryAll();
            
        // ordena por proximidade
        if(!empty($filter['latitude']) && !empty($filter['longitude'])) {
            $proximos = $outros = $ordemOriginal = [];
            foreach ($result as $r) {
                if($r['CB04_END_LATITUDE'] && $r['CB04_END_LONGITUDE']) {
                    $r['DISTANCIA'] = \Yii::$app->u->arredondar(\Yii::$app->u->distanciaGeografica($filter['latitude'], $r['CB04_END_LATITUDE'], $filter['longitude'], $r['CB04_END_LONGITUDE']));
                    $proximos[] = $r;
                } else {
                    $outros[] = $r;
                }
                $ordemOriginal[] = $r;
            }
            
            // ordena os estabelecimentos com a geoposicao  
            if(!empty($filter['ord']) && $filter['ord'] == 'mais-proximos') {
                $proximos = \Yii::$app->u->orderArrayMult($proximos, 'DISTANCIA');
                // merge com os outros que nao tem geoposicao
                $result = array_merge($proximos, $outros);
            } else {
                $result = $ordemOriginal;
            }
        }
        return $result; 
    }
	
}
