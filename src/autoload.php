<?php

require(__DIR__  . '/../vendor/autoload.php');

Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
    'Symfony\Component\Validator\Constraints', 
    __DIR__ . '/../vendor/symfony/validator'
);
 
Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
    'JMS\Serializer\Annotation', 
    __DIR__ . '/../vendor/jms/serializer/src'
);
 
