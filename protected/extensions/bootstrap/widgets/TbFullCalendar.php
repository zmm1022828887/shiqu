<?php
class TbFullCalendar extends CWidget{
    /**
     * @var the id of the created fullCalendar
     */
    private $id;
    /**
     * @var array the fullCalendar options 
     */
    public $calendarOptions;
    /**
     * @var the wrap's htmlOptions of the created fullCalendar  
     */
    public $htmlOptions = array();
    
    private $_assetsUrl = '';

    /**
     * init the widget
     */
    public function init(){
        $this->registerClientScript();
        $this->getCalendarId();
       
        echo CHtml::openTag('div', $this->htmlOptions);
        //Yii::app()->clientScript->registerScript(__CLASS__.'#'.$this->getId(), "var calendar = $('#$this->id').fullCalendar($this->calendarOptions);");
    }
    /**
     * run the widget
     */
    public function run(){
        echo CHtml::closeTag('div');
    }
    /**
     * get the id of the created fullCalendar
     */
    private function getCalendarId(){
        foreach ($this->htmlOptions as $key => $value) {
            if($key == 'id') $this->id = $value;
        }
    }
     /**
     * register required script
     */
    public function registerClientScript(){
        $this->_assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.bootstrap.assets.js.fullcalendar'));
         Yii::app()->clientScript->registerCssFile($this->_assetsUrl.'/fullcalendar.css')
                ->registerCssFile($this->_assetsUrl.'/fullcalendar.print.css','print')
                ->registerScriptFile($this->_assetsUrl.'/fullcalendar.min.js')
                ->registerCoreScript("jquery.ui");
    }
}