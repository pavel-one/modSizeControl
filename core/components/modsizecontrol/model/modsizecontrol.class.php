<?php

class modSizeControl
{
    /** @var modX $modx */
    public $modx;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $corePath = MODX_CORE_PATH . 'components/modsizecontrol/';
        $assetsUrl = MODX_ASSETS_URL . 'components/modsizecontrol/';

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'publicProcessors' => $corePath . 'processors/public/',

            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'tpl' => $this->modx->getOption('modsizecontrol_tpl'),
            'limit' => $this->modx->getOption('modsizecontrol_site_limit') ?: 1073741824,
            'site_size' => $this->modx->getOption('modsizecontrol_site_size'),
            'web_connector' => $assetsUrl . 'action.php'
        ], $config);

        $language = $this->modx->getObject('modSystemSetting', 'manager_language');
        $lang = $language->get('value');

        $this->modx->addPackage('modsizecontrol', $this->config['modelPath']);
        $this->modx->lexicon->load($lang.':modsizecontrol:default');
    }

    // Функция форматирует вывод размера
    public function format_size($size)
    {

        $language = $this->modx->getObject('modSystemSetting', 'manager_language');
        $lang = $language->get('value');
        $this->modx->lexicon->load($lang.':modsizecontrol:default');

        $metrics[0] = $this->modx->lexicon('modsizecontrol_metrics_byte', array(), $lang) ?: 'b';
        $metrics[1] = $this->modx->lexicon('modsizecontrol_metrics_kilobyte', array(), $lang) ?: 'kb';
        $metrics[2] = $this->modx->lexicon('modsizecontrol_metrics_megabyte', array(), $lang) ?: 'Mb';
        $metrics[3] = $this->modx->lexicon('modsizecontrol_metrics_gigabyte', array(), $lang) ?: 'Gb';
        $metrics[4] = $this->modx->lexicon('modsizecontrol_metrics_terabyte', array(), $lang) ?: 'Tb';
        $metric = 0;
        while (floor($size / 1024) > 0) {
            ++$metric;
            $size /= 1024;
        }
        $ret = round($size, 1) . " " . (isset($metrics[$metric]) ? $metrics[$metric] : '??');
        return $ret;
    }

    public function dir_size($dirName)
    {
        $totalSize = 0;
        if ($dirStream = @opendir($dirName)) {
            while (false !== ($filename = readdir($dirStream))) {
                if ($filename != "." && $filename != "..") {
                    if (is_file($dirName . "/" . $filename))
                        $totalSize += filesize($dirName . "/" . $filename);

                    if (is_dir($dirName . "/" . $filename))
                        $totalSize += $this->dir_size($dirName . "/" . $filename);
                }
            }
        }

        closedir($dirStream);
        return $totalSize;
    }

}