<?php
namespace tests\unit\fixtures;

use yii\test\ActiveFixture;

/**
 * User fixture
 */
class TransferFixture extends ActiveFixture
{
    public $modelClass = 'app\modules\transfer\models\User';
    public $dataFile = 'tests/unit/fixtures/data/transfer.php';
}
