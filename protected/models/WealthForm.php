<?php

class WealthForm extends CFormModel {

    //注册的财富值
    public $register_score;
    //注册财富值的类型
    public $register_type;
    //登陆的财富值
    public $login_score;
    //登陆财富值的类型
    public $login_type;
    //创建文章的财富值
    public $article_score;
    //创建文章财富值的类型
    public $article_type;
   //提问的财富值
    public $question_score;
    //提问财富值的类型
    public $question_type;
    //创建话题的财富值
    public $topic_score;
    //创建话题财富值的类型
    public $topic_type;
    //回答的财富值
    public $answer_score;
    //回答财富值的类型
    public $answer_type;

    public function __construct() {
        parent::__construct();
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeLabels() {
        return array(
            'register_score'=>'财富值',
            'login_score' => '财富值',
            'topic_score' => '财富值',
            'article_score' => '财富值',
            'question_score'=>'财富值',
            'answer_score' => '财富值',
            'register_type'=>'类型',
            'login_type' => '类型',
            'question_type' => '类型',
            'article_type' => '类型',
            'answer_type' => '类型',
            'topic_type' => '类型',  
        );
    }
}

?>