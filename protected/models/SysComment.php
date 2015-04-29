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
class SysComment extends CActiveRecord {

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
        return 'sys_comment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, content, create_time ', 'required'),
            array('user_id, create_time', 'numerical', 'integerOnly' => true),
            array('content', 'length', 'max' => 5000),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, user_id, content, create_time, score, delete_flag', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
//		return array(
//            return array(
//            'diary' => array(self::BELONGS_TO, 'Diary', 'diary_id'),
//            'createUser' => array(self::BELONGS_TO, 'User', 'create_user'),
//        );
//		);
        return array(
            'createUser' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => '用户名',
            'content' => '评论内容',
            'create_time' => '点评时间',
            'score' => 'score',
            'tags' => '标签',
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

        $criteria->compare('score', $this->score);
        $criteria->compare('is_show', $this->is_show);

        $sysComment = $_GET["SysComment"];
        if ($sysComment['create_time']) {
            $time_arr = explode("-", $sysComment['create_time']);
            $start_time = strtotime(trim($time_arr[0]) . " 00:00:00");
            $end_time = strtotime(trim($time_arr[1]) . " 23:59:59");
            $criteria->addCondition("create_time >= :start_time and create_time <= :end_time");
            $criteria->params[':start_time'] = $start_time;
            $criteria->params[':end_time'] = $end_time;
        } else {
            $criteria->compare('create_time', $this->create_time);
        }
        if ($sysComment['content']) {
            $criteria->addSearchCondition('content', trim($sysComment['content']));
        } else {
            $criteria->compare('content', $this->content, true);
        }
        if ($sysComment['tags']) {
            $criteria->addSearchCondition('tags', trim($sysComment['tags']));
        } else {
            $criteria->compare('tags', $this->tags);
        }
        if ($sysComment['user_id']) {
            $criteriaUser = new CDbCriteria;
            $idArray = array();
            $criteriaUser->addSearchCondition('user_name', trim($sysComment['user_id']));
            $userModel = User::model()->findAll($criteriaUser);
            foreach ($userModel as $value){
                $idArray[] = $value->id;
            }
             $criteria->addInCondition('user_id',$idArray);
        } else {
            $criteria->compare('user_id', $this->user_id);
        }
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function getCountById() {
        $count = self::model()->count("is_show=0");
        return $count;
    }

    public function getScoreArrayById() {
        $array = array();
        for ($i = 1; $i < 6; $i++) {
            $count = self::model()->count("score = :score and is_show=0", array(":score" => $i));
            $array[$i] = $count;
        }
        return $array;
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

    public static function getCountComment($userId) {
        $count = self::model()->count("user_id = :user_id", array(":user_id" => $userId));
        return $count;
    }

    public function allcomment($userName = '', $content = '') {
        if ($userName == '' && $content == '') {
            return new CActiveDataProvider($this);
        } else {
            $criteria = new CDbCriteria;
            if ($userName != '') {
                $user = array();
                $criteria1 = new CDbCriteria;
                $criteria1->addSearchCondition('user_name', $userName);
                $users = User::model()->findAll($criteria1);
                foreach ($users as $key => $value) {
                    $user[] = $value['id'];
                }
                $criteria->addInCondition('user_id', $user);
            }
            if ($content != '') {
                $criteria->addSearchCondition('content', $content);
            }
            return new CActiveDataProvider($this, array(
                        'criteria' => $criteria,
                    ));
        }
    }

    public static function getCountAgreeByCommentId($commentId) {
        $agree = self::model()->findByPk($commentId)->agree;
        if ($agree == "") {
            return 0;
        } else {
            $agree_array = array();
            $agree_array = explode(",", rtrim($agree, ","));
            return count($agree_array);
        }
    }

    public static function getTagsArray($userId) {
        $tagsArray = array();
        $string = "";
        $model = self::model()->findAll("user_id = :user_id", array(":user_id" => $userId));
        foreach ($model as $value) {
            $string .= $value->tags . ",";
        }
        $tagsArray = explode(",", rtrim($string, ","));
        $tags = array_values(array_unique($tagsArray));
        return $tags;
    }

    public static function getIdByTagsName($tags) {
        $id_array = array();
        $tags_array = explode(",", $tags);
        for ($i = 0; $i < count($tags_array); $i++) {
            $model = self::model()->findAll("tags like '%" . $tags_array[$i] . "%'");
            foreach ($model as $value) {
                $id_array[] = $value->id;
            }
        };
        return array_unique($id_array);
    }

    public static function getTagsOrder() {
        $id_array = array();
        $model = self::model()->findAll("is_show = 0");
        $tagsStr = "";
        $tagsArray = array();
        foreach ($model as $key => $value) {
            $tagsStr .= $value->tags . ",";
        };
        $tagsArray = explode(",", rtrim($tagsStr, ","));
        $tags = array_values(array_unique($tagsArray));
        for ($i = 0; $i < count($tags); $i++) {
            $id_array[$tags[$i]] = self::model()->count("tags like '%" . $tags[$i] . "%' and is_show = 0");
        }
        return $id_array;
    }

    public static function getCountByTagsName($tagsName) {
        return self::model()->count("tags like '%" . $tagsName . "%' and is_show = 0");
    }
   public function getShowIdStr() {
        $model = self::model()->findAll("is_show=:is_show",array(":is_show"=>0));
        $idString = "";
        foreach ($model as $value){
            $idString .= $value->id .',';
        }
        return  rtrim($idString, ",");
    }
}