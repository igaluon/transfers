<?php

namespace tests\unit\fixtures;

use yii\test\ActiveFixture;

class BalanceFixture extends ActiveFixture
{
    public $modelClass = 'app\modules\transfer\models\User';
    public $dataFile = 'tests/unit/fixtures/data/user.php';
}