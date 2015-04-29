<?php

/**
 * This is model class for table "sys_log".
 * 
 * The followings are the available columns in table "sys_log":
 * @property integer $id
 * @property integer $user_id
 * @property string $user_name
 * @property integer $log_time
 * @property string $log_ip
 * @property string $message
 *
 * @author fang lei <fl@tongda2000.com>
 */
class SysLog extends CActiveRecord {

    /**
     * Return the static model of the specified AR class.
     * @param string $className active record class name
     * @return SysLog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('user_id, log_time', 'numerical', 'integerOnly' => true),
            array('user_name, log_ip', 'length', 'max' => 32),
            array('message', 'safe'),
            array('user_id, log_time, log_ip, message', 'safe', 'on' => 'search'),
                //array('log_ip', 'match', 'pattern'=>'/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/', 'on'=>'search'),
        );
    }

    /**
     * @return string associated database table name.
     */
    public function tableName() {
        return 'sys_log';
    }

    /**
     * @return array customized attributes label (name=>value)
     */
    public function attributeLabels() {
        return array(
            'user_name' => '用户',
            'log_time' => '时间',
            'log_ip' => 'IP地址',
            'message' => '备注',
        );
    }

    public function search() {
        $criteria = new CDbCriteria();
        return new CActiveDataProvider('SysLog', array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'log_time desc',
            ),
        ));
    }

    public function createLog($user_id) {
        $model = new SysLog();
        $time = time();
        $model->user_id = $user_id;
        $model->user_name = User::getNameById($user_id);
        $model->log_time = $time;
        $model->message = "登陆成功！";
        $model->log_ip = Yii::app()->request->userHostAddress;
        if (SysLog::model()->count("user_id=:user_id and log_time=:log_time", array(":user_id" => $user_id, ":lig_time" => $time)) == 0)
            $model->save();
    }

}

?>