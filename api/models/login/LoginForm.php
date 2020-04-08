<?php
namespace api\models\login;

use Yii;
use yii\base\Model;

/**
 * Login form
 */



class LoginForm extends Model
{
    public $username;
    public $passwd;
    public $rememberMe = false;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'passwd'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // passwd is validated by validatePassword()
            ['passwd', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->passwd)) {
                $this->addError($attribute, 'Incorrect username or passwd.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return UserModel|null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = UserModel::findSchoolAccount($this->username);
        }

        return $this->_user;
    }

    public function getUserStatus()
    {
        if ($this->_user === null) {
            $this->_user = UserModel::findSchoolAccountStatus($this->username);
        }
        return $this->_user;
    }
}
