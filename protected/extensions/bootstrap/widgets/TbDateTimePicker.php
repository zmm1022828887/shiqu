<?php

class TbDateTimePicker extends CInputWidget
{
	/**
	 * @var TbActiveForm when created via TbActiveForm.
	 * This attribute is set to the form that renders the widget
	 * @see TbActionForm->inputRow
	 */
	public $form;

	/**
	 * @var array the options for the Bootstrap JavaScript plugin.
	 */
	public $options = array();

	/**
	 * @var string[] the JavaScript event handlers.
	 */
	public $events = array();

	/**
	 *### .init()
	 *
	 * Initializes the widget.
	 */
	public function init()
	{
		if (!isset($this->options['language'])) {
            $language = Yii::app()->getLanguage();
            $pos = strpos($language, '_');
            if($pos > 0) {
                $language = substr($language, 0, $pos). '-' . strtoupper(substr($language, $pos+1));
            }
            $this->options['language'] = $language;
        }
        
		if (!isset($this->options['format']))
			$this->options['format'] = 'yyyy-mm-dd hh:ii';

		if (!isset($this->options['weekStart']))
			$this->options['weekStart'] = 1; // Monday
	}

	/**
	 *### .run()
	 *
	 * Runs the widget.
	 */
	public function run()
	{
		list($name, $id) = $this->resolveNameID();

		if ($this->hasModel())
		{
			if ($this->form)
				echo $this->form->textField($this->model, $this->attribute, $this->htmlOptions);
			else
				echo CHtml::activeTextField($this->model, $this->attribute, $this->htmlOptions);

		} else
			echo CHtml::textField($name, $this->value, $this->htmlOptions);

		$this->registerClientScript();
		$this->registerLanguageScript();
		$options = !empty($this->options) ? CJavaScript::encode($this->options) : '';

		ob_start();
		echo "jQuery('#{$id}').datetimepicker({$options})";
		foreach ($this->events as $event => $handler)
			echo ".on('{$event}', " . CJavaScript::encode($handler) . ")";

		Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $this->getId(), ob_get_clean() . ';');

	}

	/**
	 *### .registerClientScript()
	 *
	 * Registers required client script for bootstrap datepicker. It is not used through bootstrap->registerPlugin
	 * in order to attach events if any
	 */
	public function registerClientScript()
	{
		Yii::app()->bootstrap->registerAssetCss('bootstrap-datetimepicker.css');
		Yii::app()->bootstrap->registerAssetJs('bootstrap-datetimepicker.js');
	}

	public function registerLanguageScript()
	{
		if (isset($this->options['language']) && $this->options['language'] != 'en')
		{
			$file = 'locales/datetimepicker/bootstrap-datetimepicker.'.$this->options['language'].'.js';
			if (@file_exists(Yii::getPathOfAlias('bootstrap.assets').'/js/'.$file))
				Yii::app()->bootstrap->registerAssetJs('locales/datetimepicker/bootstrap-datetimepicker.'.$this->options['language'].'.js', CClientScript::POS_END);
		}
	}
}
