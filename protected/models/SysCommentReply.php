<?php

/**
 * This is the model class for table "comment_reply".
 *
 * The followings are the available columns in table 'comment_reply':
 * @property integer $id
 * @property integer $user_id
 * @property integer $comment_id
 * @property integer $reply_user_id
 * @property string $content
 * @property integer $create_time
 */
class SysCommentReply extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CommentReply the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sys_comment_reply';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, comment_id, reply_user_id, content, create_time', 'required'),
			array('user_id, comment_id, reply_user_id, create_time', 'numerical', 'integerOnly'=>true),
			array('content', 'length', 'max'=>5000),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, comment_id, reply_user_id, content, create_time, is_show', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => '点评人',
			'comment_id' => '点评ID',
			'reply_user_id' => '回复人',
			'content' => '回复内容',
			'create_time' => '回复时间',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('comment_id',$this->comment_id);
        $sysReply = $_GET["SysCommentReply"];
        if ($sysReply['create_time']) {
            $time_arr = explode("-", $sysReply['create_time']);
            $start_time = strtotime(trim($time_arr[0]) . " 00:00:00");
            $end_time = strtotime(trim($time_arr[1]) . " 23:59:59");
            $criteria->addCondition("create_time >= :start_time and create_time <= :end_time");
            $criteria->params[':start_time'] = $start_time;
            $criteria->params[':end_time'] = $end_time;
        } else {
            $criteria->compare('create_time', $this->create_time);
        }
        if ($sysReply['content']) {
            $criteria->addSearchCondition('content', trim($sysReply['content']));
        } else {
            $criteria->compare('content', $this->content, true);
        }
        if ($sysReply['reply_user_id']) {
            $criteriaUser = new CDbCriteria;
            $idArray = array();
            $criteriaUser->addSearchCondition('user_name', trim($sysReply['reply_user_id']));
            $userModel = User::model()->findAll($criteriaUser);
            foreach ($userModel as $value){
                $idArray[] = $value->id;
            }
             $criteria->addInCondition('reply_user_id',$idArray);
        } else {
            $criteria->compare('reply_user_id', $this->reply_user_id);
        }
        $criteria->compare('is_show', $this->is_show);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    public static function  getCountReply(){
        $userId = Yii::app()->user->id;
        $count = self::model()->count("reply_user_id = :reply_user_id",array(":reply_user_id"=>$userId));
        return $count;
    }
    public function allreply($userName='',$content=''){
        if($userName=='' && $content==''){
            return new CActiveDataProvider($this);
        }else{
            $criteria=new CDbCriteria;
            if($userName!=''){
                $user=array();
                $criteria1=new CDbCriteria;
                $criteria1->addSearchCondition('user_name',$userName);
                $users=  User::model()->findAll($criteria1);
                foreach($users as $key =>$value){
                    $user[]=$value['id'];
                }
                $criteria->addInCondition('reply_user_id', $user);
            }
            if($content!=''){
                $criteria->addSearchCondition('content', $content);
            }
            return new CActiveDataProvider($this,array(
                'criteria'=>$criteria,
            ));
        }
    }
}