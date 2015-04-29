<?php

class TWebModule extends CWebModule {

    private $_assetsUrl;
    private $_viewDir;

    public function getAssetsUrl() {
        $moduleId = $this->getId();
        if (strpos($moduleId, '/'))
            $moduleId = str_replace('/', '/modules/', $moduleId);
        $assetsPath = Yii::getPathOfAlias('application.modules.' . $moduleId . '.assets');
        if ($this->_assetsUrl == null) {
            //$this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath, false, -1, YII_DEBUG);
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish($assetsPath);
        }
        return $this->_assetsUrl;
    }

    public function setAssetsUrl($value) {
        $this->_assetsUrl = $value;
    }

    public function getViewDir() {
        if (!isset($this->_viewDir)) {
            $moduleId = $this->getId();
            if (strpos($moduleId, '/'))
                $moduleId = str_replace('/', '/modules/', $moduleId);
            $this->_viewDir = Yii::getPathOfAlias('application.modules.' . $moduleId . '.views');
        }
        return $this->_viewDir;
    }

    public function setViewDir($viewDir) {
        if (strpos($viewDir, '.'))
            $this->_viewDir = Yii::getPathOfAlias($viewDir);
        $this->setLayoutPath($this->_viewDir . DIRECTORY_SEPARATOR . 'layouts');
        $this->setViewPath($this->_viewDir);
    }

    public function init() {
        $this->setImport(array(
            $this->getId() . '.models.*',
            $this->getId() . '.components.*',
        ));
    }
}