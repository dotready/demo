<?php

namespace DemoBundle\Exception;

/**
 * Class UserNotFoundException
 * @package DemoBundle\Exception
 */
class UserNotFoundException extends \Exception
{
    /**
     * @var array
     */
    private $data;

    /**
     * UserNotFoundException constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        parent::__construct('User could not be found');
    }
}
