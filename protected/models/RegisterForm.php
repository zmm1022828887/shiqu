<?php

/**
 * RegisterForm class.
 * RegisterForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RegisterForm extends CFormModel {

    public $username;
    public $password;
    public $repasword;
    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            // username and password are required
            array('username, password,repasword', 'required'),
            array('username','checkUsername'),
             array('repasword','checkRepassword'),
            array('username, password,repasword', 'length', 'min' => 5),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'username' => '用户名',
            'password' => '密码',
            'repasword' => '确认密码',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function checkUsername($attribute, $params) {
        $record = User::model()->count('user_name=:user_name', array(':user_name' => $this->username));
        if ($record > 0)
            $this->addError('username', '该账号已经存在');
    }
    public function checkRepassword($attribute, $params) {
        $password = $this->password;
        $repasword = $this->repasword;
        if (crypt($password, $repasword) !== crypt($repasword, $password))
            $this->addError('repasword', '确认密码和密码不相同');
    }
}
