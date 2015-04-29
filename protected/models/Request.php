<?php

/**
 * This is the model class for table "notification".
 *
 * The followings are the available columns in table 'notification':
 * @property integer $id
 * @property integer $to_user
 * @property integer $create_user
 * @property integer $create_time
 * @property integer $question_id
 *
 * The followings are the available model relations:
 * @property NotificationContent $content
 */
class Request extends CActiveRecord {

    public $user_name;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Notification the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'request';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('to_user,user_name,create_user,question_id', 'required'),
            array('to_user,create_user,create_time,question_id', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,to_user,create_user,create_time,question_id,delete_flag,answer_flag', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'to_user' => '邀请人',
            'question_id' => '问题',
            'create_user' => '创建人',
            'answer_flag' => '回答状态',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('create_user', $this->create_user);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('to_user', $this->to_user);
        $criteria->compare('question_id', $this->question_id);
        if ($_GET["type"] == "help") {
            $criteria->addCondition('delete_flag!=2');
        } else {
            $criteria->addCondition('delete_flag!=1');
        }
        if (isset($_GET['reply'])) {
            if ($_GET['reply'] == '1') {
                $criteria->addCondition('answer_flag=1');
            } else {
                $criteria->addCondition('answer_flag=0');
            }
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * 发送提醒
     */
    public function afterSave() {
        parent::afterSave();
        $createUserModel = User::model()->findByPk($this->to_user);
        $recvArray = unserialize($createUserModel->recv_option);
        $value = "subscribe_ask_like";
        $inser = false;
        if (($recvArray[$value] == 1) || (($recvArray[$value] == 2) && ($createUserModel->followees != "") && in_array($this->create_user, explode(",", trim($createUserModel->followees, ","))))) {
            $inser = true;
        }
        $notificationContentModel = new NotificationContent;
        $notificationData = array("pk_id" => $this->id, "content" => "邀请你回答问题", "send_time" => $this->create_time, "notification_type" => "createask");
        $notificationContentModel->insertNotificationContent($notificationData, $inser);
    }

}
