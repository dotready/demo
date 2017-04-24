<?php

namespace DemoBundle\Entity;

use Demo\Entity\UserInterface;
use Demo\Enum\Role;

/**
 * Class User
 * @package DemoBundle\Entity
 */
class User implements UserInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var string
     */
    private $role;

    /**
     * @var string
     */
    private $password;

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        //explode the date to get month, day and year
        $diff = $this->getDateOfBirth()->diff(new \DateTime());

        //get age from date or birthdate
        return $diff->format('%y');
    }

    /**
     * @param $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * Creates BCRYPT password
     */
    public function createPassword()
    {
        $pass = password_hash($this->password, PASSWORD_DEFAULT);
        $this->password = $pass;
    }

    /**
     * Sets the number of credits to 0
     */
    public function lifeCyclePreRole()
    {
        $this->role = Role::USER;
    }
}
