<?php

namespace app\modules\transfer;

/**
 * transfer module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\transfer\controllers';
    public $layout = 'app\modules\transfer\views\layouts';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
