<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use elisdn\hybrid\AuthRoleModelInterface;

class User extends ActiveRecord implements IdentityInterface
{
	public $password;
	public $new_password;

	const GENDER_MALE = 1;
	const GENDER_FEMALE = 2;

	public static function tableName()
	{
		return 'users';
	}

	public function behaviors()
	{
		return [
			[
				'class' => \voskobovich\linker\LinkerBehavior::className(),
				'relations' => [
					'address_ids' => 'addresses',
				],
			],
		];
	}

	public function rules()
	{
		return [
			[['name', 'surname', 'login', 'gender', 'address_ids'], 'required'],
			['login', 'unique'],
			['login', 'string', 'length' => [4]],
			['password', 'string', 'length' => [6]],
			[['password', 'password_hash', 'auth_key'], 'required', 'on' => 'register'],
			[['new_password'], 'required', 'on' => 'password'],
			[['gender', 'created_at', 'address_id'], 'safe'],
			[['address_ids'], 'each', 'rule' => ['integer']]
		];
	}

	public function attributeLabels()
	{
		return [
			'name' => 'Имя',
			'surname' => 'Фамилия',
			'login' => 'Логин',
			'gender' => 'Пол',
			'password' => 'Пароль',
			'created_at' => 'Дата создания',
			'new_password'=>'Новый пароль',
			'address_ids' => 'Адрес(а)',
		];
	}

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
	    return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
//        return static::findOne(['access_token' => $token]);
          throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
	    return static::findOne(['login' => $username]);
    }

	/**
	 * Finds user by password reset token
	 *
	 * @param string $token password reset token
	 * @return static|null
	 */
	public static function findByPasswordResetToken($token)
	{
		if (!static::isPasswordResetTokenValid($token)) {
			return null;
		}

		return static::findOne([
			'password_reset_token' => $token,
		]);
	}

	/**
	 * Finds out if password reset token is valid
	 *
	 * @param string $token password reset token
	 * @return boolean
	 */
	public static function isPasswordResetTokenValid($token)
	{
		if (empty($token)) {
			return false;
		}
		$expire = Yii::$app->params['user.passwordResetTokenExpire'];
		$parts = explode('_', $token);
		$timestamp = (int)end($parts);
		return $timestamp + $expire >= time();
	}

    /**
     * @inheritdoc
     */
    public function getId()
    {
	    return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
	    return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
	    return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
	    return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->password_hash = Yii::$app->security->generatePasswordHash($password);
	}

	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey()
	{
		$this->auth_key = Yii::$app->security->generateRandomString();
	}

	/**
	 * Generates new password reset token
	 */
	public function generatePasswordResetToken()
	{
		$this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
	}

	/**
	 * Removes password reset token
	 */
	public function removePasswordResetToken()
	{
		$this->password_reset_token = null;
	}

	/**
	 * @return array
	 */
	public static function getGenderList()
	{
		return [
			self::GENDER_MALE => 'Мужской',
			self::GENDER_FEMALE => 'Женский',
		];
	}

	public function getAddresses()
	{
		return $this->hasMany(Address::className(),['id' => 'address_id'])
		            ->viaTable('user_has_address', ['user_id' => 'id']);
	}
}
