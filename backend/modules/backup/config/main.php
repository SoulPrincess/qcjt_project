<?php
/**
 * Created by PhpStorm.
 * User: lhp
 * time: 2020/3/23
 */
return [
    'params' => [
        'DATA_BACKUP_PATH' => Yii::getAlias('@backup') . '/data/',
        'DATA_BACKUP_PART_SIZE' => 20971520,
        'DATA_BACKUP_COMPRESS' => 1,
        'DATA_BACKUP_COMPRESS_LEVEL' => 9,
    ]
];