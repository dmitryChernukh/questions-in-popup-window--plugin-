<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('UserTrait', realpath(__DIR__).'/../src/AppBundle/Traits');

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
