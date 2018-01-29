<?php
namespace app\controllers;

use app\models\Address;
use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\components\Alert;

/**
 * Class UserController
 * @package app\controllers
 */
class UserController extends Controller {

	/**
	 * @return array
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['list', 'create', 'update', 'delete', 'password'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}

	/**
	 * @return string
	 */
	public function actionList()
	{
		$query = User::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 20,
			],
			'sort' => false,
		]);

		$genderList = User::getGenderList();
		$addressList = Address::getList();

		return $this->render('list', compact('dataProvider', 'genderList', 'addressList'));
	}


	/**
	 * @param $model
	 * @param $new
	 *
	 * @return string
	 * @throws \yii\base\Exception
	 */
	private function _edit($model, $new)
	{
		if ($model->load($post=Yii::$app->request->post())) {
			if ($new) {
				$model->password_hash = Yii::$app->security->generatePasswordHash($model->password);
				$model->auth_key = Yii::$app->security->generateRandomString();
			}
			if ($model->save())
			{
				Alert::save('success', ' Запись сохранена');
			}
		}

		$genderList = User::getGenderList();
		$addressList = Address::getList();
		return $this->render('form', compact('model', 'genderList', 'addressList'));
	}


	/**
	 * @return string
	 * @throws \yii\base\Exception
	 */
	public function actionCreate()
	{
		$model = new User();
		$model->scenario = 'register';
		return $this->_edit($model, true);
	}


	/**
	 * @param $id
	 *
	 * @return string
	 * @throws NotFoundHttpException
	 * @throws \yii\base\Exception
	 */
	public function actionUpdate($id)
	{
		if (!$model = User::findOne((int)$id))
			throw new NotFoundHttpException();
		return $this->_edit($model,false);
	}


	/**
	 * @param $id
	 *
	 * @return \yii\web\Response
	 * @throws NotFoundHttpException
	 * @throws \Exception
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function actionDelete($id)
	{
		if (!$model = User::findOne((int)$id))
			throw new NotFoundHttpException();

		try {
			if ($model->delete());
		}  catch (Exception $e) {
			$msg = $e->getMessage();
			Alert::save('danger', $msg);
		}
		Alert::save('success', ' Запись удалена');
		return $this->redirect(['/user/list']);
	}


	/**
	 * @param $id
	 *
	 * @return string|\yii\web\Response
	 * @throws NotFoundHttpException
	 * @throws \yii\base\Exception
	 */
	public function actionPassword($id)
	{
		if(!$model = User::findOne((int)$id))
			throw new NotFoundHttpException();

		$model->scenario = 'password';
		if ($model->load(Yii::$app->request->post())) {
			if (!empty($model->new_password)) {
				$model->password_hash=Yii::$app->security->generatePasswordHash($model->new_password);
				$model->auth_key=Yii::$app->security->generateRandomString();
			}
			if ($model->save())
				Alert::save('success','Пароль изменён');
			return $this->redirect(['/user/list']);
		}
		return $this->render('password', [
			'model' => $model,
		]);
	}
}