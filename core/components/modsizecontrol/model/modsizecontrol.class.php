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

            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'tpl' => ''
        ], $config);

        $this->modx->addPackage('modsizecontrol', $this->config['modelPath']);
        $this->modx->lexicon->load('modsizecontrol:default');
    }
    // Функция форматирует вывод размера
    public function format_size($size){
        $metrics[0] = 'байт';
        $metrics[1] = 'Кбайт';
        $metrics[2] = 'Мбайт';
        $metrics[3] = 'Гбайт';
        $metrics[4] = 'Тбайт';
        $metric = 0;
        while(floor($size/1024) > 0){
            ++$metric;
            $size /= 1024;
        }
        $ret =  round($size,1)." ".(isset($metrics[$metric])?$metrics[$metric]:'??');
        return $ret;
    }

}