<?php

declare(strict_types = 1);

/**
 * @license See LICENSE file in project root
 */

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;

$app = new Application();

$app->setName('Enumable');
$app->setVersion('0.1.0-dev');
$app->add(new \Cspray\Enumable\ConsoleCommand\BuildEnumCommand());
$app->run();