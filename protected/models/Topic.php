<?php

/**
 * This is the model class for table "topic".
 *
 * The followings are the available columns in table 'topic':
 * @property integer $id
 * @property string $title
 * @property string $desc
 * @property integer $create_user
 * @property integer $create_time
 * @property integer $group_id
 */
class Topic extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Topic the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'topic';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, create_user, create_time', 'required'),
            array('id, create_user, create_time, parent_id', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 100),
            array('desc', 'length', 'max' => 500),
            array('join_user,desc', 'safe'),
            array('logo','file','allowEmpty'=>true,'types'=>'jpg,png,gif,jpeg','maxSize'=>1024*100,'tooLarge'=>'文件大于100K，上传失败！请上传小于100K的文件！'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,name,desc,create_user,create_time,parent_id,logo', 'safe'),
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
            'name' => '名称',
            'desc' => '描述',
            'create_user' => '创建人',
            'create_time' => '创建时间',
            'parent_id' => '父话题',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($id=0) {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        if(Yii::app()->controller->getAction()->getId() == "alltopic"){
           $criteria->addInCondition('id',explode(",",trim(self::getTopicList($id),",")));
        }
        $criteria->compare('id', $this->id);
        $criteria->compare('desc', $this->desc);
        $criteria->compare('create_user', $this->create_user);
      //  $criteria->compare('parent_id', $this->parent_id);
        $criteria->compare('name', $this->name, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


    /**
     * 删除主题下的评论
     */
    public function afterDelete() {
        parent::afterDelete();
        $criteria = new CDbCriteria;

        $criteria->addCondition("pk_id=" . $this->id);
        $criteria->addCondition("model='topic'");
        $commentModel = Comment::model()->findAll($criteria);
        foreach ($commentModel as $value) {
            Comment::model()->findByPk($value->id)->delete();
        }
        $criteria = new CDbCriteria;
        $criteria->addCondition("topic_id=" . $this->id);
        $commentModel = LoveTopic::model()->deleteAll($criteria);
        $notificationCriteria = new CDbCriteria;
        $notificationCriteria->addCondition("pk_id=" . $this->id);
        $notificationCriteria->addCondition("notification_type='createtopic'");
        $notifyModel = NotificationContent::model()->find($notificationCriteria);
        if ($notifyModel != null)
            NotificationContent::model()->findByPk($notifyModel->id)->delete();
    }

    public static function getTopicArray($type="count",$limit= "") {
        $criteria = new CDbCriteria;
        $id_array = array();
        if($limit!="")
        $criteria->limit = $limit;
        $model = self::model()->findAll($criteria);
        foreach ($model as $key => $value) {
           if($type=="question"){
            $count = Question::model()->count("topic_ids like '%,".$value->id.",%'");
            if($count!=0)
              $id_array[$value->id] = $count;
           }else if($type=="article"){
            $count = Article::model()->count("publish=1 and topic_ids like '%,".$value->id.",%'");
            if($count!=0)
              $id_array[$value->id] = $count;
           }else{
              $id_array[] = $value->name;  
           }
        }
        return $id_array;
    }

    public static function getTopicOrder($type = "question") {
        $array = self::model()->getTopicArray($type);
        $topic = array();
        $topicArray = array();
        if (count($array) != 0) {
            arsort($array);
            $topic = array_keys($array);
            $i = 0;
            foreach ($topic as $key => $value) {
                $i++;
                $model = self::model()->findByPk($value);
                $topicArray[$i] = array(
                    'id' => $model->id,
                    'count' => $type=="question" ? Question::model()->count("topic_ids like '%,".$model->id.",%'"):Article::model()->count("publish=1 and topic_ids like '%,".$model->id.",%'"),
                    'name' => $model->name,
                );
            }
        }
        return $topicArray;
    }

    public function listTopics($id, $hasIcon = true, $hasRoot = false, $category = '') {
        $results = array();
        // $results = ;
        $results = Yii::app()->getDb()->createCommand()->select()->from($this->tableName())->order("id asc");
        //$results = $results->order("id asc, sort desc");
        $results = $results->queryAll();

        return $this->lisTopicTreeHasIcons($results, $id, '', $category);
    }

    public function lisTopicTreeHasIcons($data, $pid, $icon = "", $category = '') {
        global $returnData;
        if (!empty($category))
            $returnData[0] = '默认话题';
        foreach ($data as $topic) {
            if (isset($_GET["id"])) {
                if (($topic['parent_id'] == $pid) && ($topic['id'] != $_GET["id"])) {
                    $id = $topic['id'];
                    $topicName = $icon . $topic['name'];
                    $returnData[$id] = $topicName;
                    $this->lisTopicTreeHasIcons($data, $topic['id'], $icon . '&nbsp;|—&nbsp;');
                    //$icon = "";
                }
            } else {
                if ($topic['parent_id'] == $pid) {
                    $id = $topic['id'];
                    $topicName = $icon . $topic['name'];
                    $returnData[$id] = $topicName;
                    $this->lisTopicTreeHasIcons($data, $topic['id'], $icon . '&nbsp;|—&nbsp;');
                    //$icon = "";
                }
            }
        }
        return $returnData;
    }

    /*
     * 商品分类
     */

    public function listCategoryTreeHasIcon($data, $pid, $icon = "") {
        global $returnData;
        foreach ($data as $topic) {
            if ($topic['parent_id'] == $pid) {
                $returnData[] = array('id' => $topic['id'], 'name' => $icon . $topic['name'], 'create_time' => $topic['create_time'], 'create_user' => $topic['create_user'], 'parent_id' => $topic['parent_id']);
                $this->listCategoryTreeHasIcon($data, $topic['id'], $icon . '&nbsp;|—&nbsp;');
            }
        }
        return $returnData;
    }
    
    /*
     * 获取关注的话题数量
     */
    public function getAttendtionCount($userId) {
        $criteria = new CDbCriteria;
        $criteria->addSearchCondition("join_user",",".$userId.",");
        return self::model()->count($criteria);
    }
    public static function getTopicList($parentId) {
        $data = "";
        $data .=$parentId.",";
        $result = self::model()->findAll("parent_id =:parent_id",array(':parent_id'=>$parentId));
        if($result){
            foreach($result as $value){
               $data .= self::getTopicList($value["id"]);
            }
        }
        return $data;
    }
    public static function  getQuestionArray($id){
        $return = array();
        $criteria = new CDbCriteria;
        $criteria->addSearchCondition("topic_ids",",".$id.",");
        $models = Question::model()->findAll($criteria);
        foreach ($models as $model) {
            $return[] = $model->id;
        }
        return $return;
    }

}
