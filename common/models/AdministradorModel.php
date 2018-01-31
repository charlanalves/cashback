<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "PAG04_TRANSFERENCIAS".
 */
class TransferenciasModel extends ActiveRecord
{	

    /**
     * @inheritdoc
     */
    public function rules()
    {
       
    }
	
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
                
        
        ];
    }
    
    /**
    * @inheritdoc
    */
    public function gridQueryFormasPgto()
    {
    	die('a');
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
    public function gridSettingsFormasPgto()
    {
    	$al = $this->attributeLabels();
        return [
            
            ['sets' => ['title' => $al['PAG04_ID_TRANSACAO'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_ID_TRANSACAO' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_COD_TRANS_ADQ'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_COD_TRANS_ADQ' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_TRANS'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_TRANS' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_TRANS_LIQ'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_TRANS_LIQ' ], 'filter' => ['title'=>'#text_filter']], 
            ['sets' => ['title' => $al['PAG04_VLR_EMPRESA'], 'width'=>'200', 'type'=>'ro' , 'id'  => 'PAG04_VLR_EMPRESA' ], 'filter' => ['title'=>'#text_filter']], 
         
                        				
        ];
    }
    

}
