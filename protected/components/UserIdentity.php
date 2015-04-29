<?php
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
		
	
    private $_id;
    public function authenticate()
    {
        $record=User::model()->find('user_name=:username and not_login=0',array(':username'=>$this->username));
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if($record->password!==crypt($this->password,$record->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            $this->_id=$record->id;
            foreach($record as $k => $v) {
                $this->setState($k, $v); 
            }
           //更新user_online表
            if (!($userOnline = UserOnline::model()->findByPk($record->id))) {
                $userOnline = new UserOnline;
            }
            $userOnline->setAttributes(array('id' => $record->id, 'time' => time(), 'sid' => Yii::app()->session->sessionId));
            $userOnline->save();
         //   SysLog::model()->createLog($record->id);
            $this->errorCode=self::ERROR_NONE;
        }
        //var_dump($this->errorCode);
        return !$this->errorCode;
    }
 
    public function getId()
    {
        return $this->_id;
    }
}