<?php

/**
 * This is the model class for table "desktop_setting".
 *
 * The followings are the available columns in table 'desktop_setting':
 * @property integer $id
 * @property integer $app_no
 * @property string $app_id
 * @property string $app_name
 * @property integer $app_status
 * @property string $app_direction
 */
class DesktopSetting extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'desktop_setting';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('app_no, app_id, app_name,app_direction,app_length', 'required'),
            array('app_no, app_status,app_length', 'numerical', 'integerOnly' => true),
            array('app_id, app_name,', 'length', 'max' => 100),
//			array('app_direction', 'length', 'max'=>2),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, app_no, app_id, app_name, app_status, app_direction,app_length', 'safe', 'on' => 'search'),
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
            'id' => '自增ID',
            'app_no' => '序号',
            'app_id' => '标识',
            'app_name' => '名字',
            'app_status' => '状态',
            'app_direction' => '方向',
            'app_length' => '数据条数',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('app_no', $this->app_no);
        $criteria->compare('app_id', $this->app_id, true);
        $criteria->compare('app_name', $this->app_name, true);
        $criteria->compare('app_status', $this->app_status);
        $criteria->compare('app_direction', $this->app_direction, true);
        $criteria->compare('app_length', $this->app_length, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return DesktopSetting the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public static function getArray($directin = "r") {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->order = "app_no";
        $criteria->addCondition("app_status=1");
        $criteria->addCondition("app_direction='" . $directin."'");
        $model = self::model()->findAll($criteria);
        return $model;
    }

}
