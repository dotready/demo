<?php

namespace DemoBundle\Entity;

use Demo\Entity\TokenInterface;

/**
 * Class Token
 * @package DemoBundle\Entity
 */
class Token implements TokenInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @inheritDoc
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getToken() : string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getUserId() : string
    {
        return $this->userId;
    }

    /**
     * Creates default created date
     */
    public function lifecyclePreCreateDate()
    {
        $this->created = new \DateTime();
    }

    /**
     * Creates a new token
     */
    public function lifecyclePreGenerateToken()
    {
        try {
            $string = random_bytes(32);
        } catch (\TypeError $e) {
            // Well, it's an integer, so this IS unexpected.
            die("An unexpected error has occurred");
        } catch (\Error $e) {
            // This is also unexpected because 32 is a reasonable integer.
            die("An unexpected error has occurred");
        } catch (\Exception $e) {
            // If you get this message, the CSPRNG failed hard.
            die("Could not generate a random string. Is our OS secure?");
        }

        $this->token = bin2hex($string);
    }
}
