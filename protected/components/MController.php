<?php
class MController extends CController {

    public $layout = '//layouts/page';
    public $layoutParams = array();
    public $widgets = array();

    public function filters() {
            return array(
                array('application.filters.AuthFilter'),
            );
    }

}
?>
