<?php

/**
 * TbMultiSelect class file.
 * @author lx <lx@tongda2000.com>
 */

/**
 * Multi-select widget.
 * @see https://github.com/lou/multi-select/
 */
class TbMultiSelect extends CWidget {

    /**
     * @var array 初始化数据, 形如:
     * array(
     *     array("label"=>'第一', 'key'=>'1', 'selected'=>true),
     *     array("label"=>'第二', 'key'=>'2'),
     * )
     * 默认为不选中
     */
    public $data = array();

    /**
     * @var array 参数.
     * 例如：selectableHeader, selectionHeader, selectableFooter, selectionFooter, style
     */
    public $options = array();

    /**
     * @var string[] the Javascript event handlers.
     * 支持如下事件：afterInit, afterSelect, afterDeselect
     */
    public $events = array();

    /**
     * @var array the HTML attributes for the widget container.
     */
    public $htmlOptions = array();

    /**
     * Initializes the widget.
     */
    public function init() {
        if (!isset($this->htmlOptions['id']))
            $this->htmlOptions['id'] = $this->getId();
        $this->htmlOptions['multiple'] = 'multiple';
    }

    /**
     * Runs the widget.
     */
    public function run() {
        echo CHtml::openTag('select', $this->htmlOptions);
        if(!empty($this->data)) {
	        foreach ($this->data as $item) {
	            echo CHtml::tag('option', array('value' => $item['key'], 'selected' => $item['selected']), $item['label']);
	        }
    	}
        echo CHtml::closeTag('select');

        Yii::app()->bootstrap->registerAssetCss('jquery.multi-select.css');
        Yii::app()->bootstrap->registerAssetJs('jquery.multi-select.js');

        $options = !empty($this->options) ? CJavaScript::encode($this->options) : '';

        ob_start();
        echo "jQuery('#{$this->getId()}').multiSelect({$options})";
        foreach ($this->events as $event => $handler)
            echo ".on('{$event}', " . CJavaScript::encode($handler) . ")";

        Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $this->getId(), ob_get_clean() . ';');
    }

}
?>