<?php
/** @var modSizeControl $modSizeControl */
$modSizeControl = $modx->getService('modSizeControl', 'modSizeControl', MODX_CORE_PATH . 'components/modsizecontrol/model/', array());

switch($modx->event->name) {
    case 'OnManagerPageInit':
        if($action != 'welcome') return;

        $action = $modSizeControl->config['web_connector'];
        $jsonConfig = $modx->toJSON($modSizeControl->config);
        $modx->regClientStartupHTMLBlock("
            <script>
            var modSizeControlConfig = {$jsonConfig};
            </script>
        ");
        $modx->regClientCSS($modSizeControl->config['cssUrl'] . 'mgr/default.css?ver=1.0.7');
        $modx->regClientStartupScript($modSizeControl->config['jsUrl'] . 'mgr/default.js?ver=1.0.7');
        break;
    case 'OnFileManagerBeforeUpload':
        if($modx->getOption('modsizecontrol_control')) {
            $limit = ($modSizeControl->config['limit'] * 1024) * 1024;
            
            $response = $modx->runProcessor('size/update', array(), array(
                'processors_path' => $modSizeControl->config['publicProcessors']
            ));
    
            $site_size = $response->response['object']['clearSize'] ?: $modx->cacheManager->get('modSizeControl');
            $available = $limit - $site_size;
            $total = $site_size + $file['size'];
    
            if($file['size'] > $available) {
                $modx->event->params['file']['error'] = 1;
                $source->addError('limit', $modx->lexicon('modsizecontrol_limit_out_header'));
            } else {
                $modx->cacheManager->set('modSizeControl', $total, 43200);
            }
        } else {
            $modx->cacheManager->delete('modSizeControl');
        }
        
        break;
    case 'OnFileManagerFileCreate':
    case 'OnFileManagerFileRemove':
    case 'OnFileManagerFileUpdate':
    case 'OnFileManagerDirRemove':
        $modx->cacheManager->delete('modSizeControl');
}