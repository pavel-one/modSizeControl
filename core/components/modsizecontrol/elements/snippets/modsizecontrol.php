<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/** @var modSizeControl $modSizeControl */
$modSizeControl = $modx->getService('modSizeControl', 'modSizeControl', MODX_CORE_PATH . 'components/modsizecontrol/model/', $scriptProperties);
if (!$modSizeControl) {
    return 'Could not load modSizeControl class!';
}

// Do your snippet code here. This demo grabs 5 items from our custom table.
$tpl = $modx->getOption('tpl', $scriptProperties, 'Item');
$sortby = $modx->getOption('sortby', $scriptProperties, 'name');
$sortdir = $modx->getOption('sortbir', $scriptProperties, 'ASC');
$limit = $modx->getOption('limit', $scriptProperties, 5);
$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, "\n");
$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);

// Build query
$c = $modx->newQuery('modSizeControlItem');
$c->sortby($sortby, $sortdir);
$c->where(['active' => 1]);
$c->limit($limit);
$items = $modx->getIterator('modSizeControlItem', $c);

// Iterate through items
$list = [];
/** @var modSizeControlItem $item */
foreach ($items as $item) {
    $list[] = $modx->getChunk($tpl, $item->toArray());
}

// Output
$output = implode($outputSeparator, $list);
if (!empty($toPlaceholder)) {
    // If using a placeholder, output nothing and set output to specified placeholder
    $modx->setPlaceholder($toPlaceholder, $output);

    return '';
}
// By default just return output
return $output;
