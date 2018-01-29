<?php
use app\assets\DataTablesAsset;
use yii\grid\GridView;
//DataTablesAsset::register($this);
\p2m\sbAdmin\assets\SBAdmin2Asset::register($this);
$this->title = 'Адреса';

echo GridView::widget([
	'tableOptions' => ['class' => 'table table-responsive table-bordered dataTable'],
	'dataProvider' => $dataProvider,
	'layout' => "{items}",
	'columns' => [
		'postcode',
		[
			'attribute' => 'country_code',
			'value' => function ($data) use ($countryCodesList) {
				return $countryCodesList[$data->country_code];
			},
		],
		'city',
		'street',
		'house_number',
		'apartment_number',
		[
			'header' => 'Действие',
			'class' => 'yii\grid\ActionColumn',
			'template' => '{update} {delete}',
		],
	],
]);



