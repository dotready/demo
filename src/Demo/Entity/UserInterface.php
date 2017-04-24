<?php

namespace Demo\Entity;

interface UserInterface
{
    /**
     * Get a user id
     * @return string
     */
    public function getId() : string;

    /**
     * @return string
     */
    public function getUsername(): string;

    /**
     * @return string
     */
    public function getFirstname(): string;

    /**
     * @return string
     */
    public function getLastname(): string;
}
