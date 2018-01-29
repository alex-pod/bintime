<?php
namespace app\models;

use app\components\MyArrayHelper;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
\p2m\sbAdmin\assets\SBAdmin2Asset::register($this);

$this->title = $model->isNewRecord ? 'Добавить пользователя' : 'Редактирование пользователя';

?>
<div class="well col-xs-12 col-sm-12 col-md-9 col-lg-6">
	<?php
	$form = ActiveForm::begin();
	?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<?php
			echo $form->field($model, 'login');
			if ($model->isNewRecord) {
				echo $form->field($model, 'password')->passwordInput();
			}
			echo $form->field($model, 'name');
			echo $form->field($model, 'surname');
			echo $form->field($model, 'gender')->dropDownList($genderList, ['prompt' => '-- Пол']);
			echo $form->field($model, 'address_ids')
                      ->dropDownList(MyArrayHelper::map($addressList, 'id',
	                      ["postcode", "country_code", "city", "street", "house_number", "apartment_number"],
                          ', '), ['multiple' => true]);
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
