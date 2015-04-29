<?php

/**
 * This is the model class for table "ask_quest".
 *
 * The followings are the available columns in table 'ask_quest':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $question_id
 * @property integer $create_user
 */
class Answer extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AskQuest the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'answer';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content, question_id,is_anonymous', 'required'),
            array('create_user,create_time,question_id', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,create_time,content,create_user, question_id,is_anonymous,hide_reason', 'safe', 'on' => 'search'),
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
            'content' => '描述',
            'create_user' => '回答人',
            'create_time' => '回答时间',
            'question_id' => '问题ID',
            'is_anonymous' => '匿名',
            'hide_reason'=>'折叠原因',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($question = "",$type ="show") {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $quetModel = Question::model()->findByPk($question);
        $answerArray = $quetModel->hide_answer_id== "" ? array() : explode(",", trim($quetModel->hide_answer_id,","));
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('content', $this->content, true);
        if ($question != "")
            $criteria->addCondition('question_id=' . $question);
        
        if($type=="show"){
           $criteria->addNotInCondition('id',$answerArray);
        }else{
           $criteria->addInCondition('id',$answerArray); 
        }
        $criteria->compare('create_user', $this->create_user);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('is_anonymous', $this->is_anonymous,true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * 发送提醒
     */
    public function afterSave() {
        parent::afterSave();
        $createUser = Question::Model()->findByPk($this->question_id)->create_user;
        $createUserModel = User::model()->findByPk($createUser);
        $answerModel = User::model()->findByPk($this->create_user);
        $score = Sys::model()->getvaluesByType("answer_score");
        $type = Sys::model()->getvaluesByType("answer_type");
        $time = strtotime(date("Y-m-d", time()));
        $count = Answer::model()->count("create_user=:create_user and create_time>:create_time",array(":create_user"=> $this->create_user,":create_time"=>$time));
        if ($count == 1) {
            $wealthModel = new Wealth();
            if ($type == "0") {
                $answerModel->wealth = $answerModel->wealth + intval($score);
                $content = "回答成功，奖励" . $score . "个财富值";
                $data = array('content' => $content, 'create_time' => $this->create_time);
                $wealthModel->insertWealth($data);
            }
        }
        if ($type == 1) {
            $answerModel->wealth = $answerModel->wealth - intval($score);
            $content = "回答成功，花费" . $score . "个财富值";
            $data = array('content' => $content, 'create_time' => $this->create_time);
            $wealthModel->insertWealth($data);
        }
        $answerModel->save();
        $recvArray = unserialize($createUserModel->recv_option);
        $value = "subscribe_question_like";
        $inser = false;
        if (($recvArray[$value] == 1) || (($recvArray[$value] == 2) && ($createUserModel->followees != "") && in_array($this->create_user, explode(",", trim($createUserModel->followees, ","))))) {
          $inser = true;
        }
        $notificationContentModel = new NotificationContent;
        $notificationData = array("pk_id" => $this->id, "content" => "回答了你的问题", "send_time" => $this->create_time, "notification_type" => "createanswer");
        $notificationContentModel->insertNotificationContent($notificationData,$inser);
        Request::model()->updateAll(array("answer_flag"=>1),"to_user=:to_user and question_id=:question_id",array(":to_user"=>$this->create_user,":question_id"=>$this->question_id));
        Question::model()->updateAll(array("update_time"=>$this->create_time),"id=:id",array(":id"=>$this->question_id));
    }

    /**
     * 更新user表 
     */
    public function afterDelete() {
        parent::afterDelete();
        $createUser = Yii::app()->user->id;
        $time = strtotime(date("Y-m-d", time()));
        $count = self::model()->count("create_user = " . $createUser . " and create_time > " . $time);
        if (($count == 0) && ($this->create_time > $time)) {
            $userModal = User::model()->findByPk($createUser);
            $score = Sys::model()->getvaluesByType("answer_score");
            $wealthModel = new Wealth;
            if (Sys::model()->getvaluesByType("answer_type") == "0") {
                $userModal->wealth = $userModal->wealth - intval($score);
                $content = "删除答案成功，花费" . $score . "个财富值";
                $data = array('content' => $content, 'create_time' => time());
                $wealthModel->insertWealth($data);
            }
            $userModal->save();
        }
        $criteria = new CDbCriteria;
        $criteria->addCondition("pk_id=" . $this->id);
        $criteria->addCondition("model='answer'");
        $commentModel = Comment::model()->findAll($criteria);
        foreach ($commentModel as $value) {
            Comment::model()->findByPk($value->id)->delete();
        }

        $criteria = new CDbCriteria;
        $criteria->addCondition("pk_id=" . $this->id);
        $criteria->addCondition("model='answer'");
        Vote::model()->deleteAll($criteria);

        $notificationCriteria = new CDbCriteria;
        $notificationCriteria->addCondition("pk_id=" . $this->id);
        $notificationCriteria->addCondition("notification_type='createanswer'");
        $notifyModel = NotificationContent::model()->find($notificationCriteria);
        if ($notifyModel != null)
            NotificationContent::model()->findByPk($notifyModel->id)->delete();
    }

}
