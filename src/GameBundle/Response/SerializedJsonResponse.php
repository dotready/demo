<?php

namespace GameBundle\Response;

use Demo\Enum\ResponseFormat;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SerializedJsonResponse.
 */
class SerializedJsonResponse extends Response implements SerializedResponseInterface
{
    /**
     * @var mixed|string
     */
    private $entity;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * SerializedJsonResponse constructor.
     *
     * @param mixed|string        $entity
     * @param int                 $statusCode
     * @param array               $headers
     * @param SerializerInterface $serializer
     */
    public function __construct($entity, SerializerInterface $serializer, int $statusCode = 200, array $headers = [])
    {
        $this->entity = $entity;
        $this->serializer = $serializer;
        parent::__construct($this->convert($entity), $statusCode, ['Content-Type' => 'application/json']);
    }

    /**
     * @param $entity
     *
     * @return string
     */
    public function convert($entity) : string
    {
        return $this->serializer->serialize($this->entity, ResponseFormat::JSON);
    }

    /**
     * @return array
     */
    public function getHeaders() : array
    {
        return $this->headers->all();
    }

    /**
     * @return string
     */
    public function getContent() : string
    {
        return parent::getContent();
    }

    /**
     * @return int
     */
    public function getStatusCode() : int
    {
        return parent::getStatusCode();
    }
}
