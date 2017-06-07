<?php

namespace common\models;

use Yii;
use common\models\base\Menu as BaseMenu;

/**
 * This is the model class for table "menu".
 */
class Menu extends BaseMenu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['name'], 'required'],
            [['parent', 'order'], 'integer'],
            [['data'], 'string'],
            [['name'], 'string', 'max' => 128],
            [['route'], 'string', 'max' => 255],
            
            
        ]);
    }
	
}
