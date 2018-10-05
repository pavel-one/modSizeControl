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

    public function dir_size($dir)
    {
        $dir = rtrim(str_replace('\\', '/', $dir), '/');

        if (is_dir($dir) === true) {
            $totalSize = 0;
            $os        = strtoupper(substr(PHP_OS, 0, 3));
            // If on a Unix Host (Linux, Mac OS)
            if ($os !== 'WIN') {
                $io = popen('/usr/bin/du -sb ' . $dir, 'r');
                if ($io !== false) {
                    $totalSize = intval(fgets($io, 80));
                    pclose($io);
                    return $totalSize;
                }
            }
            // If on a Windows Host (WIN32, WINNT, Windows)
            if ($os === 'WIN' && extension_loaded('com_dotnet')) {
                $obj = new \COM('scripting.filesystemobject');
                if (is_object($obj)) {
                    $ref       = $obj->getfolder($dir);
                    $totalSize = $ref->size;
                    $obj       = null;
                    return $totalSize;
                }
            }
            // If System calls did't work, use slower PHP 5
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
            foreach ($files as $file) {
                $totalSize += $file->getSize();
            }
            return $totalSize;
        } else if (is_file($dir) === true) {
            return filesize($dir);
        }
    }

}