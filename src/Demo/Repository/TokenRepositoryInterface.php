<?php

namespace Demo\Repository;

use Demo\Entity\TokenInterface;

/**
 * Interface TokenRepositoryInterface
 * @package Demo\Repository
 */
interface TokenRepositoryInterface
{
    /**
     * Add a new Token
     * @param TokenInterface $token
     * @return TokenInterface
     */
    public function add(TokenInterface $token) : TokenInterface;

    /**
     * Update a Token
     * @param TokenInterface $token
     * @return TokenInterface
     */
    public function update(TokenInterface $token) : TokenInterface;

    /**
     * Get a Token
     * @param array $criteria
     * @return TokenInterface
     */
    public function get(array $criteria);

    /**
     * Remove a Token
     * @param TokenInterface $token
     * @return TokenInterface
     */
    public function remove(TokenInterface $token);
}
