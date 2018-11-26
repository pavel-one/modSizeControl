<?php

return [
    'modSizeControl' => [
        'file' => 'modsizecontrol',
        'description' => 'Подключение скриптов',
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