<?php
/** @var xPDOTransport $transport */
/** @var array $options */
/** @var modX $modx */
if ($transport->xpdo) {
    $modx =& $transport->xpdo;

    $dev = MODX_BASE_PATH . 'Extras/modSizeControl/';
    /** @var xPDOCacheManager $cache */
    $cache = $modx->getCacheManager();
    if (file_exists($dev) && $cache) {
        if (!is_link($dev . 'assets/components/modsizecontrol')) {
            $cache->deleteTree(
                $dev . 'assets/components/modsizecontrol/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_ASSETS_PATH . 'components/modsizecontrol/', $dev . 'assets/components/modsizecontrol');
        }
        if (!is_link($dev . 'core/components/modsizecontrol')) {
            $cache->deleteTree(
                $dev . 'core/components/modsizecontrol/',
                ['deleteTop' => true, 'skipDirs' => false, 'extensions' => []]
            );
            symlink(MODX_CORE_PATH . 'components/modsizecontrol/', $dev . 'core/components/modsizecontrol');
        }
    }
}

return true;