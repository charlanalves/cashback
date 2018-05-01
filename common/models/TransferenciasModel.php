<?php

namespace common\models;


use common\models\base\TransferenciasModel as BaseTransferenciasModel;

/**
 * This is the model class for table "PAG04_TRANSFERENCIAS".
 */
class TransferenciasModel extends BaseTransferenciasModel
{	

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['PAG04_ID_TRANSACAO', 'PAG04_COD_TRANS_ADQ', 'PAG04_VLR_TRANS', 'PAG04_VLR_TRANS_LIQ', 'PAG04_VLR_EMPRESA', 'PAG04_VLR_CLIENTE', 'PAG04_VLR_ADMIN'], 'required'],
            [['PAG04_ID_TRANSACAO'], 'integer'],
            [['PAG04_VLR_TRANS', 'PAG04_VLR_TRANS_LIQ', 'PAG04_VLR_EMPRESA', 'PAG04_VLR_CLIENTE', 'PAG04_VLR_ADMIN'], 'number'],
            [['PAG04_DT_PREV_DEP_CONTA_BANC_MASTER', 'PAG04_DT_DEP_CONTA_BANC_MASTER', 'PAG04_DT_PREV_DEP_CONTA_VIRTUAL_MASTER', 'PAG04_DT_DEP_CONTA_VIRTUAL_MASTER', 'PAG04_DT_PREV_DEP_SUBCONTA_VIRTUAL', 'PAG04_DT_DEP_SUBCONTA_VIRTUAL'], 'safe'],
            [['PAG04_COD_TRANS_ADQ'], 'string', 'max' => 500]
        ]);
    }
	
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PAG04_ID' => \Yii::t('app','Pag04  ID'),
            'PAG04_ID_TRANSACAO' => \Yii::t('app','Pag04  Id  Transacao'),
            'PAG04_COD_TRANS_ADQ' => \Yii::t('app','Pag04  Cod  Trans  Adq'),
            'PAG04_VLR_TRANS' => \Yii::t('app','Pag04  Vlr  Trans'),
            'PAG04_VLR_TRANS_LIQ' => \Yii::t('app','Pag04  Vlr  Trans  Liq'),
            'PAG04_VLR_EMPRESA' => \Yii::t('app','Pag04  Vlr  Empresa'),
            'PAG04_VLR_CLIENTE' => \Yii::t('app','Pag04  Vlr  Cliente'),
            'PAG04_VLR_ADMIN' => \Yii::t('app','Pag04  Vlr  Admin'),
            'PAG04_DT_PREV_DEP_CONTA_BANC_MASTER' => \Yii::t('app','Pag04  Dt  Prev  Dep  Conta  Banc  Master'),
            'PAG04_DT_DEP_CONTA_BANC_MASTER' => \Yii::t('app','Pag04  Dt  Dep  Conta  Banc  Master'),
            'PAG04_DT_PREV_DEP_CONTA_VIRTUAL_MASTER' => \Yii::t('app','Pag04  Dt  Prev  Dep  Conta  Virtual  Master'),
            'PAG04_DT_DEP_CONTA_VIRTUAL_MASTER' => \Yii::t('app','Pag04  Dt  Dep  Conta  Virtual  Master'),
            'PAG04_DT_PREV_DEP_SUBCONTA_VIRTUAL' => \Yii::t('app','Pag04  Dt  Prev  Dep  Subconta  Virtual'),
            'PAG04_DT_DEP_SUBCONTA_VIRTUAL' => \Yii::t('app','Pag04  Dt  Dep  Subconta  Virtual'),
            'CB04_ID' => \Yii::t('app','Cod Empresa'),
            'CB04_NOME' => \Yii::t('app','Empresa'),
            'VLR_TOTAL' => \Yii::t('app','Valor Total Transferências (R$)'),
            'CB16_ID' => \Yii::t('app','Cod Produto'),
            'CB16_COD_TRANSACAO' => \Yii::t('app','Cod Transação'),
            'CB02_NOME' => \Yii::t('app','Cliente'),
            'CB16_VALOR' => \Yii::t('app','Valor R$'),
            'PAG04_DT_PREV' => \Yii::t('app','Previsão Lib.'),
            'CB16_VLR_CB_TOTAL' => \Yii::t('app','CB Cliente R$'),
            'CB16_PERC_ADQ' => \Yii::t('app','Perc ADQ %'),
            'CB16_PERC_ADMIN' => \Yii::t('app','Perc Admin %'),
            'CB16_DT' => \Yii::t('app','Criado'),
            'TIPO' => \Yii::t('app','Tipo'),
            'PAG04_VLR' => \Yii::t('app','Valor'),
            'NOME' => \Yii::t('app','Nome'),
            'CB02_NOME' => \Yii::t('app','Cliente'),
            'PAG04_DATA_CRIACAO' => \Yii::t('app','Criado'),
            'PAG04_VLR' => \Yii::t('app','Valor'),
            'name' => \Yii::t('app','Cliente'),
            'PAG04_STATUS' => \Yii::t('app','STATUS'),
            'BTN' => \Yii::t('app','AÇÃO'),
        
        ];
    }
    
    /**
    * @inheritdoc
    */
    public function gridQueryMain()
    {
	    $query =  "
	    				     SELECT DISTINCT
                              	DATE_FORMAT(PAG04_TRANSFERENCIAS.PAG04_DATA_CRIACAO, '%d/%m/%Y %H:%i:%s')AS PAG04_DATA_CRIACAO,
                                PAG04_TRANSFERENCIAS.PAG04_ID AS ID,
                                PAG04_TRANSFERENCIAS.PAG04_ID,
                                PAG04_TRANSFERENCIAS.PAG04_VLR,
                                user.name,
                                CASE 
                                WHEN PAG04_STATUS = 1 then 'REALIZADO'
                                ELSE 'PENDENTE'
                                END AS PAG04_STATUS
                              
                              FROM PAG04_TRANSFERENCIAS
                              JOIN user on user.id = PAG04_TRANSFERENCIAS.PAG04_ID_USER_CONTA_ORIGEM
                              JOIN CB03_CONTA_BANC ON CB03_CONTA_BANC.CB03_USER_ID = user.id
                              JOIN CB02_CLIENTE ON CB02_CLIENTE.CB02_ID = user.id_cliente
                              WHERE 
                              PAG04_TRANSFERENCIAS.PAG04_TIPO = 'V2B' AND
                              PAG04_TRANSFERENCIAS.PAG04_STATUS IS NULL AND
                              PAG04_TRANSFERENCIAS.PAG04_ID_PEDIDO IS NULL
                              GROUP BY PAG04_ID
                              ORDER BY PAG04_TRANSFERENCIAS.PAG04_DATA_CRIACAO DESC
            ";
		
            $connection = \Yii::$app->db;
            $command = $connection->createCommand($query);
            $reader = $command->query();
		
            return $reader->readAll();
    }
    
        /**
     * @inheritdoc
     */
    public function gridSettingsMain()
    {
    	$al = $this->attributeLabels();
        return [
            ['btnsAvailable' => ['editar']],
            ['sets' => ['title' => 'ID', 'width'=>'50', 'type'=>'ro' , 'id'  => 'PAG04_ID' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DATA_CRIACAO'], 'width'=>'170', 'type'=>'ro' , 'id'  => 'PAG04_DATA_CRIACAO' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR'], 'width'=>'100', 'type'=>'ro' , 'id'  => 'PAG04_VLR' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['name'], 'width'=>'300', 'type'=>'ro' , 'id'  => 'name' ], 'filter' => ['title'=>'#text_filter']],            
            ['sets' => ['title'=>\Yii::t("app",'AÇÕES'), 'width'=>'60' , 'type'=>'img', 'sort'=>'str', 'align'=>'center', 'id' => 'editar', 'id' => 'editar']],
        ];
    }
    
    
    public function gridQueryAgendadas()
    {
    
	    $query =  "
				SELECT
					CB04_EMPRESA.CB04_ID AS ID,
					CB04_EMPRESA.CB04_ID AS OO,
					CB04_EMPRESA.CB04_ID,
					CB04_EMPRESA.CB04_NOME,
					SUM(CB16_PEDIDO.CB16_VALOR) AS VLR_TOTAL
				FROM CB04_EMPRESA
				JOIN CB16_PEDIDO ON CB16_PEDIDO.CB16_EMPRESA_ID = CB04_EMPRESA.CB04_ID
				WHERE CB16_PEDIDO.CB16_STATUS = :statusPagoTansAgendadas
				GROUP BY CB04_EMPRESA.CB04_ID	    
            ";
		
            $connection = \Yii::$app->db;
            $command = $connection->createCommand($query);
            $command->bindValue(':statusPagoTansAgendadas', \common\models\CB16PEDIDO::status_pago_trans_agendadas);
            $reader = $command->query();
		
            return $reader->readAll();
    }
    
        /**
     * @inheritdoc
     */
    public function gridSettingsAgendadas()
    {
    	$al = $this->attributeLabels();
        return [
            ['btnsAvailable' => ['editar']],            
            ['sets' => ['type'=>'sub_row_grid' ], 'filter' => ['title'=>'']],
            ['sets' => ['title' => $al['CB04_ID'], 'width'=>'*', 'type'=>'ro' , 'id'  => 'OO' ], 'filter' => ['title'=>'#text_filter']],
            ['sets' => ['title' => $al['CB04_NOME'], 'width'=>'*', 'type'=>'ro' , 'id'  => 'OO' ], 'filter' => ['title'=>'#text_filter']],
            ['sets' => ['title' => $al['VLR_TOTAL'], 'width'=>'*', 'type'=>'ro' , 'id'  => 'OO' ], 'filter' => ['title'=>'#text_filter']],
            ['sets' => [ 'width'=>'*', 'type'=>'ro' , 'id'  => 'CB04_ID' ], 'filter' => ['title'=>'']],
            ['sets' => [ 'width'=>'*', 'type'=>'ro' , 'id'  => 'CB04_NOME' ], 'filter' => ['title'=>'']],
            ['sets' => [ 'width'=>'*', 'type'=>'ro' , 'id'  => 'VLR_TOTAL' ], 'filter' => ['title'=>'']],            
            
                                      				
        ];
    
    }
    
 public function gridQueryTAPedidosEmp($cdEmpresa)
    {
    
	    $query =  "
				SELECT
					CB16_PEDIDO.CB16_COD_TRANSACAO,
					CB02_CLIENTE.CB02_NOME,
					CB16_PEDIDO.CB16_VALOR,
					DATE_FORMAT(CB16_PEDIDO.CB16_DT, '%d/%m/%Y %H:%i:%s') AS CB16_DT ,
					DATE_FORMAT(PAG04_TRANSFERENCIAS.PAG04_DT_PREV, '%d/%m/%Y') AS PAG04_DT_PREV,
					CB16_PEDIDO.CB16_VLR_CB_TOTAL,
					CB16_PEDIDO.CB16_PERC_ADQ,
					CB16_PEDIDO.CB16_PERC_ADMIN
				FROM
					CB16_PEDIDO
				JOIN user on user.id = CB16_PEDIDO.CB16_USER_ID
				JOIN CB02_CLIENTE ON CB02_CLIENTE.CB02_ID = user.id_cliente
				JOIN PAG04_TRANSFERENCIAS ON PAG04_TRANSFERENCIAS.PAG04_ID_PEDIDO = CB16_PEDIDO.CB16_ID
				
				WHERE 
					CB16_PEDIDO.CB16_EMPRESA_ID = :cdEmpresa 
					AND CB16_PEDIDO.CB16_STATUS = :statusPagoTansAgendadas
				GROUP BY CB16_PEDIDO.CB16_ID
            ";
		
            $connection = \Yii::$app->db;
            $command = $connection->createCommand($query);
            $command->bindValue(':statusPagoTansAgendadas', \common\models\CB16PEDIDO::status_pago_trans_agendadas);
            $command->bindValue(':cdEmpresa', $cdEmpresa);
            $reader = $command->query();
		
            return $reader->readAll();
    }
    
        /**
     * @inheritdoc
     */
    public function gridSettingsTAPedidosEmp()
    {
    	$al = $this->attributeLabels();
        return [
            ['sets' => ['title' => $al['CB16_COD_TRANSACAO'], 'width'=>'*', 'type'=>'ro' , 'id'  => 'CB16_COD_TRANSACAO' ], ], 
            ['sets' => ['title' => $al['CB16_DT'], 'width'=>'150', 'type'=>'ro' , 'id'  => 'CB16_DT' ], ],
            ['sets' => ['title' => $al['PAG04_DT_PREV'], 'width'=>'100', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV' ], ],
            ['sets' => ['title' => $al['CB02_NOME'], 'width'=>'*', 'type'=>'ro' , 'id'  => 'CB02_NOME' ], ], 
            ['sets' => ['title' => $al['CB16_VALOR'], 'width'=>'100', 'type'=>'ro' , 'id'  => 'CB16_VALOR' ], ],   
             
             
            ['sets' => ['title' => $al['CB16_VLR_CB_TOTAL'], 'width'=>'100', 'type'=>'ro' , 'id'  => 'CB16_VLR_CB_TOTAL' ], ],  
            ['sets' => ['title' => $al['CB16_PERC_ADQ'], 'width'=>'50', 'type'=>'ro' , 'id'  => 'CB16_PERC_ADQ' ], ], 
            ['sets' => ['title' => $al['CB16_PERC_ADMIN'], 'width'=>'50', 'type'=>'ro' , 'id'  => 'CB16_PERC_ADMIN' ], ], 
                             				
        ];

    }
    public function gridQueryVencerHoje()
    {
	    $query =  "
                       SELECT *
						FROM (
							SELECT
											CB16_PEDIDO.CB16_COD_TRANSACAO,
											CASE 
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 1 then 'Cliente'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 2 then 'Admin'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 3 then 'Empresa'
											END AS TIPO,				
											PAG04_TRANSFERENCIAS.PAG04_VLR,
						  				   CB02_CLIENTE.CB02_NOME AS NOME,
											DATE_FORMAT(CB16_PEDIDO.CB16_DT, '%d/%m/%Y %H:%i:%s') AS CB16_DT ,
											DATE_FORMAT(PAG04_TRANSFERENCIAS.PAG04_DT_PREV, '%d/%m/%Y') AS PAG04_DT_PREV
										FROM
											CB16_PEDIDO
										JOIN user on user.id = CB16_PEDIDO.CB16_USER_ID
										JOIN CB02_CLIENTE ON CB02_CLIENTE.CB02_ID = user.id_cliente
										JOIN PAG04_TRANSFERENCIAS ON PAG04_TRANSFERENCIAS.PAG04_ID_PEDIDO = CB16_PEDIDO.CB16_ID
										
										WHERE 
											PAG04_TRANSFERENCIAS.PAG04_TIPO = 1
											AND CB16_PEDIDO.CB16_STATUS = 50
											AND PAG04_DT_PREV = CURDATE()
						
							UNION
							SELECT
											CB16_PEDIDO.CB16_COD_TRANSACAO,
											CASE 
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 1 then 'Cliente'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 2 then 'Admin'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 3 then 'Empresa'
											END AS TIPO,				
											PAG04_TRANSFERENCIAS.PAG04_VLR,
						  				   CB04_EMPRESA.CB04_NOME AS NOME,
											DATE_FORMAT(CB16_PEDIDO.CB16_DT, '%d/%m/%Y %H:%i:%s') AS CB16_DT ,
											DATE_FORMAT(PAG04_TRANSFERENCIAS.PAG04_DT_PREV, '%d/%m/%Y') AS PAG04_DT_PREV
										FROM
											CB16_PEDIDO
										JOIN user on user.id = CB16_PEDIDO.CB16_USER_ID
										JOIN CB04_EMPRESA ON CB04_EMPRESA.CB04_ID = CB16_PEDIDO.CB16_EMPRESA_ID
										JOIN PAG04_TRANSFERENCIAS ON PAG04_TRANSFERENCIAS.PAG04_ID_PEDIDO = CB16_PEDIDO.CB16_ID
										
										WHERE 
											PAG04_TRANSFERENCIAS.PAG04_TIPO = 3
											AND CB16_PEDIDO.CB16_STATUS = 50
											AND PAG04_DT_PREV = CURDATE()
						
										
							UNION
							SELECT
											CB16_PEDIDO.CB16_COD_TRANSACAO,
											CASE 
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 1 then 'Cliente'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 2 then 'Admin'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 3 then 'Empresa'
											END AS TIPO,				
											PAG04_TRANSFERENCIAS.PAG04_VLR,
						  				   '-' AS NOME,
											DATE_FORMAT(CB16_PEDIDO.CB16_DT, '%d/%m/%Y %H:%i:%s') AS CB16_DT ,
											DATE_FORMAT(PAG04_TRANSFERENCIAS.PAG04_DT_PREV, '%d/%m/%Y') AS PAG04_DT_PREV
										FROM
											CB16_PEDIDO
										JOIN user on user.id = CB16_PEDIDO.CB16_USER_ID
										JOIN CB02_CLIENTE ON CB02_CLIENTE.CB02_ID = user.id_cliente
										JOIN PAG04_TRANSFERENCIAS ON PAG04_TRANSFERENCIAS.PAG04_ID_PEDIDO = CB16_PEDIDO.CB16_ID
										
										WHERE 
											PAG04_TRANSFERENCIAS.PAG04_TIPO = 2
											AND CB16_PEDIDO.CB16_STATUS = :statusPagoTansLiberadas
											AND PAG04_DT_PREV = CURDATE()
						) A
						
						ORDER BY PAG04_VLR DESC
					
            ";
		
            $connection = \Yii::$app->db;
            $command = $connection->createCommand($query);
            $command->bindValue(':statusPagoTansLiberadas', \common\models\CB16PEDIDO::status_pago_trans_liberadas);
            $reader = $command->query();
		
            return $reader->readAll();
    }
    
    
    
     /**
     * @inheritdoc
     */
    public function gridSettingsVencerHoje()
    {
    	$al = $this->attributeLabels();
        return [
            ['btnsAvailable' => ['editar', 'excluir']],
            ['sets' => ['title'=>\Yii::t("app",'AÇÕES'), 'width'=>'60' , 'type'=>'img', 'sort'=>'str', 'align'=>'center', 'id' => 'editar', 'id' => 'editar']],
            ['sets' => ['title' => $al['CB16_COD_TRANSACAO'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'CB16_COD_TRANSACAO' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['TIPO'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'TIPO' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['NOME'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'NOME' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['CB16_DT'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'CB16_DT' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_PREV'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV' ], 'filter' => ['title'=>'#text_filter']],            				
        ];
    }
    /**
    * @inheritdoc
    */
    public function gridQueryVencer()
    {
	    $query =  "
                      SELECT *
						FROM (
							SELECT
											CB16_PEDIDO.CB16_COD_TRANSACAO,
											CASE 
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 1 then 'Cliente'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 2 then 'Admin'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 3 then 'Empresa'
											END AS TIPO,				
											PAG04_TRANSFERENCIAS.PAG04_VLR,
						  				   CB02_CLIENTE.CB02_NOME AS NOME,
											DATE_FORMAT(CB16_PEDIDO.CB16_DT, '%d/%m/%Y %H:%i:%s') AS CB16_DT ,
											DATE_FORMAT(PAG04_TRANSFERENCIAS.PAG04_DT_PREV, '%d/%m/%Y') AS PAG04_DT_PREV
										FROM
											CB16_PEDIDO
										JOIN user on user.id = CB16_PEDIDO.CB16_USER_ID
										JOIN CB02_CLIENTE ON CB02_CLIENTE.CB02_ID = user.id_cliente
										JOIN PAG04_TRANSFERENCIAS ON PAG04_TRANSFERENCIAS.PAG04_ID_PEDIDO = CB16_PEDIDO.CB16_ID
										
										WHERE 
											PAG04_TRANSFERENCIAS.PAG04_TIPO = 1
											AND CB16_PEDIDO.CB16_STATUS = :statusPagoTansLiberadas
											AND PAG04_DT_PREV > CURDATE()
						
							UNION
							SELECT
											CB16_PEDIDO.CB16_COD_TRANSACAO,
											CASE 
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 1 then 'Cliente'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 2 then 'Admin'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 3 then 'Empresa'
											END AS TIPO,				
											PAG04_TRANSFERENCIAS.PAG04_VLR,
						  				   CB04_EMPRESA.CB04_NOME AS NOME,
											DATE_FORMAT(CB16_PEDIDO.CB16_DT, '%d/%m/%Y %H:%i:%s') AS CB16_DT ,
											DATE_FORMAT(PAG04_TRANSFERENCIAS.PAG04_DT_PREV, '%d/%m/%Y') AS PAG04_DT_PREV
										FROM
											CB16_PEDIDO
										JOIN user on user.id = CB16_PEDIDO.CB16_USER_ID
										JOIN CB04_EMPRESA ON CB04_EMPRESA.CB04_ID = CB16_PEDIDO.CB16_EMPRESA_ID
										JOIN PAG04_TRANSFERENCIAS ON PAG04_TRANSFERENCIAS.PAG04_ID_PEDIDO = CB16_PEDIDO.CB16_ID
										
										WHERE 
											PAG04_TRANSFERENCIAS.PAG04_TIPO = 3
											AND CB16_PEDIDO.CB16_STATUS = 50
											AND PAG04_DT_PREV > CURDATE()
						
										
							UNION
							SELECT
											CB16_PEDIDO.CB16_COD_TRANSACAO,
											CASE 
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 1 then 'Cliente'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 2 then 'Admin'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 3 then 'Empresa'
											END AS TIPO,				
											PAG04_TRANSFERENCIAS.PAG04_VLR,
						  				   '-' AS NOME,
											DATE_FORMAT(CB16_PEDIDO.CB16_DT, '%d/%m/%Y %H:%i:%s') AS CB16_DT ,
											DATE_FORMAT(PAG04_TRANSFERENCIAS.PAG04_DT_PREV, '%d/%m/%Y') AS PAG04_DT_PREV
										FROM
											CB16_PEDIDO
										JOIN user on user.id = CB16_PEDIDO.CB16_USER_ID
										JOIN CB02_CLIENTE ON CB02_CLIENTE.CB02_ID = user.id_cliente
										JOIN PAG04_TRANSFERENCIAS ON PAG04_TRANSFERENCIAS.PAG04_ID_PEDIDO = CB16_PEDIDO.CB16_ID
										
										WHERE 
											PAG04_TRANSFERENCIAS.PAG04_TIPO = 2
											AND CB16_PEDIDO.CB16_STATUS = :statusPagoTansLiberadas
											AND PAG04_DT_PREV > CURDATE()
						) A
						
						ORDER BY PAG04_VLR DESC
						
						 
											
            ";
		
            $connection = \Yii::$app->db;
            $command = $connection->createCommand($query);
            $command->bindValue(':statusPagoTansLiberadas', \common\models\CB16PEDIDO::status_pago_trans_liberadas);
            $reader = $command->query();
		
            return $reader->readAll();
    }
    
        /**
     * @inheritdoc
     */
    public function gridSettingsVencer()
    {
    	$al = $this->attributeLabels();
         return [
            ['btnsAvailable' => ['editar', 'excluir']],
            ['sets' => ['title'=>\Yii::t("app",'AÇÕES'), 'width'=>'60' , 'type'=>'img', 'sort'=>'str', 'align'=>'center', 'id' => 'editar', 'id' => 'editar']],       
            ['sets' => ['title'=>'#cspan' ,'width'=>'60', 'type'=>'img', 'sort'=>'str', 'align'=>'center', 'id' => 'excluir']],
            ['sets' => ['title' => $al['CB16_COD_TRANSACAO'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'CB16_COD_TRANSACAO' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['TIPO'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'TIPO' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['NOME'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'NOME' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['CB16_DT'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'CB16_DT' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_PREV'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV' ], 'filter' => ['title'=>'#text_filter']],            				
        ];
    }
    /**
    * @inheritdoc
    */
    public function gridQueryVencidas()
    {
	    $query =  "
                        SELECT *
						FROM (
							SELECT
											CB16_PEDIDO.CB16_COD_TRANSACAO,
											CASE 
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 1 then 'Cliente'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 2 then 'Admin'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 3 then 'Empresa'
											END AS TIPO,				
											PAG04_TRANSFERENCIAS.PAG04_VLR,
						  				   CB02_CLIENTE.CB02_NOME AS NOME,
											DATE_FORMAT(CB16_PEDIDO.CB16_DT, '%d/%m/%Y %H:%i:%s') AS CB16_DT ,
											DATE_FORMAT(PAG04_TRANSFERENCIAS.PAG04_DT_PREV, '%d/%m/%Y') AS PAG04_DT_PREV
										FROM
											CB16_PEDIDO
										JOIN user on user.id = CB16_PEDIDO.CB16_USER_ID
										JOIN CB02_CLIENTE ON CB02_CLIENTE.CB02_ID = user.id_cliente
										JOIN PAG04_TRANSFERENCIAS ON PAG04_TRANSFERENCIAS.PAG04_ID_PEDIDO = CB16_PEDIDO.CB16_ID
										
										WHERE 
											PAG04_TRANSFERENCIAS.PAG04_TIPO = 1
											AND CB16_PEDIDO.CB16_STATUS = 50
											AND PAG04_DT_PREV < CURDATE()
						
							UNION
							SELECT
											CB16_PEDIDO.CB16_COD_TRANSACAO,
											CASE 
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 1 then 'Cliente'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 2 then 'Admin'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 3 then 'Empresa'
											END AS TIPO,				
											PAG04_TRANSFERENCIAS.PAG04_VLR,
						  				   CB04_EMPRESA.CB04_NOME AS NOME,
											DATE_FORMAT(CB16_PEDIDO.CB16_DT, '%d/%m/%Y %H:%i:%s') AS CB16_DT ,
											DATE_FORMAT(PAG04_TRANSFERENCIAS.PAG04_DT_PREV, '%d/%m/%Y') AS PAG04_DT_PREV
										FROM
											CB16_PEDIDO
										JOIN user on user.id = CB16_PEDIDO.CB16_USER_ID
										JOIN CB04_EMPRESA ON CB04_EMPRESA.CB04_ID = CB16_PEDIDO.CB16_EMPRESA_ID
										JOIN PAG04_TRANSFERENCIAS ON PAG04_TRANSFERENCIAS.PAG04_ID_PEDIDO = CB16_PEDIDO.CB16_ID
										
										WHERE 
											PAG04_TRANSFERENCIAS.PAG04_TIPO = 3
											AND CB16_PEDIDO.CB16_STATUS = 50
											AND PAG04_DT_PREV < CURDATE()
						
										
							UNION
							SELECT
											CB16_PEDIDO.CB16_COD_TRANSACAO,
											CASE 
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 1 then 'Cliente'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 2 then 'Admin'
											    WHEN PAG04_TRANSFERENCIAS.PAG04_TIPO = 3 then 'Empresa'
											END AS TIPO,				
											PAG04_TRANSFERENCIAS.PAG04_VLR,
						  				   '-' AS NOME,
											DATE_FORMAT(CB16_PEDIDO.CB16_DT, '%d/%m/%Y %H:%i:%s') AS CB16_DT ,
											DATE_FORMAT(PAG04_TRANSFERENCIAS.PAG04_DT_PREV, '%d/%m/%Y') AS PAG04_DT_PREV
										FROM
											CB16_PEDIDO
										JOIN user on user.id = CB16_PEDIDO.CB16_USER_ID
										JOIN CB02_CLIENTE ON CB02_CLIENTE.CB02_ID = user.id_cliente
										JOIN PAG04_TRANSFERENCIAS ON PAG04_TRANSFERENCIAS.PAG04_ID_PEDIDO = CB16_PEDIDO.CB16_ID
										
										WHERE 
											PAG04_TRANSFERENCIAS.PAG04_TIPO = 2
											AND CB16_PEDIDO.CB16_STATUS = :statusPagoTansLiberadas
											AND PAG04_DT_PREV < CURDATE()
						) A
						
						ORDER BY PAG04_VLR DESC

 
					
            ";
		
            $connection = \Yii::$app->db;
            $command = $connection->createCommand($query);
            $command->bindValue(':statusPagoTansLiberadas', \common\models\CB16PEDIDO::status_pago_trans_liberadas);
            $reader = $command->query();
		
            return $reader->readAll();
    }
    
        /**
     * @inheritdoc
     */
    public function gridSettingsVencidas()
    {
    	$al = $this->attributeLabels();
          return [
            ['btnsAvailable' => ['editar', 'excluir']],
            ['sets' => ['title'=>\Yii::t("app",'AÇÕES'), 'width'=>'60' , 'type'=>'img', 'sort'=>'str', 'align'=>'center', 'id' => 'editar', 'id' => 'editar']],       
            ['sets' => ['title'=>'#cspan' ,'width'=>'60', 'type'=>'img', 'sort'=>'str', 'align'=>'center', 'id' => 'excluir']],
            ['sets' => ['title' => $al['CB16_COD_TRANSACAO'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'CB16_COD_TRANSACAO' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['TIPO'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'TIPO' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['NOME'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'NOME' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['CB16_DT'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'CB16_DT' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_PREV'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV' ], 'filter' => ['title'=>'#text_filter']],            				
        ];
    }
    
  /**
    * @inheritdoc
    */
    public function gridQuerySolSaquesClientes()
    {
	    $query =  "
                     SELECT *
					FROM PAG04_TRANSFERENCIAS 
					JOIN user ON user.id = PAG04_TRANSFERENCIAS.PAG04_ID_USER_CONTA_ORIGEM 
					JOIN CB02_CLIENTE ON CB02_CLIENTE.CB02_ID = user.id_cliente
					
					WHERE PAG04_DT_DEP IS NULL AND PAG04_TIPO = 'C2B'
            ";
		
            $connection = \Yii::$app->db;
            $command = $connection->createCommand($query);
            $reader = $command->query();
		
            return $reader->readAll();
    }
    
        /**
     * @inheritdoc
     */
    public function gridSettingsSolSaquesClientes()
    {
    	$al = $this->attributeLabels();
          return [
            ['sets' => ['title' => $al['PAG04_DATA_CRIACAO'], 'width'=>'200', 'type'=>'ro' , 'PAG04_DATA_CRIACAO'  => 'CB16_COD_TRANSACAO' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_PREV'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_ID_PEDIDO'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_ID_PEDIDO' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['CB02_NOME'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'CB02_NOME' ], 'filter' => ['title'=>'#text_filter']], 
           				
        ];
    }
    
    


}
