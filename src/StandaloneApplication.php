<?php

// Demo implementation

use Adrotec\BreezeJs\Serializer\MetadataInterceptor;
use Adrotec\BreezeJs\Doctrine\ORM\Dispatcher;
use Adrotec\BreezeJs\Serializer\SerializerBuilder;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class StandaloneApplication {

    const REQUEST_METHOD_GET = 'GET';
    const REQUEST_METHOD_POST = 'POST';
    const REQUEST_METHOD_OPTIONS = 'OPTIONS';
    
    private $classes;
    private $serializer;
    private $entityManager;
    private $debug = false;
    private $dispatcher;
    private $cors = false;

    public function __construct($debug = false) {
        $this->debug = $debug;
    }

    public function getRequestPath() {
        $path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $path = preg_replace('/.+?\/api\//', '', $path);
        $path = trim($path, '/');
        return $path;
    }

    public function getRequestContent() {
        return file_get_contents('php://input');
    }
    
    public function getRequestMethod(){
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getClasses() {
        return $this->classes;
    }

    public function setClasses($classes) {
        $this->classes = $classes;
    }

    public function getSerializer() {
        if ($this->serializer === null) {
            $this->serializer = SerializerBuilder::create($this->getEntityManager())
                    ->build();
        }
        return $this->serializer;
    }

    public function setSerializer($serializer) {
        $this->serializer = $serializer;
    }

    public function getEntityManager() {
        return $this->entityManager;
    }

    public function setEntityManager($entityManager) {
        $this->entityManager = $entityManager;
    }

    public function getDispatcher() {
        if ($this->dispatcher === null) {
            $interceptor = new MetadataInterceptor($this->getSerializer());
            // limit the api to certain classes. If you want to expose all classes 
            // from the entity manager, $classes parameter should be null
            $classes = $this->getClasses();
            $this->dispatcher = new Dispatcher($this->getEntityManager(), $interceptor, $classes);
        }
        return $this->dispatcher;
    }

    protected function sendResponse($response) {
        // set response headers here
        echo $this->getSerializer()->serialize($response, 'json');
    }

    public function metadata() {
        $response = $this->getDispatcher()->getMetadata();
        return $this->sendResponse($response);
    }

    public function saveChanges() {
        $response = $this->getDispatcher()->saveChanges($this->getRequestContent());
        return $this->sendResponse($response);
    }

    public function resource($class) {
        $params = $_GET;
        $response = $this->getDispatcher()->getQueryResults($class, $params);
        return $this->sendResponse($response);
    }
    
    public function enableCors($cors = true){
        $this->cors = $cors;
    }
    
    protected function sendCorsHeaders(){
        // allow cross origin requests
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Accept, X-Requested-With');
    }

    public function run() {
        if($this->cors){
            $this->sendCorsHeaders();
        }
        // no need to process "pre flight" requests
        if ($this->getRequestMethod() === self::REQUEST_METHOD_OPTIONS) {
            exit;
        }
        
        if($this->entityManager === null){
            throw new \RuntimeException('EntityManager should be set before you run the application');
        }
        $path = $this->getRequestPath();
        if ($path == 'Metadata') {
            return $this->metadata();
        } else if ($path == 'SaveChanges') {
            return $this->saveChanges();
        } else {
            $classes = $this->getClasses();
            foreach ($classes as $resourceName => $class) {
                if ($path == $resourceName) {
                    return $this->resource($class);
                }
            }
        }

        echo json_encode(array('error' => 'Not Found'));
    }

}
