<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $user_name
 * @property string $chinese_name
 * @property string $password
 * @property string $not_login
 * @property integer $last_visit_time
 */
class User extends CActiveRecord {

    public $old_password;
    public $new_password;
    public $retype_password;
    public $recv_option; //私信条件
    public $subscribe_member_follow; //私信条件
    public $subscribe_ask_like;
    public $subscribe_question_like;
    public $subscribe_answer_like;
    public $subscribe_article_like;
    public $subscribe_message_like;
    public $subscribe_comment_like;
    public $visit_priv;
    public $comment_priv;
    public $visit_count;
    public $refuse_count;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_name, password', 'required'),
            array('user_name, password', 'length', 'min' => 5),
            array('user_name, chinese_name', 'length', 'max' => 32),
            array('password', 'length', 'max' => 50),
            array('signature', 'length', 'max' => 225),
            array('desc,topic_ids', 'safe'),
            array('retype_password', 'validatePassword'),
            array('not_login,gender,topic_status', 'length', 'max' => 1),
            array('user_name', 'validateUserName'),
            array('avatar', 'file', 'allowEmpty' => true, 'types' => 'jpg,png,gif,jpeg', 'maxSize' => 1024 * 100, 'tooLarge' => '文件大于100K，上传失败！请上传小于100K的文件！'
            ),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,register_code,user_name, chinese_name, password, not_login, last_visit_time, gender, desc, signatures,retype_password,topic_status,topic_ids', 'safe', 'on' => 'search'),
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
    public function validateUserName($attribute, $params) {
        $count = User::model()->count("user_name = :user_name", array(":user_name" => $this->user_name));
        $user_name = User::getNameById(Yii::app()->user->id);
        if (($count > 0) && ($user_name != $this->user_name) && (Yii::app()->controller->getAction()->getId() != "changelogin") && (Yii::app()->controller->getAction()->getId() != "initpassword") && (Yii::app()->controller->getAction()->getId() != "updateuser")) {
            $this->addError($attribute, '用户名已经存在');
            return false;
        }
    }

