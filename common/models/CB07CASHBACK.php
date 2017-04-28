<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "CB07_CASH_BACK".
 *
 * @property integer $CB07_ID
 * @property integer $CB07_PRODUTO_ID
 * @property integer $CB07_VARIACAO_ID
 * @property integer $CB07_DIA_SEMANA
 * @property string $CB07_PERCENTUAL
 *
 * @property CB05PRODUTO $cB07PRODUTO
 * @property CB06VARIACAO $cB07VARIACAO
 */
class CB07CASHBACK extends \common\models\GlobalModel
{   
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CB07_CASH_BACK';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CB07_PRODUTO_ID', 'CB07_VARIACAO_ID', 'CB07_DIA_SEMANA'], 'integer'],
            [['CB07_PERCENTUAL'], 'required'],
            [['CB07_PERCENTUAL'], 'number'],
            [['CB07_PRODUTO_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB05PRODUTO::className(), 'targetAttribute' => ['CB07_PRODUTO_ID' => 'CB05_ID']],
            [['CB07_VARIACAO_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CB06VARIACAO::className(), 'targetAttribute' => ['CB07_VARIACAO_ID' => 'CB06_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CB07_ID' => Yii::t('app', 'ID'),
            'CB07_PRODUTO_ID' => Yii::t('app', 'Produto'),
            'CB07_VARIACAO_ID' => Yii::t('app', 'Promoção'),
            'CB07_DIA_SEMANA' => Yii::t('app', 'Dia da Semana'),
            'CB07_PERCENTUAL' => Yii::t('app', 'Percentual'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB07PRODUTO()
    {
        return $this->hasOne(CB05PRODUTO::className(), ['CB05_ID' => 'CB07_PRODUTO_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCB07VARIACAO()
    {
        return $this->hasOne(CB06VARIACAO::className(), ['CB06_ID' => 'CB07_VARIACAO_ID']);
    }

    /**
     * @inheritdoc
     * @return CB07CASHBACKQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CB07CASHBACKQuery(get_called_class());
    }

    public static function getCashback($produto)
    {
        $query = "
            SELECT CB05_ID AS PRODUTO_ID,CB06_ID AS VARIACAO_ID,CB05_TITULO AS PRODUTO,CB06_DESCRICAO AS VARIACAO,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 1, CB07_PERCENTUAL, NULL)) AS DIA_SEG,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 2, CB07_PERCENTUAL, NULL)) AS DIA_TER,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 3, CB07_PERCENTUAL, NULL)) AS DIA_QUA,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 4, CB07_PERCENTUAL, NULL)) AS DIA_QUI,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 5, CB07_PERCENTUAL, NULL)) AS DIA_SEX,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 6, CB07_PERCENTUAL, NULL)) AS DIA_SAB,
            GROUP_CONCAT(IF(CB07_DIA_SEMANA = 0, CB07_PERCENTUAL, NULL)) AS DIA_DOM
            FROM CB07_CASH_BACK 
            LEFT JOIN CB05_PRODUTO on (CB05_ID = CB07_PRODUTO_ID AND CB05_ID = :produto)
            LEFT JOIN CB06_VARIACAO on (CB06_ID = CB07_VARIACAO_ID AND CB06_PRODUTO_ID = :produto)
            WHERE CB05_ID IS NOT NULL OR CB06_ID IS NOT NULL
            GROUP BY CB05_ID,CB06_ID";
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($query);
        $command->bindParam(':produto', $produto);
        $reader = $command->query();
        
        return $reader->readAll();
    }
    
    public function saveCashback($data) {
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            
            // verifica se é produto ou variacao
            if (substr($data['PRODUTO_VARIACAO'], 0, 1) == 'P') {
                $data_['CB07_PRODUTO_ID'] = substr($data['PRODUTO_VARIACAO'], 1);
            } else {
                $data_['CB07_VARIACAO_ID'] = $data['PRODUTO_VARIACAO'];
            }

            $this->deleteCashback($data_);
            for ($i = 0; $i <= 6; $i++) {
                $data_['CB07_DIA_SEMANA'] = $i;
                $data_['CB07_PERCENTUAL'] = $data['DIA_' . $i];

                $CB07CASHBACK = new CB07CASHBACK();
                $CB07CASHBACK->setAttributes($data_);
                $CB07CASHBACK->save();
            }

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
    
    public function deleteCashback($data) {
        self::deleteAll($data);
    }
}
