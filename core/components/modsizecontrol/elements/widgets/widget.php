<?php
class modSizeControlWidget extends modDashboardWidgetInterface {
    public $cssBlockClass = 'modsize-control-widget';
    /** @var pdoTools $pdo */
    public $pdo;
    /** @var modSizeControl $modSizeControl */
    public $modSizeControl;

    public function process()
    {
        $this->pdo = $this->modx->getService('pdoTools');
        $this->modSizeControl = $this->modx->getService('modSizeControl', 'modSizeControl', MODX_CORE_PATH . 'components/modsizecontrol/model/', array());

        return parent::process();
    }

    public function render()
    {
        // TODO: Implement render() method.
    }
}

return 'modSizeControlWidget';