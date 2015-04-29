<?php

/**
 * This is the model class for table "notification_content".
 *
 * The followings are the available columns in table 'notification_content':
 * @property integer $id
 * @property integer $from_id
 * @property string $notification_type
 * @property string $content
 * @property integer $send_time
 * @property integer $pk_id
 *
 * The followings are the available model relations:
 * @property Notification[] $notifications
 * @property SysRemind $notificationType
 */
class NotificationContent extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return NotificationContent the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'notification_content';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('from_id, notification_type, content, send_time, pk_id', 'required'),
            array('from_id, send_time, pk_id', 'numerical', 'integerOnly' => true),
            array('notification_type', 'length', 'max' => 64),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, from_id, notification_type, content, send_time, pk_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'notifications' => array(self::HAS_MANY, 'Notification', 'content_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'from_id' => '来自于',
            'notification_type' => '类型',
            'content' => '描述',
            'send_time' => '发送时间',
            'pk_id' => 'Pk',
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
        $criteria->compare('from_id', $this->from_id);
        $criteria->compare('notification_type', $this->notification_type, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('send_time', $this->send_time);
        $criteria->compare('pk_id', $this->pk_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
        public function getIdArray() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $data = array();
        $model = self::model()->findAll("notification_type != 'block' and from_id=".Yii::app()->user->id);
        foreach ($model as $key => $value) {
            $data[] = $value->id;
        }
        return $data;
    }

    public function insertNotificationContent($data,$type) {
        $model = new NotificationContent;
        $model->content = $data['content'];
        $model->send_time = $data['send_time'];
        $model->from_id = Yii::app()->user->id;
        $model->pk_id = $data['pk_id'];
        $model->notification_type = $data['notification_type'];
        if ($model->save()){
            if($type){
                NotificationContent::model()->insertNotification($model->id);
            }
            return true;
        }
    }

    public function insertNotification($id) {
        $notificationContentModel = NotificationContent::model()->findBypk($id);
        $success_count = 0;
        $to_id_arr = array();
        $createUser = $notificationContentModel->from_id;
        if (in_array($notificationContentModel->notification_type,array("report","reportquestion","reportanswer","reportarticle"))) {
            $userModal = User::model()->find("user_name='admin'")->id;
            $to_id_arr[] = $userModal;
        } else if($notificationContentModel->notification_type == "attention"){
            $userModel = unserialize(User::model()->findByPk($notificationContentModel->pk_id)->recv_option);
            if($userModel['subscribe_member_follow']==1){
               $userId = $notificationContentModel->pk_id;
               $to_id_arr[] = $userId;
            }
        }else if($notificationContentModel->notification_type == "answer"){
           $to_id_arr[] = Answer::model()->findByPk(Comment::model()->findByPk($notificationContentModel->pk_id)->pk_id)->create_user;
        }else if($notificationContentModel->notification_type == "article"){
           $to_id_arr[] = Article::model()->findByPk(Comment::model()->findByPk($notificationContentModel->pk_id)->pk_id)->create_user;
        }else if($notificationContentModel->notification_type == "question"){
           $to_id_arr[] = Question::model()->findByPk(Answer::model()->findByPk($notificationContentModel->pk_id)->question_id)->create_user;
        }else if($notificationContentModel->notification_type == "comment"){
           $to_id_arr[] = Comment::model()->findByPk(Comment::model()->findByPk($notificationContentModel->pk_id)->parent_id)->user_id;
        }
        foreach (array_unique($to_id_arr) as $uid) {
            $model = new Notification;
            $model->to_id = $uid;
            $model->content_id = $notificationContentModel->id;
            $model->remind_time = $notificationContentModel->send_time;
            $model->delete_flag = 0;
            $model->remind_flag = 0;
            if ($model->save())
                $success_count++;
        }

        if (count($to_id_arr) == $success_count)
            return true;
        else
            return false;
    }
    public function afterDelete() {
        parent::afterDelete();
        $criteria = new CDbCriteria;
        $criteria->addCondition("content_id=" . $this->id);
        Notification::model()->deleteAll($criteria);
    }
}
