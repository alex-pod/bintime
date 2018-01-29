<?php

use app\components\MyArrayHelper;
use app\models\Address;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
\p2m\sbAdmin\assets\SBAdmin2Asset::register($this);
$this->title = 'Все пользователи';

echo GridView::widget([
	'tableOptions' => ['class' => 'table table-responsive table-bordered'],
	'dataProvider' => $dataProvider,
	'layout' => "{items}\n{summary}\n{pager}",
    'columns' => [
        'name',
        'surname',
        'login',
	    [
		    'attribute' => 'gender',
		    'value'     => function ( $data ) use ( $genderList ) {
			    return $genderList[ $data->gender ];
		    },
	    ],
	    [
	    	'attribute' => 'address_ids',
		    'value' => function ($data) use ($addressList) {
				$addressOutput = '';
				foreach ($data->address_ids as $address) {
					$addressOutput .= rtrim(MyArrayHelper::map($addressList, 'id',
							["postcode", "country_code", "city", "street", "house_number", "apartment_number"],
							', ')[$address], ' ,').'<br>';
				}
				return $addressOutput;
		    },
		    'format' => 'raw',
	    ],
        'created_at',
	    [
		    'class' => 'yii\grid\ActionColumn',
		    'template' => '{password} {update} {delete}',
		    'buttons' => [
			    'password' => function ($url, $model) {
				    return Html::a(
					    '<i class="glyphicon glyphicon-lock"></i>',
					    ['user/password','id' => $model->id],
					    [
						    'title' => 'Сменить пароль',
					    ]
				    );
			    },
		    ],
	    ],
    ],
]);



