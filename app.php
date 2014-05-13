<?php

require_once 'bootstrap.php';

new JMS\Serializer\Annotation\Exclude();
new JMS\Serializer\Annotation\AccessType();
new JMS\Serializer\Annotation\Accessor();
new JMS\Serializer\Annotation\ReadOnly();

//new JMS\Serializer\Annotation\SerializedName(array('value' => "COol"));

$builder = Adrotec\BreezeJs\Serializer\SerializerBuilder::create($entityManager);
//$builder = JMS\Serializer\SerializerBuilder::create();
$serializer = $builder->build();

//$data = new Employee('Muhammad', 'Safraz', 23);
$metadataBuilder = new Adrotec\BreezeJs\Doctrine\ORM\MetadataBuilder($entityManager, 
        new Adrotec\BreezeJs\Serializer\MetadataInterceptor($serializer));

$data = $metadataBuilder->buildMetadata();

$queryService = new \Adrotec\BreezeJs\Doctrine\ORM\QueryService($entityManager);
$params = $_GET;
$className = 'Acme\Entity\Product';
$data = $queryService->getQueryResult($className, $params);

//print_r($data);

//echo json_encode($data);

//exit();

$jsonContent = $serializer->serialize($data, 'json');
echo $jsonContent; // or return it in a Response
