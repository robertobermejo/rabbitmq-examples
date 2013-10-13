<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rbermejo
 * Date: 11/10/13
 * Time: 23:37
 *
 */

$loader = require(__DIR__.'/vendor/autoload.php');

use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;


$application = new Application();
$finder = new Finder();
$files = $finder->files()->in(__DIR__.'/src/*/Command')->name('/\.php$/');
$currentDir = str_replace('/','\/', __DIR__.'/src');

foreach ($files as $file) {
    $commandClass = preg_replace("/$currentDir/",'', $file->getRealPath());
    $commandClass = preg_replace('/\.php$/','',$commandClass);
    $commandClass = str_replace('/', '\\', $commandClass);
    $application->add(new $commandClass);
}
$application->run();

