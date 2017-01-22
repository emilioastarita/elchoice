<?php
require __DIR__ . '/vendor/autoload.php';
use Doctrine\ORM\Tools\Console\ConsoleRunner;

$default = require __DIR__ . '/src/settings.php';
$local = require __DIR__ . '/src/settings.local.php';
$settings = array_replace_recursive($default, $local);
$app = new \Slim\App($settings);
require __DIR__ . '/src/dependencies.php';
$entityManager = $app->getContainer()->get('em');
return ConsoleRunner::createHelperSet($entityManager);
