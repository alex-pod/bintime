<?php
namespace app\models;

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
\p2m\sbAdmin\assets\SBAdmin2Asset::register($this);

$this->title = $model->isNewRecord ? 'Добавить адрес' : 'Редактирование адреса';
?>
<div class="well col-xs-12 col-sm-12 col-md-7 col-lg-5">
	<?php
	$form = ActiveForm::begin();
	?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<?php
			echo $form->field($model, 'postcode');
			echo $form->field($model, 'country_code')->dropDownList($countryCodesList,
				['prompt' => '-- Выберите страну']);
			echo $form->field($model, 'city');
			echo $form->field($model, 'street');
			echo $form->field($model, 'house_number');
			echo $form->field($model, 'apartment_number');
			echo Html::submitButton('<i class="indicator glyphicon glyphicon-ok"></i>&nbsp;Сохранить', ['class' => 'btn btn-primary']);
			?>
			<?php

			?>
		</div>
		<div class="col-md-6">
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>
