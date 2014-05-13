<?php

// create_product.php
require_once "bootstrap.php";

$product = new \Acme\Entity\Product();

$product = $entityManager->find('Acme\Entity\Product', 4);
$product->setCategory($entityManager->getReference('Acme\Entity\Category', 2));

$entityManager->persist($product);
$entityManager->flush();

exit(0);

$newProductName = $argv[1];

$type = isset($argv[2]) ? $argv[2] : 'Product';



if ($type == 'Product') {

    $product = new \Acme\Entity\Product();
    $product->setName($newProductName);

    $entityManager->persist($product);
    $entityManager->flush();

    echo "Created Product with ID " . $product->getId() . "\n";
    
} else if ($type == 'Category') {

    $category = new \Acme\Entity\Category();
    $category->setName($newProductName);

    $entityManager->persist($category);
    $entityManager->flush();

    echo "Created Category with ID " . $category->getId() . "\n";
}