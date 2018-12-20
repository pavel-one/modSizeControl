<?php

return [
    'modSizeControl' => [
        'file' => 'modsizecontrol',
        'description' => 'Подключение скриптов и контроль загрузки файлов',
        'events' => [
            'OnManagerPageInit' => [],
            'OnFileManagerBeforeUpload' => [],
            'OnFileManagerDirRemove' => [],
            'OnFileManagerFileCreate' => [],
            'OnFileManagerFileRemove' => [],
            'OnFileManagerFileUpdate' => [],
        ],
    ],
];