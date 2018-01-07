<?php

declare(strict_types = 1);

use Doctrine\Common\Annotations\AnnotationRegistry;

error_reporting(E_ALL);

$loader = require __DIR__ . '/../vendor/autoload.php';

AnnotationRegistry::registerLoader([$loader, 'loadClass']);
