<?php

class DefaultController extends MController {

    public $breadcrumbs;
    public $layout = '//layouts/main';
    private $_posPath;
    public $title = "识趣";

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('index', 'personal', 'logout', 'createreply', 'createcomment', 'search', 'registercheck', 'userinfo', 'check'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('dialogue', 'delete', 'updatesys', 'deleteuser', 'updateuser', 'changelogin', 'changesyscommentshow', 'changeclassshow', 'changesysreplyshow', 'changegroupshow', 'changetopicshow', 'admin'),
                'users' => array('admin'),
            ),
            array('deny',
                'actions' => array('delete', 'updatesys', 'deleteuser', 'updateuser', 'changelogin', 'changesyscommentshow', 'changeclassshow', 'changesysreplyshow', 'changegroupshow', 'changetopicshow', 'admin'),
                'users' => array('*'),
            ),
        );
    }

    public function filters() {
        return array(
            'accessControl',
        );
    }

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            )
        );
    }

    public function beforeAction($action) {
        parent::beforeAction($action);

        $actionArray = array("createcomment", "createanswer", "createsyscomment", "createreply", "dialogue", "personal", "inbox", "mytopic", "deletemessage", "deletequestion", "deletearticle");
        if (in_array($action->getId(), $actionArray)) {
            if (Yii::app()->user->isGuest) {
                throw new CHttpException(404, '请先登录！');
            }
        }
        return true;
    }
    public function actionSearchtopic(){
        $criteria = new CDbCriteria;
        $id_array = array();
        $criteria->addSearchCondition("name", strip_tags($_GET['name']));
        $model = Topic::model()->findAll($criteria);
        foreach ($model as $key => $value) {
            $id_array[] = array(
                'id'=>$value->id,
               'text'=>$value->name,
            );
        }
       echo CJSON::encode($id_array);
        Yii::app()->end();
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {

        $model = new PloginForm;
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'loginForm') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['PloginForm'])) {
            $model->attributes = $_POST['PloginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->request->url);
        }
        $dataProvider = Question::model()->search();
        $IPaddress = $_SERVER["REMOTE_ADDR"];
        $cookies = Yii::app()->request->getCookies();
        $list_show = $cookies['User_IP']->value;
        if($list_show!=$IPaddress){
            $cookie = Yii::app()->request->getCookies();
            $cookie = new CHttpCookie('User_IP', $IPaddress);
            $cookie->expire = time() + 60 * 60 * 24;  //有限期30天
            Yii::app()->request->cookies['User_IP'] = $cookie;
            $sysModel = Sys::model()->find();
            Sys::model()->updateByPk(1,array("view_count"=>$sysModel->view_count+1));
        }
        $this->render('index', array('model' => $model, 'dataProvider' => $dataProvider));
    }
    
    /**
     * 删除在线人员
     */
    public function actionOfflineUser($id){
        UserOnline::model()->offlineUser($id);
        echo CJSON::encode(array(
            'success'=>'处理完成！',
        ));
        Yii::app()->end();
    }

    public function actionGetimage() {
        $id = $_GET["id"];
        $type = $_GET["type"];
        if ($type == "avatar") {
            $model = User::model()->findByPk($id);
            if ($model->avatar == "") {
                $path = $model->gender == 1 ? "http://09cljy.sinaapp.com/avatar/0/male_b.png" : "http://09cljy.sinaapp.com/avatar/0/female_b.png";
                header("Content-type:image/png");
                echo file_get_contents($path);
            } else {
                header("Content-type: image/jpg");
                echo $model->avatar;
            }
        } else if ($type == "topic") {
            $model = Topic::model()->findByPk($id);
            if ($model->logo == "") {
                $path = "http://www.shiqu.sinaapp.com/images/default.jpg";
                header("Content-type:image/jpg");
                echo file_get_contents($path);
            } else {
                header("Content-type: image/jpg");
                echo $model->logo;
            }
        }
    }

    /**
     * 通过ID查询用户信息
     */
    public function actionUserlabel() {
        $user_id = intval($_POST["id"]);
        $userModel = User::model()->findByPk($user_id);
        $answerUrl = $this->createUrl("default/userinfo", array("type" => "answer", "user_id" => $user_id));
        $userinfoUrl = $this->createUrl("default/userinfo", array("user_id" => $user_id));
        $questionUrl = $this->createUrl("default/userinfo", array("type" => "question", "user_id" => $user_id));
        $articleUrl = $this->createUrl("default/userinfo", array("type" => "article", "user_id" => $user_id));
        $chineseName = $userModel->chinese_name;
        $followersUrl = $this->createUrl("/default/userinfo", array("user_id" => $userModel->id, "type" => "followers"));
        $avatarSrc = $this->createUrl("/default/getimage", array("id" => $user_id, "type" => "avatar"));

        if (!Yii::app()->user->isGuest) {
            $array1 = (trim($userModel->followees, ",") == 0) ? array() : explode(",", trim($userModel->followees, ","));
            $loginUser = User::model()->findByPk(Yii::app()->user->id);
            $array2 = (trim($loginUser->followees, ",") == 0) ? array() : explode(",", trim($loginUser->followees, ","));
            $result = count(array_intersect($array1, $array2));
        }
        $tagString = "";
        if ($userModel->tags != "") {
            $tagsArray = explode(",", $userModel->tags);
            for ($i = 0; $i < count($tagsArray); $i++) {
                $tagString.='<a target="_blank" title="' . $tagsArray[$i] . '"  href="' . $this->createUrl("/default/query", array("q" => $tagsArray[$i], "type" => "user")) . '" style="margin-right:4px;" class="label ' . ((isset($_GET["q"]) && ($_GET["q"] == $tagsArray[$i])) ? 'label-info' : '') . '">' . $tagsArray[$i] . '</a>';
            }
        }
        $tpl = '<div class="profile-card card">';
        $tpl.= '<div class="upper">';
        $tpl.= '<a class="avatar-link" href="' . $userinfoUrl . '" title="' . $this->title . " - 访问" . $userModel->user_name . '主页"><img class="card-avatar" src="' . $avatarSrc . '"><span class="name" style="font-weight:bold;font-size:16px;">' . $userModel->user_name . '</span></a>';
        if ((!Yii::app()->user->isGuest) && (trim($chineseName) != "")) {
            $tpl.= '<div class="tagline">' . ($chineseName . "&nbsp&nbsp" . ($userModel->gender == 1 ? "男" : "女") . "&nbsp&nbsp") . '</div>';
        } else {
            $tpl.= '<div class="tagline">' . (($userModel->gender == 1 ? "男" : "女")) . '</div>';
        }
        if (trim($userModel->tags) != "") {
            $tpl.= '<div class="tagline">' . $tagString . '</div>';
        }
        if (trim($userModel->signature) != "") {
            $tpl.= '<div class="tagline">' . $userModel->signature . '</div>';
        }
        if (trim($userModel->desc) != "") {
            $tpl.= '<div class="tagline">' . $userModel->desc . '</div>';
        }
        if (!Yii::app()->user->isGuest) {
            $tpl.= '<div class="tagline" style="margin:5px 0;">你们有<span class="font-weight:bold;">' . $result . '</span>个共同关注者</div>';
        }
        $tpl.= '</div>';
        $tpl.= '<div class="lower clearfix">';
        $tpl.= '<div class="meta">';
        $tpl.= '<a class="item" target="_blank" href="' . $answerUrl . '">';
        $tpl.= '<span class="value">' . Answer::model()->count("is_anonymous=0 and  create_user=" . $userModel->id) . '</span>';
        $tpl.= '<span class="key">回答</span>';
        $tpl.= '</a>';
        $tpl.= '<a class="item" target="_blank" href="' . $articleUrl . '">';
        $tpl.= '<span class="value">' . Article::model()->count("publish=1 and create_user=" . $userModel->id) . '</span>';
        $tpl.= '<span class="key">文章</span>';
        $tpl.= '</a>';
        $tpl.= '<a class="item" target="_blank" href="' . $questionUrl . '">';
        $tpl.= '<span class="value">' . Question::model()->count("create_user=" . $userModel->id) . '</span>';
        $tpl.= '<span class="key">提问</span>';
        $tpl.= '</a>';
        $tpl.= '<a class="item" target="_blank" href="' . $followersUrl . '" style="border-right:none;">';
        $tpl.= '<span class="value">' . ($userModel->followers == '' ? 0 : count(explode(",", trim($userModel->followers, ",")))) . '</span>';
        $tpl.= '<span class="key">关注者</span>';
        $tpl.= '</a>';
        $tpl.= '</div>';
        $tpl.= '<div class="operation">';
        $tpl.= '<button  class="btn btn-success btn-small" style="width:78px;" data-uid="' . $user_id . '" data-username="' . User::getNameById($user_id) . '"  name="' . (Yii::app()->user->isGuest ? 'noLogin' : 'attention') . '">' . ((!in_array($userModel->id, explode(",", User::model()->findByPk(Yii::app()->user->id)->followees)) || Yii::app()->user->isGuest) ? "立即关注" : "取消关注") . '</button>';
        if (Yii::app()->user->isGuest) {
            $tpl.= '<a  class="btn btn-white btn-small" name="noLogin"  style="margin-right:8px;height:24px;"><i class="icon-envelop"></i></a>';
        } else {
            $tpl.= '<a  class="btn btn-white btn-small" style="margin-right:8px;height:24px;" data-uid="' . $user_id . '" data-username="' . User::getNameById($user_id) . '" name="reply"><i class="icon-envelop"></i></a>';
        }
        $tpl.= '</div></div></div>';
        echo $tpl;
        Yii::app()->end();
    }

    /**
     * 话题类
     */
    public function actionTopiclabel() {
        $topic_id = intval($_POST["id"]);
        $topicModel = Topic::model()->findByPk($topic_id);
        $alluserUrl = $this->createUrl("/default/topic", array("id" => $topicModel->id, "type" => "user"));
        $articleUrl = $this->createUrl("/default/topic", array("id" => $topicModel->id, "type" => "article"));
        $topicUrl = $this->createUrl("/default/topic", array("id" => $topicModel->id));
        $avatarSrc = $this->createUrl("/default/getimage", array("id" => $topicModel->id, "type" => "topic"));
        $tpl = '<div class="group-card card">';
        $tpl.= '<div class="upper">';
        $tpl.= '<a class="avatar-link" href="' . $topicUrl . '" title="' . $this->title . " - " . $topicModel->name . '"><img class="card-avatar" src="' . $avatarSrc . '"><span class="name" style="font-weight:bold;font-size:16px;">' . $topicModel->name . '</span></a>';
        if (trim($topicModel->desc) != "") {
            $tpl.= '<div class="tagline">' . $topicModel->desc . '</div>';
        }
        $tpl.= '</div>';
        $tpl.= '<div class="lower clearfix">';
        $tpl.= '<div class="meta">';
        $tpl.= '<a class="item" target="_blank" href="' . $topicUrl . '">';
        $tpl.= '<span class="value">' . Question::model()->count("topic_ids like '%," . $topic_id . "%'") . '</span>';
        $tpl.= '<span class="key">问题</span>';
        $tpl.= '</a>';
        $tpl.= '<a class="item" target="_blank" href="' . $articleUrl . '">';
        $tpl.= '<span class="value">' . Article::model()->count("publish=1 and topic_ids like '%," . $topic_id . "%'") . '</span>';
        $tpl.= '<span class="key">文章</span>';
        $tpl.= '</a>';
        $tpl.= '<a class="item" target="_blank" href="' . $alluserUrl . '" style="border-right:none;">';
        $tpl.= '<span class="value">' . ($topicModel->join_user == "" ? 0 : count(explode(",", trim($topicModel->join_user, ",")))) . '</span>';
        $tpl.= '<span class="key">关注者</span>';
        $tpl.= '</a>';
        $tpl.= '</div>';
        $tpl.= '<div class="operation">';
        $tpl.= '<button  class="btn btn-smal btn-success"  name="' . (Yii::app()->user->isGuest ? 'noLogin' : 'joinTopic') . '" data-topicid="' . $topicModel->id . '">' . ((!in_array(Yii::app()->user->id, explode(",", $topicModel->join_user)) || Yii::app()->user->isGuest) ? "关注" : "取消关注") . '</button>';
        $tpl.= '</div></div></div>';
        echo $tpl;
        Yii::app()->end();
    }

    /**
     * 用户信息查看
     */
    public function actionAlluser() {

        $model = new PloginForm;
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'loginForm') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['PloginForm'])) {
            $model->attributes = $_POST['PloginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->request->url);
        }
        $this->render('alluser', array('model' => $model));
    }
    
     /**
     * 用户信息查看
     */
    public function actionCollapsedanswer() {

        // collect user input data
        if (isset($_POST['Answer'])) {
            $answerModel = Answer::model()->findByPk($_POST['Answer']['id']);
             if(Yii::app()->user->name=="admin"){
                 $hide_answer_id = "";
                 $questModel = Question::model()->findByPk($answerModel->question_id);
                 $hideArray = $questModel->hide_answer_id==""? array() : explode(",", trim($questModel->hide_answer_id,","));
                 if(!in_array($answerModel->id,$hideArray)){
                     $hide_answer_id = $questModel->hide_answer_id .$answerModel->id .",";
                 }else{
                    for($i=0;$i<count($hideArray);$i++){
                        if($hideArray[$i]!=$answerModel->id){
                            $hide_answer_id.=$hideArray[$i].",";
                        }
                    }
                 }
                 Question::model()->updateByPk($answerModel->question_id,array("hide_answer_id"=>$hide_answer_id));
                 Answer::model()->updateByPk($answerModel->id,array("hide_reason"=>$_POST['Answer']['hide_reason']));
                 $this->redirect(array("question","id"=>$answerModel->question_id));
             }
        }
    }

    /**
     * 用户信息查看
     */
    public function actionCreatearticle() {

        $model = new Article;
        $this->articleAjaxValidation($model);
        if (isset($_POST['Article'])) {
            $model->attributes = $_POST['Article'];
            $model->create_user = Yii::app()->user->id;
            $model->create_time = time();
            $model->update_time = time();
            $model->topic_ids = $_POST['Article']['topic_ids'];
            if ($model->save()) {
                $topic_ids = "";
                if ($model->topic_ids != "") {
                    $topicArray = explode(",", trim($model->topic_ids, ","));
                    $topic_ids.=",";
                    for ($i = 0; $i < count($topicArray); $i++) {
                        $topicModel = Topic::model()->find("name='" . $topicArray[$i] . "'");
                        if ($topicModel == NULL) {
                            $newModel = new Topic();
                            $newModel->name = $topicArray[$i];
                            $newModel->create_user = Yii::app()->user->id;
                            $newModel->create_time = time();
                            if ($newModel->save()) {
                                $topic_ids.=$newModel->id . ",";
                            }
                        } else {
                            $topic_ids.=$topicModel->id . ",";
                        }
                    }
                    Article::model()->updateByPk($model->id, array("topic_ids" => $topic_ids));
                }
                $this->redirect(array("personal", 'type' => 'article'));
            }
        }
        $this->render('editarticle', array('model' => $model));
    }

    /**
     * 用户修改文章
     */
    public function actionUpdatearticle($id) {
        $model = Article::model()->findByPk($id);
        if (($model->create_user != Yii::app()->user->id) || (Yii::app()->user->name != "admin")) {
            throw new CHttpException(900, '您没有修改权限！');
        }
        $topic_names = "";
        if ($model->topic_ids != "") {
            $topicArray = explode(",", trim($model->topic_ids, ","));
            for ($i = 0; $i < count($topicArray); $i++) {
                $topicModel = Topic::model()->findByPk($topicArray[$i]);
                $topic_names.=$topicModel->name . ",";
            }
        }
        $model->topic_ids = trim($topic_names, ",");
        $this->articleAjaxValidation($model);
        if (isset($_POST['Article'])) {
            $model->attributes = $_POST['Article'];
            $model->update_time = time();
            $model->topic_ids = $_POST['Article']['topic_ids'];
            if ($model->save()) {
                $topic_ids = "";
                if ($model->topic_ids != "") {
                    $topicArray = explode(",", trim($model->topic_ids, ","));
                    $topic_ids.=",";
                    for ($i = 0; $i < count($topicArray); $i++) {
                        $topicModel = Topic::model()->find("name='" . $topicArray[$i] . "'");
                        if ($topicModel == NULL) {
                            $newModel = new Topic();
                            $newModel->name = $topicArray[$i];
                            $newModel->create_user = Yii::app()->user->id;
                            $newModel->create_time = time();
                            if ($newModel->save()) {
                                $topic_ids.=$newModel->id . ",";
                            }
                        } else {
                            $topic_ids.=$topicModel->id . ",";
                        }
                    }
                    Article::model()->updateByPk($model->id, array("topic_ids" => $topic_ids));
                }
            }
            $this->redirect(array("personal", 'type' => 'article'));
        }
        $this->render('editarticle', array('model' => $model));
    }

    /**
     * 创建评论
     */
    public function actionCreatecomment() {
        $modelComment = new Comment;
        if (isset($_POST['Comment'])) {
            $modelComment->attributes = $_POST['Comment'];
            $modelComment->model = $_POST['Comment']['model'];
            $modelComment->create_time = time();
            $modelComment->user_id = Yii::app()->user->id;
            if ($modelComment->save()) {
                $this->redirect(array($modelComment->model, "id" => $modelComment->pk_id, "#" => "form"));
            }
        }
    }

    /**
     * 创建回答
     */
    public function actionCreateanswer() {
        $modelAnswer = new Answer;
        if (isset($_POST['Answer'])) {

            $modelAnswer->attributes = $_POST['Answer'];
            $modelAnswer->create_time = time();
            $modelAnswer->create_user = Yii::app()->user->id;
            $modelAnswer->is_anonymous = is_array($_POST['Answer']['is_anonymous']) ? 1 : 0;
            $questionModel = Question::model()->findByPk($modelAnswer->question_id);
            if ($questionModel == NULL) {
                throw new CHttpException(404, '没有此问题！');
            } else if (Yii::app()->user->isGuest) {
                throw new CHttpException(404, '回答问题前，请先登录！');
            }
            if ($modelAnswer->save()) {
                $this->redirect(array('question', "id" => $modelAnswer->question_id, "#" => "form"));
            }
        }
    }

    /**
     * 修改回答
     */
    public function actionUpdateanswer($id) {
        $modelAnswer = Answer::model()->findByPk($id);
        if (isset($_POST['Answer'])) {
            $modelAnswer->attributes = $_POST['Answer'];
            $modelAnswer->is_anonymous = is_array($_POST['Answer']['is_anonymous']) ? 1 : 0;
            $questionModel = Question::model()->findByPk($modelAnswer->question_id);
            if ($modelAnswer->create_user != Yii::app()->user->id) {
                throw new CHttpException(404, '没有此权限！');
            } else if ($questionModel == NULL) {
                throw new CHttpException(404, '没有此问题！');
            } else if (Yii::app()->user->isGuest) {
                throw new CHttpException(404, '回答问题前，请先登录！');
            }
            if ($modelAnswer->save()) {
                $this->redirect(array('question', "id" => $modelAnswer->question_id, "#" => "answer_" . $modelAnswer->id));
            }
        }
    }

    /**
     * 创建点评
     */
    public function actionCreateSysComment() {
        $modelComment = new SysComment;
        $this->commentAjaxValidation($modelComment);
        if (isset($_POST['SysComment'])) {
            $modelComment->attributes = $_POST['SysComment'];
            $modelComment->create_time = time();
            $modelComment->user_id = Yii::app()->user->id;
            $modelComment->tags = $_POST['tags'];
            $modelComment->score = $_POST['SysComment']['score'];
            if ($modelComment->save()) {
                $this->redirect(array('comment'));
            }
        }
    }

    /**
     * 用户信息
     */
    public function actionUserinfo() {
        //   $this->layout = '//layouts/main';
        $user_id = $_GET["user_id"];
        $model = User::model()->findByPk($user_id);
        $privArray = unserialize($model->priv);
        $visitPriv = $privArray['visit_priv'];
        $countArray = unserialize($model->visit_count);
        $visitCount = $countArray['visit_count'] ? $countArray['visit_count'] : 0;
        $refuseCount = $countArray['refuse_count'] ? $countArray['refuse_count'] : 0;
        $followeesArray = $model->followees == "" ? array() : explode(",", trim($model->followees));
        if ((!Yii::app()->user->isGuest) && (Yii::app()->user->id != $user_id) && (($visitPriv == 2) || (($visitPriv == 1) && (in_array(Yii::app()->user->id, $followeesArray))))) {

            $count = Visit::model()->count("is_visit=0 and from_user = " . Yii::app()->user->id . " and to_user = " . $user_id);
            if ($count == 0) {
                $newVisit = new Visit;
                $newVisit->from_user = Yii::app()->user->id;
                $newVisit->to_user = $user_id;
                $newVisit->create_time = time();
                $newVisit->is_visit = 0;
                $newVisit->save();
                $visitArray = array(
                    'visit_count' => $visitCount + 1,
                    'refuse_count' => $refuseCount + 1,
                );
                User::model()->updateByPk($user_id, array("visit_count" => serialize($visitArray)));
            } else {
                $time = strtotime(date("Y-m-d", time()));
                $oldCount = Visit::model()->count("is_visit=0 and from_user = " . Yii::app()->user->id . " and to_user = " . $user_id . " and create_time > " . $time);
                if ($oldCount == 0) {
                    $visitArray = array('visit_count' => $visitCount + 1, 'refuse_count' => $refuseCount + 1);
                    User::model()->updateByPk($user_id, array("visit_count" => serialize($visitArray)));
                }
                $oldVist = Visit::model()->find("is_visit=0 and from_user = " . Yii::app()->user->id . " and to_user = " . $user_id);
                $oldVist->create_time = time();
                $oldVist->save();
            }
            throw new CHttpException(404, '你没有访问TA空间的权限！');
        } else if (((Yii::app()->user->isGuest) && ($visitPriv == 2))) {
            if (!isset($_GET['type'])) {
                $visitArray = array(
                    'visit_count' => $visitCount + 1,
                    'refuse_count' => $refuseCount + 1,
                );
                User::model()->updateByPk($user_id, array("visit_count" => serialize($visitArray)));
            }
            throw new CHttpException(404, '你没有访问TA空间的权限！');
        } else {
            if (Yii::app()->user->isGuest) {
                if (!isset($_GET['type'])) {
                    $visitArray = array(
                        'visit_count' => $visitCount + 1,
                        'refuse_count' => $refuseCount,
                    );
                    User::model()->updateByPk($user_id, array("visit_count" => serialize($visitArray)));
                }
            } else if (Yii::app()->user->id != $user_id) {
                $count = Visit::model()->count("is_visit=1 and from_user = " . Yii::app()->user->id . " and to_user = " . $user_id);
                if ($count == 0) {
                    $newVisit = new Visit;
                    $newVisit->from_user = Yii::app()->user->id;
                    $newVisit->to_user = $user_id;
                    $newVisit->create_time = time();
                    $newVisit->is_visit = 1;
                    $newVisit->save();
                    $visitArray = array(
                        'visit_count' => $visitCount + 1,
                        'refuse_count' => $refuseCount,
                    );
                    User::model()->updateByPk($user_id, array("visit_count" => serialize($visitArray)));
                } else {
                    $time = strtotime(date("Y-m-d", time()));
                    $oldCount = Visit::model()->count("is_visit=1 and from_user = " . Yii::app()->user->id . " and to_user = " . $user_id . " and create_time > " . $time);
                    if ($oldCount == 0) {
                        $visitArray = array('visit_count' => $visitCount + 1, 'refuse_count' => $refuseCount);
                        User::model()->updateByPk($user_id, array("visit_count" => serialize($visitArray)));
                    }
                    $oldVist = Visit::model()->find("is_visit=1 and from_user = " . Yii::app()->user->id . " and to_user = " . $user_id);
                    $oldVist->create_time = time();
                    $oldVist->save();
                }
            }
            if (isset($_GET["pk_id"])) {
                if (Notification::model()->findByPk($_GET["pk_id"])->remind_flag == 0) {
                    Notification::model()->updateByPk($_GET["pk_id"], array("remind_flag" => 1));
                }
            }
            $modelComment = new Comment;
            $criteria = new CDbCriteria;
            $criteria->addCondition("publish=1 and create_user=" . $user_id);
            $dataProvider = new CActiveDataProvider('Article', array(
                'criteria' => $criteria,
                'sort' => array(
                    'defaultOrder' => 'creaet_time desc'
                ),
                'pagination' => array(
                    'pageVar' => 'page',
                    'pageSize' => 5)
                    )
            );
            $this->render('userinfo', array("user_id" => $user_id, 'dataProvider' => $dataProvider, 'modelComment' => $modelComment, "model" => $model));
        }
    }

    /**
     * 创建回复
     */
    public function actionCreatereply() {
        $modelReply = new Comment;
        $modelCommentModel = Comment::model()->findByPk($_POST['comment_id']);
        $modelReply->parent_id = $_POST['comment_id'];
        $modelReply->user_id = Yii::app()->user->id;
        $modelReply->pk_id = $modelCommentModel->pk_id;
        $modelReply->model = $modelCommentModel->model;
        $modelReply->content = $_POST['content'];
        $modelReply->create_time = time();
        if ($modelReply->save()) {

            $pkId = $modelCommentModel->pk_id;
            if ($modelReply->model == "article") {
                $this->redirect(array('article', "id" => $pkId, "#" => "sys_comment_" . $_POST['comment_id'], "page" => $_POST['page'], "action" => "view"));
            } else if ($modelReply->model == "answer") {
                $this->redirect(array('answer', "id" => $pkId, "#" => "sys_comment_" . $_POST['comment_id'], "page" => $_POST['page'], "action" => "view"));
            }
        } else {
            $this->redirect(array('index'));
        }
    }

    /**
     * 创建回复
     */
    public function actionViewnotify($id) {
        $notificationModel = Notification::model()->findByPk($id);
        if ($notificationModel != NULL) {
            Notification::model()->updateByPk($id, array("remind_flag" => 1), "to_id=:to_id and remind_flag=0", array(":to_id" => Yii::app()->user->id));
            $notificationContentModel = NotificationContent::model()->findByPk($notificationModel->content_id);
            if ($notificationContentModel->notification_type == "reportanswer") {
                $this->redirect(array('answer', "id" => $notificationContentModel->pk_id));
            } else if ($notificationContentModel->notification_type == "reportquestion") {
                $this->redirect(array('question', "id" => $notificationContentModel->pk_id));
            } else if ($notificationContentModel->notification_type == "reportarticle") {
                $this->redirect(array('article', "id" => $notificationContentModel->pk_id));
            } else {
                $this->redirect(array('index'));
            }
        } else {
            $this->redirect(array('index'));
        }
    }

    /**
     * 创建点评回复
     */
    public function actionCreatesysreply() {
        $modelReply = new SysCommentReply;
        $this->commentAjaxValidation($modelReply);
        $type = $_POST['type'];
        $modelReply->comment_id = $_POST['comment_id'];
        $modelReply->user_id = $_POST['user_id'];
        $modelReply->content = $_POST['content'];
        $modelReply->create_time = time();
        $modelReply->reply_user_id = Yii::app()->user->id;
        if ($modelReply->save()) {
            $this->redirect(array('comment', "type" => $type, "#" => "sys_comment_" . $_POST['comment_id'], "page" => $_POST['page']));
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        $model = new PloginForm;
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'loginForm') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['PloginForm'])) {
            $model->attributes = $_POST['PloginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $userModal = User::model()->findByPk(Yii::app()->user->id);
                $visitTime = strtotime(date("Y-m-d", $userModal->last_visit_time));
                $time = strtotime(date("Y-m-d", time()));
                $score = Sys::model()->getvaluesByType("login_score");
                $wealthModel = new Wealth;
                if ((Sys::model()->getvaluesByType("login_type") == "0") && ($visitTime != $time)) {
                    $userModal->wealth = $userModal->wealth + intval($score);
                    $content = "登陆成功，奖励" . $score . "个财富值";
                    $data = array('content' => $content, 'create_time' => time());
                    $wealthModel->insertWealth($data);
                    $userModal->last_visit_time = time();
                    $userModal->save();
                } else {
                    $userModal->last_visit_time = time();
                    $userModal->save();
                }
                $this->redirect(Yii::app()->request->getUrlReferrer());
            }
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        $userOnline = UserOnline::model()->findByPk(Yii::app()->user->id);
        User::model()->updateByPk(Yii::app()->user->id, array("last_visit_time" => time()));
        if ($userOnline !== null)
            $userOnline->delete();
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * 点评界面
     */
    public function actionComment() {
        $criteria = new CDbCriteria;
        $userId = Yii::app()->user->id;
        if ((isset($_GET["score"])) && ($_GET["score"] != "")) {
            $criteria->addCondition("score='" . $_GET["score"] . "'");
        }
        if ((isset($_GET["tags"])) && ($_GET["tags"] != "")) {
            $criteria->addInCondition("id", SysComment::model()->getIdByTagsName($_GET["tags"]));
        }
        $criteria->addCondition("is_show=0");
        $dataProvider = new CActiveDataProvider('SysComment', array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'create_time asc'
            ),
            'pagination' => array(
                'pageVar' => 'page',
                'pageSize' => 10)
                )
        );
        $dataProviderByTime = new CActiveDataProvider('SysComment', array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'create_time desc'
            ),
            'pagination' => array(
                'pageVar' => 'page',
                'pageSize' => 10)
                )
        );
        $total = SysComment::model()->getCountById();
        $countArray = SysComment::model()->getScoreArrayById();
        $tagsArray = SysComment::model()->getTagsOrder();
        arsort($tagsArray);
        $tagsArray = array_keys($tagsArray);
        $this->render('comment', array('dataProviderByTime' => $dataProviderByTime, 'dataProvider' => $dataProvider, 'total' => $total, 'countArray' => $countArray, 'tagsArray' => $tagsArray));
    }

    public function actionRegister() {
        $password = $_POST['password'];
        $psname = $_POST['psname'];
        $model = new User();
        $model->setIsNewRecord(TRUE);
        $model->user_name = $psname;
        $model->register_time = time();
        $model->password = crypt($password);
        if ($model->save()) {
//                $this->redirect(array('default/index'));
            echo 'ok';
        } else {
            throw new CHttpException(500, '保存失败！');
        }
    }

    public function actionRegisteruser() {
        $model = new User();
        $this->registerAjaxValidation($model);
        //   $model->setIsNewRecord(TRUE);
        if (isset($_POST["User"])) {

            $model->attributes = $_POST["User"];
            $model->user_name = $_POST["User"]["user_name"];
            $model->register_time = time();
            $uploadedFiles = CUploadedFile::getInstance($model, 'avatar');
            $model->avatar = empty($uploadedFiles) ? "" : $uploadedFiles->getName();
            $model->password = crypt($_POST["User"]["password"]);
            $model->retype_password = $model->password;
            $recvArray = array(
                "subscribe_member_follow" => 1,
                "subscribe_ask_like" => 1,
                "subscribe_diary_like" => 1,
                "subscribe_recent_like" => 1,
                "subscribe_picture_like" => 1,
                "subscribe_timeline_like" => 1,
                "subscribe_message_like" => 0,
            );
            $model->recv_option = serialize($recvArray);
            $visitArray = array(
                "visit_count" => 0,
                "refuse_count" => 0,
            );
            $model->visit_count = serialize($visitArray);
            $privArray = array(
                "visit_priv" => 0,
                "comment_priv" => 0,
            );
            $model->priv = serialize($privArray);
            if ($model->save()) {
                // 头像缩略图
                if (!empty($uploadedFiles)) {
                    if (!is_dir(Yii::app()->params["avatarPath"] . $model->id)) {
                        mkdir(Yii::app()->params["avatarPath"] . $model->id);
                    };
                    $fileExt = CFileHelper::getExtension($uploadedFiles->getName());
                    $fileName = rtrim($uploadedFiles->getName(), "." . $fileExt);
                    $filepath = Yii::app()->params["avatarPath"] . $model->id . '/' . md5($fileName) . '.' . $fileExt;
                    $ret = $uploadedFiles->saveAs($filepath);
                    $image = Yii::app()->image->load($filepath);
                    $image->resize(100, 100, Image::NONE);
                    $image->save($filepath); // or $image->save('images/small.jpg');
                }
                $score = Sys::model()->getvaluesByType("register_score");
                $wealthModel = new Wealth;
                if (Sys::model()->getvaluesByType("register_type") == "0") {
                    $content = "注册成功，奖励" . $score . "个财富值";
                    $data = array('content' => $content, 'create_time' => $model->register_time, 'create_user' => $model->id);
                    $wealthModel->insertWealth($data);
                    User::model()->updateByPk($model->id, array("wealth" => intval($score)));
                }
                $this->redirect(Yii::app()->request->getUrlReferrer());
            }
        } else {
            $this->redirect(array("alluser"));
        }
        //     var_dump($_POST["User"]);
    }

    public function actionInitpassword() {
        if (isset($_POST["User"])) {
            $model = User::model()->findByPk($_POST["User"]["id"]);
            $model->attributes = $_POST["User"];
            $model->user_name = $_POST["User"]["user_name"];
            if ($_POST["User"]["password"] != "") {
                $model->password = crypt($_POST["User"]["password"]);
            } else {
                $model->password = "";
            }
            $model->save();
            echo CJSON::encode($model->getErrors());
        }
    }

    /**
     * 个人中心界面
     */
    public function actionPersonal() {
        $successMessage = array();
        $user_id = Yii::app()->user->id;
        if (isset($_POST["User"])) {
            $userModel = $this->loadModelUser(Yii::app()->user->id);

            if ($_GET["type"] == "user") {
                $uploadedFiles = CUploadedFile::getInstance($userModel, 'avatar');
                if (!empty($uploadedFiles)) {
                    $fp = fopen($uploadedFiles->tempName, 'r');
                    $content = fread($fp, filesize($uploadedFiles->tempName));
                    fclose($fp);
                    $userModel->avatar = $content;
                }
                $userModel->attributes = $_POST["User"];
                if ($userModel->save()) {
                    $successMessage[] = '个人信息修改成功';
                }
            }
        }
        $modelArticle = new Article();
        if (isset($_POST["Article"])) {


            if (isset($_GET["id"])) {
                $modelArticle = $this->loadDiaryModel($_GET["id"]);
            };
            $modelArticle->attributes = $_POST["Article"];
            $modelArticle->content = $_POST["Article"]["content"];
            $modelArticle->subject = $_POST["Article"]["subject"];
            $modelArticle->create_user = Yii::app()->user->id;
            $modelArticle->create_time = time();
            $modelArticle->update_time = time();
            if ($modelArticle->save()) {
                $this->redirect(array('personal', 'type' => 'diary'));
            }
        };

        $user_id = $_GET["user_id"];
        $Message = Message::model()->listDialogue($user_id);
        if ((isset($_GET["user_id"])) && ($_GET["action"] == "view")) {
            $criteria = new CDbCriteria();
            $criteria->addCondition("create_user =" . $_GET["user_id"] . " and to_uid =" . Yii::app()->user->id . " and remind_flag = 1 and delete_flag != 1");
            $model = Message::model()->updateAll(array("remind_flag" => 0), $criteria);
        }
        foreach ($Message->getData() as $v) {
            if ($v['create_user'] == Yii::app()->user->id)
                $v['is_me_send'] = true;
            else
                $v['is_me_send'] = false;
        };
        $this->render('personal', array("dataProvider" => $dataProvider, "model" => $modelArticle, 'userModel' => $userModel, 'successMessage' => $successMessage, 'Message' => $Message->getData()));
    }

    /**
     * 微讯对话记录
     */
    public function actionDialogue() {
        $id = intval($_GET['id']);
        $model = Message::model()->listDialogue($id);
        //处理原始数据，增加头像与信息发送方判断
        foreach ($model->getData() as $v) {
            if ($v['create_user'] == Yii::app()->user->id)
                $v['is_me_send'] = true;
            else
                $v['is_me_send'] = false;
        }
        Message::model()->updateAll(array("remind_flag" => 0), "create_user=:create_user and to_uid=:to_uid and delete_flag!=:delete_flag", array(":create_user" => $id, ":to_uid" => Yii::app()->user->id, ":delete_flag" => 1));
        $this->render('/default/dialogue', array(
            'data' => $model->getData(),
            'total' => $model->totalItemCount
        ));
    }

    /**
     * 修改空间访问权限
     */
    public function actionPrivvisit() {
        if (isset($_POST['User'])) {
            $userModel = User::model()->findByPk(Yii::app()->user->id);
            $userPriv = unserialize($userModel->priv);
            $newPriv = array(
                'visit_priv' => $_POST['User']['visit_priv'],
            );
            User::model()->updateByPk(Yii::app()->user->id, array("priv" => serialize($newPriv)));
            $this->redirect(Yii::app()->request->getUrlReferrer());
        }
    }

    /**
     * 删除一条微讯
     */
    public function actionDeletemessage() {
        if (Yii::app()->request->isPostRequest) {
            $id = intval($_POST["id"]);
            $model = Message::model()->findByPk($id);
            if ($model->create_user == Yii::app()->user->id) {
                if (($model->remind_flag == Message::$msg_stauts["to"]["unread_remind_flag"]) || ($model->delete_flag == Message::$msg_stauts["to"]["unread_delete_flag"])) {
                    Message::model()->deleteByPk($model->id);
                } else {
                    $delete_flag = intval(Message::$msg_stauts["from"]["unread_delete_flag"]);
                    Message::model()->updateByPk($id, array('delete_flag' => $delete_flag));
                };
            } else {
                if ($model->delete_flag == Message::$msg_stauts["from"]["unread_delete_flag"]) {
                    Message::model()->deleteByPk($model->id);
                } else {
                    $delete_flag = intval(Message::$msg_stauts["to"]["unread_delete_flag"]);
                    Message::model()->updateByPk($id, array('delete_flag' => $delete_flag));
                }
            };
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * 微讯按人删除对话记录
     */
    public function actionDeletebyuser() {
        $return = array();
        if (isset($_POST['uid'])) {
            $chat_uid = intval($_POST['uid']);
            $me_send == $me_recv = '';

            $criteria = new CDbCriteria();
            $criteria->condition = '(create_user = :create_user and to_uid = :to_uid) or (create_user = :to_uid and to_uid = :create_user)';
            $criteria->params = array(':create_user' => Yii::app()->user->id, ':to_uid' => $chat_uid);
            $model = Message::model()->findAll($criteria);
            foreach ($model as $data) {
                if ($data->create_user == Yii::app()->user->id)
                    $me_send.= $data->id . ",";
                else
                    $me_recv.= $data->id . ",";
            }
            if ($me_send)
                Message::model()->delete_msg($me_send, 2);
            if ($me_recv)
                Message::model()->delete_msg($me_recv, 1);

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            $return = array('code' => 'ok', 'tips' => '删除成功');
        }else {
            $return = array('code' => 'failed', 'tips' => '删除失败');
        };

        echo CJSON::encode($return);
        Yii::app()->end();
    }

    /**
     * 我的话题
     */
    public function actionMytopic() {
        $criteria = new CDbCriteria;
        $criteria->limit = 10;
        $userId = Yii::app()->user->id;
        $criteria->addSearchCondition("join_user", "," . $userId . ",");
        $dataProvider = new CActiveDataProvider('Topic', array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'create_time desc'
            )
        ));
        $model = new LoveTopic();
        $model->create_user = Yii::app()->user->id;
        $topicDataProvider = $model->search();
        $this->render('mytopic', array('dataProvider' => $dataProvider, 'topicDataProvider' => $topicDataProvider));
    }

    /**
     * 拖动元素处理
     */
    public function actionNewSort() {
        $login_user_id = Yii::app()->user->id;
        $list = explode("#", $_POST['list']); //print_r($list);
        $j = 0;
        foreach ($list as $v) {
            LoveTopic::model()->updateAll(array("order_no" => $i), "id=:id and create_user=:create_user", array(":id" => $v, ":create_user" => $login_user_id));
            $i++;
        }
        echo count($list);
    }

    /**
     * 删除固定话题
     */
    public function actionRemovetopic() {
        $login_user_id = Yii::app()->user->id;
        $topic_id = $_POST["topic_id"];
        $delete = LoveTopic::model()->deleteAll("topic_id=:topic_id and create_user=:create_user", array(":topic_id" => $topic_id, ":create_user" => $login_user_id));
        $message = $delete ? "ok" : "false";
        $count = LoveTopic::model()->count("create_user=:create_user", array(":create_user" => $login_user_id));
        $return = array('message' => $message, 'count' => $count);
        echo CJSON::encode($return);
        Yii::app()->end();
    }

    /**
     * 置顶固定话题
     */
    public function actionUptopic() {
        $login_user_id = Yii::app()->user->id;
        $topic_id = $_POST["topic_id"];
        $model = LoveTopic::model()->find("create_user=:create_user order by order_no asc", array(":create_user" => $login_user_id));
        $update = LoveTopic::model()->updateAll(array('order_no' => $model ? ($model->order_no > 1 ? ($model->order_no - 1) : 0 ) : 0), "topic_id=:topic_id and create_user=:create_user", array(":topic_id" => $topic_id, ":create_user" => $login_user_id));
        $count = LoveTopic::model()->count("create_user=:create_user and order_no=0", array(":create_user" => $login_user_id));
        if ($count > 1) {
            LoveTopic::model()->updateAll(array('order_no' => 1, "topic_id<>:topic_id and create_user=:create_user", array(":topic_id" => $topic_id, ":create_user" => $login_user_id)));
        }
        //   $update = LoveTopic::model()->updateAll(array('order_no' => $model ? ($model->order_no > 1 ? ($model->order_no - 1) : 0 ) : 0), "topic_id=:topic_id and create_user=:create_user", array(":topic_id" => $topic_id, ":create_user" => $login_user_id));

        $message = ($count == 1 || $update) ? "ok" : "false";
        $return = array('message' => $message, 'count' => $count);
        echo CJSON::encode($return);
        Yii::app()->end();
    }

    /**
     * 全部话题
     */
    public function actionALltopic() {

        $criteria = new CDbCriteria;
        $criteria->addCondition("parent_id=0");
        $topicModel = Topic::model()->findAll($criteria);
        $criteria = new CDbCriteria;
        $criteria->order = "create_time asc";
        $model = Topic::model()->find($criteria);
        $newModel = new Topic();
        $id = isset($_GET["id"]) ? ($_GET["id"]) : ($model ? $model->id : 0);
        $topicDataProvider = $newModel->search($id);
        $hotDataProvider = $newModel->search($id);
        $this->render('alltopic', array('topicModel' => $topicModel, "model" => $model, 'topicDataProvider' => $topicDataProvider, 'hotDataProvider' => $hotDataProvider));
    }

    /**
     * 删除更新历史记录
     */
    public function actionDeletedatalog() {

        $id = isset($_GET["id"]) ? $_GET["id"] : $_POST["id"];
        $model = DataLog::model()->findByPk($id)->delete();
        echo "ok";
        Yii::app()->end();
    }

    /**
     * 删除用户
     */
    public function actionDeleteuser($id) {
        $model = User::model()->findByPk($id)->delete();
        echo "ok";
        Yii::app()->end();
    }

    /**
     * 删除一个话题
     */
    public function actionDeletetopic() {

        $id = isset($_GET["id"]) ? $_GET["id"] : $_POST["id"];
        $model = Topic::model()->findByPk($id);
        if ($model->create_user == Yii::app()->user->id || Yii::app()->user->name == "admin")
            $model->delete();
        echo "ok";
        Yii::app()->end();
    }

    /**
     * 删除一篇文章
     */
    public function actionDeletearticle() {
        if (Yii::app()->request->isPostRequest) {
            $id = $_POST["id"];
            $model = Article::model()->findByPk($id);
            if ($model->create_user == Yii::app()->user->id || Yii::app()->user->name == "admin")
                $model->delete();
            echo "ok";
            Yii::app()->end();
        }else {
            echo "error";
        }
    }

    /**
     * 删除一个问题
     */
    public function actionDeletequestion() {
        $id = isset($_GET["id"]) ? $_GET["id"] : $_POST["id"];
        $model = Question::model()->findByPk($id);
        if ($model->create_user == Yii::app()->user->id || Yii::app()->user->name == "admin") {
            $model->delete();
            echo "ok";
        } else {
            echo "false";
        }
        Yii::app()->end();
    }

    /**
     * 删除一个回答
     */
    public function actionDeleteanswer() {
        $id = $_POST["answer_id"];
        $model = Answer::model()->findByPk($id);
        if ($model->create_user == Yii::app()->user->id || Yii::app()->user->name == "admin") {
            $model->delete();
            echo "ok";
        } else {
            echo "false";
        }
        Yii::app()->end();
    }

    /**
     * 发布一篇文章
     */
    public function actionPublisharticle($id) {
        $model = Article::model()->findByPk($id);
        if ($model->create_user == Yii::app()->user->id || Yii::app()->user->name == "admin") {
            $model->publish = 1;
            if ($model->save()) {
                echo "ok";
            } else {
                echo "false";
            }
        } else {
            echo "false";
        }
        Yii::app()->end();
    }

    public function actionSearch() {
        $arr = array();
        $key = $_GET['q'];
        $criteria = new CDbCriteria();
        $criteria->addCondition('not_login=0  and id!=' . Yii::app()->user->id);
        $criteria->addSearchCondition('user_name', $key);
        $data = User::model()->findAll($criteria);
        if (empty($data)) {
            echo json_encode(array('message' => '没有找到此人', 'user_name' => '没有找到此人', 'returnValue' => false)) . "\n";
        } else {
            foreach ($data as $keys => $value) {
                echo json_encode(array('returnValue' => true, 'user_name' => $value->user_name, 'user_id' => $value->id, 'url' => $this->createUrl("getimage", array("id" => $value->id, "type" => "avatar")))) . "\n";
            }
        }
    }

    public function actionSearchall() {
        $arr = array();
        $key = Yii::app()->request->isPostRequest ? $_POST['search'] : $_GET['q'];
        if (trim($key) == "") {  //什么也没有输入转到提示页面
            $this->redirect($this->createUrl("query"));
        } else {
            if (Yii::app()->request->isPostRequest) {
                $url = $this->createUrl("query", array("q" => $key));
                $this->redirect($url);
            } else {
                $criteria = new CDbCriteria();
                $criteria->addCondition('not_login=0');
                $criteria->addSearchCondition('user_name', $key);
                $criteria->addSearchCondition('tags', $key, 'true', 'OR');
                $dataUser = User::model()->findAll($criteria);
                if (!empty($dataUser)) {
                    echo json_encode(array('message' => '用户', 'returnValue' => false)) . "\n";
                    foreach ($dataUser as $keys => $value) {
                        echo json_encode(array('returnValue' => true, 'type' => 'user', 'message' => $value->user_name, 'user_id' => $value->id, 'url' => $this->createUrl("getimage", array("id" => $value->id, "type" => "avatar")), 'href' => $this->createUrl("userinfo", array("user_id" => $value->id)))) . "\n";
                    }
                }
                $criteria = new CDbCriteria();
                $criteria->addSearchCondition('name', $key);
                $dataTopic = Topic::model()->findAll($criteria);
                if (!empty($dataTopic)) {
                    echo json_encode(array('message' => '话题', 'returnValue' => false)) . "\n";
                    foreach ($dataTopic as $keys => $value) {

                        $tip = "共" . Comment::model()->getCount($value->id, "topic") . "个回应";
                        echo json_encode(array('returnValue' => true, 'type' => 'topic', 'tip' => $tip, 'message' => $value->name, 'user_id' => $value->id, 'href' => $this->createUrl("topic", array("id" => $value->id)))) . "\n";
                    }
                }
                $criteria = new CDbCriteria();
                $criteria->addSearchCondition('subject', $key);
                $dataArticle = Article::model()->findAll($criteria);
                if (!empty($dataArticle)) {
                    echo json_encode(array('message' => '文章', 'returnValue' => false)) . "\n";
                    foreach ($dataArticle as $keys => $value) {
                        $tip = "共" . Comment::model()->getCount($value->id, "article") . "条评论";
                        echo json_encode(array('returnValue' => true, 'type' => 'diary', 'tip' => $tip, 'message' => $value->subject, 'user_id' => $value->id, 'href' => $this->createUrl("article", array("id" => $value->id)))) . "\n";
                    }
                }
                $criteria = new CDbCriteria();
                $criteria->addSearchCondition('title', $key);
                $dataQuestion = Question::model()->findAll($criteria);
                if (!empty($dataQuestion)) {
                    echo json_encode(array('message' => '问题', 'returnValue' => false)) . "\n";
                    foreach ($dataQuestion as $keys => $value) {
                        $tip = "共" . Answer::model()->count("question_id=" . $value->id) . "个回答";
                        echo json_encode(array('returnValue' => true, 'type' => 'topic', 'tip' => $tip, 'message' => $value->title, 'user_id' => $value->id, 'href' => $this->createUrl("question", array("id" => $value->id)))) . "\n";
                    }
                }
                if (empty($dataGroup) && empty($dataUser) && empty($dataTopic) && empty($dataArticle)) {
                    echo json_encode(array('message' => '没有找到相应的结果', 'returnValue' => false)) . "\n";
                } else {
                    echo json_encode(array('message' => '查看全部搜索结果', 'returnValue' => true, 'type' => 'all', 'returnValue' => true, 'href' => $this->createUrl("query", array("q" => $key))));
                }
            }
        }
    }

    /**
     * 查询界面
     */
    public function actionQuery() {
        $key = $_GET["q"];
        $criteria = new CDbCriteria();
        $criteria->addCondition('not_login=0');
        $criteria->addSearchCondition('user_name', $key);
        $criteria->addSearchCondition('tags', $key, 'true', 'OR');
        $dataUser = User::model()->findAll($criteria);
        $followers == "";
        if (!empty($dataUser)) {
            foreach ($dataUser as $keys => $value) {
                $followers .= $value->id . ",";
            }
        }
        $criteria = new CDbCriteria();
        $criteria->addSearchCondition('subject', $key);
//        if ((isset($_GET["tags"])) && ($_GET["tags"] != "")) {
//            $criteria->addInCondition("id", Diary::model()->getDiaryIdByTagsName($_GET["tags"]));
//        }
        $dataProvider = new CActiveDataProvider('Article', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageVar' => 'page',
                'pageSize' => 10)
                )
        );
        $criteria = new CDbCriteria();
        $criteria->addSearchCondition('name', $key);
        $topicDataProvider = new CActiveDataProvider('Topic', array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'create_time asc'
            ),
            'pagination' => array(
                'pageVar' => 'page',
                'pageSize' => 10)
                )
        );
        $this->render('query', array('followers' => $followers, 'dataProvider' => $dataProvider, 'topicDataProvider' => $topicDataProvider));
    }

    /**
     * 新建消息
     */
    public function actionCreatemessage() {
        $model_message = new Message();
        if (isset($_POST['Message'])) {
            $model_message->attributes = $_POST['Message'];
            $model_message->to_uid = $_POST['Message']['to_uid'];
            $model_message->user_name = $_POST['Message']['user_name'];
            $model_message->create_time = time();
            $model_message->create_user = Yii::app()->user->id;
            $model_message->save();
            echo CJSON::encode($model_message->getErrors());
        }
    }

    /**
     * 新建举报信息
     */
    public function actionCreatereport() {
        $model_message = new Message();
        $returnArr = array();
        if (isset($_POST['Message'])) {
            $type = $_POST['Message']['report_model'];
            if (!in_array($type, array("user", "question", "answer", "article")) || Yii::app()->user->isGuest) {
                $returnArr['type'] = "error";
                $returnArr['message'] = "举报类型错误";
                echo json_encode($returnArr);
            } else if (($_POST['Message']['report_uid'] == Yii::app()->user->id) || ($_POST['Message']['report_model'] == "user")) {
                $returnArr['type'] = "error";
                $returnArr['message'] = "不能举报自己";
                echo json_encode($returnArr);
            } else {
                $notificationContentModel = new NotificationContent;
                $notificationData = array("pk_id" => $_POST['Message']['report_uid'], "content" => $_POST['Message']['report_content'], "send_time" => time(), "notification_type" => $type == "user" ? "report" : "report" . $type);
                $notificationContentModel->insertNotificationContent($notificationData, true);

                $returnArr['type'] = "success";
                $returnArr['message'] = "举报成功";
                echo json_encode($returnArr);
            }
        }
    }

    /**
     * 新建更新历史数据
     */
    public function actionCreatedatalog() {
        $modelDatalog = new DataLog();
        $successMessage = array();
        if (isset($_POST['DataLog'])) {
            $modelDatalog->attributes = $_POST['DataLog'];
            $modelDatalog->create_time = strtotime($_POST['DataLog']['create_time']);
            $modelDatalog->create_user = Yii::app()->user->id;
            if ($modelDatalog->save()) {
                $successMessage[] = "创建成功";
            }
        }
        $this->render('admin', array("successMessage" => $successMessage, "modelDatalog" => $modelDatalog));
    }

    public function actionAbout() {
        $this->render("about");
    }

    /**
     * 新建更新历史数据
     */
    public function actionCreatesetting() {
        $modelDesktopSetting = new DesktopSetting();
        $successMessage = array();
        if (isset($_POST['DesktopSetting'])) {
            $modelDesktopSetting->attributes = $_POST['DesktopSetting'];
            $modelDesktopSetting->app_direction = $_POST['DesktopSetting']['app_direction'];
            if ($modelDesktopSetting->save()) {
                $this->redirect(array("admin", "type" => "module"));
            }
        }
        $this->render('admin', array("successMessage" => $successMessage, "desktopSettingModel" => $modelDesktopSetting));
    }

    /**
     * 新建友情链接
     */
    public function actionCreatelink() {
        $modelLink = new Link();
        $successMessage = array();
        if (isset($_POST['Link'])) {
            $modelLink->attributes = $_POST['Link'];
            if ($modelLink->save()) {
                $this->redirect(array("admin", "type" => "link"));
            }
        }
        $this->render('admin', array("successMessage" => $successMessage, "modelLink" => $modelLink));
    }

    /**
     * 更新友情链接
     */
    public function actionUpdatelink($id) {
        $modelLink = Link::model()->findByPk($id);
        $successMessage = array();
        if (isset($_POST['Link'])) {
            $modelLink->attributes = $_POST['Link'];
            if ($modelLink->save()) {
                $this->redirect(array("admin", "type" => "link"));
            }
        }
        $this->render('admin', array("successMessage" => $successMessage, "modelLink" => $modelLink));
    }

    /**
     * 更新友情链接状态
     */
    public function actionChangelink($id) {
        if (Yii::app()->user->name == "admin") {
            $modelLink = Link::model()->findByPk($id);
            $modelLink->status = ($modelLink->status == 0) ? 1 : 0;
            $modelLink->save();
        }
    }

    /**
     * 删除友情链接
     */
    public function actionDeletelink($id) {
        if (Yii::app()->user->name == "admin") {
            $modelLink = Link::model()->findByPk($id);
            $modelLink->delete();
        }
    }

    /**
     * 新建更新历史数据
     */
    public function actionUpdatedesktop($id) {
        $modelDesktopSetting = DesktopSetting::model()->findByPk($id);
        $successMessage = array();
        if (isset($_POST['DesktopSetting'])) {
            $modelDesktopSetting->attributes = $_POST['DesktopSetting'];
            $modelDesktopSetting->app_direction = $_POST['DesktopSetting']['app_direction'];
            if ($modelDesktopSetting->save()) {
                $this->redirect(array("admin", "type" => "module"));
            }
        }
        $this->render('admin', array("successMessage" => $successMessage, "desktopSettingModel" => $modelDesktopSetting));
    }

    /**
     * 更新友情链接状态
     */
    public function actionChangedesktop($id) {
        if (Yii::app()->user->name == "admin") {
            $modelDesktopSetting = DesktopSetting::model()->findByPk($id);
            $modelDesktopSetting->app_status = ($modelDesktopSetting->app_status == 0) ? 1 : 0;
            $modelDesktopSetting->save();
        }
    }

    /**
     * 新建更新历史数据
     */
    public function actionDeletedesktop($id) {
        if (Yii::app()->user->name == "admin") {
            $modelDesktopSetting = DesktopSetting::model()->findByPk($id);
            $modelDesktopSetting->delete();
        }
    }

    /**
     * 编辑类别
     * 返回json数组
     */
    public function actionEditQuestion() {
        $id = $_POST['id'];
        $questionModel = Question::model()->findByPK($id);
        $topicArray = explode(",", trim($questionModel->topic_ids, ","));
        $topic_names = array();
        for ($i = 0; $i < count($topicArray); $i++) {
            $model = Topic::model()->findByPk($topicArray[$i]);
            if ($model != NULL) {
                $topic_names[] = $model->name;
            }
        }
        $array = array();
        $array['topic_names'] = $topic_names;
        $array['id'] = $questionModel->id;
        $array['content'] = $questionModel->content;
        $array['title'] = $questionModel->title;
        $array['topic_ids'] = implode(',', $topic_names);
        ;
        echo CJSON::encode($array);
        Yii::app()->end();
    }

    /**
     * 修改更新历史数据
     */
    public function actionUpdatedatalog() {
        $modelDatalog = DataLog::model()->findByPk($_GET["id"]);
        $successMessage = array();
        if (isset($_POST['DataLog'])) {
            $modelDatalog->attributes = $_POST['DataLog'];
            $modelDatalog->create_time = strtotime($_POST['DataLog']['create_time']);
            if ($modelDatalog->save()) {
                $successMessage[] = "修改成功";
            }
        }
        $this->render('admin', array('successMessage' => $successMessage, 'modelDatalog' => $modelDatalog));
    }

    /**
     * 修改用户
     */
    public function actionUpdateuser() {
        $userModel = User::model()->findByPk($_GET["id"]);
        $successMessage = array();
        if (isset($_POST['User'])) {
            $uploadedFiles = CUploadedFile::getInstance($userModel, 'avatar');
            if (!empty($uploadedFiles)) {
                $fp = fopen($uploadedFiles->tempName, 'r');
                $content = fread($fp, filesize($uploadedFiles->tempName));
                fclose($fp);
                $userModel->avatar = $content;
            }
            $userModel->attributes = $_POST["User"];
            if ($userModel->save()) {
                $successMessage[] = "修改成功";
            }
        }
        $this->render('admin', array('successMessage' => $successMessage, 'userModel' => $userModel));
    }

    /**
     * 加载用户
     */
    public function loadModelUser($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * 加载评论
     */
    public function loadModelComment($id) {
        $model = Comment::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * 删除回复
     */
    public function actionDeleteReply() {
        $id = $_POST['id'];
        $result = Comment::model()->deleteByPk($id);
        if ($result) {
            echo 'ok';
            Yii::app()->end();
        } else
            echo 'error';
        Yii::app()->end();
    }

    /**
     * 删除点评回复
     */
    public function actionDeletesysreply() {
        $id = $_POST['id'];
        $result = SysCommentReply::model()->deleteByPk($id);
        if ($result) {
            echo 'ok';
            Yii::app()->end();
        } else
            echo 'error';
        Yii::app()->end();
    }

    /**
     * 删除评论
     */
    public function actionDeleteComment() {
        $id = $_POST['id'];

        $result = Comment::model()->findByPk($id)->delete();
        if ($result) {
            echo 'ok';
            Yii::app()->end();
        } else
            echo 'error';
        Yii::app()->end();
    }

    /**
     * 删除点评
     */
    public function actionDeletesyscomment() {
        $id = $_POST['id'];
        $criteria = new CDbCriteria;
        $criteria->addCondition("comment_id=" . $id);
        SysCommentReply::model()->deleteAll($criteria);
        $result = SysComment::model()->deleteByPk($id);
        if ($result) {
            echo 'ok';
            Yii::app()->end();
        } else
            echo 'error';
        Yii::app()->end();
    }

    /**
     * 关注和取消关注触发事件
     */
    public function actionAttentionuser() {
        if (isset($_POST["user_id"])) {
            $userId = $_POST["user_id"];
            $loginUser = Yii::app()->user->id;
            $followees = User::model()->findByPk($loginUser)->followees;
            $otherfollowers = User::model()->findByPk($userId)->followers;
            $otherfolloweesArray = explode(",", trim($otherfollowers, ","));
            if (in_array($loginUser, $otherfolloweesArray)) {
                $otherfollowersString = ",";
                for ($j = 0; $j < count($otherfolloweesArray); $j++) {
                    if ($otherfolloweesArray[$j] != $loginUser) {
                        $otherfollowersString .= $otherfolloweesArray[$j] . ",";
                    }
                }
                $followers = $otherfollowersString == "," ? "" : $otherfollowersString;
            } else {
                $followers = ($otherfollowers == "") ? "," . $loginUser . "," : $otherfollowers . $loginUser . ",";
            }
            User::model()->updateByPk($userId, array("followers" => $followers));
            if (in_array($loginUser, explode(",", User::model()->findByPk($userId)->block_users))) {
                echo "FAILE";
            } else if ($userId == Yii::app()->user->id) {
                echo "DENGER";
            } else {
                $followeesArray = explode(",", trim($followees, ","));
                if (in_array($userId, $followeesArray)) {
                    $followersString = ",";
                    for ($i = 0; $i < count($followeesArray); $i++) {
                        if ($followeesArray[$i] != $userId) {
                            $followeesString .= $followeesArray[$i] . ",";
                        }
                    }
                    $model = $this->loadModelUser($loginUser);
                    $model->followees = $followeesString == "," ? "" : $followeesString;
                    $model->save();
                    echo "立即关注";
                } else {
                    $model = $this->loadModelUser($loginUser);
                    $model->followees = ($model->followees == "") ? "," . $userId . "," : $model->followees . $userId . ",";
                    $model->save();
                    echo "取消关注";
                }

                $notificationContentModel = new NotificationContent;
                $notificationData = array("pk_id" => $userId, "content" => in_array($userId, $followeesArray) ? "取消关注了" : "关注了", "send_time" => time(), "notification_type" => "attention");
                $notificationContentModel->insertNotificationContent($notificationData);
            }
        }
    }

    /**
     * 屏蔽用户
     */
    public function actionBlockuser() {
        if (isset($_POST["user_id"])) {
            $userId = $_POST["user_id"];
            $loginUser = Yii::app()->user->id;
            if ($userId == $loginUser) {
                echo "DENGER";
            } else {
                $followees = User::model()->findByPk($loginUser)->block_users;
                $blockArray = explode(",", rtrim($followees, ","));
                if (in_array($userId, $blockArray)) {
                    $blockString = "";
                    for ($i = 0; $i < count($blockArray); $i++) {
                        if ($blockArray[$i] != $userId) {
                            $blockString .= $followeesArray[$i] . ",";
                        }
                    }
                    $model = $this->loadModelUser($loginUser);
                    $model->block_users = $blockString;
                    $model->save();
                    echo "屏蔽用户";
                } else {
                    $model = $this->loadModelUser($loginUser);
                    $model->block_users = $model->block_users . $userId . ",";
                    $model->save();
                    echo "取消屏蔽";
                }
                $notificationContentModel = new NotificationContent;
                $notificationData = array("pk_id" => $userId, "content" => in_array($userId, $blockArray) ? "取消屏蔽了" : "屏蔽了", "send_time" => time(), "notification_type" => "block");
                $notificationContentModel->insertNotificationContent($notificationData);
            }
        }
    }

    /**
     * 批量删除心情
     */
    public function actionBatchdeletenotify() {
        if (Yii::app()->request->isPostRequest) {
            for ($i = 0; $i < count($_POST['selectdel']); $i++) {
                $type = $_POST["type"];
                if ($type != "message") {
                    $model = Notification::model()->findByPk($_POST['selectdel'][$i]);
                    $toUser = $model->to_id;
                } else {
                    $model = Message::model()->findByPk($_POST['selectdel'][$i]);
                    $toUser = $model->to_uid;
                }
                if ($toUser == Yii::app()->user->id) {
                    $type = $_POST["type"];

                    if ($model->delete_flag == 2) {
                        $model->delete();
                    } else {

                        if ($type != "message") {
                            Notification::model()->updateByPk($_POST['selectdel'][$i], array("delete_flag" => 1));
                        } else {
                            Message::model()->updateByPk($_POST['selectdel'][$i], array("delete_flag" => 1));
                        }
                    }
                } else {
                    if (($model->delete_flag == 1) || ($model->remind_flag == 0)) {
                        $model->delete();
                    } else {
                        if ($type != "message") {
                            Notification::model()->updateByPk($_POST['selectdel'][$i], array("delete_flag" => 2));
                        } else {
                            Message::model()->updateByPk($_POST['selectdel'][$i], array("delete_flag" => 2));
                        }
                    }
                }
            }
            if (isset(Yii::app()->request->isAjaxRequest)) {
                echo 'ok';
            } else {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('notify'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * 批量标记为已读
     */
    public function actionBatchreadnotify() {
        if (Yii::app()->request->isPostRequest) {
            $criteria = new CDbCriteria();
            $criteria->addInCondition('id', $_POST['selectdel']);

            if ($_POST["type"] == "message") {
                $criteria->addCondition('remind_flag=1');
                Message::model()->updateAll(array("remind_flag" => 0), $criteria);
            } else {
                $criteria->addCondition('remind_flag=0');
                Notification::model()->updateAll(array("remind_flag" => 1), $criteria);
            }
            if (isset(Yii::app()->request->isAjaxRequest)) {
                echo 'ok';
            } else {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('notify'));
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    /**
     * 修改系统设置
     */
    public function actionUpdatesys() {
        //    $id = intval($_POST['Sys']['id']);

        $successMessage = array();
        $model = Sys::model()->find();
        $this->performAjaxValidation($model);

        if ($_POST['Sys']) {
            $model->attributes = $_POST['Sys'];
            $tagsArray = array(
                'identity' => $_POST['Sys']['identity'],
                'profession' => $_POST['Sys']['profession'],
                'hobbies' => $_POST['Sys']['hobbies'],
            );
            $model->tags = serialize($tagsArray);

            if ($model->save()) {
                $successMessage[] = "修改网站信息成功";
            }
        }

        $tagsArray = unserialize($model->tags);
        $model->identity = $tagsArray['identity'];
        $model->profession = $tagsArray['profession'];
        $model->hobbies = $tagsArray['hobbies'];
        $this->render('setting', array("successMessage" => $successMessage, 'sysModel' => $model));
    }

    public function actionTask() {
        //    $id = intval($_POST['Sys']['id']);
        $model = Sys::model()->find();
        $settingArray = unserialize($model->setting_wealth);
        $userArray = User::model()->getUserWealth();
        $listDataProvider = new CArrayDataProvider($userArray);
        $this->render('task', array("settingArray" => $settingArray, 'listDataProvider' => $listDataProvider));
    }

    /**
     * 个人设置页面
     */
    public function actionSetting() {
        if (Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->controller->createUrl("index"));
        $errorMessage = array();
        $successMessage = array();
        $userModel = $this->loadModelUser(Yii::app()->user->id);

        $model = Sys::model()->find();
        $tagsArray = unserialize($model->tags);
        $model->identity = $tagsArray['identity'];
        $model->profession = $tagsArray['profession'];
        $model->hobbies = $tagsArray['hobbies'];
        if (isset($_POST["User"])) {
            if (!isset($_GET["type"]) || ($_GET["type"] == "info")) {
                $uploadedFiles = CUploadedFile::getInstance($userModel, 'avatar');
                if (!empty($uploadedFiles)) {
                    $fp = fopen($uploadedFiles->tempName, 'r');
                    $content = fread($fp, filesize($uploadedFiles->tempName));
                    fclose($fp);
                    $userModel->avatar = $content;
                }
                $userModel->attributes = $_POST["User"];
                if ($userModel->save()) {
                    $successMessage[] = "用户账户修改成功";
                }
            } else if ($_GET["type"] == "message") {
                $recvArray = array(
                    "subscribe_member_follow" => $_POST["User"]['subscribe_member_follow'] == "" ? 0 : 1,
                    "subscribe_ask_like" => $_POST["User"]['subscribe_ask_like'],
                    "subscribe_question_like" => $_POST["User"]['subscribe_question_like'],
                    "subscribe_answer_like" => $_POST["User"]['subscribe_answer_like'],
                    "subscribe_article_like" => $_POST["User"]['subscribe_article_like'],
                    "subscribe_message_like" => $_POST["User"]['subscribe_message_like'],
                    "subscribe_comment_like" => $_POST["User"]['subscribe_comment_like'],
                );
                $userModel->recv_option = serialize($recvArray);
                if ($userModel->save()) {
                    $successMessage[] = "消息设置修改成功";
                }
            } else if ($_GET["type"] == "tags") {
                $userModel->tags = $_POST["User"]["tags"];
                if ($userModel->save()) {
                    $successMessage[] = "个人标签修改成功";
                }
            } else {

                $password = $userModel->password;
                $old_password = $_POST['User']['old_password'];
                $new_password = $_POST['User']['new_password'];
                $retype_password = $_POST['User']['retype_password'];
                if ((crypt($old_password, $password) === $password) && (crypt($new_password, $retype_password) === crypt($retype_password, $new_password)) && (strlen($new_password)) >= 6) {
                    $userModel->password = crypt($retype_password);
                    if ($userModel->save())
                        $successMessage[] = "密码修改成功";
                } else {
                    if (($old_password == "") || ($new_password == "") || (($retype_password == "") )) {
                        $errorMessage[] = "原始密码和新的密码都不能为空";
                    } else if (crypt($old_password, $password) !== $password) {
                        $errorMessage[] = '原始密码和新的密码不一致';
                    } else if ($retype_password !== $new_password) {
                        $errorMessage[] = '新的密码和确认密码不一致';
                    } else if (strlen($new_password) < 6) {
                        $errorMessage[] = '密码长度不能小于6';
                    }
                }
            }
        } else if ((isset($_POST["WealthForm"])) && (Yii::app()->user->name == "admin")) {
            $wealth = $_POST['WealthForm'];
            $wealthArray = array(
                "register_score" => $wealth['register_score'],
                "register_type" => $wealth['register_type'],
                "login_score" => $wealth['login_score'],
                "login_type" => $wealth['login_type'],
                "topic_type" => $wealth['topic_type'],
                "topic_score" => $wealth['topic_score'],
                "question_type" => $wealth['question_type'],
                "question_score" => $wealth['question_score'],
                "answer_type" => $wealth['answer_type'],
                "answer_score" => $wealth['answer_score'],
                "article_type" => $wealth['article_type'],
                "article_score" => $wealth['article_score'],
            );
            $sysModal = Sys::model()->find();
            $sysModal->setting_wealth = serialize($wealthArray);
            if ($sysModal->save())
                $successMessage[] = "财富值设置成功";
        }
        $recvArray = unserialize($userModel->recv_option);
        $userModel->subscribe_member_follow = $recvArray['subscribe_member_follow'];
        $userModel->subscribe_ask_like = $recvArray['subscribe_ask_like'];
        $userModel->subscribe_question_like = $recvArray['subscribe_question_like'];
        $userModel->subscribe_answer_like = $recvArray['subscribe_answer_like'];
        $userModel->subscribe_article_like = $recvArray['subscribe_article_like'];
        $userModel->subscribe_message_like = $recvArray['subscribe_message_like'];
        $userModel->subscribe_comment_like = $recvArray['subscribe_comment_like'];
        $this->render('/default/setting', array("errorMessage" => $errorMessage, "userModel" => $userModel, "successMessage" => $successMessage, 'sysModel' => $model));
    }

    public function actionDeletenotify($id) {
        $model = Notification::model()->findByPk($id);

        if ($model->to_id == Yii::app()->user->id) {
            if ($model->delete_flag == 2) {
                $model->delete();
            } else {
                $model->delete_flag = 1;
                $model->save();
            }
        } else {

            if (($model->delete_flag == 1) || ($model->remind_flag == 0)) {
                $model->delete();
            } else {
                $model->delete_flag = 2;
                $model->save();
            }
        }
    }

    /**
     * 个人设置页面
     */
    public function actionAdmin() {
        if (Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->controller->createUrl("index"));
        $errorMessage = array();
        $successMessage = array();
        $userModel = $this->loadModelUser(Yii::app()->user->id);
        $this->render('/default/admin', array("errorMessage" => $errorMessage, "userModel" => $userModel, "successMessage" => $successMessage, 'sysModel' => $model));
    }

    /**
     * 更改用户是否允许登陆状态
     */
    public function actionNotify() {
        $this->render('notify');
    }

    /**
     * 更改用户是否允许登陆状态
     */
    public function actionChangelogin($id) {
        $model = User::model()->findByPk($id);
        $login = $model->not_login == 1 ? 0 : 1;
        $model->not_login = $login;
        if ($model->save()) {
            echo "ok";
        }
    }

    /**
     * 更改点评是否显示
     */
    public function actionChangesyscommentshow($id) {
        $model = SysComment::model()->findByPk($id);
        $show = $model->is_show == 1 ? 0 : 1;
        $model->is_show = $show;
        if ($model->save()) {
            echo "ok";
        }
    }

    /**
     * 更改点评回复是否显示
     */
    public function actionChangesysreplyshow($id) {
        $model = SysCommentReply::model()->findByPk($id);
        $show = $model->is_show == 1 ? 0 : 1;
        $model->is_show = $show;
        if ($model->save()) {
            echo "ok";
        }
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionCheck() {

        $model = new RegisterForm();
        $this->registerformAjaxValidation($model);

        // collect user input data
        if (isset($_POST['RegisterForm'])) {
            $model = new User();
            $model->user_name = $_POST['RegisterForm']['username'];
            $model->register_time = time();
            $model->password = crypt($_POST['RegisterForm']['password']);
            $recvArray = array(
                "subscribe_member_follow" => 1,
                "subscribe_ask_like" => 1,
                "subscribe_question_like" => 1,
                "subscribe_answer_like" => 1,
                "subscribe_comment_like" => 1,
                "subscribe_article_like" => 1,
                "subscribe_message_like" => 0,
            );
            $model->recv_option = serialize($recvArray);
            $visitArray = array(
                "visit_count" => 0,
                "refuse_count" => 0,
            );
            $model->visit_count = serialize($visitArray);
            $privArray = array(
                "visit_priv" => 0,
            );
            $model->priv = serialize($privArray);
            if ($model->save()) {
                $score = Sys::model()->getvaluesByType("register_score");
                $wealthModel = new Wealth;
                if (Sys::model()->getvaluesByType("register_type") == "0") {
                    $content = "注册成功，奖励" . $score . "个财富值";
                    $data = array('content' => $content, 'create_time' => $model->register_time, 'create_user' => $model->id);
                    $wealthModel->insertWealth($data);
                    User::model()->updateByPk($model->id, array("wealth" => intval($score)));
                }
                Yii::app()->user->setFlash("success", "注册成功,<span id='time' style='font-weight:bold;'>3</span>秒后自动关闭");
                $this->redirect(Yii::app()->request->getUrlReferrer());
            }
        }
        //  $this->render('index', array('model' => $model));
    }

    public function actionAllquestion() {
        $criteria = new CDbCriteria;
        $dataProvider = new CActiveDataProvider('Question', array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'update_time asc'
            ),
            'pagination' => array(
                'pageVar' => 'page',
                'pageSize' => 10)
                )
        );
        $this->render('allquestion', array('dataProvider' => $dataProvider));
    }

    public function renderDiv($data, $row) {
        $vote = Vote::model()->count("model=:model and pk_id=:pk_id", array(":pk_id" => $data->id, ":model" => "question"));
        $answer = Answer::model()->find("question_id=:question_id order by create_time desc", array(":question_id" => $data->id));
        $userID = $answer == NULL ? $data->create_user : $answer->create_user;
        $avatarUrl = $this->createUrl("default/getimage", array("id" => $userID, "type" => "avatar"));
        $userUrl = $this->createUrl("default/userinfo", array("user_id" => $userID));
        $viewUrl = $this->createUrl('default/question', array('id' => $data->id));
        $topLabel = "";
        $topicArray = array();
        $topicArray = explode(",", trim($data->topic_ids, ","));
        for ($i = 0; $i < count($topicArray); $i++) {
            $topicModel = Topic::model()->findByPk($topicArray[$i]);
            $topicUrl = $this->createUrl('default/topic', array('id' => $topicModel->id));
            $topLabel.="<a href='" . $topicUrl . "' class='topic-label' data-id='" . $topicModel->id . "' title='" . $topicModel->name . "' style='margin-right:4px;'><span class='label'>" . $topicModel->name . "</a></a>";
        }
        $count = Answer::model()->count("question_id=:question_id", array(":question_id" => $data->id));
        $class = $data->answer_id != 0 ? "upvote success" : ($count != 0 ? "upvote" : "upvote danger");
        $desc = $answer == NULL ? Comment::timeintval($data->create_time) . "提问" : Comment::timeintval($answer->create_time) . "回答";
        $aText = $answer->is_anonymous == 0 ? '<a class="user-label" href="javascript:;" data-id="' . $userID . '" style="margin-right:10px;">' . User::getNameById($userID) . '</a>' : '<a  href="javascript:;" style="margin-right:10px;">匿名用户</a>';
        $html = '<div class="post">';
        $html .= '<a class="upvote" href="' . $viewUrl . '" target="_blank" title="' . $vote . '个人觉得很赞"  rel="tooltip"><i class="icon icon-arrow-up-2"></i><span class="vote-count">' . $vote . '</span></a>';
        $html .= '<a class="' . $class . '" href="' . $viewUrl . '" target="_blank" title="' . $count . '个回答" rel="tooltip"><i class="icon-bubble"></i><span class="vote-count">' . $count . '</span></a>';
        $html .= '<a class="upvote" href="' . $viewUrl . '" target="_blank" title="' . $data->view_count . '个访问量" rel="tooltip"><i class="icon-eye-2"></i><span class="vote-count">' . $data->view_count . '</span></a>';
        $html .= '<div class="url"><span class="ellipsis description">' . $aText . $desc . '</span><div><a class="post-url title" href="' . $viewUrl . '" target="_blank" title="' . $data->title . '">' . $data->title . '</a>' . $topLabel . '</div></div>';
        //    $html .= '<a href="' . $viewUrl . '" target="_blank" class="view-discussion" title="浏览量"><i class="icon-eye-2"></i><p class="comment-count">' . $data->view_count . '</p></a>';
        //  $html .= '<a href="' . $viewUrl . '" target="_blank" class="view-discussion" title="回答数"><i class="icon-bubble"></i><p class="comment-count">' . Answer::model()->count("question_id=:question_id", array(":question_id" => $data->id)) . '</p></a>';
        if (($answer != NULL && $answer->is_anonymous == 0) || $count == 0)
            $html .= '<div class="user-image-holder"><a class="user-label" href="javascript:;" data-id="' . $userID . '"><img alt="' . User::getNameById($userID) . ' - ' . $this->title . '" class="twitter-rounded user-image" height="30" src="' . $avatarUrl . '" width="30"></a></div>';
        $html .= '</div>';
        return $html;
    }

    public function renderVoteDiv($data, $row) {
        $questionModel = Question::model()->findByPk($data->pk_id);
        $vote = Vote::model()->count("model=:model and pk_id=:pk_id", array(":pk_id" => $questionModel->id, ":model" => "question"));
        $answer = Answer::model()->find("question_id=:question_id order by create_time desc", array(":question_id" => $questionModel->id));
        $userID = $answer == NULL ? $questionModel->create_user : $answer->create_user;
        $avatarUrl = $this->createUrl("default/getimage", array("id" => $userID, "type" => "avatar"));
        $userUrl = $this->createUrl("default/userinfo", array("user_id" => $userID));
        $viewUrl = $this->createUrl('default/question', array('id' => $questionModel->id));
        $topLabel = "";
        $topicArray = array();
        $topicArray = explode(",", trim($questionModel->topic_ids, ","));
        for ($i = 0; $i < count($topicArray); $i++) {
            $topicModel = Topic::model()->findByPk($topicArray[$i]);
            $topicUrl = $this->createUrl('default/topic', array('id' => $topicModel->id));
            $topLabel.="<a class='topic-label' data-id='" . $topicModel->id . "' href='" . $topicUrl . "' title='" . $topicModel->name . "' style='margin-right:4px;'><span class='label'>" . $topicModel->name . "</a></a>";
        }
        $count = Answer::model()->count("question_id=:question_id", array(":question_id" => $questionModel->id));
        $class = $questionModel->answer_id != 0 ? "upvote success" : ($count != 0 ? "upvote" : "upvote danger");
        $desc = $answer == NULL ? Comment::timeintval($questionModel->create_time) . "提问" : Comment::timeintval($answer->create_time) . "回答";
        $aText = $answer->is_anonymous == 0 ? '<a class="user-label" href="javascript:;" data-id="' . $userID . '" style="margin-right:10px;">' . User::getNameById($userID) . '</a>' : '<a  href="javascript:;" style="margin-right:10px;">匿名用户</a>';
        $html = '<div class="post">';
        $html .= '<a class="upvote" data-id="' . $data->id . '"><i class="icon icon-arrow-up-2"></i><span class="vote-count">' . $data->num . '</span></a>';
        $html .= '<a class="' . $class . '" href="' . $viewUrl . '" target="_blank" title="' . $count . '个回答" rel="tooltip"><i class="icon-bubble"></i><span class="vote-count">' . $count . '</span></a>';
        $html .= '<a class="upvote" href="' . $viewUrl . '" target="_blank" title="' . $questionModel->view_count . '个访问量" rel="tooltip"><i class="icon-eye-2"></i><span class="vote-count">' . $questionModel->view_count . '</span></a>';
//        $html .= '<div class="url"><a class="post-url title" href="' . $viewUrl . '" target="_blank" title="' . $questionModel->title . '">' . $questionModel->title . '</a><span class="ellipsis description">' . $topLabel . '</span></div>';
        $html .= '<div class="url"><span class="ellipsis description">' . $aText . $desc . '</span><div><a class="post-url title" href="' . $viewUrl . '" target="_blank" title="' . $questionModel->title . '">' . $questionModel->title . '</a>' . $topLabel . '</div></div>';
//        //        $html .= '<a href="' . $viewUrl . '" target="_blank" class="view-discussion" title="浏览量"><i class="icon-eye-2"></i><p class="comment-count">' . $questionModel->view_count . '</p></a>';
//        $html .= '<a href="' . $viewUrl . '" target="_blank" class="view-discussion" title="回答数"><i class="icon-bubble"></i><p class="comment-count">' . Answer::model()->count("question_id=:question_id", array(":question_id" => $questionModel->id)) . '</p></a>';
        if (($answer != NULL && $answer->is_anonymous == 0) || $count == 0)
            $html .= '<div class="user-image-holder"><a class="user-label" href="javascript:;" data-id="' . $userID . '"><img alt="' . User::getNameById($userID) . ' - ' . $this->title . '" class="twitter-rounded user-image" height="30" src="' . $avatarUrl . '" width="30"></a></div>';
        $html .= '</div>';
        return $html;
    }

    public function renderArticleDiv($data, $row) {
        $vote = Vote::model()->count("model=:model and pk_id=:pk_id", array(":pk_id" => $data->id, ":model" => "article"));
        $comment = Comment::model()->find("pk_id=:pk_id and model=:model order by create_time desc", array(":pk_id" => $data->id, ":model" => "article"));
        $userID = $comment == NULL ? $data->create_user : $comment->user_id;
        $avatarUrl = $this->createUrl("default/getimage", array("id" => $userID, "type" => "avatar"));
        $userUrl = $this->createUrl("default/userinfo", array("user_id" => $userID));
        $viewUrl = $this->createUrl('default/article', array('id' => $data->id));
        $topLabel = "";
        $topicArray = array();
        $topicArray = explode(",", trim($data->topic_ids, ","));
        for ($i = 0; $i < count($topicArray); $i++) {
            $topicModel = Topic::model()->findByPk($topicArray[$i]);
            $topicUrl = $this->createUrl('default/topic', array('id' => $topicModel->id));
            $topLabel.="<a href='" . $topicUrl . "' class='topic-label' data-id='" . $topicModel->id . "' title='" . $topicModel->name . "' style='margin-right:4px;'><span class='label'>" . $topicModel->name . "</a></a>";
        }
        $count = Comment::model()->count("pk_id=:pk_id and model=:model and parent_id=0", array(":pk_id" => $data->id, ":model" => "article"));
        $class = $count != 0 ? "upvote" : "upvote danger";
        $desc = $comment == NULL ? Comment::timeintval($data->create_time) . "发布" : Comment::timeintval($comment->create_time) . "评论";
        $html = '<div class="post">';
        $html .= '<a class="upvote" href="' . $viewUrl . '" target="_blank" title="' . $vote . '个人觉得很赞" rel="tooltip"><i class="icon icon-arrow-up-2"></i><span class="vote-count">' . $vote . '</span></a>';
        $html .= '<a class="' . $class . '" href="' . $viewUrl . '" target="_blank" title="' . $count . '条评论" rel="tooltip"><i class="icon-bubble"></i><span class="vote-count">' . $count . '</span></a>';
        $html .= '<a class="upvote" href="' . $viewUrl . '" target="_blank" title="' . $data->view_count . '个访问量" rel="tooltip"><i class="icon-eye-2"></i><span class="vote-count">' . $data->view_count . '</span></a>';
        $html .= '<div class="url"><span class="ellipsis description"><a class="user-label" href="javascript:;" data-id="' . $userID . '" style="margin-right:10px;">' . User::getNameById($userID) . '</a> ' . $desc . '</span><div><a class="post-url title" href="' . $viewUrl . '" target="_blank" title="' . $data->subject . '">' . $data->subject . '</a>' . $topLabel . '</div></div>';
//        $html .= '<a href="' . $viewUrl . '" target="_blank" class="view-discussion" title="浏览量"><i class="icon-eye-2"></i><p class="comment-count">' . $data->view_count . '</p></a>';
//        $html .= '<a href="' . $viewUrl . '" target="_blank" class="view-discussion" title="评论数"><i class="icon-bubble"></i><p class="comment-count">' . Comment::model()->count("pk_id=:pk_id and model=:model and parent_id=0", array(":pk_id" => $data->id, ":model" => "article")) . '</p></a>';
        $html .= '<div class="user-image-holder"><a class="user-label" href="javascript:;" data-id="' . $userID . '"><img alt="' . User::getNameById($userID) . ' - ' . $this->title . '" class="twitter-rounded user-image" height="30" src="' . $avatarUrl . '" width="30"></a></div>';
        $html .= '</div>';
        return $html;
    }

    public function renderArticleVoteDiv($data, $row) {
        $articleModel = Article::model()->findByPk($data->pk_id);
        $comment = Comment::model()->find("pk_id=:pk_id and model=:model order by create_time desc", array(":pk_id" => $articleModel->id, ":model" => "article"));
        $userID = $comment == NULL ? $articleModel->create_user : $comment->user_id;
        $avatarUrl = $this->createUrl("default/getimage", array("id" => $userID, "type" => "avatar"));
        $userUrl = $this->createUrl("default/userinfo", array("user_id" => $userID));
        $viewUrl = $this->createUrl('default/article', array('id' => $articleModel->id));
        $topicArray = array();
        $topicArray = explode(",", trim($articleModel->topic_ids, ","));
        for ($i = 0; $i < count($topicArray); $i++) {
            $topicModel = Topic::model()->findByPk($topicArray[$i]);
            $topicUrl = $this->createUrl('default/topic', array('id' => $topicModel->id));
            $topLabel.="<a href='" . $topicUrl . "' class='topic-label' data-id='" . $topicModel->id . "' title='" . $topicModel->name . "' style='margin-right:4px;'><span class='label'>" . $topicModel->name . "</a></a>";
        }
        $count = Comment::model()->count("pk_id=:pk_id and model=:model and parent_id=0", array(":pk_id" => $articleModel->id, ":model" => "article"));
        $class = $count != 0 ? "upvote" : "upvote danger";
        $desc = $comment == NULL ? Comment::timeintval($articleModel->create_time) . "发布" : Comment::timeintval($comment->create_time) . "评论";
        $html = '<div class="post">';
        $html .= '<a class="upvote"  href="' . $viewUrl . '" target="_blank" title="' . $data->num . '个人觉得很赞" rel="tooltip"><i class="icon icon-arrow-up-2"></i><span class="vote-count">' . $data->num . '</span></a>';
        $html .= '<a class="' . $class . '" href="' . $viewUrl . '" target="_blank" title="' . $count . '条评论" rel="tooltip"><i class="icon-bubble"></i><span class="vote-count">' . $count . '</span></a>';
        $html .= '<a class="upvote" href="' . $viewUrl . '" target="_blank" title="' . $articleModel->view_count . '个访问量" rel="tooltip"><i class="icon-eye-2"></i><span class="vote-count">' . $articleModel->view_count . '</span></a>';
        $html .= '<div class="url"><span class="ellipsis description"><a class="user-label" href="javascript:;" data-id="' . $userID . '" style="margin-right:10px;">' . User::getNameById($userID) . '</a> ' . $desc . '</span><div><a class="post-url title" href="' . $viewUrl . '" target="_blank" title="' . $articleModel->subject . '">' . $articleModel->subject . '</a>' . $topLabel . '</div></div>';
        //   $html .= '<a href="' . $viewUrl . '" target="_blank" class="view-discussion" title="浏览量"><i class="icon-eye-2"></i><p class="comment-count">' . $articleModel->view_count . '</p></a>';
        //$html .= '<a href="' . $viewUrl . '" target="_blank" class="view-discussion" title="回答数"><i class="icon-bubble"></i><p class="comment-count">' . Comment::model()->count("pk_id=:pk_id and parent_id=0 and model=:model order by create_time desc", array(":pk_id" => $articleModel->id, ":model" => "article")) . '</p></a>';
        $html .= '<div class="user-image-holder"><a class="user-label" href="javascript:;" data-id="' . $userID . '"><img alt="' . User::getNameById($userID) . ' - ' . $this->title . '" class="twitter-rounded user-image" height="30" src="' . $avatarUrl . '" width="30"></a></div>';
        $html .= '</div>';
        return $html;
    }

    public function actionQuestion($id) {
        $model = Question::model()->findByPk($id);
        if (!isset($_GET["action"])) {
            Question::model()->updateByPk($id, array("view_count" => $model->view_count + 1));
        }
        if (isset($_GET["pk_id"])) {
            $notification = Notification::model()->findByPk($_GET["pk_id"]);
            if ($notification != NULL) {
                Notification::model()->updateByPk($_GET["pk_id"], array("remind_flag" => 1), "to_id=:to_id and remind_flag=0", array(":to_id" => Yii::app()->user->id));
            }
        }
        $this->render('question', array('model' => $model));
    }

    public function actionArticle($id) {
        $model = Article::model()->findByPk($id);
        if (!isset($_GET["action"])) {
            Article::model()->updateByPk($id, array("view_count" => $model->view_count + 1));
        }
        if (isset($_GET["pk_id"])) {
            $notification = Notification::model()->findByPk($_GET["pk_id"]);
            if ($notification != NULL) {
                Notification::model()->updateByPk($_GET["pk_id"], array("remind_flag" => 1), "to_id=:to_id and remind_flag=0", array(":to_id" => Yii::app()->user->id));
            }
        }
        $this->render('article', array('model' => $model));
    }

    public function actionAnswer($id) {
        $model = Answer::model()->findByPk($id);
        if (isset($_GET["pk_id"])) {
            $notification = Notification::model()->findByPk($_GET["pk_id"]);
            if ($notification != NULL) {
                Notification::model()->updateByPk($_GET["pk_id"], array("remind_flag" => 1), "to_id=:to_id and remind_flag=0", array(":to_id" => Yii::app()->user->id));
            }
        }
        $this->render('answer', array('model' => $model));
    }

    public function actionChangecomment() {

        if (isset($_POST)) {
            $answerId = $_POST["answerId"];
            $type = $_POST["type"];
            $update = Answer::model()->updateByPk($answerId, array("anonymity_yn" => $type), "create_user=:create_user", array(":create_user" => Yii::app()->user->id));
            echo $update ? "ok" : "fasle";
        }
    }

    public function actionDeleteanswers() {

        if (isset($_POST)) {
            $answerId = $_POST["answerId"];
            $model = Answer::model()->findByPk($answerId);
            $return = "false";
            if ($model->create_user == Yii::app()->user->id || Yii::app()->user->name == "admin") {
                $delete = $model->delete();
                $return = $delete ? "ok" : "fasle";
            }
            echo $return;
        }
    }

    //文章页面
    public function actionAllarticle() {
        $criteria = new CDbCriteria;
        $dataProvider = new CActiveDataProvider('Question', array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'create_time asc'
            ),
            'pagination' => array(
                'pageVar' => 'page',
                'pageSize' => 10)
                )
        );
        $this->render('allarticle', array('dataProvider' => $dataProvider));
    }

    /**
     * 微讯列表
     */
    public function actionInbox() {
        $model = Message::model()->listMessage();
        $this->render('inbox', array('model' => $model));
    }

    public function actionCreatetopic() {
        $topicModel = new Topic();
        if (isset($_POST["Topic"])) {
            $topicModel->attributes = $_POST["Topic"];
            $topicModel->create_time = time();
            $topicModel->create_user = Yii::app()->user->id;
            $topicModel->desc = $_POST["Topic"]["desc"];
            $uploadedFiles = CUploadedFile::getInstance($topicModel, 'logo');
            if (!empty($uploadedFiles)) {
                $fp = fopen($uploadedFiles->tempName, 'r');
                $content = fread($fp, filesize($uploadedFiles->tempName));
                fclose($fp);
                $topicModel->logo = $content;
            }
            $topicModel->save();
            echo CJSON::encode($topicModel->getErrors());
        }
    }

    public function actionCreatequestion() {
        $questionModel = new Question();
        if (isset($_POST["Question"])) {
            $questionModel->attributes = $_POST["Question"];
            $questionModel->content = htmlspecialchars($_POST["Question"]["content"]);
            $questionModel->title = htmlspecialchars($_POST["Question"]["title"]);
            $questionModel->create_time = time();
            $questionModel->update_time = time();
            $questionModel->create_user = Yii::app()->user->id;
            if ($questionModel->save()) {
                Question::model()->inserNotify($questionModel->id);
                $topic_ids = "";
                $topicArray = explode(",", trim($questionModel->topic_ids, ","));
                $topic_ids.=",";
                for ($i = 0; $i < count($topicArray); $i++) {
                    $model = Topic::model()->find("name='" . $topicArray[$i] . "'");
                    if ($model == NULL) {
                        $newModel = new Topic();
                        $newModel->name = $topicArray[$i];
                        $newModel->create_user = Yii::app()->user->id;
                        $newModel->create_time = time();
                        if ($newModel->save()) {
                            $topic_ids.=$newModel->id . ",";
                        }
                    } else {
                        $topic_ids.=$model->id . ",";
                    }
                }
                Question::model()->updateByPk($questionModel->id, array("topic_ids" => $topic_ids));
            }
            echo CJSON::encode($questionModel->getErrors());
        }
    }

    public function actionUpdatequestion($id) {
        $questionModel = Question::model()->findByPk($id);
        if (isset($_POST["Question"])) {
            $questionModel->attributes = $_POST["Question"];
            $questionModel->content = $_POST["Question"]["content"];
            $questionModel->topic_ids = $_POST["Question"]["topic_ids"];
            $questionModel->update_time = time();
            if ($questionModel->save()) {
                $topic_ids = "";
                $topicArray = explode(",", trim($questionModel->topic_ids, ","));
                $topic_ids.=",";
                for ($i = 0; $i < count($topicArray); $i++) {
                    $model = Topic::model()->find("name='" . $topicArray[$i] . "'");
                    if ($model == NULL) {
                        $newModel = new Topic();
                        $newModel->name = $topicArray[$i];
                        $newModel->create_user = Yii::app()->user->id;
                        $newModel->create_time = time();
                        if ($newModel->save()) {
                            $topic_ids.=$newModel->id . ",";
                        }
                    } else {
                        $topic_ids.=$model->id . ",";
                    }
                }
                Question::model()->updateByPk($id, array("topic_ids" => $topic_ids));
            }
            echo CJSON::encode($questionModel->getErrors());
        }
    }

    public function actionEdittopic() {
        $id = $_POST['topic_id'];
        $groupModel = Topic::model()->findByPk($id);
        $return = array('name' => $groupModel->name, 'desc' => $groupModel->desc, 'parent_id' => $groupModel->parent_id);
        echo CJSON::encode($return);
        Yii::app()->end();
    }

    public function actionUpdatetopic($id) {
        $topicModel = Topic::model()->findByPk($id);
        if (isset($_POST["Topic"])) {
            $topicModel->attributes = $_POST["Topic"];
            $topicModel->desc = $_POST["Topic"]["desc"];
            $uploadedFiles = CUploadedFile::getInstance($topicModel, 'logo');
            if (!empty($uploadedFiles)) {
                $fp = fopen($uploadedFiles->tempName, 'r');
                $content = fread($fp, filesize($uploadedFiles->tempName));
                fclose($fp);
                $topicModel->logo = $content;
            }
            $topicModel->save();
            echo CJSON::encode($topicModel->getErrors());
        };
    }

    public function actionDeletevisit() {
        if (Yii::app()->request->isPostRequest) {
            $model = Visit::model()->findByPk($_POST["id"]);
            if (($model->to_user == Yii::app()->user->id ) || (Yii::app()->user->id == $model->from_user)) {
                $model->delete();
                echo "ok";
            }
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionTopic() {
        $id = $_GET["id"];
        if (isset($_GET["pk_id"])) {
            if (Notification::model()->findByPk($_GET["pk_id"])->remind_flag == 0) {
                Notification::model()->updateByPk($_GET["pk_id"], array("remind_flag" => 1));
            }
        }
        $criteria = new CDbCriteria;
        $topicModel = Topic::model()->findByPk($id);
        $criteria->addCondition("pk_id='" . $id . "'");
        $criteria->addCondition("model='topic'");
        $dataProvider = new CActiveDataProvider('Comment', array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'create_time asc'
            ),
            'pagination' => array(
                'pageVar' => 'page',
                'pageSize' => 10)
                )
        );
        $this->render('topic', array("dataProvider" => $dataProvider, 'topicModel' => $topicModel));
    }

    /**
      Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function registerformAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'registerForm') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
      Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sys-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
      Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function commentAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'comment-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
      Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function articleAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'article-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
      Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function registerAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'register-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    function actionAgreeavote() {
        $pk_id = $_POST['pk_id'];
        $modelName = isset($_POST['model']) ? $_POST['model'] : "answer";
        $return = array();
        $count = Vote::model()->count("create_user=:create_user and pk_id=:pk_id and model=:model", array(":model" => $modelName, ":pk_id" => $pk_id, ":create_user" => Yii::app()->user->id));
        if ($count > 0) {
            $Vote = Vote::model()->find("create_user=:create_user and pk_id=:pk_id  and model=:model", array(":model" => $modelName, ":pk_id" => $pk_id, ":create_user" => Yii::app()->user->id));
            if ($Vote->opinion == 0) {
                $return['message'] = ($Vote->delete()) ? "ok" : "false";
            } else {
                $Vote->opinion = 0;
                $Vote->create_time = time();
                $Vote->model = $modelName;
                $return['message'] = ($Vote->save()) ? "ok" : "false";
            }
        } else {
            $model = new Vote();
            $model->pk_id = $pk_id;
            $model->create_user = Yii::app()->user->id;
            $model->create_time = time();
            $model->model = $modelName;
            $model->opinion = 0;
            $return['message'] = ($model->save()) ? "ok" : "false";
        }
        $return['count'] = Vote::model()->count("opinion=0 and model=:model and pk_id=:pk_id", array(":pk_id" => $pk_id, ":model" => $modelName));
        echo json_encode($return);
    }

    function actionDisagreevote() {
        $pk_id = $_POST['pk_id'];
        $modelName = isset($_POST['model']) ? $_POST['model'] : "answer";
        $return = array();
        $count = Vote::model()->count("create_user=:create_user and model=:model and pk_id=:pk_id", array(":pk_id" => $pk_id, ":create_user" => Yii::app()->user->id, ":model" => $modelName));
        if ($count > 0) {
            $Vote = Vote::model()->find("create_user=:create_user and model=:model and pk_id=:pk_id", array(":pk_id" => $pk_id, ":create_user" => Yii::app()->user->id, ":model" => $modelName));
            if ($Vote->opinion == 1) {
                $return['message'] = ($Vote->delete()) ? "ok" : "false";
            } else {
                $Vote->opinion = 1;
                $Vote->model = $modelName;
                $Vote->create_time = time();
                $return['message'] = ($Vote->save()) ? "ok" : "false";
            }
        } else {
            $model = new Vote();
            $model->pk_id = $pk_id;
            $model->create_user = Yii::app()->user->id;
            $model->create_time = time();
            $model->model = $modelName;
            $model->opinion = 1;
            $return['message'] = ($model->save()) ? "ok" : "false";
        }
        $return['count'] = Vote::model()->count("opinion=0 and model=:model and pk_id=:pk_id", array(":pk_id" => $pk_id, ":model" => $modelName));
        echo json_encode($return);
    }

    public function actionCreateattention() {
        $id = $_POST["topic_id"];
        $model = Topic::model()->findByPk($id);
        $agree = $model->join_user;
        $agreeArray = explode(",", trim($agree, ","));
        if (in_array(Yii::app()->user->id, $agreeArray)) {
            $agreeString = ",";
            for ($i = 0; $i < count($agreeArray); $i++) {
                if ($agreeArray[$i] != Yii::app()->user->id) {
                    $agreeString .= $agreeArray[$i] . ",";
                }
            }
            $model->join_user = ($agreeString == ",") ? "" : $agreeString;
            if ($model->save())
                echo "ERROR";
        } else {
            $join_user = $model->join_user == "" ? "," : $model->join_user;
            $model->join_user = $join_user . Yii::app()->user->id . ",";
            if ($model->save())
                echo "OK";
        }
    }

    public function actionAttentiontopic() {
        $topic_id = $_POST["topic_id"];
        $model = LoveTopic::model()->find("create_user=:create_user and topic_id=:topic_id", array(":topic_id" => $topic_id, ":create_user" => Yii::app()->user->id));
        $info = LoveTopic::model()->find("create_user=:create_user order by `order_no` desc", array(":create_user" => Yii::app()->user->id));

        if ($model == NULl) {
            $loveTopic = new LoveTopic;
            $loveTopic->create_user = Yii::app()->user->id;
            $loveTopic->create_time = time();
            $loveTopic->topic_id = $topic_id;
            $loveTopic->order_no = ($info == NULL) ? 1 : $info->order_no;
            $return['message'] = ($loveTopic->save()) ? "ok" : "false";
        } else {
            $return['message'] = ($model->delete()) ? "ok" : "false";
        }
        $count = LoveTopic::model()->count("create_user=:create_user", array(":create_user" => Yii::app()->user->id));
        $return['count'] = $count;
        echo json_encode($return);
    }

    public function actionAcceptedanswer() {
        $answer_id = $_POST["answer_id"];
        $return = array();
        $answerModel = Answer::model()->findByPk($answer_id);
        if ($answerModel != null) {
            $questionModel = Question::model()->findByPk($answerModel->question_id);
            $return = array();
            if (Yii::app()->user->id == $questionModel->create_user) {
                $questionModel->answer_id = $answer_id;
                $return['message'] = ($questionModel->save()) ? "ok" : "false";
            }
        } else {
            $return['message'] = "false";
        }
        echo json_encode($return);
    }

    public function actioncancelnotanonymous() {
        $answer_id = $_POST["answer_id"];
        $answerModel = Answer::model()->findByPk($answer_id);
        $return = array();
        $return['message'] = "false";
        if ($answerModel == NULL) {
            $tip = '没有此答案！';
        } else if (Yii::app()->user->isGuest) {
            $tip = '回答问题前，请先登录！';
        } else if ($answerModel->create_user != Yii::app()->user->id) {
            $tip = '你没有修改权限！';
        } else {
            $answerModel->is_anonymous = 0;
            $return['message'] = ($answerModel->save()) ? "ok" : "false";
            $tip = $return['message'] == "ok" ? '修改成功！' : '修改失败！';
        }
        $return['tip'] = $tip;
        echo json_encode($return);
    }

    /**
     * 新建消息
     */
    public function actionRequest() {
        $model = new Request();
        if (isset($_POST['Request'])) {
            if (Question::model()->findByPk($_POST['Request']['question_id']) != NULL) {
                $model->question_id = $_POST['Request']['question_id'];
            }
            if (Question::model()->findByPk($_POST['Request']['to_user']) != NULL) {
                $model->to_user = $_POST['Request']['to_user'];
            }
            $model->user_name = $_POST['Request']['user_name'];
            $model->create_time = time();
            $model->create_user = Yii::app()->user->id;
            $model->save();
            echo CJSON::encode($model->getErrors());
        }
    }

    /**
     * 删除邀请
     */
    public function actionRemoverequest() {
        $login_user_id = Yii::app()->user->id;
        $request_id = $_POST["request_id"];
        $model = Request::model()->findByPk($request_id);
        if ($model != NULL) {
            if ($model->create_user == $login_user_id) {
                if (($model->delete_flag == 2) || ($model->to_user == $login_user_id)) {
                    Request::model()->deleteByPK($request_id);
                } else {
                    Request::model()->updateByPk($request_id, array("delete_flag" => 1));
                }
                $message = "ok";
            } else if ($model->to_user == $login_user_id) {
                if ($model->delete_flag == 1) {
                    Request::model()->deleteByPK($request_id);
                } else {
                    Request::model()->updateByPk($request_id, array("delete_flag" => 2));
                }
                $message = "ok";
            } else {
                $message = "false";
            }
        } else {
            $message = "false";
        }
        $return = array('message' => $message);
        echo CJSON::encode($return);
        Yii::app()->end();
    }

    /**
     * 显示个人文件柜更多操作的按钮
     */
    public function renderButtons($data, $row) {
        $userId = $data->create_user == Yii::app()->user->id ? $data->to_uid : $data->create_user;
        $this->widget('bootstrap.widgets.TbButton', array(
            'icon' => 'icon-bubble',
            'label' => '',
            'type' => 'link',
            'htmlOptions' => array("rel" => "tooltip", "data-original-title" => "发送消息", "style" => "font-size:12px;padding:0 4px;", "name" => "reply", "data-uid" => $userId, "data-username" => User::model()->getNameById($userId)),
        ));
        $this->widget('bootstrap.widgets.TbButton', array(
            'icon' => 'icon-eye',
            'label' => '',
            'type' => 'link',
            'url' => $this->createUrl("notify", array("type" => "dialogue", "id" => $userId)),
            'htmlOptions' => array("style" => "font-size:12px;padding:0 4px;", "rel" => "tooltip", "data-original-title" => "查看对话",),
        ));
        $this->widget('bootstrap.widgets.TbButton', array(
            'icon' => 'icon-remove',
            'label' => '',
            'type' => 'link',
            'url' => $this->createUrl("deletemessage"),
            'htmlOptions' => array("action-type" => "delMessage", "action-data-id" => $data->id, "style" => "font-size:12px;padding:0 4px;", "rel" => "tooltip", "data-original-title" => "删除",),
        ));
    }

}
