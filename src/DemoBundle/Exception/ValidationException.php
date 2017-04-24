<?php

namespace DemoBundle\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class ValidationException
 * @package DemoBundle\Exception
 */
class ValidationException extends \Exception
{
    const INVALID_PARAMETERS = 'invalid parameters';

    /**
     * @var ConstraintViolationList
     */
    private $errors;

    /**
     * ValidationException constructor.
     * @param ConstraintViolationList $errors
     * @param \Exception|null $previous
     */
    public function __construct(ConstraintViolationList $errors, \Exception $previous = null)
    {
        $this->errors = $errors;
        parent::__construct(self::INVALID_PARAMETERS, Response::HTTP_BAD_REQUEST, $previous);
    }

    /**
     * @return ConstraintViolationList
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $messages = [];

        foreach ($this->errors as $error) {
            $messages[$error->getPropertyPath()] = $error->getMessage();
        }

        return json_encode($messages);
    }
}
