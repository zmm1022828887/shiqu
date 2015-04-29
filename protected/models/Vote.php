<?php

/**
 * This is the model class for table "article".
 *
 * The followings are the available columns in table 'article':
 * @property integer $id
 * @property integer $product_id
 * @property integer $create_user
 * @property integer $create_time
 */
class Vote extends CActiveRecord {

    public $num;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Article the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'vote';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('pk_id, create_user, create_time', 'required'),
            array('pk_id,create_user, create_time', 'numerical', 'integerOnly' => true),
            array('id, pk_id, create_user, create_time, model,to_user', 'safe', 'on' => 'search'),
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
            'pk_id' => '主键ID',
            'create_user' => '点赞者',
            'model' => '模型',
            'create_time' => '点赞时间',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($model = "question") {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->select = "*,count(pk_id) as num";
        //      $criteria->compare('id', $this->id);
        $criteria->compare('create_user', $this->create_user);
        $criteria->addCondition("model='" . $model . "'");
        $criteria->group = "pk_id";
        $criteria->order = "create_time desc,num desc";
        $criteria->compare('create_time', $this->create_time);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageVar' => 'page',
                'pageSize' => 30)
        ));
    }
    /**
     * 自己不给自己点赞
     */
    public function beforeSave() {
        parent::beforeSave();
        $to_user = "";
        if ($this->model == "answer") {
            $to_user = Answer::model()->findByPk($this->pk_id)->create_user;
        } else if ($this->model == "article") {
            $to_user = Article::model()->findByPk($this->pk_id)->create_user;
        } else if ($this->model == "question") {
            $to_user = Question::model()->findByPk($this->pk_id)->create_user;
        }
        if ($to_user == Yii::app()->user->id){
            return false;
        }else{
          return true;  
        }
    }
    /**
     * 修改被点赞的人员
     */
    public function afterSave() {
        parent::afterSave();
        $to_user = "";
        if ($this->model == "answer") {
            $to_user = Answer::model()->findByPk($this->pk_id)->create_user;
        } else if ($this->model == "article") {
            $to_user = Article::model()->findByPk($this->pk_id)->create_user;
        } else if ($this->model == "question") {
            $to_user = Question::model()->findByPk($this->pk_id)->create_user;
        }
        if ($to_user != "")
            Vote::model()->updateByPk($this->id, array("to_user" => $to_user));
    }

}
