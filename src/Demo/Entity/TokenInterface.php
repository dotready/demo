<?php

namespace Demo\Entity;

interface TokenInterface
{
    /**
     * Get a user id
     * @return string
     */
    public function getId() : string;

    /**
     * @return string
     */
    public function getToken() : string;
}
