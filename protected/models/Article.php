<?php

/**
 * This is the model class for table "diary".
 *
 * The followings are the available columns in table 'diary':
 * @property integer $id
 * @property integer $create_user
 * @property integer $create_time
 * @property string $subject
 * @property string $content
 *
 * The followings are the available model relations:
 * @property User $createUser
 * @property DiaryComment[] $diaryComments
 * @property DiaryShare[] $diaryShares
 */
class Article extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Diary the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'article';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('subject,content,publish,anonymity_yn', 'required'),
//            array('create_user', 'numerical', 'integerOnly' => true),
//            array('subject', 'length', 'max' => 200),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, create_user, create_time, subject, content, publish, anonymity_yn', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
//            'createUser' => array(self::BELONGS_TO, 'User', 'create_user'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'create_user' => '创建人',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
            'subject' => '标题',
            'content' => '内容',
            'topic_ids' => '话题',
            'publish' => '发布状态',
            'anonymity_yn' => '是否允许评论',
        );
    }

    /**
     * 添加查询条件
     */
    public function addQuery($current, $new) {
        $str = "";
        $array = array();
        if ($current == "") {
            $str = $new;
        } else {
            if (strpos($current, ',') !== false) {
                $current_array = explode(',', $current);
                if (in_array($new, $current_array)) {
                    $str = $current;
                } else {
                    $str = $current . "," . $new;
                }
            } else {
                if ($current == $new) {
                    $str = $current;
                } else {
                    $str = $current . "," . $new;
                }
            }
        }
        return $str;
    }

    /**
     * 删除查询条件
     */
    public function removeQuery($current, $old) {
        $str = "";
        $array = array();
        $current_array = explode(',', $current);
        for ($i = 0; $i < count($current_array); $i++) {
            if ($current_array[$i] != $old) {
                $str .= $current_array[$i] . ",";
            }
        }
        return rtrim($str, ',');
    }

    /**
     * 更新user表 
     */
    public function afterSave() {
        parent::afterSave();
        $createUser = $this->create_user;
        $time = strtotime(date("Y-m-d", time()));
        $count = self::model()->count("create_user = " . $createUser . " and update_time > " . $time);
        $userModal = User::model()->findByPk($createUser);
        $score = Sys::model()->getvaluesByType("article_score");
        $type = Sys::model()->getvaluesByType("article_type");
        $wealthModel = new Wealth;
        if (($count == 1) && (!isset($_GET["id"])) && ($this->publish == 1)) {
            if ($type == "0") {
                $userModal->wealth = $userModal->wealth + intval($score);
                $content = "撰写文章成功，奖励" . $score . "个财富值";
                $data = array('content' => $content, 'create_time' => $this->update_time);
                $wealthModel->insertWealth($data);
            }
        }
        if($type=="1"){
           $userModal->wealth = $userModal->wealth - intval($score);
           $content = "撰写文章成功，花费" . $score . "个财富值";
           $data = array('content' => $content, 'create_time' => $this->update_time);
           $wealthModel->insertWealth($data);
        }
          $userModal->save();
        if (!isset($_GET["id"]) && ($this->publish == 1)) {
            $notificationContentModel = new NotificationContent;
            $notificationData = array("pk_id" => $this->id, "content" => "撰写了一篇文章", "send_time" => $this->update_time, "notification_type" => "createarticle");
            $notificationContentModel->insertNotificationContent($notificationData);
        }
    }

    /**
     * 更新user表 
     */
    public function afterDelete() {
        parent::afterDelete();
        $createUser = Yii::app()->user->id;
        $time = strtotime(date("Y-m-d", time()));
        $count = self::model()->count("create_user = " . $createUser . " and update_time > " . $time);
        if (($count == 0) && ($this->update_time > $time) && ($this->publish == 1)) {
            $userModal = User::model()->findByPk($createUser);
            $score = Sys::model()->getvaluesByType("artice_score");
            $wealthModel = new Wealth;
            if (Sys::model()->getvaluesByType("artice_type") == "0") {
                $userModal->wealth = $userModal->wealth - intval($score);
                $content = "删除文章成功，花费" . $score . "个财富值";
                $data = array('content' => $content, 'create_time' => time());
                $wealthModel->insertWealth($data);
            }
            $userModal->save();
        }
        $criteria = new CDbCriteria;
        $criteria->addCondition("pk_id=" . $this->id);
        $criteria->addCondition("model='article'");
        $commentModel = Comment::model()->findAll($criteria);
        foreach ($commentModel as $value) {
            Comment::model()->findByPk($value->id)->delete();
        }

        $criteria = new CDbCriteria;
        $criteria->addCondition("pk_id=" . $this->id);
        $criteria->addCondition("model='article'");
        Vote::model()->deleteAll($criteria);

        $notificationCriteria = new CDbCriteria;
        $notificationCriteria->addCondition("pk_id=" . $this->id);
        $notificationCriteria->addCondition("notification_type='createarticle'");
        $notifyModel = NotificationContent::model()->find($notificationCriteria);
        if ($notifyModel != null)
            NotificationContent::model()->findByPk($notifyModel->id)->delete();
    }

    /**
     * 获取下一篇日志
     */
    public function getNext($action, $id) {
        $model = self::model()->findByPk($id);
        $criteria = new CDbCriteria;
        if ($action == "personal") {
            $criteria->addCondition("create_user=" . Yii::app()->user->id);
        } else {
            $criteria->addCondition("is_share=1");
        }
        $criteria->order = "create_time desc";
        $criteria->addCondition("create_time < " . $model->create_time);
        $data = self::model()->find($criteria);
        if (empty($data)) {
            echo "";
        } else {
            if ($action == "personal") {
                return Yii::app()->controller->createUrl("/default/personal", array('type' => 'diary', 'action' => 'view', 'id' => $data->id,));
            } else {
                return Yii::app()->controller->createUrl("/default/diary", array('id' => $data->id, 'action' => 'view'));
            }
        }
    }

    /**
     * 获取上一篇日志
     */
    public function getPrev($action, $id) {
        $model = self::model()->findByPk($id);
        $criteria = new CDbCriteria;
        if ($action == "personal") {
            $criteria->addCondition("create_user=" . Yii::app()->user->id);
        } else {
            $criteria->addCondition("is_share=1");
        }
        $criteria->order = "create_time asc";
        $criteria->addCondition("create_time > " . $model->create_time);
        $data = self::model()->find($criteria);
        if (empty($data)) {
            echo "";
        } else {
            if ($action == "personal") {
                return Yii::app()->controller->createUrl("/default/personal", array('type' => 'diary', 'action' => 'view', 'id' => $data->id,));
            } else {
                return Yii::app()->controller->createUrl("/default/diary", array('id' => $data->id, 'action' => 'view'));
            }
        }
    }

    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('create_user', $this->create_user);
        $criteria->addCondition('publish=1');
        $criteria->order = "update_time desc";
        $criteria->compare('create_time', $this->create_time);
        if (isset($_GET["q"])) {
            $criteria->addSearchCondition('subject', trim($_GET["q"]));
        }
        if (isset($_GET["user_id"])) {
            $criteria->addCondition('create_user=:create_user');
            $criteria->params[':create_user'] = intval($_GET["user_id"]);
        }
        if (isset($_GET["id"]) && Yii::app()->controller->getAction()->getId() == "topic") {
            $criteria->addSearchCondition('topic_ids', "," . intval($_GET["id"]) . ",");
            $criteria->addCondition('topic_ids!=""');
        }
        if (isset($_GET["type"]) && ($_GET["type"]=="skilltopic")) {
            $criteria->addSearchCondition('topic_ids', "," . intval($_GET["id"]) . ",");
            $criteria->addCondition('topic_ids!=""');
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
