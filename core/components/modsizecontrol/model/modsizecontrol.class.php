<?php

class modSizeControl
{
    /** @var modX $modx */
    public $modx;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = array())
    {

        $this->modx =& $modx;
        $corePath = MODX_CORE_PATH . 'components/modsizecontrol/';
        $assetsUrl = MODX_ASSETS_URL . 'components/modsizecontrol/';

        $lang = $this->modx->getOption('manager_language');
        $this->modx->setOption('cultureKey', $lang);
        $this->modx->lexicon->load('modsizecontrol:default');

        $this->config = array_merge(array(
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
            'site_size' => $this->modx->cacheManager->get('modSizeControl') ?: 0,
            'web_connector' => $assetsUrl . 'action.php',
            'loading_text' => $this->modx->lexicon('modsizecontrol_load_text', array(), $lang) ?: 'Loading',
            'error_text' => $this->modx->lexicon('modsizecontrol_err_error', array(), $lang) ?: 'Error'
        ), $config);
        
        $this->modx->addPackage('modsizecontrol', $this->config['modelPath']);
    }

    // Функция форматирует вывод размера
    public function format_size($size, $precision = 2)
    {
        $base = log($size, 1024);

        $metrics = array(
            $this->modx->lexicon('modsizecontrol_metrics_byte') ?: 'b',
            $this->modx->lexicon('modsizecontrol_metrics_kilobyte') ?: 'kb',
            $this->modx->lexicon('modsizecontrol_metrics_megabyte') ?: 'Mb',
            $this->modx->lexicon('modsizecontrol_metrics_gigabyte') ?: 'Gb',
            $this->modx->lexicon('modsizecontrol_metrics_terabyte') ?: 'Tb'
        );

        return round(pow(1024, $base - floor($base)), $precision) .' '. $metrics[floor($base)];
    }
 
    public function dir_size($dir)
    {
        $dir = rtrim(str_replace('\\', '/', $dir), '/');

        if (is_dir($dir) === true) {
            $totalSize = 0;
            $os = strtoupper(substr(PHP_OS, 0, 3));
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
                    $ref = $obj->getfolder($dir);
                    $totalSize = $ref->size;
                    $obj = null;
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
 
    public function checkSize($file = NULL)
    {
        if(is_null($file)) {
            $this->modx->log(MODX_LOG_LEVEL_ERROR, $this->modx->lexicon('modsizecontrol_err_file_exist'));
            return false;
        }

        if($file['size'] > $this->getAvailable()) return false;

        $total = $this->getSiteSize() + $file['size'];
        $this->modx->cacheManager->set('modSizeControl', $total, 43200);

        return true;
    }

    public function getAvailable() {
        $limit = ($this->config['limit'] * 1024) * 1024;

        return $limit - $this->getSiteSize();
    }

    public function getSiteSize() {
        $response = $this->modx->runProcessor('size/update', array(), array(
            'processors_path' => $this->config['publicProcessors']
        ));

        return !empty($response->response['success']) ? $response->response['object']['clearSize'] : $this->modx->cacheManager->get('modSizeControl');
    }

}