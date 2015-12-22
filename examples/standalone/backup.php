<?php

$manager = require 'bootstrap.php';
$manager->makeBackup()->run('development', 's3', 'test/backup.sql', 'gzip');

// delete old files
$manager->deleteOldFiles()->run('s3','test/', new \DateTime('2016-01-01'));
