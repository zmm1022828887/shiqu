<?php
/**
 * 视图选择按钮
 *
 * @author fl <fl@tongda2000.com>
 */
class TbViewSwitchButton extends CWidget {
    
    const VIEW_GRID = 'grid';
    const VIEW_KANBAN = 'kanban';
    const VIEW_DETAIL = 'detail';
    
    const ICON_GRID = 'icon-menu-3';
    const ICON_KANBAN = 'icon-grid-5';
    
    const TITLE_GRID = '列表视图';
    const TITLE_KANBAN = '看板视图';

    /**
     * 按钮列表HTML属性
     * @var array 
     */
    public $htmlOptions;
    
    /**
     * 视图配置
     * @var string
     */
    public $views;
     
    /**
     * 定义按钮列表数组
     * @var array
     */
    public $buttons=array();
    
    /**
     * 当前视图按钮ID
     * @var string
     * 
     * options: kanban, list
     */
    public $activeView;
    
    /**
     * init
     */
    public function init() {
        if(isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] = 'switch-button ' . $this->htmlOptions['class'];
        else
            $this->htmlOptions['class'] = 'switch-button';
        
        $this->initDefaultButtons();
        $this->registerCss();
        
        $cookies = Yii::app()->request->cookies;
        $cookies['userView'] = new CHttpCookie('userView', $this->activeButton, array('path'=>'/'));
    }
    
    public function run() {
        echo CHtml::openTag('div', $this->htmlOptions)."\n";
        $this->renderButtons();
        echo CHtml::closeTag('div');
    }
    
    /**
     * 显示切换按钮
     */
    public function renderButtons() {
        if(empty($this->buttons))
            return;
        
        foreach($this->buttons as $key=>$option) {
            $iconClass = $option['icon'];
            unset($option['icon']);
            if($key===$this->activeButton)
                $option['class'] .= ' active';
            
            echo CHtml::tag('a', $option, CHtml::tag('i', array('class'=>$iconClass), ''));
        }
    }
    
    /**
     * 初始化按钮
     */
    public function initDefaultButtons() {
        foreach(array('kanban', 'list') as $id) {
            $this->buttons[$id] = array(
                'class'=>$this->{$id.'Class'}, 
                'href'=>$this->{$id.'Url'}, 
                'data-original-title'=>$this->{$id.'Title'}, 
                'icon'=>$this->{$id.'Icon'},
                'rel'=>'tooltip',
                'data-placement'=>'bottom',
            );
        }
    }
    
    
}

?>
