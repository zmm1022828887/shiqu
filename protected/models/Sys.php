<?php

/**
 * This is the model class for table "sys".
 *
 * The followings are the available columns in table 'sys':
 * @property integer $id
 * @property string $site_name
 * @property string $domain_name
 * @property string $mail
 * @property string $other
 */
class Sys extends CActiveRecord {

   public $identity;
   public $profession;
   public $hobbies;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Sys the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'sys';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('site_name, domain_name, mail', 'required'),
            array('site_name', 'length', 'max' => 50),
            array('mail', 'email'),
            array('domain_name, mail, browser_title,copyright,site_desc', 'length', 'max' => 100),
            array('status_text','safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, site_name, domain_name, mail,browser_title,copyright,site_desc,status_text', 'safe', 'on' => 'search'),
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
            'site_name' => '本站名称',
            'domain_name' => '本站域名',
            'mail' => '邮件',
            'browser_title' => '浏览器窗口标题',
            'copyright' => '版权归属',
            'site_desc' => '网站简介',
            'identity'=>'身份',
            'profession'=>'职位',
            'hobbies'=>'爱好',
            'status_text'=>'通知栏提示文字',

        );
    }

    public function getType($value = "") {
        $array = array(
            0 => "增加财富值",
            1 => "减少财富值",
        );
        return $value == "" ? $array : $array[$value];
    }
    public function getvaluesByType($type) {
        $sysModal = self::model()->find();
        $wealthSetting = unserialize($sysModal->setting_wealth);
        return $wealthSetting[$type];
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
        $criteria->compare('site_name', $this->site_name, true);
        $criteria->compare('domain_name', $this->domain_name, true);
        $criteria->compare('mail', $this->mail, true);
        $criteria->compare('browser_title', $this->browser_title, true);
        $criteria->compare('copyright', $this->copyright, true);
        $criteria->compare('site_desc', $this->site_desc, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
