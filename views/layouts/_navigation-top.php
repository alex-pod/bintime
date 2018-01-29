<?php

use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Dropdown;
use yii\helpers\Url;
use p2m\helpers\FA;

/* @var $this \yii\web\View */
/* @var $content string */

$dropDownOptions = ['class' => 'dropdown-menu dropdown-messages'];
$dropDownCaret = FA::i('caret-down');

if (!Yii::$app->user->isGuest) {
	$userMenu = array(
		'label' => FA::i('user')->fixedWidth(),
		'dropDownOptions' => $dropDownOptions,
		'dropDownCaret' => $dropDownCaret,
		'url' => '#',
		'items' => [
			['url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post'], 'label' => FA::i('sign-out')->fixedWidth() . ' Выйти', 'encode' => false],
		],
	);
}
?>
<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
	<?= Html::a(Yii::$app->name, Yii::$app->homeUrl, ['class' => 'navbar-brand']) ?>
</div>

<?php
echo Nav::widget([
	'options' => ['class' => 'nav navbar-top-links navbar-right'],
	'items' => [
		$userMenu,
	],
	'encodeLabels' => false,
]);
?>
