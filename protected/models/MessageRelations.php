<?php

/**
 * This is the model class for table "message_relations".
 *
 * The followings are the available columns in table 'message_relations':
 * @property integer $id
 * @property integer $user1
 * @property integer $user2
 * @property integer $msg_id
 * @property integer $update_time
 * @property integer $counter1
 * @property integer $counter2
 */
class MessageRelations extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'message_relations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user1, user2, msg_id, update_time, counter1, counter2', 'required'),
			array('user1, user2, msg_id, update_time, counter1, counter2', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user1, user2, msg_id, update_time, counter1, counter2', 'safe', 'on'=>'search'),
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
         //  "msg" => array(self::BELONGS_TO, "Message", "msg_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user1' => 'User1',
			'user2' => 'User2',
			'msg_id' => 'Msg',
			'update_time' => 'Update Time',
			'counter1' => 'Counter1',
			'counter2' => 'Counter2',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user1',$this->user1);
		$criteria->compare('user2',$this->user2);
		$criteria->compare('msg_id',$this->msg_id);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('counter1',$this->counter1);
		$criteria->compare('counter2',$this->counter2);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MessageRelations the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
}
