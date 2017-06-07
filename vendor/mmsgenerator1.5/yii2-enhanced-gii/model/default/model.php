<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator mmsgenerator15\enhancedgii\crud\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->nsModel ?>\base;

use Yii;

/*** 
* Essa classe será útil quando eventualmente acontecer uma alteração na tabela 
* da mesma, pois será possivel atualizar as rules e relations através 
* do gerador de CRUD.  
*
* NOTA:
* Gentileza não alterar as funções dessa classe, 
* pois, após regera-la pelo gerador de CRUD todos os métodos inseridos 
* manualmente serão substituidos pelas funções padrões
*
* Para modificações, sobrescreva o método desejado no modelo específico.
* 
*
<?php foreach ($generator->tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
<?php if(!in_array($name, $generator->skippedRelations)): ?>
 * @property <?= '\\' . $generator->nsModel . '\\' . $relation[$generator::REL_CLASS] . ($relation[$generator::REL_IS_MULTIPLE] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends \app\models\GlobalModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . "\n        " ?>];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($generator->tableName) ?>';
    }
    
 	public static function colFlagAtivo()
    {
        <?php 
            $prefixTable = $generator->generateTableName($generator->tableName);
            $prefixTable = explode('_', $prefixTable)[0];
            $colFlagAtivo =   $prefixTable . '_FLG_ATIVO';
        ?>
        return '<?= $colFlagAtivo ?>';
    }
    
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    
<?php if (!empty($relations)): ?>   
 <?php foreach ($relations as $name => $relation): ?>
    <?php if(!in_array($name, $generator->skippedRelations)): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= ucfirst($name) ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
    <?php endif; ?>
 <?php endforeach; ?>
 <?php endif; ?>  

}
