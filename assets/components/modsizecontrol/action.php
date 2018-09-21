<?php
/**
 * Коннектор для запросов с web контекста
 */
/* @var modX $modx */
define('MODX_API_MODE', true);
include dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';
/** @var modSizeControl $modSizeControl */
$modSizeControl = $modx->getService('modSizeControl', 'modSizeControl', MODX_CORE_PATH . 'components/modsizecontrol/model/', array());

$scriptProperties = $_REQUEST;
$path = $modSizeControl->config['publicProcessors'];
$processorProps = array(
    'processors_path' => $path,
);

/* @var modProcessorResponse $result */
$result = $modx->runProcessor($_REQUEST['action'], $scriptProperties, $processorProps);
if (!$result) {
    exit($modx->toJSON(array(
        'success' => false,
        'data' => [],
        'html' => '',
        'messages' => array('Обработчик не найден!'),
    )));

}

exit($modx->toJSON($result->response));