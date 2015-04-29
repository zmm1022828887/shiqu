<?php

/**
 * This is the model class for table "message".
 *
 * The followings are the available columns in table 'message':
 * @property integer $id
 * @property integer $create_user
 * @property integer $to_uid
 * @property integer $remind_flag
 * @property integer $delete_flag
 * @property integer $create_time
 * @property string $content
 */
class Message extends CActiveRecord {

    public $report_type;
    public $report_uid;
    public $report_content;
    public $report_model;
    public $is_me_send = false;
    public $start_time = '';
    public $end_time = '';
    public $user_name;
    public static $msg_stauts = array(
        'from' => array(
            'unread_remind_flag' => 1,
            'unread_delete_flag' => 2
        ),
        'to' => array(
            'unread_remind_flag' => 1,
            'unread_delete_flag' => 1
        ),
    );

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Message the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'message';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('create_user,content,user_name', 'required'),
            array('create_user, to_uid, create_time', 'numerical', 'integerOnly' => true),
            array('content', 'length', 'max' => 5000),
            array('to_uid', 'checkUser'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, create_user, to_uid, remind_flag, delete_flag, create_time, content', 'safe', 'on' => 'search'),
        );
    }

    /**
     * 校验用户名
     */
    public function checkUser($attribute, $params) {
        $loginUser = User::model()->findByPk($this->to_uid);
        $recvArray = unserialize($loginUser->recv_option);
        $user_name = User::getNameById(Yii::app()->user->id);
        if (($recvArray['subscribe_message_like'] == '1' ) && (!in_array(Yii::app()->user->id, explode(",", $loginUser->followees)))) {
            $this->addError($attribute, '只有TA关注的人才能够发给TA私信');
            return false;
        } else if ((in_array(Yii::app()->user->id, explode(",", $loginUser->block_users)))) {
            $this->addError($attribute, '你已经被他屏蔽');
            return false;
        } else if (Yii::app()->user->id == $this->to_uid) {
            $this->addError($attribute, '自己不能给自己发私信');
            return false;
        }
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
            'create_user' => '发送人',
            'to_uid' => '接收人',
            'user_name' => '接收人',
            'remind_flag' => 'Remind Flag',
            'delete_flag' => 'Delete Flag',
            'create_time' => '发送时间',
            'content' => '内容',
            'start_time' => '',
            'end_time' => '',
            'sendMail' => '是否发送邮件提醒'
        );
    }

    /**
     * 删除信息
     * @param string or array $msg_id_str 要删除的微讯ID串或者数组
     * @param intger $del_type 1 删除收到的短信，2 删除发送的短信
     */
    public function delete_msg($msg_id, $del_type) {
        $msg_id_arr = array();
        if (!is_array($msg_id)) {
            $msg_id_arr = explode(",", $msg_id);
            $msg_id = trim($msg_id);
            if ($msg_id == "")
                return;
            $msg_id = str_replace(',', '\',\'', $msg_id);
            $msg_id = "'" . $msg_id . "'";
        }else {
            $msg_id_arr = $msg_id;
            $msg_id = implode(",", $msg_id);
        }
        $criteria_delete = new CDbCriteria;

        if ($del_type == 1) {
            $criteria_delete->addInCondition('id', $msg_id_arr);
            $criteria_delete->addCondition('to_uid = ' . Yii::app()->user->id);
            $criteria_delete->addCondition('delete_flag = 2');
            self::model()->deleteAll($criteria_delete);

            self::model()->updateAll(array('delete_flag' => 1), 'to_uid = :to_uid and delete_flag = :old_delete_flag and id in (' . $msg_id . ')', array(':to_uid' => Yii::app()->user->id, ':old_delete_flag' => 0));
        } else {
            $criteria_delete->addInCondition('id', $msg_id_arr);
            $criteria_delete->addCondition('create_user = ' . Yii::app()->user->id);
            $criteria_delete->addCondition('delete_flag = 1 or remind_flag =1 ');
            self::model()->deleteAll($criteria_delete);
            self::model()->updateAll(array('delete_flag' => 2), 'create_user = :create_user and delete_flag = :old_delete_flag and id in (' . $msg_id . ')', array(':create_user' => Yii::app()->user->id, ':old_delete_flag' => 0));
        }
    }

    public function getUnreadMessage() {
        $count = self::model()->count('to_uid = :to_uid and remind_flag!= 0 and delete_flag!= :to_delete_flag  order by create_time asc', array(':to_uid' => Yii::app()->user->id, ':to_delete_flag' => self::$msg_stauts['to']['unread_delete_flag']));
        return $count;
    }

    public function getMessageTotal() {
        $count = self::model()->count('create_user = :create_user  and delete_flag!= :from_delete_flag  order by create_time asc', array(':create_user' => Yii::app()->user->id, ':from_delete_flag' => self::$msg_stauts['from']['unread_delete_flag']));
        return $count;
    }

    public function getType($value = "") {
        $array = array(
            0 => "所有人",
            1 => "我关注的人",
        );
        return $value == "" ? $array : $array[$value];
    }
    public function getReportType($value = "") {
        $array = array(
            0 => "发布广告等垃圾信息",
            1 => "发布不友善内容",
            2 => "发布违法违规内容",
            3 => "不宜公开讨论的政治内容",
            4 => "其他",
        );
        return $value == "" ? $array : $array[$value];
    }

    public function listMessage($size = 10) {
        $dataArr = $relation = array();
        $data = MessageRelations::model()->findAll(
                array(
                    'limit' => $size, //默认每次取50
                    'order' => 'update_time desc',
                    'condition' => '( user1 = :to_uid  or user2 = :to_uid ) ',
                    'params' => array(
                        ':to_uid' => Yii::app()->user->id,
                    )
                )
        );
        $UserStr = array();
        if (!empty($data)) {
            foreach ($data as $key) {
                $UserStr[] = $key['user1'];
                $UserStr[] = $key['user2'];
                $criteria = new CDbCriteria();
                $user1 = $key['user1'] == Yii::app()->user->id ? $key['user2'] : $key['user1'];
                $user2 = $key['user1'] == Yii::app()->user->id ? $key['user1'] : $key['user2'];
                $criteria->order = 'create_time desc';
                $criteria->condition = "(create_user=" . $user2 . " and to_uid = " . $user1 . " and delete_flag != 2) or (create_user=" . $user1 . " and to_uid=" . $user2 . " and delete_flag != 1)";
                $messageData = self::model()->find($criteria);
                $relation[] = $messageData->id;
            }
            $UserStr = array_unique($UserStr);
            $UserStr = array_diff($UserStr, array(Yii::app()->user->id));
            $criteria = new CDbCriteria();
            $criteria->addInCondition('id', $relation);
            $criteria->order = 'create_time desc';
            $messageData = self::model()->findAll($criteria);
            foreach ($messageData as $key) {
                $userId = $key['to_uid'] == Yii::app()->user->id ? $key['create_user'] : $key['to_uid'];
                if (((($key['to_uid'] == Yii::app()->user->id) && ($key['delete_flag']) != 1)) || ((($key['create_user'] == Yii::app()->user->id) && ($key['delete_flag']) != 2))) {

                    $userId = $key['to_uid'] == Yii::app()->user->id ? $key['create_user'] : $key['to_uid'];
                    $counter1 = $this->countUser($key['create_user'], $key['to_uid']);
                    $counter2 = $this->countUser($key['to_uid'], $key['create_user']);
                    $count = $counter1 + $counter2;
                    $dataArr[] = array(
                        'user_id' => $userId, //按收者
                        'user_gender' => User::model()->findByPk($userId)->gender,
                        'user_name' => User::model()->findByPk($userId)->user_name,
                        'user_avatar' =>  Yii::app()->createUrl('/default/getimage', array('id'=>($key['to_uid'] == Yii::app()->user->id ? $key['create_user'] : $key['to_uid']),'type'=>'avatar')),
                        'msg_list_url' => Yii::app()->createUrl('/default/dialogue', array('id' => $userId)),
                        'msg_count' => $count,
                        'msg_create_time' => $key['create_time'],
                        'msg_content' => $key['content'],
                        'msg_unread' => $key['remind_flag'] == 1 ? true : false,
                        'msg_me_send' => $key['create_user'] == Yii::app()->user->id ? true : false
                    );
                }
            }
        }
        return $dataArr;
    }

    public function listDialogue($id) {
        $return = array();
        $from_uid = intval($id);
        $criteria1 = new CDbCriteria();
        $criteria1->condition = '(to_uid = :to_uid and create_user = :from_uid and delete_flag != :to_delete_flag and remind_flag = 1)';
        $criteria1->params = array(
            ':to_uid' => Yii::app()->user->id,
            ':from_uid' => $from_uid,
            ':to_delete_flag' => self::$msg_stauts['to']['unread_delete_flag']
        );
        Message::model()->updateAll(array("remind_flag"=>0),$criteria1);
        $criteria = new CDbCriteria();
        $criteria->order = 'id desc';
        $criteria->condition = '(to_uid = :to_uid and create_user = :from_uid and delete_flag != :to_delete_flag) or (to_uid = :from_uid and create_user = :to_uid and delete_flag != :from_delete_flag)';
        $criteria->params = array(
            ':to_uid' => Yii::app()->user->id,
            ':from_uid' => $from_uid,
            ':from_delete_flag' => self::$msg_stauts['from']['unread_delete_flag'],
            ':to_delete_flag' => self::$msg_stauts['to']['unread_delete_flag']
        );
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array('pageSize' => 10),
        ));
    }

    /**
     * @author caoyuqi <cyq@tongda2000.com>
     * 更新relation表 
     */
    public function afterSave() {
        parent::afterSave();
        $user1 = $this->create_user;
        $user2 = $this->to_uid;
        $updateTime = $this->create_time;
        $msgId = $this->id;
        $counter1 = $this->countUser($user1, $user2);
        $counter2 = $this->countUser($user2, $user1);
        $count = MessageRelations::model()->count('(user1=:user1 and user2=:user2) or (user1=:user2 and user2=:user1)', array(':user1' => $user1, ':user2' => $user2));
        if ($count > 0) {
            $criteria = new CDbCriteria();
            $criteria->condition = '( user1=:user1 and user2=:user2 ) or ( user1=:user2 and user2=:user1 )';
            $criteria->params[':user1'] = $user1;
            $criteria->params[':user2'] = $user2;
            $model = MessageRelations::model()->updateAll(array('msg_id' => $msgId, 'update_time' => $updateTime, 'counter1' => $counter1, 'counter2' => $counter2), $criteria);
        } else {
            //把create_user赋值给了user1，说明user1就为发送人
            $model = new MessageRelations;
            $model->user1 = $user1;
            $model->user2 = $user2;
            $model->msg_id = $msgId;
            $model->update_time = $updateTime;
            $model->counter1 = $counter1 > 0 ? $counter1 : 0;
            $model->counter2 = $counter2 > 0 ? $counter2 : 0;
            $model->save();
        }
    }

    /**
     * @author caoyuqi <cyq@tongda2000.com>
     * 统计两用户之间所发送的消息条数
     * @param type $user1
     * @param type $user2
     */
    public function countUser($user1, $user2) {
        if ($user1 == Yii::app()->user->id) {
            return $this->count('create_user=:createUser and to_uid=:ToUid and delete_flag!=2', array(':createUser' => $user1, ':ToUid' => $user2));
        } else {
            return $this->count('create_user=:createUser and to_uid=:ToUid and delete_flag!=1', array(':createUser' => $user1, ':ToUid' => $user2));
        }
    }

    /**
     * 获取JSON Portal数据
     */
    public static function getJSONData() {
        $criteria = new CDbCriteria();
        $criteria->order = 'create_time desc';
        $criteria->condition = '(to_uid = :to_uid and remind_flag = :to_remind_flag and delete_flag != :to_delete_flag)';
        $criteria->params = array(
            ':to_uid' => Yii::app()->user->id,
            ':to_remind_flag' => self::$msg_stauts['to']['unread_remind_flag'],
            ':to_delete_flag' => self::$msg_stauts['to']['unread_delete_flag']
        );
        $models = self::model()->findAll($criteria);
        $data = array();
        foreach ($models as $model) {
            $data[] = array(
                'content' => $model->content,
                'createUser' => User::getNameById($model->create_user),
                'createTime' => Comment::model()->timeintval($model->create_time),
                'dataUrl' => Yii::app()->controller->createUrl("dialogue", array("id" => $model->create_user)),
            );
        }
        return CJSON::encode($data);
    }
}