    /**
     * 校验用户名
     */
    public function validatePassword($attribute, $params) {
        if ((Yii::app()->controller->getAction()->getId() == "registeruser") && ($this->retype_password !== $this->password)) {
            $this->addError($attribute, '确认密码和最初密码不相同');
            return false;
        }
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_name' => '用户名',
            'chinese_name' => '真实姓名',
            'password' => '密码',
            'not_login' => '是否允许登陆',
            'last_visit_time' => '上次访问时间',
            'old_password' => '原始密码',
            'new_password' => '新的密码',
            'retype_password' => '确认密码',
            'register_time' => '注册时间',
            'avatar' => '个人头像',
            'gender' => '性别',
            'tags' => '个人标签',
            'signature' => '个性签名',
            'desc' => '个人简介',
            'subscribe_member_follow' => '有人关注我',
            'subscribe_comment_like' => '有人回复了我的评论，这些人为',
            'subscribe_article_like' => '有人评论了我的文章，这些人为',
            'subscribe_question_like' => '有人回答了我的问题，这些人为',
            'subscribe_answer_like' => '有人评论了我的回答，这些人为',
            'subscribe_ask_like' => '有人邀请我回答问题，这些人为',
            'visit_priv' => '访问权限',
            'topic_status' => '是否开启擅长话题',
            'topic_ids' => '擅长话题',
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
        $criteria->compare('user_name', $this->user_name, true);
        $criteria->compare('chinese_name', $this->chinese_name, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('retype_password', $this->retype_password, true);
        $criteria->compare('not_login', $this->not_login, true);
        $criteria->compare('last_visit_time', $this->last_visit_time);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('signature', $this->signature, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function getNameById($id) {
        return User::model()->findByPk($id)->user_name;
    }

    public static function getIdByClassNo($classNo) {
        $idArray = array();
        $model = self::model()->findAll("classno=" . $classNo);
        foreach ($model as $key => $value) {
            $idArray[] = $value->id;
        }
        return $idArray;
    }

    public static function getChineseNameById($id) {
        return User::model()->findByPk($id)->chinese_name;
    }

    public function getUserlist($action) {
        $criteria = new CDbCriteria;
        $user = $_GET["User"];
        $criteria->addCondition('user_name !="admin"');
        if ($action == "new") {
            $criteria->order = "register_time desc";
        };
        if ($user['register_time']) {
            $time_arr = explode("-", $user['register_time']);
            $start_time = strtotime(trim($time_arr[0]) . " 00:00:00");
            $end_time = strtotime(trim($time_arr[1]) . " 23:59:59");
            $criteria->addCondition("register_time >= :start_time and register_time <= :end_time");
            $criteria->params[':start_time'] = $start_time;
            $criteria->params[':end_time'] = $end_time;
        } else {
            $criteria->compare('register_time', $this->register_time);
        }
        if ($user['user_name']) {
            $criteria->addSearchCondition('user_name', trim($user['user_name']));
        }
        if ($user['chinese_name']) {
            $criteria->addSearchCondition('chinese_name', trim($user['chinese_name']));
        }
        if (($user['gender'] != "")) {
            $criteria->addCondition('gender=' . $user['gender']);
        }
        if (($user['classno'] != "")) {
            $criteria->addCondition('classno=' . $user['classno']);
        }
//         $criteria->addCondition('gender='.$user['gender']);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function getAvatarById($id) {

        $model = User::model()->findbyPk($id);
        if ($model === NULL) {
            $pash = Yii::app()->baseUrl . '/avatar/0/male_b.png';
        } else {
            $avatar = $model->avatar;
            if ($avatar != "") {
                $picExt = CFileHelper::getExtension($avatar);
                $picName = rtrim($avatar, "." . $picExt);
                $path = Yii::app()->baseUrl . '/avatar/' . $id;
                if (file_exists($path)) {
                    $pash = Yii::app()->baseUrl . '/avatar/no_avatar.gif';
                } else {
                    $pash = Yii::app()->baseUrl . '/avatar/' . $id . '/' . md5($picName) . '.' . $picExt;
                }
            } else {
                $model->gender == 1 ? $pash = Yii::app()->baseUrl . '/avatar/0/male_b.png' : $pash = Yii::app()->baseUrl . '/avatar/0/female_b.png';
            }
        }
        return $pash;
    }

    public function data() {
        return new CActiveDataProvider($this);
    }

    public static function getSex($sex = "") {
        $array = array(
            0 => "她",
            1 => "他",
        );
        return $sex == "" ? $array : $array[$sex];
    }

    public static function getName($model) {
        $array = array(
            'answer' => '回答',
            'article' => '文章',
        );
        return $array[$model];
    }

    public static function getStatusLabel($model, $type) {
        $createUser = Yii::app()->user->id;
        $time = strtotime(date("Y-m-d", time()));
        if ($model == "topic") {
            $count = Topic::model()->count("create_user = " . $createUser . " and create_time > " . $time);
        } else if ($model == "article") {
            $count = Article::model()->count("publish = 1 and create_user = " . $createUser . " and create_time > " . $time);
        } else if ($model == "question") {
            $count = Question::model()->count("create_user = " . $createUser . " and create_time > " . $time);
        } else if ($model == "answer") {
            $count = Answer::model()->count("create_user = " . $createUser . " and create_time > " . $time);
        }

        if ($count == 0) {
            $label = "<span class='label label-important'><b>待完成</b></span>";
            if ($type == "label") {

                return $label;
            } else {
                return false;
            }
        } else {
            $label = "<span class='label label-success'><b>已完成</b></span>";
            if ($type == "label") {
                return $label;
            } else {
                return true;
            }
        }
    }

    public function getUserWealth($userId = "") {
        $criteria = new CDbCriteria;
        $criteria->order = "wealth desc";
        $criteria->addCondition("not_login=0");
        $model = User::model()->findAll($criteria);
        $i = 0;
        foreach ($model as $key => $value) {
            $i++;
            $userArray[$value->id] = array(
                'order' => $i,
                'user_name' => $value->user_name,
                'wealth' => $value->wealth,
                'id' => $value->id,
            );
        }
        return $userId == "" ? $userArray : $userArray[$userId]["order"];
    }

    public static function dateToText($timestamp = 0, $type = "long", $viewType = "list") {
        $weekday_arr = array(
            '0' => '天',
            '1' => '一',
            '2' => '二',
            '3' => '三',
            '4' => '四',
            '5' => '五',
            '6' => '六'
        );
        $group = '';
        $timestamp = strtotime(date("Y-m-d", $timestamp));
        $diff = ceil(($timestamp - time()) / (24 * 60 * 60));
        if ($diff > 0) {
            if ($diff == 1)
                $group = "明天";
            else if ($diff == 2)
                $group = "后天";
            else if (($diff > 2) && (date("W", $timestamp) == date("W", time())) && ($diff <= -7))
                $group = "本周" . str_replace(array("0", "1", "2", "3", "4", "5", "6"), array("日", "一", "二", "三", "四", "五", "六"), date("w", $timestamp));
            else if ($diff <= 7)
                $group = "下周" . str_replace(array("0", "1", "2", "3", "4", "5", "6"), array("日", "一", "二", "三", "四", "五", "六"), date("w", $timestamp));
            else
                $group = date("Y-m-d", $timestamp);
        }else if ($diff < 0) {
            if ($diff == -1)
                $group = "昨天";
            else if ($diff == -2)
                $group = "前天";
            else if (($diff < -2) && (date("W", $timestamp) == date("W", time())) && ($diff > -7))
                $group = "本周" . str_replace(array("0", "1", "2", "3", "4", "5", "6"), array("日", "一", "二", "三", "四", "五", "六"), date("w", $timestamp));
            else if ($diff >= -7)
                $group = "上周" . str_replace(array("0", "1", "2", "3", "4", "5", "6"), array("日", "一", "二", "三", "四", "五", "六"), date("w", $timestamp));
            else
                $group = "更早";
        }else {
            $group = "今天";
        };
        return ($viewType == "list") ? ($type == "long" ? '<time class="date" datetime="' . date("Y-j-n", $timestamp) . '"><h4 class="day-name">' . $group . '</h4>' . date("n月j日", $timestamp) . ' 星期' . $weekday_arr[date("w", $timestamp)] . '</time>' : $group) : "<span class='label label-info'>" . $group . "</span>";
    }

    public static function getPriv() {
        $array = array(
            0 => "所有人",
            1 => "我关注的人",
            2 => "仅自己",
        );
        return $array;
    }

    public static function getTotalByTypeId($id, $type = "article", $userId = '') {
        $createUser = $userId == "" ? Yii::app()->user->id : $userId;
        $criteria = new CDbCriteria;
        $count = 0;
        if (($type == "article") || $type == "question")
            $criteria->addSearchCondition('topic_ids', ',' . trim($id) . ",");

        if (($type != "disagree")) {
            $criteria->addCondition("create_user = :create_user");
            $criteria->params[':create_user'] = $createUser;
        }
        if ($type == "article") {
            $criteria->addCondition("publish = 1");
            $count = Article::model()->count($criteria);
        } else if ($type == "question") {
            $count = Question::model()->count($criteria);
        } else if ($type == "disagree") {
            $criteria->addCondition("to_user = :to_user and opinion=0");
            $criteria->params[':to_user'] = $createUser;
            $voteModel = Vote::model()->findAll($criteria);
            foreach ($voteModel as $key => $value) {
                $topic_ids = "";
                if ($value->model == "article") {
                    $topic_ids = Article::model()->findByPk($value->pk_id)->topic_ids;
                } else if ($value->model == "question") {
                    $topic_ids = Question::model()->findByPk($value->pk_id)->topic_ids;
                } else if ($value->model == "answer") {
                    $answerModel = Answer::model()->findByPk($value->pk_id);
                    if ($userId != "" && ($answerModel->is_anonymous == 0)) {
                        $topic_ids = $answerModel->topic_ids;
                    } else {
                        $topic_ids = ",0,";
                    }
                }
                if (in_array($id, explode(",", trim($topic_ids, ",")))) {
                    $count++;
                }
            }
        } else if ($type == "answer") {
            if ($userId != "")
                $criteria->addCondition("is_anonymous = 0");
            $answerModel = Answer::model()->findAll($criteria);
            foreach ($answerModel as $key => $value) {
                $questionModel = Question::model()->findByPk($value->question_id);
                if (in_array($id, explode(",", trim($questionModel->topic_ids, ",")))) {
                    $count++;
                }
            }
        }
        return $count;
    }

}
