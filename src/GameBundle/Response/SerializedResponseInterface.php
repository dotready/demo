<?php

namespace GameBundle\Response;

use JMS\Serializer\SerializerInterface;

/**
 * Interface SerializedResponseInterface.
 */
interface SerializedResponseInterface
{
    /**
     * SerializedResponseInterface constructor.
     *
     * @param $entity
     * @param SerializerInterface $serializer
     * @param int                 $statusCode
     * @param array               $headers
     */
    public function __construct($entity, SerializerInterface $serializer, int $statusCode, array $headers);

    /**
     * Conversion to format
     * which is requested as response
     *
     * @param $entity
     *
     * @return string
     */
    public function convert($entity) : string;

    /**
     * Return http headers
     *
     * @return array
     */
    public function getHeaders() : array;

    /**
     * Return the content
     *
     * @return string
     */
    public function getContent() : string;

    /**
     * Return http status code
     *
     * @return int
     */
    public function getStatusCode() : int;
}
