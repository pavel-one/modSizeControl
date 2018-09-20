<?php

class modSizeControlWidget extends modDashboardWidgetInterface
{
    public $cssBlockClass = 'modsize-control-widget';
    /** @var pdoTools $pdo */
    public $pdo;
    /** @var modSizeControl $modSizeControl */
    public $modSizeControl;
    public $limit;
    public $size;

    public function process()
    {
        $this->pdo = $this->modx->getService('pdoTools');
        $this->modSizeControl = $this->modx->getService('modSizeControl', 'modSizeControl', MODX_CORE_PATH . 'components/modsizecontrol/model/', array());
        $this->limit = $this->modx->getOption('modsizecontrol_site_limit') ?: 1073741824;
        $this->size = $this->modx->getOption('modsizecontrol_site_size');

        return parent::process();
    }

    public function render()
    {

        // Просчет процентов
        $per = $this->limit / 100;
        $percent = ceil($this->size / $per);

        // Выставление плейсхолдеров
        $placeholders = array(
            'percent' => $percent,
            'limit' => $this->modSizeControl->format_size($this->limit),
            'size' => $this->modSizeControl->format_size($this->size)
        );
        $output = $this->pdo->getChunk($this->modSizeControl->config['tpl'], $placeholders);

        return $output;
    }
}

return 'modSizeControlWidget';