<?php

return [
    'site_limit' => [
        'xtype' => 'numberfield',
        'value' => round(disk_free_space(MODX_BASE_PATH) / 1024 / 1024),
        'area' => 'modsizecontrol_main',
    ],
    'file_system' => [
        'xtype' => 'textfield',
        'value' => '1',
        'area' => 'modsizecontrol_main',
    ],
    'tpl' => [
        'xtype' => 'textfield',
        'value' => 'tpl.modSizeControl',
        'area' => 'modsizecontrol_main',
    ],
    'control' => [
        'xtype' => 'combo-boolean',
        'value' => 0,
        'area' => 'modsizecontrol_main',
    ]
];