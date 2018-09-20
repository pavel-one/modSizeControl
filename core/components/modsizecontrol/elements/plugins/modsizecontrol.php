<?php
if ($modx->event->name != 'OnManagerPageInit') return;
/** @var modSizeControl $modSizeControl */
$modSizeControl = $modx->getService('modSizeControl', 'modSizeControl', MODX_CORE_PATH . 'components/modsizecontrol/model/', array());

$modx->regClientCSS($modSizeControl->config['cssUrl'] . 'mgr/default.css');
$modx->regClientStartupScript($modSizeControl->config['jsUrl'] . 'mgr/default.js');