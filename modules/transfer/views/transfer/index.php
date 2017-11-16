<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */

?>

<div class="users"> <h1>Users</h1></div>

<div class="container">

 <div class="table-responsive">
<table class="table table-striped ">
    <thead>
    <tr>
        <th>Nickname</th>
        <th>Balance</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($model as $value):?>
    <tr>
        <td><?= Html::encode($value->username) ?></td>
        <td><?= $value->balance<0 ? '-' : '' ?>$<?= $value->balance ? abs($value->balance) : 0 ?>
        </td>
    </tr>
    <?php endforeach ?>
    </tbody>
</table>
</div>
</div>
<div style="align-content: center">
<?= LinkPager::widget([
'pagination' => $pages,
]);
?>
</div>