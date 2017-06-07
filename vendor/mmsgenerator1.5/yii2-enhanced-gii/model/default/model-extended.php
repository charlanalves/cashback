<?php
/**
 * This is the template for generating the model class of a specified table.
 *
 * @var yii\web\View $this
 * @var mmsgenerator15\enhancedgii\model\Generator $generator
 * @var string $tableName full table name
 * @var string $className class name
 * @var yii\db\TableSchema $tableSchema
 * @var string[] $labels list of attribute labels (name => label)
 * @var string[] $rules list of validation rules
 * @var array $relations list of relations (name => relation declaration)
 */

echo "<?php\n";
?>

namespace <?= $generator->nsModel ?>;

use Yii;
use \<?= $generator->nsModel ?>\base\<?= $className ?> as Base<?= $className ?>;

/**
 * This is the model class for table "<?= $generator->tableName ?>".
 */
class <?= $className ?> extends Base<?= $className . "\n" ?>
{	
    
    <?php if (!empty($generator->filedPrimaryKey)): ?>
    /**
     * @inheritdoc
    */
    public static function primaryKey()
    {
        return ['<?= $generator->filedPrimaryKey ?>'];
    } 
    
    <?php endif; ?>
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [<?= "\n            " . implode(",\n            ", $rules) . "\n        " ?>]);
    }
	
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
<?php if (!in_array($name, $generator->skippedColumns)): ?>
            <?= "'$name' => Yii::t('app'," . $generator->generateString($label) . "),\n" ?>
<?php endif; ?>
<?php endforeach; ?>
        ];
    }
    
    
<?php if ($generator->generateAttributeHints): ?>
    /**
     * @inheritdoc
     */
     
    public function attributeHints()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
<?php if (!in_array($name, $generator->skippedColumns)): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endif; ?>
<?php endforeach; ?>
        ];
    }
<?php endif; ?>

<?php if ($generator->dhtmlxLayout === 'gridLayout') { ?>

	/**
     * @inheritdoc
     */
    public function gridQueryMain($filters = null)
    {
        $where = "";
    
        if (is_array($filters)) {
            foreach($filters as $k=>$v) {
                if ($k != '<?=$generator->tableSchema->name.'.'.$generator->tableSchema->primaryKey[0] ?>') {
                    if (!empty($v)) {
                        $where .= "AND $k = '$v' ";
                    }
                }
            }
        }
    
        $query =  "
            	<?php $generator->query[0] = str_ireplace('SELECT', 'SELECT '.$generator->tableSchema->name.'.'.$generator->tableSchema->primaryKey[0].' AS ID,', $generator->query[0]) ?>
    			<?php foreach ($generator->query as $stm): ?>
    		 <?php
    		      if (is_array($stm)){
    		          foreach ($stm as $s) {    		             
    		              echo $s . "\n";
    		          }
    		      } else {
    		          echo $stm . "\n";
    		      }
    		 ?>
	 	<?php endforeach; ?>
	 		
            $where
        ";
    
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($query);
        $reader = $command->query();
    
        return $reader->readAll();
    }
    
    public function gridSettingsMain()
    {
    	$al = $this->attributeLabels();
        return [
        	['btnsAvailable' => ['editar']],
        	['sets' => ['title'=>Yii::t("app",'AÇÕES'), 'width'=>'60' , 'type'=>'img', 'sort'=>'str', 'align'=>'center', 'id' => 'editar']],
            <?php foreach ($formSettings['Crud'][0]['typeFields'] as $k => $data): ?>
            <?php if (!in_array($data['column'], $generator->skippedColumns)): ?>
<?php echo "['sets' => ['title' => Yii::t('app','" . $data['label'] . "'), 'width'=>'200', 'type'=>'ro' , 'id'  => '" . $data['name'] . "' ],"?> 
 <?=           "'filter' => ['title'=>'#text_filter']], \n" ?>  
            <?php endif; ?>
            <?php endforeach; ?>	
       		 ];
    }


<?php } else { ?>

<?php if (!empty($generator->query)): ?>

    /**
    * @inheritdoc
    */
    public function gridQueryMain()
    {
	    $query =  "
            	<?php $generator->query[0] = str_ireplace('SELECT', 'SELECT '.$generator->tableSchema->name.'.'.$generator->tableSchema->primaryKey[0].' AS ID,', $generator->query[0]) ?>
    			<?php foreach ($generator->query as $stm): ?>
    		 <?php
    		      if (is_array($stm)){
    		          foreach ($stm as $s) {    		             
    		              echo $s . "\n";
    		          }
    		      } else {
    		          echo $stm . "\n";
    		      }
    		 ?>
	 	<?php endforeach; ?>
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
            <?php foreach ($formSettings['Crud'][0]['typeFields'] as $k => $data): ?>
            <?php if (!in_array($data['column'], $generator->skippedColumns)): ?>
<?php echo "['sets' => ['title' => \$al['" . $data['label'] . "'], 'width'=>'200', 'type'=>'ro' , 'id'  => '" . $data['name'] . "' ], 'filter' => ['title'=>'#text_filter']], \n"?>
            <?php endif; ?>
            <?php endforeach; ?>
				
       		 ];
    }
<?php endif; ?>

<?php } ?>

}
