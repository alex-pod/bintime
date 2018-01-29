<?php

use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

use p2m\widgets\MetisNav;
use p2m\helpers\FA;

?>
<section class="navbar-default sidebar" role="navigation">
	<div class="sidebar-nav navbar-collapse">
		<ul class="nav" id="side-menu">
            <li><a href="#"><?= FA::fw('user') ?> Пользователи</a>
	            <?= MetisNav::widget([
		            'encodeLabels' => false,
		            'options' => ['class' => 'nav nav-second-level'],
		            'items' => [
			            ['label' => 'Просмотр', 'url' => ['user/list']],
			            ['label' => 'Добавление', 'url' => ['user/create']],
		            ],
	            ]) ?></li><!-- Users -->
			<li>
				<a href="#"><?= FA::fw('building') ?> Адреса</a>
				<?= MetisNav::widget([
					'encodeLabels' => false,
					'options' => ['class' => 'nav nav-second-level'],
					'items' => [
						['label' => 'Просмотр', 'url' => ['address/list']],
						['label' => 'Добавление', 'url' => ['address/create']],
					],
				//array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				]) ?>
			</li><!-- Charts -->
		</ul>
	</div>
</section>
