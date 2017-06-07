<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "PAG04_TRANSFERENCIAS".
 */
class PAG04_TRANSFERENCIAS extends \common\models\GlobalModel
{	
    
        
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PAG04_DATA_CRIACAO', 'PAG04_ID_TRANSACAO', 'PAG04_VLR', 'PAG04_DT_PREV', 'PAG04_DT_DEP', 'PAG04_TIPO'], 'required'],
            [['PAG04_DATA_CRIACAO', 'PAG04_DT_PREV', 'PAG04_DT_DEP'], 'safe'],
            [['PAG04_ID_TRANSACAO', 'PAG04_ID_USER', 'PAG04_TIPO'], 'integer'],
            [['PAG04_VLR'], 'number'],
            [['PAG04_COD_CONTA_ORIGEM', 'PAG04_COD_CONTA_DESTINO'], 'string', 'max' => 200]
        ];
    }
	
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PAG04_ID' => Yii::t('app','Pag04  ID'),
            'PAG04_DATA_CRIACAO' => Yii::t('app','Pag04  Data  Criacao'),
            'PAG04_ID_TRANSACAO' => Yii::t('app','Pag04  Id  Transacao'),
            'PAG04_ID_USER' => Yii::t('app','Pag04  Id  User'),
            'PAG04_COD_CONTA_ORIGEM' => Yii::t('app','Pag04  Cod  Conta  Origem'),
            'PAG04_COD_CONTA_DESTINO' => Yii::t('app','Pag04  Cod  Conta  Destino'),
            'PAG04_VLR' => Yii::t('app','Pag04  Vlr'),
            'PAG04_DT_PREV' => Yii::t('app','Pag04  Dt  Prev'),
            'PAG04_DT_DEP' => Yii::t('app','Pag04  Dt  Dep'),
            'PAG04_TIPO' => Yii::t('app','Pag04  Tipo'),
        ];
    }
    
    



    /**
    * @inheritdoc
    */
    public function gridQueryMain()
    {
	    $query =  "
            	    			    		 SELECT PAG04_TRANSFERENCIAS.PAG04_ID AS ID, PAG04_TRANSFERENCIAS.PAG04_DATA_CRIACAO,PAG04_TRANSFERENCIAS.PAG04_ID_TRANSACAO
	 	    		 FROM PAG04_TRANSFERENCIAS
	 	    		 
	 	    		 
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
            ['sets' => ['title'=>Yii::t("app",'AÇÕES'), 'width'=>'60' , 'type'=>'img', 'sort'=>'str', 'align'=>'center', 'id' => 'editar', 'id' => 'editar']],        
		    ['sets' => ['title'=>'#cspan' ,'width'=>'60', 'type'=>'img', 'sort'=>'str', 'align'=>'center', 'id' => 'excluir']],
                        ['sets' => ['title' => $al['PAG04_DATA_CRIACAO'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_DATA_CRIACAO' ], 'filter' => ['title'=>'#text_filter']], 
                                    ['sets' => ['title' => $al['PAG04_ID_TRANSACAO'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_ID_TRANSACAO' ], 'filter' => ['title'=>'#text_filter']], 
                        				
       		 ];
    }


}
