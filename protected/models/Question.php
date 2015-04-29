<?php

/**
 * This is the model class for table "ask_quest".
 *
 * The followings are the available columns in table 'ask_quest':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $product_id
 * @property integer $create_user
 */
class Question extends CActiveRecord {
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
        return 'question';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, topic_ids', 'required'),
            array('create_user,create_time,answer_id,view_count,update_time', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 100),
            array('title', 'validateQuestionTitle'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, title, create_time,content,view_count, answer_id, create_user, update_time,hidden_answer_id', 'safe', 'on' => 'search'),
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
     * 校验用户名
     */
    public function validateQuestionTitle($attribute, $params) {
        $count = Question::model()->count("title = :title", array(":title" => trim($this->title)));
        $model = User::model()->findByPk(Yii::app()->user->id);
        if (($count > 0) && (Yii::app()->controller->getAction()->getId() == "createquestion")) {
            $this->addError($attribute, '问题已经存在');
            return false;
        } else if ((trim($this->title) != "") && (Sys::model()->getvaluesByType("question_type") == "1") && ($model->wealth < Sys::model()->getvaluesByType("question_score")) && ($model->user_name != "admin")) {
            $this->addError($attribute, '您的财富值不够');
            return false;
        }
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => '问题',
            'content' => '描述',
            'topic_ids' => '选择话题',
            'create_user' => '提问人',
            'create_time' => '提问时间',
            'view_count' => '访问量',
            'answer_id' => '答案',
            'update_time' => '修改时间',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($type="hot") {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('topic_ids', $this->topic_ids);
        $criteria->compare('create_user', $this->create_user);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('update_time', $this->update_time);
        if ($type == "not") {
            $idArray = array();
            $criteria1 = new CDbCriteria;
            $criteria1->group = "question_id";
            $Vote = Answer::model()->findAll($criteria1);
            foreach ($Vote as $key => $value) {
                $idArray[] = $value->question_id;
            }
            $criteria->addNotInCondition("id", $idArray);
        }
        if (isset($_GET["q"])) {
            $criteria->addSearchCondition('title', trim($_GET["q"]));
        }
       if(isset($_GET["user_id"])){
            $criteria->addCondition('create_user=:create_user'); 
            $criteria->params[':create_user']= intval($_GET["user_id"]); 
        }
      
      
        if(isset($_GET["id"]) && Yii::app()->controller->getAction()->getId()=="topic"){
            $criteria->addSearchCondition('topic_ids',",".  intval($_GET["id"].",")); 
        }
         if(isset($_GET["type"]) && ($_GET["type"]=="skilltopic")){
            $criteria->addSearchCondition('topic_ids',",".  intval($_GET["id"]) .","); 
        }
        $criteria->order = "update_time desc";
        $criteria->compare('answer_id', $this->answer_id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageVar' => 'page',
                'pageSize' => 30)
        ));
    }

    /**
     * 更新user表 
     */
    public function inserNotify($id) {
        $model = Question::model()->findByPk($id);
        $createUser = $model->create_user;
        $userModal = User::model()->findByPk($createUser);
        $score = Sys::model()->getvaluesByType("question_score");
        $type = Sys::model()->getvaluesByType("question_type");
        $time = strtotime(date("Y-m-d", time()));
        $count = Question::model()->count("create_user=:create_user and create_time>:create_time",array(":create_user"=> $this->create_user,":create_time"=>$time));
        if ($count == 1) {
            $wealthModel = new Wealth();
            if ($type == "0") {
                $userModal->wealth = $userModal->wealth + intval($score);
                $content = "提问成功，奖励" . $score . "个财富值";
                $data = array('content' => $content, 'create_time' => $model->create_time);
                $wealthModel->insertWealth($data);
            }
        }
        if ($type == "1") {
            $userModal->wealth = $userModal->wealth - intval($score);
            $content = "提问成功，花费" . $score . "个财富值";
            $data = array('content' => $content, 'create_time' => $model->create_time);
            $wealthModel->insertWealth($data);
        }
        $userModal->save();
        $notificationData = array("pk_id" => $model->id, "content" => "提出了一个问题", "send_time" => $model->create_time, "notification_type" => "createquestion");
        NotificationContent::insertNotificationContent($notificationData,true);
    }
    public function getLastAnswer(){
       $answer = Answer::model()->find("question_id=:question_id order by create_time desc",array(":question_id"=>$this->id));
       $date = ($answer!=null) ? $answer->create_time:$this->create_time;
       return Comment::timeintval($date);
    }
}
