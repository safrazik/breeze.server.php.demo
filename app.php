<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization, x-wsse');
    
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}
require_once 'bootstrap.php';

new JMS\Serializer\Annotation\Exclude();
new JMS\Serializer\Annotation\AccessType();
new JMS\Serializer\Annotation\Accessor();
new JMS\Serializer\Annotation\ReadOnly();

$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
$path = preg_replace('/.+?\/api\//', '', $path);
$path = trim($path, '/');
//new JMS\Serializer\Annotation\SerializedName(array('value' => "COol"));

$builder = Adrotec\BreezeJs\Serializer\SerializerBuilder::create($entityManager);
//$builder = JMS\Serializer\SerializerBuilder::create();
$serializer = $builder->build();

if ($path == 'Metadata') {
//$data = new Employee('Muhammad', 'Safraz', 23);
    $metadataBuilder = new Adrotec\BreezeJs\Doctrine\ORM\MetadataBuilder($entityManager
            , new Adrotec\BreezeJs\Serializer\MetadataInterceptor($serializer));

    $data = $metadataBuilder->buildMetadata();
} else if ($path == 'SaveChanges') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $saveBundleString = file_get_contents('php://input');
        $saveService = new Adrotec\BreezeJs\Doctrine\ORM\SaveService($entityManager);
        $saveBundle = $saveService->createSaveBundleFromString($saveBundleString);
        $data = $saveService->saveChanges($saveBundle);
    } else {
        exit('Method not supported');
    }
} else {
    $queryService = new \Adrotec\BreezeJs\Doctrine\ORM\QueryService($entityManager);
    $params = $_GET;
    if ($path == 'Categories') {
        $className = 'Acme\Entity\Category';
    } else if ($path == 'Products') {
        $className = 'Acme\Entity\Product';
    }

    $data = $queryService->getQueryResult($className, $params);
}
//print_r($data);
//echo json_encode($data);
//exit();

$jsonContent = $serializer->serialize($data, 'json');
echo $jsonContent; // or return it in a Response
