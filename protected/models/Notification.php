<?php

/**
 * This is the model class for table "notification".
 *
 * The followings are the available columns in table 'notification':
 * @property integer $id
 * @property integer $to_id
 * @property integer $remind_flag
 * @property integer $delete_flag
 * @property integer $content_id
 * @property integer $remind_time
 *
 * The followings are the available model relations:
 * @property NotificationContent $content
 */
class Notification extends CActiveRecord {

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
        return 'notification';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('to_id, remind_flag, delete_flag, content_id, remind_time', 'required'),
            array('to_id, remind_flag, delete_flag, content_id, remind_time', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, to_id, remind_flag, delete_flag, content_id, remind_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'content' => array(self::BELONGS_TO, 'NotificationContent', 'content_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'to_id' => 'To',
            'remind_flag' => '阅读状态',
            'delete_flag' => '阅读删除',
            'content_id' => 'Content',
            'remind_time' => '接受时间',
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
        $criteria->compare('to_id', $this->to_id);
        $criteria->compare('remind_flag', $this->remind_flag);
        $criteria->compare('delete_flag', $this->delete_flag);
        $criteria->compare('content_id', $this->content_id);
        $criteria->compare('remind_time', $this->remind_time);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * 获取JSON Portal数据
     */
    public static function getJSONData($type = "comment", $read_flag = true, $json = true, $action = false) {
        $criteria = new CDbCriteria();
        $criteria->order = 'remind_time desc';
        if(!$action || (!isset($_GET["action"]) || ($_GET["action"]=="inbox"))){
        $criteria->condition = '(to_id = :to_id and remind_flag != :to_remind_flag and delete_flag != :to_delete_flag)';
        $criteria->params = array(
            ':to_id' => Yii::app()->user->id,
            ':to_remind_flag' => $read_flag ? 1 : 2,
            ':to_delete_flag' => 1
        );
        }else{
          $criteria->condition = '(to_id  = :to_id and remind_flag != :to_remind_flag and delete_flag != :to_delete_flag)';
          $criteria->params = array(
            ':to_id' => Yii::app()->user->id,
            ':to_remind_flag' => $read_flag ? 1 : 2,
            ':to_delete_flag' => 2
        );
          $criteria->addInCondition("content_id",NotificationContent::model()->getIdArray());
        }
        $models = self::model()->findAll($criteria);
        $data = array();
        $commentData = array();
        $reportData = array();
        $attentionData = array();
        foreach ($models as $model) {
            $contentModel = NotificationContent::model()->findByPk($model->content_id);
            if (in_array($contentModel->notification_type, array("attention", "report", "reportanswer", "reportquestion", "reportarticle"))) {
               if ($contentModel->notification_type == "report") {
                    $userID = $contentModel->pk_id;
                    $content = $contentModel->content;
                    $userString = User::getNameById($userID);
                } else if ($contentModel->notification_type == "reportanswer") {
                    $anwerModel = Answer::model()->findByPk($contentModel->pk_id);
                    $userID = $anwerModel->create_user;
                    $userString = User::getNameById($userID) . " 的回答";
                    $content = $contentModel->content;
                } else if ($contentModel->notification_type == "reportquestion") {
                    $questionModel = Question::model()->findByPk($contentModel->pk_id);
                    $userID = $questionModel->create_user;
                    $userString = User::getNameById($userID) . " 的问题";
                    $content = $questionModel->title . "(" . $contentModel->content . ")";
                } else if ($contentModel->notification_type == "reportarticle") {
                    $articleModel = Article::model()->findByPk($contentModel->pk_id);
                    $userID = $articleModel->create_user;
                    $userString = User::getNameById($userID) . " 的文章";
                    $content = $articleModel->subject . "(" . $contentModel->content . ")";
                }
                $dataArray = array(
                    'id' => $model->id,
                    'type' => $contentModel->notification_type,
                    'content' => $content,
                    'remindFlag' => $model->remind_flag,
                    'createUserId' => $contentModel->from_id,
                    'reportUser' => $userString,
                    'createUser' => User::getNameById($contentModel->from_id),
                    'reportUserId' => $userID,
                    'createTime' => Comment::model()->timeintval($contentModel->send_time),
                    'avatarSrc' => Yii::app()->controller->createUrl("getimage",array("id"=>$contentModel->from_id,"type"=>"avatar")),
                    'reportAvatar' => Yii::app()->controller->createUrl("getimage",array("id"=>$userID,"type"=>"avatar")),
                    'dataUrl' => Yii::app()->controller->createUrl("userinfo", array("user_id" => $contentModel->from_id, "pk_id" => $model->id)),
                    'reportUrl' => $contentModel->notification_type != "attention" ? Yii::app()->controller->createUrl("viewnotify", array("id" => $model->id)) : Yii::app()->controller->createUrl("userinfo", array("user_id" => $contentModel->pk_id, "pk_id" => $model->id)),
                );
                if (($type == "attention") && ($contentModel->notification_type == "attention")) {
                    $attentionData[] = $dataArray;
                } else if (($type == "report") && (in_array($contentModel->notification_type,array("report", "reportanswer", "reportquestion", "reportarticle")))) {
                    $reportData[] = $dataArray;
                } else {
                    $data[] = $dataArray;
                }
            } else {
                $pk_id = Comment::model()->findByPk($contentModel->pk_id)->pk_id;
                if ($contentModel->notification_type == "answer") {
                    $dataUrl = Yii::app()->controller->createUrl("answer", array("id" => $pk_id, "pk_id" => $model->id));
                } else if ($contentModel->notification_type == "article") {
                    $dataUrl = Yii::app()->controller->createUrl("article", array("id" => $pk_id, "pk_id" => $model->id));
                } else if ($contentModel->notification_type == "question") {
                    $content = Answer::model()->findByPk($contentModel->pk_id)->content;
                    $dataUrl = Yii::app()->controller->createUrl("answer", array("id" => $contentModel->pk_id, "pk_id" => $model->id));
                }  else if ($contentModel->notification_type == "comment") {
                    $commentModel = Comment::model()->findByPk($contentModel->pk_id);
                    $dataUrl = Yii::app()->controller->createUrl($commentModel->model, array("id" => $commentModel->pk_id, "pk_id" => $model->id));
                } else if ($contentModel->notification_type == "topic") {
                    $dataUrl = Yii::app()->controller->createUrl("question", array("id" => $contentModel->pk_id, "pk_id" => $model->id));
                } else if ($contentModel->notification_type == "createask") {
                    $questionModel = Question::model()->findByPk(Request::model()->findByPk($contentModel->pk_id)->question_id);
                    $dataUrl = Yii::app()->controller->createUrl("question", array("id" => $contentModel->pk_id, "pk_id" => $questionModel->id));
                }
                $commentData[] = array(
                    'id' => $model->id,
                    'desc' => ($contentModel->notification_type == "question") ? ((strlen(strip_tags($content))>100) ? mb_strcut(strip_tags($content), 0, 100, 'utf-8')."..." : $content) : ($contentModel->notification_type == "topic" ? Question::model()->findByPk($contentModel->pk_id)->title : Comment::model()->findByPk($contentModel->pk_id)->content),
                    'content' => $contentModel->content,
                    'remindFlag' => $model->remind_flag,
                    'reportUserId' => $model->to_id,
                    'createUserId' =>  $contentModel->from_id,
                    'createUser' => User::getNameById($contentModel->from_id),
                    'reportUser' => User::getNameById($model->to_id),
                    'createTime' => Comment::model()->timeintval($contentModel->send_time),
                    'avatarSrc' => Yii::app()->controller->createUrl("getimage",array("id"=>$contentModel->from_id,"type"=>"avatar")),
                    'dataUrl' => $dataUrl
                );
            }
        };
        if((Yii::app()->user->name !="admin") && (!Yii::app()->user->isGuest)){
              $criteria = new CDbCriteria();
              $criteria->order = 'send_time desc';
              $criteria->addCondition("notification_type='report'");
              $criteria->addCondition("from_id=".Yii::app()->user->id);
              $notificationModel = NotificationContent::model()->findAll($criteria);
              foreach ($notificationModel as $notification) {
                 $model = self::model()->find("content_id=".$notification->id);
                 if($model->delete_flag!="2"){
                $reportData[] = array(
                    'id' => $model->id,
                    'type' => $notification->notification_type,
                    'content' => $notification->content,
                    'remindFlag' => $model->remind_flag,
                    'createUserId' => $notification->from_id,
                    'reportUser' => User::getNameById($notification->pk_id),
                    'createUser' => User::getNameById($notification->from_id),
                    'reportUserId' => $notification->pk_id,
                    'createTime' => Comment::model()->timeintval($notification->send_time),
                    'avatarSrc' => User::getAvatarById($notification->from_id),
                    'reportAvatar' => User::getAvatarById($notification->pk_id),
                    'dataUrl' => Yii::app()->controller->createUrl("userinfo", array("user_id" => $notification->from_id, "pk_id" => $model->id)),
                    'reportUrl' => Yii::app()->controller->createUrl("userinfo", array("user_id" => $notification->pk_id, "pk_id" => $model->id)),
                );
                 }
             }
        }
        if ($type == "comment") {
            $returnData = $json ? CJSON::encode($commentData) : $commentData;
        } else if ($type == "report") {
            $returnData = $json ? CJSON::encode($reportData) : $reportData;
        } else if ($type == "attention") {
            $returnData = $json ? CJSON::encode($attentionData) : $attentionData;
        } else {
            $returnData = $json ? CJSON::encode($data) : $data;
        }
        return $returnData;
    }

    public function getUnreadMessage() {
        $count = self::model()->count('to_id = :to_id and remind_flag = 0 and delete_flag = 0', array(':to_id' => Yii::app()->user->id));
        return $count;
    }

    /**
     * 获取JSON Portal数据
     */
    public static function getCommentJSONData($type = "") {
        $criteria = new CDbCriteria();
        $criteria->order = 'remind_time desc';
        $criteria->condition = '(to_id = :to_id and remind_flag != :to_remind_flag and delete_flag != :to_delete_flag)';
        $criteria->params = array(
            ':to_id' => Yii::app()->user->id,
            ':to_remind_flag' => $type == "" ? 1 : 2,
            ':to_delete_flag' => 1
        );
        $models = self::model()->findAll($criteria);
        $data = array();
        foreach ($models as $model) {
            $contentModel = NotificationContent::model()->findByPk($model->content_id);
            if ($contentModel->notification_type != "attention" && $contentModel->notification_type != "report") {
                $pk_id = Comment::model()->findByPk($contentModel->pk_id)->pk_id;
                if ($contentModel->notification_type == "answer") {
                    $dataUrl = Yii::app()->controller->createUrl("diary", array("action" => "view", "id" => $pk_id, "pk_id" => $model->id));
                } else if ($contentModel->notification_type == "topic") {
                    $dataUrl = Yii::app()->controller->createUrl("topic", array("id" => $pk_id, "pk_id" => $model->id));
                } else if ($contentModel->notification_type == "picture") {
                    $dataUrl = Yii::app()->controller->createUrl("picturewall", array("type" => "sharepicture", "id" => $pk_id, "pk_id" => $model->id));
                } else if ($contentModel->notification_type == "recent") {
                    $user_id = Recent::model()->findByPk($pk_id)->create_user;
                    $dataUrl = Yii::app()->controller->createUrl("recent", array("type" => "other", "action" => "view", "id" => $pk_id, "user_id" => $user_id, "pk_id" => $model->id));
                } else if ($contentModel->notification_type == "timeline") {
                    $dataUrl = Yii::app()->controller->createUrl("timeline", array("type" => "action", "action" => "view", "id" => $pk_id, "pk_id" => $model->id));
                }
                $data[] = array(
                    'id' => $model->id,
                    'desc' => Comment::model()->findByPk($contentModel->pk_id)->content,
                    'content' => $contentModel->content,
                    'remindFlag' => $model->remind_flag,
                    'createUserId' => $contentModel->from_id,
                    'createUser' => User::getNameById($contentModel->from_id),
                    'createTime' => Comment::model()->timeintval($contentModel->send_time),
                    'avatarSrc' => User::getAvatarById($contentModel->from_id),
                    'dataUrl' => $dataUrl
                );
            }
        }
        return $type == "" ? CJSON::encode($data) : $data;
    }
      /**
     * 更新user表 
     */
    public function afterDelete() {
        parent::afterDelete();
        $count = self::model()->count("content_id=".$this->content_id);
        if (($count == 0)) {
           NotificationContent::model()->findByPk($this->content_id)->delete();
        }
    }

}
