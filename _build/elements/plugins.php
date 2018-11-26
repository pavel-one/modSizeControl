<?php

return [
    'modSizeControl' => [
        'file' => 'modsizecontrol',
        'description' => 'Подключение скриптов',
        'events' => [
            'OnManagerPageInit' => [],
            'OnFileManagerBeforeUpload' => [],
            'OnFileManagerFileCreate' => [],
            'OnFileManagerFileRemove' => [],
            'OnFileManagerFileUpdate' => [],
            'OnFileManagerDirRemove' => [],
        ],
    ],
];