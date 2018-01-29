<?php

namespace app\controllers;

use yii;
use app\models\Address;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use app\components\Alert;

/**
 * Class AddressController
 * @package app\controllers
 */
class AddressController extends Controller
{

	/**
	 * @return string
	 */
	public function actionList()
	{

		$query = Address::find()->orderBy('id');

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => false,
			'sort' => false,
		]);
		$countryCodesList = Address::getCountryCodesList();

		return $this->render('list', compact('dataProvider', 'countryCodesList'));
	}


	/**
	 * @param $model
	 *
	 * @return string
	 */
	private function _edit($model)
	{
		if ($model->load($post = Yii::$app->request->post())) {
			if ($model->save())
			{
				Alert::save('success', ' Запись сохранена');
			}
		}

		$countryCodesList = Address::getCountryCodesList();
		return $this->render('form', compact('model', 'countryCodesList'));
	}


	/**
	 * @return string
	 */
	public function actionCreate()
	{
		$model = new Address();
		return $this->_edit($model);
	}


	/**
	 * @param $id
	 *
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionUpdate($id)
	{
		if (!$model = Address::findOne((int)$id))
			throw new NotFoundHttpException();

		return $this->_edit($model);
	}


	/**
	 * @param $id
	 *
	 * @return yii\web\Response
	 * @throws NotFoundHttpException
	 * @throws \Exception
	 * @throws \Throwable
	 */
	public function actionDelete($id)
	{
		if (!$model = Address::findOne((int)$id))
			throw new NotFoundHttpException();

		try {
			if ($model->delete());
		}  catch (Exception $e) {
			$msg = $e->getMessage();
			Alert::save('danger', $msg);
		}
		Alert::save('success', ' Запись удалена');
		return $this->redirect(['/address/list']);
	}
}