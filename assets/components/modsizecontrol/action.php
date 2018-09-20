<?php
/** TODO: полностью переделать, вынести в класс */
if (empty($_GET['action'])) {
    exit();
}

define('MODX_API_MODE', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/index.php');
$modx = new modX();
$modx->initialize('web');

/** @var modSizeControl $modSizeControl */
$modSizeControl = $modx->getService('modSizeControl', 'modSizeControl', MODX_CORE_PATH . 'components/modsizecontrol/model/', array());
/** @var pdoTools $pdo */
$pdo = $modx->getService('pdoTools');

$limit = $modx->getOption('modsizecontrol_site_limit') ?: 1073741824;
$source_ids = explode(',', $modx->getOption('modsizecontrol_file_system'));

if (!$source_ids) {
    exit();
}

$paths = array();
foreach($source_ids as $s) {
    /** @var modMediaSource $source */
    $source = $modx->getObject('modMediaSource', $s);

    if ($source) {
        $source->initialize();
        $path = $source->getBasePath();
        $paths[] = $path;
    }
}

$sizes = array();

foreach($paths as $p) {
    $size += dir_size($p);
}

// Сохраняем и очищаем кэш

$set = $modx->getObject('modSystemSetting', 'modsizecontrol_site_size');
if (!$set) {
    exit();
}
$set->set('value', $size);
$set->save();
$modx->cacheManager->refresh(array('system_settings' => array()));


// Функция для просмотра всех подпапок и всех вложенных файлов

function dir_size($dirname) {
    $totalsize = 0;
    if ($dirstream = @opendir($dirname)) {
        while (false !== ($filename = readdir($dirstream))) {
            if ($filename!="." && $filename!="..")
            {
                if (is_file($dirname."/".$filename))
                    $totalsize+=filesize($dirname."/".$filename);

                if (is_dir($dirname."/".$filename))
                    $totalsize+=dir_size($dirname."/".$filename);
            }
        }
    }
    closedir($dirstream);
    return $totalsize;
}



// Просчет процентов
$per = $limit / 100;
$percent = ceil($size / $per);

// Выставление плейсхолдеров
$placeholders = array(
    'percent' => $percent,
    'limit' => $modSizeControl->format_size($limit),
    'size' => $modSizeControl->format_size($size),
    'errorHeader' => $modx->lexicon('ss_limit_out_header'),
    'errorText' => $modx->lexicon('ss_limit_out_text'),
);

exit(json_encode($placeholders));