<?php

/**
 * The home manager controller for modSizeControl.
 *
 */
class modSizeControlHomeManagerController extends modExtraManagerController
{
    /** @var modSizeControl $modSizeControl */
    public $modSizeControl;


    /**
     *
     */
    public function initialize()
    {
        $this->modSizeControl = $this->modx->getService('modSizeControl', 'modSizeControl', MODX_CORE_PATH . 'components/modsizecontrol/model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['modsizecontrol:default'];
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('modsizecontrol');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->modSizeControl->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->modSizeControl->config['jsUrl'] . 'mgr/modsizecontrol.js');
        $this->addJavascript($this->modSizeControl->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->modSizeControl->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->modSizeControl->config['jsUrl'] . 'mgr/widgets/items.grid.js');
        $this->addJavascript($this->modSizeControl->config['jsUrl'] . 'mgr/widgets/items.windows.js');
        $this->addJavascript($this->modSizeControl->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->modSizeControl->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        modSizeControl.config = ' . json_encode($this->modSizeControl->config) . ';
        modSizeControl.config.connector_url = "' . $this->modSizeControl->config['connectorUrl'] . '";
        Ext.onReady(function() {MODx.load({ xtype: "modsizecontrol-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="modsizecontrol-panel-home-div"></div>';

        return '';
    }
}