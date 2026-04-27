<?php
require 'core/Database.php';
$config = require 'config.php';
\Core\Database::setConfig($config['db']);
$types = \Core\Database::fetchAll('SELECT * FROM saraban_types');
print_r($types);
