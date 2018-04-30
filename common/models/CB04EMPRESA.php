<?php

namespace common\models;

use Yii;
use common\models\base\CB04EMPRESA as BaseCB04EMPRESA;

/**
 * This is the model class for table "CB04_EMPRESA".
 */
class CB04EMPRESA extends BaseCB04EMPRESA
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['CB04_CNPJ','CB04_TEL_NUMERO', 'CB04_NOME', 'CB04_END_LOGRADOURO', 'CB04_END_BAIRRO', 'CB04_END_CIDADE', 'CB04_END_UF', 'CB04_END_NUMERO',  'CB04_END_CEP'], 'required'],
            [['CB04_DADOS_API_TOKEN', 'CB04_FUNCIONAMENTO', 'CB04_OBSERVACAO'], 'string'],
            [['CB04_CATEGORIA_ID', 'CB04_STATUS', 'CB04_QTD_FAVORITO', 'CB04_QTD_COMPARTILHADO', 'CB04_TIPO', 'CB04_ID_EMPRESA', 'CB04_ID_REPRESENTANTE'], 'integer'],
            [['CB04_NOME', 'CB04_END_LOGRADOURO', 'CB04_END_BAIRRO', 'CB04_END_CIDADE', 'CB04_END_COMPLEMENTO'], 'string', 'max' => 50],
            [['CB04_URL_LOGOMARCA'], 'string', 'max' => 100],
            [['CB04_END_UF'], 'string', 'max' => 2],
            [['CB04_FLG_DELIVERY'], 'integer'],
            [['CB04_END_NUMERO'], 'string', 'max' => 5],
            [['CB04_END_LONGITUDE', 'CB04_END_LATITUDE'], 'string', 'max' => 20],
            [['CB04_CNPJ'], 'string', 'max' => 14],
            ['CB04_END_CEP', 'filter', 'filter' => function ($value) {
                    return preg_replace("/[^0-9]/", "", $value);
                }],
            ['CB04_CNPJ', 'filter', 'filter' => function ($value) {
                    return preg_replace("/[^0-9]/", "", $value);
            }],
	     	[['CB04_END_CEP'], 'string', 'max' => 8],
            
            
        ]);
    }
    
   
    public static function getInfoEmpresa($a = []) {
        $categoria = $item = '';

        if (!empty($a['categoria'])) {
            $categoria = " AND CB04_CATEGORIA_ID = " . (int) $a['categoria'];

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
            CB04_END_LONGITUDE,
            CB04_END_LATITUDE,
            CASE CB04_URL_LOGOMARCA 
                WHEN CB04_URL_LOGOMARCA IS NULL 
                THEN CB04_URL_LOGOMARCA 
                ELSE 'img/empresa_default.png' END AS CB04_URL_LOGOMARCA,
            FORMAT(MAX(IFNULL(CB07_PERCENTUAL,0)), 0, 'de_DE') AS CASHBACK
        FROM CB04_EMPRESA
            LEFT JOIN CB05_PRODUTO ON(CB05_EMPRESA_ID = CB04_ID)
            LEFT JOIN CB06_VARIACAO ON(CB06_PRODUTO_ID = CB05_ID)
            LEFT JOIN CB10_CATEGORIA ON(CB10_ID = CB04_CATEGORIA_ID)
            LEFT JOIN CB12_ITEM_CATEG_EMPRESA ON(CB12_EMPRESA_ID = CB04_ID OR CB05_ID = CB12_PRODUTO_ID)
            LEFT JOIN CB07_CASH_BACK ON(CB07_PRODUTO_ID = CB05_ID  OR CB07_VARIACAO_ID = CB06_ID)
        WHERE CB04_EMPRESA.CB04_STATUS = 1 AND CB04_TIPO = 1  $categoria $item
            GROUP BY CB04_ID, CB04_NOME, CB04_QTD_FAVORITO, CB04_QTD_COMPARTILHADO, CB04_END_LOGRADOURO, 
            CB04_END_BAIRRO, CB04_END_CIDADE, CB04_END_UF, CB04_END_NUMERO, CB04_NOME
            ORDER BY RAND()";

        $command = \Yii::$app->db->createCommand($sql);
        return $command->queryAll();
    }

    /**
     * @inheritdoc
     */
    public static function getEmpresa($id, $idUser = null) {
        $retorno = [];

        // dados da empresa
        if (($retorno['empresa'] = self::find()->where('CB04_ID=' . $id . ' AND CB04_STATUS = 1 AND CB04_TIPO = 1  ')->one())) {

            // like
            $retorno['like'] = ($idUser) ? ((bool) CB15LIKEEMPRESA::findOne(['CB15_EMPRESA_ID' => $id, 'CB15_USER_ID' => $idUser])) : false;

            // imagens da empresa
            $retorno['img_empresa'] = CB13FOTOEMPRESA::find()
                    ->where(['CB13_EMPRESA_ID' => $retorno['empresa']['CB04_ID']])
                    ->orderBy('CB13_CAMPA DESC')
                    ->all();

            // categoria
            $retorno['categoria'] = CB10CATEGORIA::findOne($retorno['empresa']['CB04_CATEGORIA_ID']);

            // itens da categoria
            $retorno['itens_categoria'] = CB11ITEMCATEGORIA::find()
                    ->joinWith('cB12_ITEM_CATEG_EMPRESA')
                    ->where(['CB12_EMPRESA_ID' => $retorno['empresa']['CB04_ID']])
                    ->orderBy('CB11_DESCRICAO')
                    ->all();

            // formas de pagamento
            $retorno['forma_pagamento'] = CB08FORMAPAGAMENTO::find()
                    ->joinWith('cB09_FORMA_PAG_EMPRESA')
                    ->where(['CB09_EMPRESA_ID' => $retorno['empresa']['CB04_ID']])
                    ->orderBy('CB08_NOME')
                    ->all();

            // produtos
            $produto = CB05PRODUTO::find()
                    ->where(['CB05_EMPRESA_ID' => $retorno['empresa']['CB04_ID'], 'CB05_ATIVO' => 1])
                    ->orderBy('CB05_NOME_CURTO')
                    ->all();

            foreach ($produto as $p) {

                // dados do produto
                $retornoProduto = $p->attributes;

                // itens do produto
                $retornoProduto['ITEM'] = CB11ITEMCATEGORIA::find()
                        ->joinWith('cB12_ITEM_CATEG_EMPRESA')
                        ->where(['CB12_PRODUTO_ID' => $p['CB05_ID']])
                        ->orderBy('CB11_DESCRICAO')
                        ->all();

                // imagens
                $retornoProduto['IMG'] = CB14FOTOPRODUTO::find()
                        ->where(['CB14_PRODUTO_ID' => $p['CB05_ID']])
                        ->orderBy('CB14_CAPA DESC')
                        ->all();

                // cashback do produto
                $retornoProduto['CASHBACK'] = CB07CASHBACK::find()
                        ->where(['CB07_PRODUTO_ID' => $p['CB05_ID']])
                        ->orderBy('CB07_DIA_SEMANA')
                        ->all();

                // varia��es
                $retornoProduto['VARIACAO'] = CB06VARIACAO::find()
                        ->where(['CB06_PRODUTO_ID' => $p['CB05_ID']])
                        ->orderBy('CB06_DESCRICAO')
                        ->all();

                // cashback por da varia��o
                
                    $retornoProduto['CASHBACK_VARIACAO'] =  
                        \Yii::$app->db->createCommand('
                            SELECT * 
                            FROM view_variacao_produto 
                            WHERE CB05_ID = :CB05_ID 
                            ORDER BY CB07_PERCENTUAL DESC
                        ')
                        ->bindValue(':CB05_ID', $p['CB05_ID'])
                        ->queryAll();

                $retorno['produto'][] = $retornoProduto;
            }
        }

        return $retorno;
    }

    /**
     * @inheritdoc
     * @return CB09FORMAPAGTOEMPRESAQuery the active query used by this AR class.
     */
    public static function getFormaPagamento($id) {
        
        $FP = CB09FORMAPAGTOEMPRESA::findBySql(
                        "SELECT GROUP_CONCAT(CB09_ID_FORMA_PAG) AS FORMAPAGAMENTO
                        FROM CB09_FORMA_PAGTO_EMPRESA
                        WHERE CB09_ID_EMPRESA = " . $id . "
                        GROUP BY CB09_ID_EMPRESA")->one();
        
        return (!empty($FP->FORMAPAGAMENTO)) ? explode(',', $FP->FORMAPAGAMENTO) : [];
    }

    public function saveEstabelecimento($data) {
        // dados do estabelecimento
        $this->setAttributes($data);
        $this->CB04_CNPJ = preg_replace("/[^0-9]/", "", $this->CB04_CNPJ);
        $this->CB04_CONTA_VERIFICADA = 1;
        $this->save();
        
        if (!empty($data['FORMAS_PAGAMENTO'])) {
            $array_fp = str_getcsv($data['FORMAS_PAGAMENTO'],"\n");
            foreach ($array_fp as $fp) {
                $fp = explode(',', $fp);
                $CB09_ID = $fp[8];
                $status = $fp[0];
                if ($status) {
                    $CB09FORMAPAGTOEMPRESA = (!$CB09_ID) ? new CB09FORMAPAGTOEMPRESA() : CB09FORMAPAGTOEMPRESA::findOne($CB09_ID);
                    $CB09FORMAPAGTOEMPRESA->CB09_ID_EMPRESA = $this->CB04_ID;
                    $CB09FORMAPAGTOEMPRESA->CB09_PERC_ADQ = $fp[2];
                    $CB09FORMAPAGTOEMPRESA->CB09_PERC_ADMIN = $fp[3];
                    $CB09FORMAPAGTOEMPRESA->CB09_PERC_FUNCIONARIO = $fp[4];   
                    $CB09FORMAPAGTOEMPRESA->CB09_PERC_REPRESENTANTE = $fp[5];   
                    $CB09FORMAPAGTOEMPRESA->CB09_PERC_FUNC_ADMIN = $fp[6];   
                    $CB09FORMAPAGTOEMPRESA->CB09_ID_FORMA_PAG = $fp[7];
                    $CB09FORMAPAGTOEMPRESA->save();
                    
                } else if ($CB09_ID) {
                    CB09FORMAPAGTOEMPRESA::deleteAll(['CB09_ID' => $CB09_ID]);
                }
            }
        }
        return $this->CB04_ID;
    }
    
    /**
     * @inheritdoc
     */
    public static function getRepresentante($empresa)
    {
        $sql = "SELECT id AS ID_USER, REP.CB04_ID AS REPRESENTANTE
                FROM CB04_EMPRESA
                INNER JOIN VIEW_REPRESENTANTE REP ON(CB04_EMPRESA.CB04_ID_REPRESENTANTE = REP.CB04_ID)
                INNER JOIN user ON(id_company = REP.CB04_ID)
                WHERE CB04_EMPRESA.CB04_ID = :empresa
                GROUP BY id,REP.CB04_ID";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':empresa', $empresa);
        return $command->queryOne();
    }
    
    /**
     * @inheritdoc
     */
    public function saveRepresentante($data)
    {
        $this->setAttributes($data);
        $this->CB04_CNPJ = preg_replace("/[^0-9]/", "", $this->CB04_CNPJ);
        $this->CB04_CONTA_VERIFICADA = 1;
        $this->CB04_STATUS = 1;
        $this->CB04_TIPO = 2; // representante
        $this->CB04_FUNCIONAMENTO = '_';
        $this->save();
        
        // edit user - o create é no componentIugu
        if (!$this->isNewRecord) {
            if (($user = User::findOne(['id_company' => $this->CB04_ID]))) {
                $user->scenario = $user::PERFIL_REPRESENTANTE;
                $user->email = $this->CB04_EMAIL;
                $user->cpf_cnpj = $this->CB04_CNPJ;
                $user->username = $this->CB04_CNPJ . '-' . uniqid();
                $user->name = $this->CB04_NOME;
                $user->save();
            } 
        }
        
        return $this->CB04_ID;
    }
    
    /**
     * @inheritdoc
     */
    public function saveFuncionario($data)
    {
        $this->setAttributes($data);
        $this->CB04_CNPJ = preg_replace("/[^0-9]/", "", $this->CB04_CNPJ);
        $this->CB04_CONTA_VERIFICADA = 1;
        $this->CB04_STATUS = 1;
        $this->CB04_TIPO = 3; // funcionario
        $this->CB04_FUNCIONAMENTO = '_';
        $this->save();
        
        // edit user - o create é no componentIugu
        if (!$this->isNewRecord) {
            if (($user = User::findOne(['id_company' => $this->CB04_ID]))) {
                $user->scenario = $user::PERFIL_FUNCIONARIO;
                $user->email = $this->CB04_EMAIL;
                $user->cpf_cnpj = $this->CB04_CNPJ;
                $user->username = $this->CB04_CNPJ . '-' . uniqid();
                $user->name = $this->CB04_NOME;
                $user->save();
            } 
        }
        
        return $this->CB04_ID;
    }
    
    /**
     * @inheritdoc
     */
    public function gridQueryEmpresasMain() {
        $sql = "SELECT CB04_ID as ID, CB04_ID, CB04_NOME, CB04_STATUS,
                CONCAT('img/editar.png^Editar^javascript:gridEmpresa_editar(', CB04_ID, ');') AS EDITAR
                FROM CB04_EMPRESA
                WHERE  CB04_TIPO = 1 
                ORDER BY CB04_STATUS DESC,CB04_ID DESC";
        $command = \Yii::$app->db->createCommand($sql);
        return $command->query()->readAll();
    }

    /**
     * @inheritdoc
     */
    public function gridSettingsEmpresasMain() {
        $al = $this->attributeLabels();
        return [
            ['btnsAvailable' => []],
            ['sets' => ['title' => $al['CB04_ID'], 'align' => 'center', 'width' => '80', 'type' => 'ro', 'id' => 'CB04_ID'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => $al['CB04_NOME'], 'align' => 'left', 'width' => '*', 'type' => 'ro', 'id' => 'CB04_NOME'], 'filter' => ['title' => '#text_filter']],
            ['sets' => ['title' => 'ATIVO', 'align' => 'center', 'width' => '80', 'type' => 'ch', 'id' => 'CB04_STATUS'], 'filter' => ['title' => '']],
            ['sets' => ['title' => 'EDITAR', 'align' => 'center', 'width' => '80', 'type' => 'img', 'id' => 'EDITAR'], 'filter' => ['title' => '']],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getEmpresasComissao($user) {
        $sql = "SELECT TBL.CB04_ID, TBL.CB04_NOME
                FROM (
                    SELECT CB04_EMPRESA.CB04_ID, CB04_EMPRESA.CB04_NOME
                    FROM CB04_EMPRESA
                    INNER JOIN VIEW_FUNCIONARIO ON(VIEW_FUNCIONARIO.CB04_ID_EMPRESA = CB04_EMPRESA.CB04_ID)
                    INNER JOIN user ON(user.id_company = VIEW_FUNCIONARIO.CB04_ID_EMPRESA and user.id = :user)
                    WHERE CB04_EMPRESA.CB04_TIPO = 1 
                    UNION
                    SELECT CB04_EMPRESA.CB04_ID, CB04_EMPRESA.CB04_NOME
                    FROM CB04_EMPRESA
                    INNER JOIN VIEW_REPRESENTANTE ON(VIEW_REPRESENTANTE.CB04_ID = CB04_EMPRESA.CB04_ID_REPRESENTANTE)
                    INNER JOIN user ON(user.id_company = VIEW_REPRESENTANTE.CB04_ID and user.id = :user)
                    WHERE CB04_EMPRESA.CB04_TIPO = 1 ) TBL
                GROUP BY TBL.CB04_ID, TBL.CB04_NOME
                ORDER BY TBL.CB04_NOME";
        $command = \Yii::$app->db->createCommand($sql);
        $command->bindValue(':user', $user);
        return $command->query()->readAll();
    }
    
    
}