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
	        'VLR_TOTAL' => \Yii::t('app','Vlr Total Pedidos (R$)'),
        
        	
        ];
    }
    
    



    /**
    * @inheritdoc
    */
    public function gridQueryMain()
    {
	    $query =  "
                        SELECT * FROM PAG04_TRANSFERENCIAS
                        WHERE 
                            PAG04_TRANSFERENCIAS.PAG04_DT_DEP_CONTA_BANC_MASTER IS NOT NULL 
                            AND PAG04_TRANSFERENCIAS.PAG04_DT_DEP_CONTA_VIRTUAL_MASTER IS NULL
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
            ['btnsAvailable' => ['editar', 'excluir']],
            ['sets' => ['title'=>\Yii::t("app",'AÇÕES'), 'width'=>'60' , 'type'=>'img', 'sort'=>'str', 'align'=>'center', 'id' => 'editar', 'id' => 'editar']],        
            ['sets' => ['title'=>'#cspan' ,'width'=>'60', 'type'=>'img', 'sort'=>'str', 'align'=>'center', 'id' => 'excluir']],
            ['sets' => ['title' => $al['PAG04_ID_TRANSACAO'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_ID_TRANSACAO' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_COD_TRANS_ADQ'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_COD_TRANS_ADQ' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_TRANS'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_TRANS' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_TRANS_LIQ'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_TRANS_LIQ' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_EMPRESA'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_EMPRESA' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_CLIENTE'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_CLIENTE' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_ADMIN'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_ADMIN' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_PREV_DEP_CONTA_BANC_MASTER'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV_DEP_CONTA_BANC_MASTER' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_DEP_CONTA_BANC_MASTER'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_DEP_CONTA_BANC_MASTER' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_PREV_DEP_CONTA_VIRTUAL_MASTER'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV_DEP_CONTA_VIRTUAL_MASTER' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_DEP_CONTA_VIRTUAL_MASTER'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_DEP_CONTA_VIRTUAL_MASTER' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_PREV_DEP_SUBCONTA_VIRTUAL'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV_DEP_SUBCONTA_VIRTUAL' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_DEP_SUBCONTA_VIRTUAL'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_DEP_SUBCONTA_VIRTUAL' ], 'filter' => ['title'=>'#text_filter']], 
                        				
        ];
    }
    
    
    public function gridQueryAgendadas()
    {
    
	    $query =  "
                        SELECT
							CB04_EMPRESA.CB04_ID AS ID,
							CB04_EMPRESA.CB04_ID,
							CB04_EMPRESA.CB04_NOME,
							SUM(CB16_PEDIDO.CB16_VALOR) AS VLR_TOTAL
						FROM CB04_EMPRESA
						JOIN CB16_PEDIDO ON CB16_PEDIDO.CB16_EMPRESA_ID = CB04_EMPRESA.CB04_ID
						JOIN PAG04_TRANSFERENCIAS ON PAG04_TRANSFERENCIAS.PAG04_ID_PEDIDO = CB16_PEDIDO.CB16_ID
						WHERE PAG04_TRANSFERENCIAS.PAG04_DT_PREV IS NOT NULL 
						AND PAG04_TRANSFERENCIAS.PAG04_DT_DEP IS NULL
						GROUP BY CB04_EMPRESA.CB04_NOME
	    
            ";
		
            $connection = \Yii::$app->db;
            $command = $connection->createCommand($query);
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
            ['sets' => ['title'=>\Yii::t("app",'AÇÕES'), 'width'=>'60' , 'type'=>'img', 'sort'=>'str', 'align'=>'center', 'id' => 'editar', 'id' => 'editar']],
            ['sets' => ['title' => $al['CB04_ID'], 'width'=>'100', 'type'=>'ro' , 'id'  => 'CB16_ID' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['CB04_NOME'], 'width'=>'*', 'type'=>'ro' , 'id'  => 'CB04_NOME' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['VLR_TOTAL'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'VLR_TOTAL' ], 'filter' => ['title'=>'#text_filter']],             				
        ];
    
    }
    public function gridQueryVencerHoje()
    {
	    $query =  "
                        SELECT * FROM PAG04_TRANSFERENCIAS
                        WHERE 
                        PAG04_TRANSFERENCIAS.PAG04_DT_DEP_CONTA_VIRTUAL_MASTER = CURDATE()
            ";
		
            $connection = \Yii::$app->db;
            $command = $connection->createCommand($query);
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
            ['sets' => ['title'=>'#cspan' ,'width'=>'60', 'type'=>'img', 'sort'=>'str', 'align'=>'center', 'id' => 'excluir']],
            ['sets' => ['title' => $al['PAG04_ID_TRANSACAO'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_ID_TRANSACAO' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_COD_TRANS_ADQ'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_COD_TRANS_ADQ' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_TRANS'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_TRANS' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_TRANS_LIQ'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_TRANS_LIQ' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_EMPRESA'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_EMPRESA' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_CLIENTE'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_CLIENTE' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_ADMIN'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_ADMIN' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_PREV_DEP_CONTA_BANC_MASTER'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV_DEP_CONTA_BANC_MASTER' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_DEP_CONTA_BANC_MASTER'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_DEP_CONTA_BANC_MASTER' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_PREV_DEP_CONTA_VIRTUAL_MASTER'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV_DEP_CONTA_VIRTUAL_MASTER' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_DEP_CONTA_VIRTUAL_MASTER'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_DEP_CONTA_VIRTUAL_MASTER' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_PREV_DEP_SUBCONTA_VIRTUAL'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV_DEP_SUBCONTA_VIRTUAL' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_DEP_SUBCONTA_VIRTUAL'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_DEP_SUBCONTA_VIRTUAL' ], 'filter' => ['title'=>'#text_filter']], 
                        				
        ];
    }
    /**
    * @inheritdoc
    */
    public function gridQueryVencer()
    {
	    $query =  "
                        SELECT * FROM PAG04_TRANSFERENCIAS
                        WHERE 
                            PAG04_TRANSFERENCIAS.PAG04_DT_DEP_CONTA_BANC_MASTER IS NOT NULL 
                            AND PAG04_TRANSFERENCIAS.PAG04_DT_DEP_CONTA_VIRTUAL_MASTER IS NULL
            ";
		
            $connection = \Yii::$app->db;
            $command = $connection->createCommand($query);
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
            ['sets' => ['title' => $al['PAG04_ID_TRANSACAO'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_ID_TRANSACAO' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_COD_TRANS_ADQ'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_COD_TRANS_ADQ' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_TRANS'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_TRANS' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_TRANS_LIQ'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_TRANS_LIQ' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_EMPRESA'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_EMPRESA' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_CLIENTE'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_CLIENTE' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_ADMIN'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_ADMIN' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_PREV_DEP_CONTA_BANC_MASTER'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV_DEP_CONTA_BANC_MASTER' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_DEP_CONTA_BANC_MASTER'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_DEP_CONTA_BANC_MASTER' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_PREV_DEP_CONTA_VIRTUAL_MASTER'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV_DEP_CONTA_VIRTUAL_MASTER' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_DEP_CONTA_VIRTUAL_MASTER'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_DEP_CONTA_VIRTUAL_MASTER' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_PREV_DEP_SUBCONTA_VIRTUAL'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV_DEP_SUBCONTA_VIRTUAL' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_DEP_SUBCONTA_VIRTUAL'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_DEP_SUBCONTA_VIRTUAL' ], 'filter' => ['title'=>'#text_filter']], 
                        				
        ];
    }
    /**
    * @inheritdoc
    */
    public function gridQueryVencidas()
    {
	    $query =  "
                        SELECT * FROM PAG04_TRANSFERENCIAS
                        WHERE 
                            PAG04_TRANSFERENCIAS.PAG04_DT_DEP_CONTA_BANC_MASTER IS NOT NULL 
                            AND PAG04_TRANSFERENCIAS.PAG04_DT_DEP_CONTA_VIRTUAL_MASTER IS NULL
            ";
		
            $connection = \Yii::$app->db;
            $command = $connection->createCommand($query);
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
            ['sets' => ['title' => $al['PAG04_ID_TRANSACAO'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_ID_TRANSACAO' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_COD_TRANS_ADQ'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_COD_TRANS_ADQ' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_TRANS'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_TRANS' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_TRANS_LIQ'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_TRANS_LIQ' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_EMPRESA'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_EMPRESA' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_CLIENTE'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_CLIENTE' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_ADMIN'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_ADMIN' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_PREV_DEP_CONTA_BANC_MASTER'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV_DEP_CONTA_BANC_MASTER' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_DEP_CONTA_BANC_MASTER'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_DEP_CONTA_BANC_MASTER' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_PREV_DEP_CONTA_VIRTUAL_MASTER'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV_DEP_CONTA_VIRTUAL_MASTER' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_DEP_CONTA_VIRTUAL_MASTER'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_DEP_CONTA_VIRTUAL_MASTER' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_PREV_DEP_SUBCONTA_VIRTUAL'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_PREV_DEP_SUBCONTA_VIRTUAL' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_DT_DEP_SUBCONTA_VIRTUAL'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DT_DEP_SUBCONTA_VIRTUAL' ], 'filter' => ['title'=>'#text_filter']], 
                        				
        ];
    }


}
