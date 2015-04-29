<?php
/**
 * TbFileSelector class file.
 * @author lx
 * @copyright  Copyright &copy; Tongda Tec 2013
 * @package bootstrap.widgets
 */

/**
 *
 * 示例用法：
 * $this->
 */
class TbFileSelector extends CInputWidget
{
    /**
     *
     * @var TbActiveForm 表单对象
     */
    public $form;

    /**
     *
     * @var array 参数
     */
    public $options = array();

	/**
	 * @var array the HTML attributes for the widget container.
	 */
	public $htmlOptions = array();

	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		if (!isset($this->htmlOptions['id']))
			$this->htmlOptions['id'] = $this->getId();

        $this->htmlOptions['class'] ='file-selector '.$this->htmlOptions['class'];
        
        $optionsDefault = array(
            'list' => '#'.$this->id.'-container',
            'STRING' => array(
                'remove' => '<i class="icon-remove" rel="tooltip" title="去掉该文件"></i>',
                'selected' => '文件：$file',
                'denied' => '禁止上传扩展名为$ext的文件',
                'duplicate' => '您已经选择了这个文件，请勿重复选择：$file',
            )
        );
        
        $this->options = isset($this->options) ? array_merge($optionsDefault, $this->options) : $optionsDefault;
	}

	/**
	 * Runs the widget.
	 */
	public function run()
	{
        $id = $this->id.'-file';
        $containerId = $this->id.'-container';
        $label = '选择文件';
        if ($this->hasModel())
        {
            $name = CHtml::activeName($this->model,$this->attribute).'[]';
            if($this->form) {
                $label .=  $this->form->fileField($this->model, $this->attribute, array('name'=>$name,'id'=>$id, 'hideFocus'=>true));
            } else {
                $label .=  CHtml::activeFileField($this->model, $this->attribute, array('name'=>$name,'id'=>$id, 'hideFocus'=>true));
            }
        }

        $this->controller->widget('bootstrap.widgets.TbButton', array(
            'label' => $label,
            'size' => 'small',
            'icon' => 'plus',
            'encodeLabel' => false,
            'htmlOptions' => $this->htmlOptions,
        ));
        echo CHtml::tag('div',array('id'=>$containerId), '', true);

        Yii::app()->bootstrap->registerAssetCss('bootstrap-file-selector.css');
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('multifile');
        $cs->registerScript(__CLASS__ . '#' . $this->id, "\n$('#{$id}').MultiFile(".CJavaScript::encode($this->options).")");
	}
}
