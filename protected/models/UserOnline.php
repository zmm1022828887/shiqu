<?php

/**
 * This is the model class for table "user_online".
 *
 * The followings are the available columns in table 'user_online':
 * @property integer $id
 * @property integer $time
 * @property string $sid
 */
class UserOnline extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserOnline the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user_online';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, time, sid', 'required'),
            array('id, time', 'numerical', 'integerOnly' => true),
            array('sid', 'length', 'max' => 32),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, time, sid', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'time' => 'Time',
            'sid' => 'Sid',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('time', $this->time);
        $criteria->compare('sid', $this->sid, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function updateCurrentUserStatus() {
        $userId = Yii::app()->user->id;
        if ($userId) {
            $model = $this->findByPk($userId);
            if (!$model) {
                $model = new UserOnline();
                $model->setAttributes(array('id' => $userId, 'time' => time(), 'sid' => Yii::app()->session->sessionId));
            } else {
                $model = new UserOnline();
                $model->setAttributes(array('id' => $userId, 'time' => time(), 'sid' => Yii::app()->session->sessionId));
            }
            $model->save();
        }
    }

    public function clearOnlineStatus() {
        $reftime = Yii::app()->params['online_ref_time'] ? Yii::app()->params['online_ref_time'] : 120;
        $this->deleteAll('time< ' . (time() - $reftime - 10));
    }

    /**
     * 强制离线
     * @param type $ids
     */
    public function offlineUser($ids) {
        if ($ids == "")
            return false;

        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        $session = Yii::app()->session;
        $session->open();
        $mysid = $session->getSessionID();
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $ids);
        $models = $this->findAll($criteria);
        foreach ($models as $model) {
            $sid = $model['sid'];
            $session->setSessionID($sid);
            $session->destroy();
        }
        $session->setSessionID($mysid);
        $this->deleteAll($criteria);

        return true;
    }

}
