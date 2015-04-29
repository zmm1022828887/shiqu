<?php

/**
 * This is the model class for table "wish".
 *
 * The followings are the available columns in table 'wish':
 * @property integer $id
 * @property integer $create_user
 * @property string $to_user
 * @property string $content
 * @property integer $create_time
 */
class Wealth extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Wish the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'wealth';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('create_user, content, create_time', 'required'),
            array('create_user, create_time', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, create_user, content, create_time', 'safe', 'on' => 'search'),
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
            'create_user' => '创建人',
            'content' => '描述',
            'create_time' => '创建时间',
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
        $criteria->compare('content', $this->content, true);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('create_user', $this->create_user);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function insertWealth($data) {
        $model = new Wealth;
        $model->content = $data['content'];
        $model->create_time = $data['create_time'];
        $model->create_user = array_key_exists('create_user',$data) ? $data['create_user'] :Yii::app()->user->id;
        if ($model->save())
            return true;
    }

}
