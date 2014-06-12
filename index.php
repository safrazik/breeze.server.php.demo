<?php

try {

    require_once 'bootstrap.php';

    $app = new StandaloneApplication($isDevMode);

    $classes = array(
        // Resource name => Class name
//    'Products' => 'Acme\Entity\Product',
//    'Categories' => 'Acme\Entity\Category',
        'Products' => 'Demo\Entity\Product',
        'Categories' => 'Demo\Entity\Category',
    );

    $app->setClasses($classes);

// allow cross origin request
    $app->enableCors();

    $app->setEntityManager($entityManager);

    $app->run();
} catch (\Exception $e) {
    echo json_encode(array(
        'error' => $e->getMessage(),
    ));
}