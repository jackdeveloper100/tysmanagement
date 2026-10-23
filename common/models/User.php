<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $slack_username
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $password_reset_token
 * @property string $auth_key
 * @property string $access_token
 * @property string $phone
 * @property string $cell
 * @property double $hourly_rate
 * @property string $title
 * @property integer $admin
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Task[] $tasks
 * @property Job[] $favorites
 * @property UserDescriptionTemplate[] $userDescriptionTemplates
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    public static function getDataList($withEmpty = false)
    {
        $out = ArrayHelper::map(static::find()->orderBy(['last_name' => SORT_ASC, 'first_name' => SORT_ASC])->all(),
            'id', function ($item) {
                return $item->first_name . ' ' . $item->last_name;
            });
        if (!$withEmpty) {
            return $out;
        }

        return ArrayHelper::merge(['' => ''], $out);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'password', 'auth_key', 'title', 'slack_username'], 'required'],
            [['hourly_rate'], 'number'],
            [['admin', 'status'], 'integer'],
            [['first_name', 'last_name', 'email', 'title', 'slack_username', 'access_token'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 65],
            [['password_reset_token'], 'string', 'max' => 50],
            [['auth_key'], 'string', 'max' => 32],
            [['phone'], 'string', 'max' => 64],
            [['cell'], 'string', 'max' => 10],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['status'], 'default', 'value' => 1],
            [['admin'], 'default', 'value' => false]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'password' => 'Password',
            'password_reset_token' => 'Password Reset Token',
            'auth_key' => 'Auth Key',
            'phone' => 'Phone',
            'cell' => 'Cell',
            'hourly_rate' => 'Hourly Rate',
            'title' => 'Title',
            'admin' => 'Admin',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'slack_username' => 'Slack Username',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(Job::class, ['id' => 'job_id'])->viaTable('favorites', ['user_id' => 'id']);
    }

    public function getUserDescriptionTemplates()
    {
        return $this->hasMany(UserDescriptionTemplate::class, ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->where(['access_token' => $token])->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return User|null
     */
    public static function findByUsername($username)
    {
        return static::find()->where(['username' => $username])->one();
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return User|null
     */
    public static function findByEmail($email)
    {
        return static::find()->where(['email' => $email])->one();
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function beforeValidate()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
        return parent::beforeValidate();
    }

    public function getAvatar($size = 50)
    {
        return "https://www.gravatar.com/avatar/" . md5(strtolower(trim($this->email))) . "?s=" . $size;
    }

    public function getShortName()
    {
        return $this->first_name . ' ' . substr($this->last_name, 0, 1) . '.';
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function updateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
        $this->save(false, ['access_token']);
    }
}