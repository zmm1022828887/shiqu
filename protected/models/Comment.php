<?php

/**
 * This is the model class for table "comment".
 *
 * The followings are the available columns in table 'comment':
 * @property integer $id
 * @property integer $user_id
 * @property string $content
 * @property integer $create_time
 * @property integer $pk_id
 */
class Comment extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Comment the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'comment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, content, create_time, pk_id', 'required'),
            array('user_id, create_time, pk_id', 'numerical', 'integerOnly' => true),
            array('content', 'length', 'max' => 5000),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, user_id, content, create_time, pk_id', 'safe', 'on' => 'search'),
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
            'user_id' => 'User',
            'content' => '评论内容',
            'create_time' => 'Create Time',
            'pk_id' => 'pk_id',
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
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('pk_id', $this->pk_id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * 格式化时间戳，返回友好时间
     * @param int $j
     * @param string $l
     * @param type $f
     * @return string
     */
    public static function timeintval($j, $f = 'm-d H:i', $l = 'cn') {
        if ($l == "")
            $l = 'cn';
        $lang = array(
            'en' => array(' secs ago', ' mins ago', ' hours ago', ' days ago', ' just now'),
            'cn' => array("秒钟前", "分钟前", "小时前", "天前", "刚刚")
        );

        if (is_numeric($j)) {
            $i = time() - $j;
            switch ($i) { //604800 86400 3600 60
                case 0 > $i:$str = date($f, $j);
                    break;
                case "0": $str = $lang[$l][4];
                    break;
                case 60 > $i: $str = $i . $lang[$l][0];
                    break;
                case 3600 > $i: $str = round($i / 60) . $lang[$l][1];
                    break;
                case 86400 > $i: $str = round($i / 3600) . $lang[$l][2];
                    break;
                case 604800 > $i: $str = round($i / 86400) . $lang[$l][3];
                    break;
                case $i > 604800: $str = date($f, $j);
                    break;
                default: $str = date($f, $j);
            }
        }
        return $str;
    }

    public function getCount($id, $model) {
        return $this->count("pk_id = :pk_id and model = :model", array(":pk_id" => $id, ":model" => $model));
    }

    public function getLastCommentTime($id, $model) {
        $criteria = new CDbCriteria;
        $criteria->order = "create_time desc";
        $criteria->addCondition("model=:model");
        $criteria->addCondition("pk_id=:pk_id");
        $criteria->params = array(":model" => $model, ":pk_id" => $id);
        $commentModel = self::model()->find($criteria);
        if ($commentModel == null) {
            if ($model == "article") {
                return date("m-d H:i", Article::model()->findByPk($id)->create_time);
            } else {
                return date("m-d H:i", Topic::model()->findByPk($id)->create_time);
            }
        } else {
            return date("m-d H:i", $model->create_time);
        }
    }

    /**
     * 发送提醒
     */
    public function afterSave() {
        parent::afterSave();
        if ($this->model == "answer") {
            $createUser = Answer::Model()->findByPk($this->pk_id)->create_user;
        } else if ($this->model == "article") {
            $createUser = Article::Model()->findByPk($this->pk_id)->create_user;
        } else if ($this->parent_id != 0) {
            $createUser = Comment::Model()->findByPk($this->parent_id)->user_id;
        }
        $createUserModel = User::model()->findByPk($createUser);
        $recvArray = unserialize($createUserModel->recv_option);
        if (($this->model == "answer") || ($this->model == "article")) {
            $value = "subscribe_" . $this->model . "_like";
            $inser = false;
            if (($recvArray[$value] == 1) || (($recvArray[$value] == 2) && ($createUserModel->followees != "") && in_array($this->user_id, explode(",", trim($createUserModel->followees, ","))))) {

                $inser = true;
            }
            $content = User::getName($this->model);
            $notificationData = array("pk_id" => $this->id, "content" => "点评了你的" . $content, "send_time" => $this->create_time, "notification_type" => $this->model);
            NotificationContent::insertNotificationContent($notificationData, $inser);
        }
        if ($this->parent_id != 0) {
            $value = "subscribe_comment_like";
            $inser = false;
            if (($recvArray[$value] == 1) || (($recvArray[$value] == 2) && ($createUserModel->followees != "") && in_array($this->user_id, explode(",", trim($createUserModel->followees, ","))))) {
                $inser = true;
            }
            $notificationData = array("pk_id" => $this->id, "content" => "回复了你的评论", "send_time" => $this->create_time, "notification_type" => "comment");
            NotificationContent::insertNotificationContent($notificationData, $inser);
        }
    }

    /**
     * 更新回复表
     */
    public function afterDelete() {
        parent::afterDelete();
        $criteria = new CDbCriteria;
        $criteria->addCondition("parent_id=" . $this->id);
        Comment::model()->deleteAll($criteria);
        if (in_array($this->model, array('diary', 'picture', 'recent', 'timeline', 'topic'))) {
            if (NotificationContent::model()->count("notification_type=:notification_type and pk_id=:pk_id", array(":notification_type" => $this->model, ":pk_id" => $this->id)) > 0) {
                $id = NotificationContent::model()->find("notification_type=:notification_type and pk_id=:pk_id", array(":notification_type" => $this->model, ":pk_id" => $this->id))->id;
                NotificationContent::model()->findByPk($id)->delete();
            }
        }
    }

    public function getIsCreateUser($pk, $model) {
        if (($model == "diary") && (Diary::model()->findByPk($pk)->create_user == Yii::app()->user->id)) {
            return true;
        } else if (($model == "photo") && (Photo::model()->findByPk($pk)->create_user == Yii::app()->user->id)) {
            return true;
        } else if (($model == "recent") && (Recent::model()->findByPk($pk)->create_user == Yii::app()->user->id)) {
            return true;
        } else if (($model == "timeline") && (Timeline::model()->findByPk($pk)->create_user == Yii::app()->user->id)) {
            return true;
        } else if (($model == "pictue") && (Picture::model()->findByPk($pk)->create_user == Yii::app()->user->id)) {
            return true;
        }
        return false;
    }

    //获取评论内容多维数组，chrildren为评论的回复。
    public function getComment($parent_id = 0, $model, $pk) {
        $criteria = new CDbCriteria();

        $criteria->addCondition("model=:model");
        $criteria->addCondition("pk_id=:pk_id");
        $criteria->addCondition("parent_id=:parent_id");
        if ($parent_id == 0)
            $criteria->order = "create_time desc";
        else
            $criteria->order = "create_time asc";
        $criteria->params = array(":model" => $model, ":pk_id" => $pk, ":parent_id" => $parent_id);
        $data = self::model()->findAll($criteria);
        $comments = array();
        $i = 0;
        foreach ($data as $value) {
            $comments[$i]["id"] = $value["id"];
            $comments[$i]["parent_id"] = $value["parent_id"];
            $comments[$i]["content"] = $value["content"];
            $comments[$i]["model"] = $value["model"];
            $comments[$i]["pk_id"] = $value["pk_id"];
            $comments[$i]["create_time"] = $value["create_time"];
            $comments[$i]["create_user"] = self::model()->findByPk($value["parent_id"])->user_id;
            $comments[$i]["reply_user"] = $value["user_id"];
            $comments[$i]["user_avatar"] = User::getAvatarById($value["user_id"]);
            $comments[$i]["chrildren"] = $this->getComment($value["id"], $model, $pk);
            $i++;
        }
        return $comments;
    }

    public static function replyList($array, $padding = 60, $left = 20) {
        if (empty($array))
            return false;
        foreach ($array as $key) {
            $result .= '<a name="sys_comment_' . $key['id'] . '"></a>';
            $result .= '<div class="comment-reply" style="border-top:1px dashed #ccc;position:relative;padding:10px 0  10px 60px;padding-left:' . $padding . 'px;"><a  href="' . Yii::app()->controller->createUrl('default/userinfo', array('user_id' => $key['reply_user'])) . '"  class="user-label" data-id="' . $key['reply_user'] . '" target="_blank" style="font-weight:bold;"><img src="' . Yii::app()->controller->createUrl('default/getimage', array('id' => $key['create_user'], 'type' => 'avatar')) . '" width="34" height="34" style="border:none;position: absolute;left:' . $left . 'px;top: 10px;"></a>'
                    . ' <div><a  href="' . Yii::app()->controller->createUrl('default/userinfo', array('user_id' => $key['reply_user'])) . '"  class="user-label" data-id="' . $key['reply_user'] . '" target="_blank" style="font-weight:bold;">' . User::getNameById($key["reply_user"]) . '</a> 回复 <a href="' . Yii::app()->controller->createUrl('default/userinfo', array('user_id' => $key['create_user'])) . '"  class="user-label" data-id="' . $key['create_user'] . '" target="_blank"  style="font-weight:bold;">' . User::getNameById($key["create_user"]) . '</a> ：' . $key['content'] . '<a class="pull-right reply" style="padding-right:16px;display:none;" id="' . $key["id"] . '">回复</a>';
            $result.="<div>" . Comment::timeintval($key['create_time']);
            if ((Yii::app()->user->name == "admin") || (($key['model'] == "comment") && ($key['pk_id'] == Yii::app()->user->id)) || Comment::model()->getIsCreateUser($key['pk_id'], $key['model'])) {
                $result .= '<a  href="#" title="删除" data-name="' . (Yii::app()->user->isGuest ? 'noLogin' : 'delete-reply') . '"  data-value="' . $key['id'] . '" style="float:right;margin-right:20px;display: none;">删除</a>';
            }
            if (($key['model'] != "comment") || (($key['model'] == "comment") && ($key['pk_id'] == Yii::app()->user->id)) && (Yii::app()->controller->getAction()->getId() == "personal")) {
                $result.='<a user-value="' . $key['create_user'] . '" data-value="' . $key['id'] . '"  data-page="' . $_GET["page"] . '" href="" title="回复" name-value="' . User::getNameById($key['create_user']) . '" data-name="' . (Yii::app()->user->isGuest ? 'noLogin' : 'reply-comment') . '" style="float:right;margin-right:20px;display: none;">回复</a>';
            }
            $result.='</div>';
            $result.="</div></div>";
            $result.= self::replyList($key["chrildren"], $padding > 100 ? $padding : $padding + 40, $left > 80 ? $left : $left + 40);
        }

        return $result;
    }

}
