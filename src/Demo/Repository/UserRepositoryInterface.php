<?php

namespace Demo\Repository;

use Demo\Entity\UserInterface;

interface UserRepositoryInterface
{
    /**
     * Add a new User
     * @param UserInterface $user
     * @return UserInterface
     */
    public function add(UserInterface $user) : UserInterface;

    /**
     * Update a User
     * @param UserInterface $user
     * @return UserInterface
     */
    public function update(UserInterface $user) : UserInterface;

    /**
     * Delete a User
     * @param UserInterface $user
     */
    public function delete(UserInterface $user);

    /**
     * Get a User
     * @param array $criteria
     * @return UserInterface
     */
    public function get(array $criteria);
}